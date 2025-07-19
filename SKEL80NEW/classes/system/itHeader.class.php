<?
global $_JS, $_CSS, $_CDN, $plug_media, $plug_meta, $plug_skip;

$_CSS[] = 'class.itAutoSelect.css';
$_CSS[] = 'class.itButton.css';
$_CSS[] = 'class.itEditor.css';
$_CSS[] = 'class.itTitle.css';
$_CSS[] = 'class.itModal.css';
$_CSS[] = 'class.itErrorMsg.css';
$_CSS[] = 'class.itFeed.css';
$_CSS[] = 'class.itModerator.css';
$_CSS[] = 'class.itOpenClose.css';

//..............................................................................
// itHeader : класс для построения заголовка сайта
//..............................................................................
class itHeader
	{
	public 	$code,

		$title,
		$subtitle,
		$description,
		$image,
		$type,
		$url,

		$keys,
		$author,
		$charset;

	private $js,
		$js_minify,
		$og, 
		$tw,
		$meta, 
		$media,

		$favicon,
		$appicon;



	//..............................................................................
	// данные для установки в виде параметров шаринга
	//..............................................................................
	//
	// OpenGraph	:	og:title
	//			og:description
	//			og:image
	//			og:type
	//			og:url
	//
	// Twitter	:	twitter:title
	//			twitter:description
	//			twitter:image:src
	//			twitter:card
	//			twitter:url
	//
	// FaceBook	:	fb:app_id
	//
	// Общие установки массива данных для шаринга, которые растаскивает объект при
	// помощи функции prepare
	//
	// 		'subtitle'	=> подзаголовок страницы (тот же в теге <title>)
	//		'description'	=> описание (в <мета property="description")
	//		'image'		=> ссылка на изображение (link rel="image_src")
	//		'url'		=> ссылка на страницу
	//..............................................................................
	// конструктор класса - настраивает все переменные построения заголовка сайта
	//..............................................................................
	public function __construct()
		{
		$this->cdn 		= TAB.TAB."<!-- Global CDN -->";
		$this->og 	= TAB.TAB."<!-- Global Open Gpaph -->";
		$this->meta 	= TAB.TAB."<!-- Global Meta -->";
		$this->media 	= TAB.TAB."<!-- Global Mobile Devices -->";
		$this->keys 	= get_const('CMS_KEYS');
		$this->author 	= get_const('CMS_AUTHOR');
		$this->charset	= 'UTF-8';
		$this->favicon	= 'favicon.ico';
		$this->appicon	= 'apple-touch-icon.png';
		$this->type	= 'website';
//		$this->include_css();
		$this->include_js();
		}


	//..............................................................................
	// загружает все вложения стилей
	//..............................................................................
	static function _register_css()
		{
		global $_CSS;
		
		$result=NULL;

		// стили от ядра SKELETON
		if (is_dir(SKELETON_CSS_PATH))
			{
			$result .= TAB.html_comment("CORE: CSS definition");
			foreach (explode(',', 'boot,jquery,js') as $selector) 		// class вообще отключаем
				if (is_array($dir_arr = glob(SKELETON_CSS_PATH."{$selector}.*.css")))
					foreach ($dir_arr as $css_file)
						{
						if (file_exists($css_file))
						$result .= TAB.html_comment("CORE:<".basename($css_file).">").
							"\n<style>".
							(strpos($css_file, '.min')
								? rm_css_com(file_get_contents($css_file))
								: css_minify(file_get_contents($css_file))).
							"\n</style>";
						}
			}

		// классы, указанные для загрузки
		if (is_array($_CSS) AND is_dir(SKELETON_CSS_PATH))
			foreach($_CSS as $css_file)
				{
				if (strpos($css_file, 'class.')===0)
					{
					// это установка для класса ядра SKELETON
					if (file_exists($filename=SKELETON_CSS_PATH.$css_file))
					$result .= TAB.html_comment("CLASSES: <".basename($css_file).">").
						"\n<style>".
						(strpos($css_file, '.min')
							? rm_css_com(file_get_contents($filename))
							: css_minify(file_get_contents($filename))).
						"\n</style>";
					}
				}


		$result .= TAB.html_comment("PLUGGED: CSS definition");
		// стили пользовательские
		if (is_dir(USER_CSS_PATH))
			{
			$result .= TAB.html_comment("USER: CSS definition");
			foreach (explode(',', 'boot,jquery,js,class,inc') as $selector)
				if (is_array($dir_arr = glob(USER_CSS_PATH."{$selector}.*.css")))
					foreach ($dir_arr as $css_file)
						{
						$result .= TAB.html_comment("USER: <".basename($css_file).">").
							"\n<style>".
							(strpos($css_file, '.min')
								? rm_css_com(file_get_contents($css_file))
								: css_minify(file_get_contents($css_file))).
							"\n</style>";
						}
			}

		// CDN ссылки на внешние таблицы стилей		
		if (is_array($_CSS))
			foreach($_CSS as $css_file)
				{
				if (strpos($css_file, 'class.')!==0)
					{
					// это не установка для класса ядра SKELETON и мы ее добавляем
					$result .= TAB."<link rel='stylesheet' href='{$css_file}'>";
					}
				}

				
		return $result;
		}


	//..............................................................................
	// загружает все вложения CDN
	//..............................................................................
	public function _cdn() {
		global $_CDN;

		$cdn = NULL;
		if (isset($_CDN)) {

			if(isset($_CDN['material']['ver'])) {
				$cdn[]['css'] = "https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons";
				}

			if(isset($_CDN['ck_editor']['ver'])) {
				$cdn[]['js'] = "https://cdn.ckeditor.com/{$_CDN['ck_editor']['ver']}/full/ckeditor.js";
				}

			if(isset($_CDN['awesome']['ver'])) {
				$cdn[]['css'] = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/{$_CDN['awesome']['ver']}/css/all.min.css";
				}

			if(isset($_CDN['jquery']['ver'])) {
				$cdn[]['js']	= "https://code.jquery.com/jquery-{$_CDN['jquery']['ver']}.min.js";
				if (isset($_CDN['jquery']['ui'])) {
					$cdn[]['js']	= "https://code.jquery.com/ui/{$_CDN['jquery']['ui']}/jquery-ui.min.js";
					}
				}

 			if(isset($_CDN['swiperjs']['ver'])) {
				$cdn[]['js']	= "https://unpkg.com/swiper@{$_CDN['swiperjs']['ver']}/swiper-bundle.min.js";
				$cdn[]['css']	= "https://unpkg.com/swiper@{$_CDN['swiperjs']['ver']}/swiper-bundle.min.css";
				}


			if(isset($_CDN['photoswipe']['ver'])) {
				$cdn[]['js']	= "https://cdnjs.cloudflare.com/ajax/libs/photoswipe/{$_CDN['photoswipe']['ver']}/photoswipe.min.js";
				$cdn[]['js']	= "https://cdnjs.cloudflare.com/ajax/libs/photoswipe/{$_CDN['photoswipe']['ver']}/photoswipe-ui-default.min.js";
				}

			if(isset($_CDN['bxslider']['ver'])) {
				$cdn[]['js']	= "https://cdnjs.cloudflare.com/ajax/libs/bxslider/{$_CDN['bxslider']['ver']}/jquery.bxslider.min.js";
				}

			if(isset($_CDN['owlcarousel']['ver'])) {
			
				$cdn[]['js']	= "https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/{$_CDN['owlcarousel']['ver']}/owl.carousel.min.js";
				$cdn[]['css']	= "https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/{$_CDN['owlcarousel']['ver']}/assets/owl.carousel.min.css";
				$cdn[]['css']	= "https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/{$_CDN['owlcarousel']['ver']}/assets/owl.theme.default.css";
				}

			if(isset($_CDN['file'])) {
				if (is_array($_CDN['file'])) {
					foreach ($_CDN['file'] as $file) {
						$cdn[]['js']	= $file;
						}
					}
				}
			}
		if (is_array($cdn)) {
			foreach($cdn as $row) {
				$this->cdn .= isset($row['js'])
					? TAB."<script type='text/javascript' src='{$row['js']}'></script>"
					: ( isset($row['css'])
						? TAB."<link rel='stylesheet' href='{$row['css']}' media='all'/>"
						: NULL );
				}
			}

		}		

	//..............................................................................
	// загружает все вложения javascript
	//..............................................................................
	public function include_js()
		{
		global $_JS;
		
		if (!is_array($_JS)) $_JS=[];

		$inc_js = NULL;

		$itframe_js_boot = $itframe_js_jquery = $itframe_js_it = $itframe_js_user = [];
		if (is_dir(SKELETON_JS_PATH))
			{
			$itframe_js_boot 	=  is_array($dir_arr = glob(SKELETON_JS_PATH."/boot.*.js")) ? $dir_arr : [];
			$itframe_js_jquery 	=  is_array($dir_arr = glob(SKELETON_JS_PATH."/jquery.*.js")) ? $dir_arr : [];
			$itframe_js_it	 	=  is_array($dir_arr = glob(SKELETON_JS_PATH."/it.*.js")) ? $dir_arr : [];
			$itframe_js_user 	=  is_array($dir_arr = glob(SKELETON_JS_PATH."/user.*.js")) ? $dir_arr : [];
			}

		$js_boot = $js_jquery = $js_it = $js_user = [];			
		if (is_dir(USER_JS_PATH))
			{
			$js_boot 	=  is_array($dir_arr = glob(USER_JS_PATH."/boot.*.js")) ? $dir_arr : [];
			$js_jquery 	=  is_array($dir_arr = glob(USER_JS_PATH."/jquery.*.js")) ? $dir_arr : [];
			$js_it 		=  is_array($dir_arr = glob(USER_JS_PATH."/it.*.js")) ? $dir_arr : [];
			$js_user 	=  is_array($dir_arr = glob(USER_JS_PATH."/user.*.js")) ? $dir_arr : [];
			}

		$itframe_js_lang =  (is_dir(SKELETON_JS_PATH."lang/".CMS_LANG) AND is_array($dir_arr = glob(SKELETON_JS_PATH."lang/".CMS_LANG."/lang.*.js")))
			? $dir_arr : []; 

		// объеденяем резултаты
		$inc_js = array_merge(
			$itframe_js_boot,
			$js_boot,

			$itframe_js_jquery,
			$itframe_js_it,
						
			$js_jquery,
			$js_it,			

			$itframe_js_user,
			$js_user,
			
			$itframe_js_lang
			);

		// результат в начало массива
            $_JS = (is_array($inc_js) and is_array($_JS)) ? array_merge($inc_js, $_JS) : $_JS;
//                echo print_rr($_JS); die;
		}

	

	//..............................................................................
	// добавляет код стиля CSS в переменную $css (HEAD)
	//..............................................................................
	public function add_css($row)
		{
//		$this->css .= TAB."<link href='".$filename."?ver=".CSS_VER."' rel='stylesheet' type='text/css' />";
		$code = (file_exists(SKELETON_CSS_PATH.$row)) 
			? file_get_contents(SKELETON_CSS_PATH.$row) 
			: ( file_exists(DOCUMENT_ROOT."$row")
				? file_get_contents(DOCUMENT_ROOT."$row") 
				: curl_file_get_contents($row) );

		$this->css .= html_comment(" CSS: ".basename($row)).
			"\n<style>\n".
			(strpos($row, '.min') ? rm_css_com($code) : css_minify($code)).
			"\n</style>\n";
		}

	//..............................................................................
	// добавляет код загрузки сценария Jaascript
	//..............................................................................
	public function add_async_javascript($row)
		{
		$this->js .= TAB."<script type='text/javascript' src='{$row}".((strpos($row, 'http')===false) ? "?ver=".JS_VER : '')."'></script>";
		}
		
	//..............................................................................
	// добавляет код html javascript в переменную $js (HEAD)
	//..............................................................................
	public function add_minify_javascript($row)
		{
		$this->js_minify .=
			TAB.html_comment("JS:".basename($row)).
			TAB."<script type='text/javascript'>".
			(str_contains($row, '.min')
				? rm_js_com(file_get_contents($row))
				: minify_js(file_get_contents($row))).
			"</script>";
		}

	//..............................................................................
	// добавляет код установок Open Graph в переменную $og (HEAD)
	//..............................................................................
	public function add_og($key, $value, $is_name=false)
		{
		if ($is_name)
			{
			$name_or_property = 'name';
			} else $name_or_property = 'property';
		$this->og .= TAB."<meta $name_or_property='og:$key' content='$value'/>";
		}


	//..............................................................................
	// добавляет код установок Twitter в переменную $og (HEAD)
	//..............................................................................
	public function add_tw($key, $value, $is_name=false)
		{
		if ($is_name)
			{
			$name_or_property = 'name';
			} else $name_or_property = 'property';
		$this->og .= TAB."<meta $name_or_property='twitter:$key' content='$value'/>";
		}

	//..............................................................................
	// добавляет код установок Facebook в переменную $og (HEAD)
	//..............................................................................
	public function add_fb($key, $value, $is_name=false)
		{
		if ($is_name)
			{
			$name_or_property = 'name';
			} else $name_or_property = 'property';
		$this->og .= TAB."<meta $name_or_property='fb:$key' content='$value'/>";
		}

	//..............................................................................
	// добавляет код html в переменную $meta (HEAD)
	//..............................................................................
	public function add_meta($key, $value, $is_property=false)
		{
		if ($is_property==true)
			{
			$name_or_property = 'property';
			} else if ($is_property==false) 
				{
				$name_or_property = 'name';
				} else	{
					$name_or_property = $is_property;
					}
		$this->meta .= TAB."<meta $name_or_property='$key' content='$value'/>";
		}

	//..............................................................................
	// добавляет код html в переменную $media (HEAD)
	//..............................................................................
	public function add_media($size, $file)
		{
		$file = "/themes/".CMS_THEME."/css/media.$file.css";
		if (file_exists(DOCUMENT_ROOT.$file)) {
			$this->media .= 
				html_comment(basename($file)).
				css_minify(
				TAB."<style>".
				TAB."@media only all and (max-device-width: {$size}px) {".file_get_contents(DOCUMENT_ROOT.$file)."}".
				TAB."@media only all and (max-width: {$size}px) {".file_get_contents(DOCUMENT_ROOT.$file)."}".
				TAB."</style>"
				);
			}
		}

	//..............................................................................
	// добавляет данные в заголовок страницы из массива данных из контроллера
	//..............................................................................
	// $_OG
	// 		'subtitle'	=> подзаголовок страницы (тот же в теге <title>)
	//		'description'	=> описание (в <мета property="description")
	//		'image'		=> ссылка на изображение (link rel="image_src")
	//		'type'		=> тип данных (по умолчанию website)
	//		'url'		=> ссылка на страницу
	//
	//..............................................................................
	public function prepare()
		{
		global $_OG, $_CSS, $_JS, $plug_media, $plug_meta, $plug_skip;

		if (!is_array($plug_skip)) {
			$plug_skip = [];
			}

		$this->title 	= isset($_OG['title']) ? $_OG['title'] : get_const('CMS_NAME');
		if (isset($_OG['subtitle']))
			{
			$this->subtitle = $_OG['subtitle'];
			$this->title = $this->title.(($this->subtitle!="") ? " | {$this->subtitle}" : "");
			}
		$this->add_og('title', $this->title);
		$this->add_tw('title', $this->title);

		if (isset($_OG['description']))
			{
			$this->description = get_str_cut(str_replace('  ', '', strip_tags($_OG['description'])), DEFAULT_DESC_LEN);
			$this->add_meta('description', $this->description);
			$this->add_og('description', $this->description);
			$this->add_tw('description', $this->description, true);
			}

		if (!isset($_OG['image'])) {
			$_OG['image'] = DEFAULT_OG_IMAGE;
			}

		$this->image =
			isset($_OG['poster'])
				? $_OG['poster']
				: ( ((get_picture_tech($_OG['image'])!='OG_AVATAR')
			? get_thumbnail($_OG['image'], 'OG_AVATAR')
			: $_OG['image'])."?".rand_id() );
		
		$this->add_og('image', $this->image);
		$this->add_tw('image:src', $this->image, true);

		if (isset($_OG['type'])) {
			$this->type = $_OG['type'];
			}
		$this->add_og('type', $this->type);

		if (isset($_OG['url']))	{
			$this->url = $_OG['url'];
			} else $this->url = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		$this->add_og('url', $this->url);
		$this->add_tw('url', $this->url);	

		// компилируем javascript
		if (is_array($_JS))
			foreach (array_unique($_JS) as $row)
				{
				if (!in_array($row, $plug_skip))
					{
					$filename = basename($row);
					$selector = (($pos = strpos($filename, ".")) >= 2) ? substr($filename, 0, $pos) : $filename;
					if (in_array($selector, str_getcsv(ASYNC_JS_GROUPS)))
						{
						$this->add_minify_javascript($row);
						} else $this->add_async_javascript($row);
					}
				}


		// компилируем MEDIA
		if (is_array($plug_media))
			foreach (array_unique($plug_media) as $key=>$row)
				{
				if (!in_array($row, $plug_skip))
					{
					$this->add_media($key, $row);
					}
				}

		// компилируем meta
		// $keywords_detected = false;
		if (is_array($plug_meta))
			foreach ($plug_meta as $key=>$row)
				{
				if (is_array($row) AND !in_array($row['name'], $plug_skip))
					{
					if ($row['name']=='keywords')
						{
						$keywords_detected = true;
						}
						
					if (isset($row['content']))
						{
						$this->add_meta($row['name'], $row['content']);
						} else
					if (isset($row['property']))
						{
						$this->add_meta($row['name'], $row['property'], true);
						}
					}
				}

		// if (!$keywords_detected) $this->add_meta('keywords', $this->keys);
		$this->add_meta('author', $this->author);

		$this->_cdn();
		}

	//..............................................................................
	// компилирует код заголовка (HEAD) 
	//..............................................................................
	public function compile()
		{
		$this->code = TAB."<title>{$this->title}</title>".
			TAB.TAB."<meta http-equiv='Content-Type' content='text/html; charset={$this->charset}' />".
			$this->meta.
			$this->og.
			TAB.
			(($this->image) ? TAB."<link rel='image_src' href='{$this->image}' />" : '').
			(file_exists($this->favicon) ? TAB."<link rel='icon' type='image/x-icon' href='/{$this->favicon}' />" : NULL).
			(file_exists($this->favicon) ? TAB."<link rel='shortcut icon' type='image/x-icon' href='/{$this->favicon}' />" : NULL).
			TAB."<link rel='apple-touch-icon' href='/{$this->appicon}' />".
			$this->cdn.
			self::_register_css().
			$this->media.
			$this->js_minify.
			$this->js.			
			( (defined('RECAPTCHA_KEY') AND defined('USE_CAPTCHA') AND USE_CAPTCHA) ?  
				TAB."<script>var capchasitekey = '".RECAPTCHA_KEY."';</script>".
				TAB."<script src='https://www.google.com/recaptcha/api.js?render=".RECAPTCHA_KEY."'></script>".
				""
				: NULL);
		// var_dump(USE_CAPTCHA);
		}
		
	//..............................................................................
	// возвращает код полноценного заголовка (HEAD) 
	//..............................................................................
	public function code()
		{
		return $this->code;
		}	

	} // класс

?>
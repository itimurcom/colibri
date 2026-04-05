<?php
// ================ CRC ================
// version: 1.15.14
// hash: abfa096512b81a86849493b85b8639d9b71f573fa57e934b8f5ea9c8db89b695
// date: 21 May 2021 10:57
// ================ CRC ================
global $plug_js, $plug_css, $plug_media, $plug_meta, $plug_skip;

$plug_css[] = 'class.itAutoSelect.css';
$plug_css[] = 'class.itButton.css';
$plug_css[] = 'class.itEditor.css';
$plug_css[] = 'class.itTitle.css';
$plug_css[] = 'class.itModal.css';
$plug_css[] = 'class.itErrorMsg.css';
$plug_css[] = 'class.itFeed.css';
$plug_css[] = 'class.itModerator.css';
$plug_css[] = 'class.itOpenClose.css';

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
		global $plug_css;
		
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
						$result .= TAB.html_comment(" CSS CORE: <".basename($css_file).">").
							"\n<style>".
							(strpos($css_file, '.min')
								? file_get_contents($css_file) 
								: css_minify(file_get_contents($css_file))).
							"\n</style>";
						}
			}

		// классы, указанные для загрузки
		if (is_array($plug_css) AND is_dir(SKELETON_CSS_PATH))
			foreach($plug_css as $css_file)
				{
				if (strpos($css_file, 'class.')===0)
					{
					// это установка для класса ядра SKELETON
					if (file_exists($filename=SKELETON_CSS_PATH.$css_file))
					$result .= TAB.html_comment(" CSS CLASSES: <".basename($css_file).">").
						"\n<style>".
						(strpos($css_file, '.min')
							? file_get_contents($filename) 
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
						$result .= TAB.html_comment(" CSS USER: <".basename($css_file).">").
							"\n<style>".
							(strpos($css_file, '.min')
								? file_get_contents($css_file) 
								: css_minify(file_get_contents($css_file))).
							"\n</style>";
						}
			}

		// CDN ссылки на внешние таблицы стилей		
		if (is_array($plug_css))
			foreach($plug_css as $css_file)
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
	// загружает все вложения javascript
	//..............................................................................
	public function include_js()
		{
		global $plug_js;
		
		if (!is_array($plug_js)) $plug_js=[];

		$inc_js = NULL;
/*		
		// пришиваем сначала все скрипты из ядра
		$itframe_js_boot = $itframe_js_jquery = $itframe_js_it = $itframe_js_user = $itframe_js_lang = [];
		$handle = scandir(SKELETON_JS_PATH);
		foreach ($handle as $key=>$file)
			{
			$selector = (($pos = strpos($file, ".")) >= 2) ?substr($file, 0, $pos) : $file;
			switch ($selector)
				{
				case 'boot' : {
					$itframe_js_boot[] = SKELETON_JS_PATH."$file";
					break;
					}
					
				case 'jquery' : {
					$itframe_js_jquery[] = SKELETON_JS_PATH."$file";
					break;
					}

				case 'it' : {
					$itframe_js_it[] = SKELETON_JS_PATH."$file";
					break;
					}

				case 'user' : {
					$itframe_js_user[] = SKELETON_JS_PATH."$file";
					break;
					}

				}
			}
		
		
		// пользовательские скрипты
		$js_boot = $js_jquery = $js_it = $js_user = [];	
		$handle = is_dir(USER_JS_PATH) ? scandir(USER_JS_PATH) : [];
		foreach ($handle as $key=>$file)
			{
			$selector = (($pos = strpos($file, ".")) >= 2) ?substr($file, 0, $pos) : $file;
			switch ($selector)
				{
				case 'boot' : {
					$js_boot[] = USER_JS_PATH."$file";
					break;
					}
					
				case 'jquery' : {
					$js_jquery[] = USER_JS_PATH."$file";
					break;
					}

				case 'it' : {
					$js_it[] = USER_JS_PATH."$file";
					break;
					}

				case 'user' : {
					$js_user[] = USER_JS_PATH."$file";
					break;
					}
				}
			}
*/
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
            $plug_js = (is_array($inc_js) and is_array($plug_js)) ? array_merge($inc_js, $plug_js) : $plug_js;
//                echo print_rr($plug_js); die;
		}

	

	//..............................................................................
	// добавляет код стиля CSS в переменную $css (HEAD)
	//..............................................................................
	public function add_css($row)
		{
//		$this->css .= TAB."<link href='".$filename."?ver=".CSS_VER."' rel='stylesheet' type='text/css' />";
		$code = (file_exists(SKELETON_CSS_PATH.$row)) 
			? file_get_contents(SKELETON_CSS_PATH.$row) 
			: ( file_exists($_SERVER['DOCUMENT_ROOT']."$row")
				? file_get_contents($_SERVER['DOCUMENT_ROOT']."$row") 
				: curl_file_get_contents($row) );

		$this->css .= html_comment(" CSS: ".basename($row)).
			"\n<style>\n".
			(strpos($row, '.min') ? $code : css_minify($code)).
			"\n</style>\n";
		}

	//..............................................................................
	// добавляет код загрузки сценария Jaascript
	//..............................................................................
	public function add_async_javascript($row)
		{
//		$this->js .= TAB."<script type='text/javascript' src='".$row."?ver=".JS_VER."'></script>";
		$this->js .= TAB."<script type='text/javascript' src='{$row}".((strpos($row, 'http')===false) ? "?ver=".JS_VER : '')."'></script>";

/*		$code = (file_exists(SKELETON_JS_PATH.$row)) ? file_get_contents(SKELETON_JS_PATH.$row) : 
			((file_exists($_SERVER['DOCUMENT_ROOT']."$row")) ? file_get_contents($_SERVER['DOCUMENT_ROOT']."$row") : curl_file_get_contents($row));

		$this->js .= html_comment(" JavaScript: ".basename($row)).
			TAB."<script>\n".
			$code.
			TAB."</script>\n";
*/
		}
		
	//..............................................................................
	// добавляет код html javascript в переменную $js (HEAD)
	//..............................................................................
	public function add_minify_javascript($row)
		{
//		$filename = (strpos($row, SKELETON_CORE_PATH) !==false) ? $row : $_SERVER['DOCUMENT_ROOT'].$row;
		$this->js_minify .=
			TAB.html_comment(" JavaScript: ".basename($row)).
			TAB."<script type='text/javascript'>".
			(strpos($row, '.min') ? file_get_contents($row) : minify_js(file_get_contents($row))).
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
		if (file_exists("{$_SERVER['DOCUMENT_ROOT']}$file"))
			{
			$this->media .= 
				html_comment(basename($file)).
				css_minify(
				TAB."<style>".
				TAB."@media only all and (max-device-width: {$size}px) {".file_get_contents("{$_SERVER['DOCUMENT_ROOT']}$file")."}".
				TAB."@media only all and (max-width: {$size}px) {".file_get_contents("{$_SERVER['DOCUMENT_ROOT']}$file")."}".
				TAB."</style>"
				);
			}
		}

	//..............................................................................
	// добавляет данные в заголовок страницы из массива данных из контроллера
	//..............................................................................
	// $plug_og
	// 		'subtitle'	=> подзаголовок страницы (тот же в теге <title>)
	//		'description'	=> описание (в <мета property="description")
	//		'image'		=> ссылка на изображение (link rel="image_src")
	//		'type'		=> тип данных (по умолчанию website)
	//		'url'		=> ссылка на страницу
	//
	//..............................................................................
	public function prepare()
		{
		global $plug_og, $plug_css, $plug_js, $plug_media, $plug_meta, $plug_skip;

		if (!is_array($plug_skip))
			{
			$plug_skip = [];
			}

		$this->title 	= isset($plug_og['title']) ? $plug_og['title'] : get_const('CMS_NAME');
		if (isset($plug_og['subtitle']))
			{
			$this->subtitle = $plug_og['subtitle'];
			$this->title = $this->title.(($this->subtitle!="") ? " | {$this->subtitle}" : "");
			}
		$this->add_og('title', $this->title);
		$this->add_tw('title', $this->title);

		if (isset($plug_og['description']))
			{
			$this->description = get_str_cut(str_replace('  ', '', strip_tags($plug_og['description'])), DEFAULT_DESC_LEN);
			$this->add_meta('description', $this->description);
			$this->add_og('description', $this->description);
			$this->add_tw('description', $this->description, true);
			}

		if (!isset($plug_og['image']))
			{
			$plug_og['image'] = DEFAULT_OG_IMAGE;
			}


		$this->image = ((get_picture_tech($plug_og['image'])!='OG_AVATAR')
			? get_thumbnail($plug_og['image'], 'OG_AVATAR')
			: $plug_og['image'])."?".rand_id();
		
		$this->add_og('image', $this->image);
		$this->add_tw('image:src', $this->image, true);

		if (isset($plug_og['type']))
			{
			$this->type = $plug_og['type'];
			}
		$this->add_og('type', $this->type);


		if (isset($plug_og['url']))
			{
			$this->url = $plug_og['url'];
			} else $this->url = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		$this->add_og('url', $this->url);
		$this->add_tw('url', $this->url);

		// компилируем javascript
		if (is_array($plug_js))
			foreach (array_unique($plug_js) as $row)
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
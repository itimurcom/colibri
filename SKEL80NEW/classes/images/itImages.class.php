<?
global $itimages_counter;
$itimages_counter = (function_exists('rand_id')) ? rand_id() : time();

//-------------------------------------------------------------------------------------
// itImages : класс обработки галереи изображений в поле таблицы
//-------------------------------------------------------------------------------------
class itImages
	{
	public $data, $code;
	//..............................................................................
	// конструктор класса - создает привязку редактора к записи в базе данных
	//..............................................................................
	public function __construct($data=NULL)
		{
		global $itimages_counter;
		$itimages_counter++;
		$this->table_name 	= isset($data['table_name']) 	? $data['table_name'] 	: DEFAULT_IMAGES_TABLE;
		$this->rec_id	 	= isset($data['rec_id'])	? $data['rec_id'] 	: NULL;
		$this->column	 	= isset($data['column']) 	? $data['column'] 	: DEFAULT_IMAGES_COLUMN;		// поле хранения изображения (массив)
		$this->field	 	= isset($data['field']) 	? $data['field'] 	: DEFAULT_IMAGES_FIELD;			// поле хранения изображения (массив)
		$this->type		= isset($data['type']) 		? $data['type'] 	: DEFAULT_IMAGES_TYPE;			// тип слайдер или галлерея
		$this->name 		= isset($data['name'])		? $data['name'] 	: "{$itimages_counter}-images";
		$this->element_id	= isset($data['element_id'])	? $data['element_id'] 	: "{$this->name}-{$this->type}";
		$this->title		= isset($data['title']) 	? $data['title'] 	: NULL;		
		$this->img_class	= isset($data['img_class']) 	? $data['img_class'] 	: DEFAULT_IMAGES_CLASS;
		$this->img_type		= isset($data['img_type']) 	? $data['img_type'] 	: DEFAULT_IMAGES_IMGTYPE;		// тип слайдер или галлерея
		$this->edclass 		= isset($data['edclass']) 	? $data['edclass'] 	: DEFAULT_IMAGES_EDCLASS;		// тип слайдер или галлерея
		$this->class 		= isset($data['class']) 	? $data['class'] 	: NULL;
		
		$this->mode		= isset($data['mode']) 		? $data['mode'] 	: DEFAULT_IMAGES_MODE;
		$this->pause		= isset($data['pause']) 	? $data['pause'] 	: DEFAULT_IMAGES_PAUSE;	
	
		$this->f_caption	= isset($data['f_caption']) 	? $data['f_caption'] 	: NULL;					// функция генерирования строки титула изображения
		$this->f_title		= isset($data['f_title']) 	? $data['f_title'] 	: NULL;					// функция титула изображения
				
		$this->data 		= isset($data['data'])		? $data['data']		: itMySQL::_get_rec_from_db($this->table_name, $this->rec_id);

		$this->root 		= isset($data['root']) 		? $data['root'] 	: NULL;

		$this->minSlides	= isset($data['minSlides']) 		? $data['minSlides'] 	: 1;
		$this->maxSlides	= isset($data['maxSlides']) 		? $data['maxSlides'] 	: 1;
		
		if (!is_null($this->root))
			{
			$this->storage = &$this->data[$this->column][$this->root][$this->field];
			} else  {
				$this->storage = &$this->data[$this->column];
				}		
		// подкорретирем номер записи
		$this->rec_id		= isset($data['data'])
			? (isset($data['data']['id']) ? $data['data']['id'] : NULL)
			: $this->rec_id;
		}

	//..............................................................................
	// простая ленточная галлерея
	//..............................................................................	
	public function _view_gallery()
		{
		$result = NULL;
		if (isset($this->storage) AND is_array($this->storage))
		foreach ($this->storage as $key=>$image)
			{
			$caption = stripQuotas(function_exists($f_caption = $this->f_caption) ? $f_caption($this->data, $key) : NULL);
			$title = stripQuotas(function_exists($f_title = $this->f_title) ? $f_title($this->data, $key) : $this->title);
			$result .=
				TAB."<div class='avatar boxed'>".
				TAB."<div class='image boxed'>".				
				get_big_thumbnail([
					'src'		=> basename($image), 
					'class'		=> $this->img_class,
					'gallery'	=> $this->name,
					'title'		=> $title,
					'type'		=> $this->img_type,
					'caption'	=> $caption,
					]).
				(!is_null($caption) ? TAB."<div class='caption'>{$caption}</div>" : NULL).
				TAB."</div>".
				TAB."</div>";
			}
		
		$class_str = !is_null($this->class) ? " {$this->class}" : NULL;

		return 
			TAB."<div class='container itimages{$class_str} boxed fancygall' id='{$this->element_id}'>".
			$result.
			TAB."</div>";
		}
		
	//..............................................................................
	// простая ленточная галлерея
	//..............................................................................	
	public function _edit_gallery()
		{
		$result = NULL;
		if (isset($this->storage) AND is_array($this->storage))
		foreach ($this->storage as $key=>$image)
			{
			$caption = stripQuotas(function_exists($f_caption = $this->f_caption) ? $f_caption($this->data, $key) : NULL);
			$title = stripQuotas(function_exists($f_title = $this->f_title) ? $f_title($this->data, $key) : $this->title);

			$data = [
				'table_name'	=> $this->table_name,
				'rec_id'	=> $this->rec_id,
				'field'		=> $this->field,
				'column'	=> $this->column,
				'image'		=> $image,
				'key'		=> $key,
				'count'		=> count($this->storage),					
				];
			
			$result .=
				TAB."<div class='avatar boxed'>".
				TAB."<div class='image'>".
				get_big_thumbnail([
					'src'		=> basename($image), 
					'class'		=> $this->img_class,
					'gallery'	=> $this->name,
					'title'		=> $title,
					'type'		=> $this->img_type,
					'caption'	=> $caption,
					]).
				(!is_null($caption) ? TAB."<div class='caption'>{$caption}</div>" : NULL).
				TAB."</div>".
				TAB."<div class='gallery_n'>#".($key+1)."</div>".
					get_itimage_x_event($data).
					get_itimage_down_event($data).
					get_itimage_up_event($data).
					get_itimage_n_event($data).
				TAB."</div>";
			}

		$event_data = [
			'table_name'	=> $this->table_name,
			'rec_id'	=> $this->rec_id,
			'field'		=> $this->field,
			'column'	=> $this->column,			
			];

		$class_str = !is_null($this->class) ? " {$this->class}" : NULL;

		return 
			TAB."<div class='container itimages{$class_str} boxed fancygall' id='{$this->element_id}'>".
			$result.
			get_itimage_add_event($event_data).
			TAB."</div>";			
		}

	//..............................................................................
	// просмотр слайдера из данных
	//..............................................................................	
	public function _view_slider()
		{
		global $_USER;
		$result = NULL;
			
		if (isset($this->storage) AND is_array($this->storage))
		foreach ($this->storage as $key=>$image)
			{
			$caption = stripQuotas(function_exists($f_caption = $this->f_caption) ? $f_caption($this->data, $key) : NULL);
			$title = stripQuotas(function_exists($f_title = $this->f_title) ? $f_title($this->data, $key) : $this->title);
			$result .=
				TAB."<div>".
				get_big_thumbnail([
					'src'		=> basename($image), 
					'class'		=> $this->img_class,
					'gallery'	=> $this->name,
					'title'		=> $title,
					'type'		=> $this->img_type,
					'caption'	=> $caption,
					]).
				TAB."</div>";
			}
	
		return	
			TAB."<div class='slider fancygall' id='{$this->element_id}'>".
			$result.
			TAB."</div>".
			TAB.minify_js(
			"<script>
			$('#{$this->element_id}').css({'visibility':'hidden','opacity':'0'});
			$(document).ready(function()
				{
				$('#{$this->element_id}').bxSlider(
					{
					minSlides: {$this->minSlides}, 
					maxSlides: {$this->maxSlides},
					moveSlides: 1,
					mode: 		'{$this->mode}',
					useCSS: 	false,
					pause: 		{$this->pause}, 
//					captions: 	true,
					auto:		true,
					autoStart:	true,
					touchEnabled: false,
					preloadImages: 'visible',
					});
				$('#{$this->element_id}').css('visibility','visible');
				$('#{$this->element_id}').animate({'opacity':'1'});
				});
			</script>");			
		}

	//..............................................................................
	// редактирование слайдера из данных
	//..............................................................................	
	public function _edit_slider()
		{
		global $_USER;
		$result = NULL;
			
		if (isset($this->storage) AND is_array($this->storage))
		foreach ($this->storage as $key=>$image)
			{
			$caption = stripQuotas(function_exists($f_caption = $this->f_caption) ? $f_caption($this->data, $key) : NULL);
			$title = stripQuotas(function_exists($f_title = $this->f_title) ? $f_title($this->data, $key) : $this->title);

			$data = [
				'table_name'	=> $this->table_name,
				'rec_id'	=> $this->rec_id,
				'field'		=> $this->field,
				'column'	=> $this->column,
				'image'		=> $image,
				'key'		=> $key,
				'count'		=> count($this->storage),
				];
			
			$result .=
				TAB."<div>".				
				get_big_thumbnail([
					'src'		=> basename($image), 
					'class'		=> $this->img_class,
					'gallery'	=> $this->name,
					'title'		=> $title,
					'type'		=> $this->img_type,
					'caption'	=> $caption,
					]).
//				(!is_null($caption) ? TAB."<div class='caption'>{$caption}</div>" : NULL).				
 				TAB."<div class='gallery_n'>#".($key+1)."</div>".
					get_itimage_x_event($data).
					get_itimage_down_event($data).
					get_itimage_up_event($data).
					get_itimage_n_event($data).	
				TAB."</div>".
				"";

			}

		$event_data = [
			'table_name'		=> $this->table_name,
			'rec_id'		=> $this->rec_id,
			'field'		=> $this->field,
			'column'	=> $this->column,
			];
				
		return	
			TAB."<div class='itimages slider fancygall' id='{$this->element_id}'>".
			$result.
			TAB."</div>".
			TAB.minify_js(
			"<script>
			$('#{$this->element_id}').css({'visibility':'hidden','opacity':'0'});
			$(document).ready(function()
				{
				$('#{$this->element_id}').bxSlider(
					{
					minSlides: {$this->minSlides}, 
					maxSlides: {$this->maxSlides},
					moveSlides : 	1,
					mode: 		'{$this->mode}',
					useCSS: 	false,
					pause: 		{$this->pause}, 
//					captions: 	true, 
					auto: 		false,
					autoStart: 	false,
					touchEnabled: false,
					preloadImages: 'visible',					
					});
				$('#{$this->element_id}').css('visibility','visible');
				$('#{$this->element_id}').animate({'opacity':'1'});
				});
			</script>").
			get_itimage_add_event($event_data).
		"";
		}
		

	//..............................................................................
	// просмотр галлереи 
	//..............................................................................	
	public function _view()
		{
		switch($this->type)
			{
			case 'gallery' : {
				return $this->_view_gallery();
				break;
				}
			case 'slider' : {
				return $this->_view_slider();
				break;
				}
			}
		}

	//..............................................................................
	// редактирование галлереи 
	//..............................................................................	
	public function _edit()
		{
		switch($this->type)
			{
			case 'gallery' : {
				return $this->_edit_gallery();
				break;
				}
			case 'slider' : {
				return $this->_edit_slider();
				break;
				}

			}
		}
				
	//..............................................................................
	// разыменовывает поле 
	//..............................................................................	
	public function compile()
		{
		global $_USER;
		$this->code = $_USER->is_logged(itEditor::moderators())
			? $this->_edit()
			: $this->_view();		
		}

	//..............................................................................
	// возвращает код текстового блока
	//..............................................................................
	public function code()
		{
		return $this->code;
		}

	//..............................................................................
	// сохраняет поле с изображениями
	//..............................................................................
	public function store()
		{
		itMySQL::_update_value_db($this->table_name, $this->rec_id, $this->storage, $this->column);
		}
		

	//..............................................................................
	// обработка стандартных событий в обработчике
	//..............................................................................
	static function events($url='/', $path=UPLOADS_ROOT)
		{
		return itimages_events($url, $path);
		}
		
	//..............................................................................
	// перемещает запись изображения в галлереее поля
	//..............................................................................
	public function gal_replace($gal_id, $place_id)
		{
		if ($gal_id>0)
			{
			$this->storage['tmp']	= $this->storage[$gal_id];
			$this->storage[$gal_id]	= $this->storage[$place_id];
			$this->storage[$place_id] 	= $this->storage['tmp'];
			unset($this->storage['tmp']);
			$this->sort_gallery();			
			}
		}

	//..............................................................................
	// удаляет запись изображения из галлереее поля
	//..............................................................................
	public function gal_x($gal_id)
		{
		if (isset($this->storage[$gal_id]))
			{
			unset($this->storage[$gal_id]);
			$this->sort_gallery();
			}
		}

	//..............................................................................
	// поднимает запись изображения в галлерее поля вверх на одну позицию
	//..............................................................................
	public function gal_up($gal_id)
		{
		if ($gal_id>0)
			{
			$this->storage['tmp'] 	= $this->storage[$gal_id];
			$this->storage[$gal_id]	= $this->storage[($gal_id-1)];
			$this->storage[($gal_id-1)] 	= $this->storage['tmp'];
			unset($this->storage['tmp']);
			$this->sort_gallery();
			}
		}

	//..............................................................................
	// перемещает запись изображения в галлереее поля
	//..............................................................................
	public function gal_move($gal_id, $new_id)	
		{
		if ($gal_id!=$new_id)
			{
			$this->storage['tmp'] 	= $this->storage[$gal_id];
			$this->storage['tmp2'] 	= $this->storage[$new_id];
			$this->storage[$new_id] 	= $this->storage['tmp'];
			$this->storage[$gal_id] 	= $this->storage['tmp2'];
			unset($this->storage['tmp']);
			unset($this->storage['tmp2']);
			$this->sort_gallery();
			}
		}


	//..............................................................................
	// опускает запись изображения в галлерее поля вниз на одну позицию
	//..............................................................................
	public function gal_down($gal_id)
		{
		if ($gal_id<=count($this->storage))
			{
			$this->storage['tmp'] 	= $this->storage[$gal_id];
			$this->storage[$gal_id]	= $this->storage[($gal_id+1)];
			$this->storage[($gal_id+1)] 	= $this->storage['tmp'];
			unset($this->storage['tmp']);
			$this->sort_gallery();
			}
		}

	//..............................................................................
	// сортирует индексы изображений в галлерее поля
	//..............................................................................
	public function sort_gallery()
		{
		
		if (isset($this->storage) AND (is_array($this->storage))) {
			$this->storage = array_values($this->storage);
			}
		}

	//..............................................................................
	// создает редактируемый контейнер для загрузки галлереи поля по ajax
	//..............................................................................
	public function container($options=NULL)
		{
		global $_USER;
		
		$this->data['state'] 		= isset($options['state']) ? $options['state'] : DEFAULT_IMAGESSTATE;
		$this->data['container_id']	= itImages::_container_id((array)$this);
		
		$data = itEditor::event_data([
			'table_name'	=> $this->table_name,
			'rec_id'	=> $this->rec_id,
			'field'		=> $this->field,
			'column'	=> $this->column,	
			'type'		=> $this->type,
			'name'		=> $this->name,
			'element_id'	=> $this->element_id,
			'title'		=> $this->title,
			'img_class'	=> $this->img_class,
			'img_type'	=> $this->img_type,
			'mode'		=> $this->mode,
			'pause'		=> $this->pause,
			'f_caption'	=> $this->f_caption,
			'f_title'	=> $this->f_title,
			'edclass'	=> $this->edclass,			
			'container_id'	=> $this->data['container_id'],
			'state'		=> $this->data['state'],
			]);
			

		return $_USER->is_logged(itEditor::moderators())
			? 
				TAB."<div class='itimages_container {$this->edclass}' id='".itImages::_container_id((array)$this)."' rel='{$data}'>".
				( ($this->data['state']=='view') ? $this->_view() : $this->_edit() ).
				get_itimages_async_event($this->data).
				TAB."</div>".
				""
			: 	$this->_view();
		}		
		
	//..............................................................................
	// возвращает имя контейнера
	//..............................................................................
	static function _container_id($data)
		{
		return "itimages-{$data['rec_id']}-{$data['table_name']}-{$data['column']}-{$data['field']}-container";
		}		
	}
?>
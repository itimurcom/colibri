<?php
global $upgal_counter;
$upgal_counter = (function_exists('rand_id')) ? rand_id() : time();

// itUpGal : класс построение поля ввода галереи изображений для формы
class itUpGal
	{
	public $element_id, $code, $name, $value, $ajax, 
		$label, $compact, $form_id,
		
		$upgal_class, $upgal_field, $upgal_img,
		
		$gallery_field, $files_field, $data_field,
		$btn_code, $gallery_code, $label_compact, $field_compact;

	// конструктор класса - создает поле ввода текста
	public function __construct($options=NULL)
		{
		global $upgal_counter;
		$options = is_array($options) ? $options : [];
		$class_options = (isset($options['class']) AND is_array($options['class'])) ? $options['class'] : [];
		
	
		$this->form_id		= ready_val(isset($options['form_id']) ? $options['form_id'] : NULL, "");
		$this->name		= ready_val(isset($options['name']) ? $options['name'] : NULL, DEFAULT_UPGAL_NAME);
		$this->element_id	= ready_val(isset($options['element_id']) ? $options['element_id'] : NULL, "{$this->form_id}-{$this->name}");
		
		$this->upgal_class	= ready_val(isset($class_options['main']) ? $class_options['main'] : NULL, DEFAULT_UPGAL_CLASS);
		$this->upgal_field	= ready_val(isset($class_options['field']) ? $class_options['field'] : NULL, DEFAULT_UPGAL_FIELD);
		$this->upgal_img	= ready_val(isset($class_options['img']) ? $class_options['img'] : NULL, DEFAULT_UPGAL_IMG);

		$request_value = (isset($_REQUEST) AND is_array($_REQUEST) AND array_key_exists($this->name, $_REQUEST)) ? $_REQUEST[$this->name] : '';
		$this->value		= ready_val(isset($options['value']) ? $options['value'] : NULL, $request_value);
		$this->value		= is_array($this->value) ? implode(DEFAULT_UPGAL_DELIMITER, $this->value) : (string)$this->value;
		
		$this->compact		= ready_val(isset($options['compact']) ? $options['compact'] : NULL, DEFAULT_UPGAL_COMPACT);
		$this->label_compact 	= $this->compact ? " class='compact'" : "";
		$this->field_compact 	= $this->compact ? " compact" : "";
		$this->label		= ready_val(isset($options['label']) ? $options['label'] : NULL, TAB."\t<label{$this->label_compact}></label>");
		
		// данные для построения кода поля
		$this->data_field	= "{$this->element_id}";
		$this->files_field	= "file-{$this->element_id}";
		$this->gallery_field	= "gallery-{$this->element_id}";		

		$this->btn();
		$this->gallery();
		$this->compile();
		}


	// генерирует html код кнопки поля ввода
	public function btn()
		{
		$this->btn_code = 
			TAB."<input type='hidden' id='{$this->data_field}' rel='{$this->gallery_field}' name='{$this->name}' value='{$this->value}'/>".
			TAB."<div class='{$this->upgal_field}'>".		
			TAB."<img class='{$this->upgal_img}' src='/themes/".CMS_THEME."/images/".DEFAULT_UPGAL_BTN_IMG."' onclick=\"document.getElementById('$this->files_field').click();\"/>".
			TAB."<input class='upload_gal_btn' name='".DEFAULT_UPGAL_FILES."' style='display: none;' rel='{$this->element_id}' accept='image/jpeg,image/png,image/gif' type='file' id='{$this->files_field}' multiple>".
			TAB."</div>";
		}

	// генерирует html код галерреи добавленных изображений поля ввода
	public function gallery()
		{
		$images_result = NULL;

		if (is_array($images = str_getcsv($this->value, DEFAULT_UPGAL_DELIMITER)))
			{
			foreach($images as $image_key=>$image_row)
				if (!is_null($image_row) AND file_exists(UPLOADS_ROOT.$image_row))
				$images_result .= 
					get_form_gallery_row($image_row, $this->data_field);
			}
			
		$this->gallery_code =
			TAB."<div class='fancygall {$this->field_compact}' id='{$this->gallery_field}' rel='{$this->files_field}'>".
			$images_result.
			TAB."</div>";
		}


	// генерирует html код поля ввода
	public function compile()
		{
		$label_compact = $this->compact ? " class='compact'" : "";
		$field_compact = $this->compact ? " compact" : "";
		$class_str = (!is_null($this->upgal_class) OR $this->compact) ? " class='{$this->upgal_class}{$field_compact}'" : "";

		$this->code =
			TAB."<div class='{$class_str}'>".
			$this->label.
			$this->gallery_code.
			$this->btn_code.
			TAB."</div>";
		}	
	// возвращает код
	public function code()
		{
		return $this->code;
		}
	// обработка стандартных событий в обработчике
	static function events($url='/', $path=UPLOADS_ROOT)
		{
		return upload_gal_events($url, $path);
		}

	}
?>

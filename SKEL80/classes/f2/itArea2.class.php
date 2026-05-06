<?php
global $area_counter;
$area_counter = (function_exists('rand_id')) ? rand_id() : time();

// itArea2 : класс построения поля текста для формы (2.1)
class itArea2
	{
	public $element_id, $code, $type, $name, $value, $ajax, $class, $placeholder, $label, $compact, $form_id, $no_label, $max;

	// конструктор класса - создает поле ввода текста
	public function __construct($options=NULL)
		{
		global $area_counter;
		$area_counter++;
		$options = is_array($options) ? $options : [];

//		if (isset($options['name']) AND $options['name'] == 'f2_values') {print_r($options); die;}
		
		$this->form_id		= ready_val(isset($options['form_id']) ? $options['form_id'] : NULL, "");
		$this->name		= ready_val(isset($options['name']) ? $options['name'] : NULL, "area-{$area_counter}");
		$this->element_id	= ready_val(isset($options['element_id']) ? $options['element_id'] : NULL, "{$this->form_id}-{$this->name}");
		$this->class		= "fixed";
		$request_value = (isset($_REQUEST) AND is_array($_REQUEST) AND array_key_exists($this->name, $_REQUEST)) ? $_REQUEST[$this->name] : '';
		$this->value		= itForm2::_smart_value(ready_val(isset($options['value']) ? $options['value'] : NULL, $request_value), true);
		
		$this->placeholder	= ready_val(isset($options['placeholder']) ? $options['placeholder'] : NULL);
		$this->label		= ready_val(isset($options['label']) ? $options['label'] : NULL);
		$this->no_label		= ready_val(isset($options['no_label']) ? $options['no_label'] : NULL, DEFAULT_AREA_NOLABEL);		
		$this->compact		= ready_val(isset($options['compact']) ? $options['compact'] : NULL, DEFAULT_AREA_COMPACT);
		$this->max		= ready_val(isset($options['max']) ? $options['max'] : NULL);
		$this->compile();
		}

	// генерирует html код поля ввода
	public function compile()
		{		
		$compact = $this->compact ? " compact" : "";
		$class_str = (!is_null($this->class) OR $this->compact) ? " class='{$this->class}{$compact}'" : "test";
		
		$protection_str = !is_null($this->max)
			? TAB.minify_js(
			"<script>
				$('#{$this->element_id}').keyup(function()
					{
					if (this.value.length > {$this->max})
						{
						this.value = this.value.substr(0, {$this->max});
					if (!$(this).hasAttr('evented'))
						{
						$('<span class=\'red\'>".get_const('MAX_AREA_ALLOWED')." {$this->max}</span>').insertAfter($(this));
						$(this).attr('evented','1');
						}
					}
				});
			</script>") : "";
			
		$value = is_array($this->value) ? get_field_by_lang($this->value, CMS_LANG, '') : $this->value;
		$value = is_scalar($value) ? (string)$value : '';
		$compile_code =				
				TAB."\t<textarea id=\"{$this->element_id}\"{$class_str} name=\"{$this->name}\"".(($this->placeholder) ? " placeholder=\"{$this->label}\"" : '').">".
				htmlentities(stripslashes($value)).
				"</textarea>".
				$protection_str;


		$this->code =
			($this->no_label)
				? 	$compile_code
				:	TAB."<div class=\"modal_row{$compact}\">".
						itForm2::_label_zone((array) $this).
						$compile_code.
					TAB."</div>";
		}	

	// возвращает код
	public function code()
		{
		return $this->code;
		}
	}
?>

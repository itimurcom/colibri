<?php
global $area_counter;
$area_counter = (function_exists('rand_id')) ? rand_id() : time();

// itArea : класс построение поля текса для формы
class itArea
	{
	public $element_id, $code, $type, $name, $value, $ajax, $class, $placeholder, $label, $compact, $form_id, $editor, $max;

	// конструктор класса - создает поле ввода текста
	public function __construct($options=NULL)
		{
		global $area_counter;
		$options = is_array($options) ? $options : [];
		
		$this->form_id		= ready_val(isset($options['form_id']) ? $options['form_id'] : NULL, "");
		$this->name		= ready_val(isset($options['name']) ? $options['name'] : NULL, "area-{$area_counter}");
		$this->element_id	= ready_val(isset($options['element_id']) ? $options['element_id'] : NULL, "{$this->form_id}-{$this->name}");
		$this->class		= ready_val(isset($options['class']) ? $options['class'] : NULL);
		$request_value = (isset($_REQUEST) AND is_array($_REQUEST) AND array_key_exists($this->name, $_REQUEST)) ? $_REQUEST[$this->name] : '';
		$this->value		= ready_val(isset($options['value']) ? $options['value'] : NULL, $request_value);
		
		$this->placeholder	= ready_val(isset($options['placeholder']) ? $options['placeholder'] : NULL);
		$this->label		= ready_val(isset($options['label']) ? $options['label'] : NULL);
		$this->compact		= ready_val(isset($options['compact']) ? $options['compact'] : NULL, DEFAULT_AREA_COMPACT);
		$this->max		= ready_val(isset($options['max']) ? $options['max'] : NULL);
		$this->editor		= ready_val(isset($options['editor']) ? $options['editor'] : NULL);
				
		$this->compile();
		}

	// генерирует html код поля ввода
	public function compile()
		{
		global $form_blocks;
		
		$compact = $this->compact ? " compact" : "";
		$class_str = (!is_null($this->class) OR $this->compact) ? " class='{$this->class}{$compact}'" : "";
		
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

		$this->code = mstr_replace([
			'[TITLE]'	=> empty($this->placeholder) ? $this->label : '',
			'[COMPACT]'	=> $compact,
			'[EDITOR]'	=> $this->editor,
			'[CODE]'	=> 
				TAB."\t<textarea id=\"{$this->element_id}\" name=\"{$this->name}\"".(($this->placeholder) ? " placeholder=\"{$this->label}\"" : '')."{$class_str}>".
				htmlentities(stripslashes($value)).
				"</textarea>".
				$protection_str,
			], TAB.$form_blocks['AREA']['code']);
			
			
		} 
	// возвращает код
	public function code()
		{
		return $this->code;
		}
	}
?>

<?php
global $input_counter;
$input_counter = (function_exists('rand_id')) ? rand_id() : time();

// itInput : класс построение поля ввода для формы
class itInput
	{
	public $element_id, $code, $type, $name, $value, $ajax, $class, $placeholder, $label, $compact, $form_id, $editor, $readonly;

	// конструктор класса - создает поле ввода текста
	public function __construct($options=NULL)
		{
		global $input_counter;
		$options = is_array($options) ? $options : [];
		
		$this->form_id		= ready_val(isset($options['form_id']) ? $options['form_id'] : NULL, "");
		$this->name		= ready_val(isset($options['name']) ? $options['name'] : NULL, "input-{$input_counter}");
		$this->element_id	= ready_val(isset($options['element_id']) ? $options['element_id'] : NULL, "{$this->form_id}-{$this->name}");
		$this->class		= ready_val(isset($options['class']) ? $options['class'] : NULL);
		$request_value = (isset($_REQUEST) AND is_array($_REQUEST) AND array_key_exists($this->name, $_REQUEST)) ? $_REQUEST[$this->name] : '';
		$this->value		= ready_val(isset($options['value']) ? $options['value'] : NULL, $request_value);
		
		$this->placeholder	= ready_val(isset($options['placeholder']) ? $options['placeholder'] : NULL);
		$this->label		= ready_val(isset($options['label']) ? $options['label'] : NULL);
		$this->compact		= ready_val(isset($options['compact']) ? $options['compact'] : NULL, DEFAULT_INPUT_COMPACT);
		$this->editor		= ready_val(isset($options['editor']) ? $options['editor'] : NULL);
		
		$this->type		= ready_val(isset($options['type']) ? $options['type'] : NULL, DEFAULT_INPUT_TYPE);
		$this->ajax		= ready_val(isset($options['ajax']) ? $options['ajax'] : NULL);
		$this->readonly		= ready_val(isset($options['readonly']) ? $options['readonly'] : NULL, false);
				
		$this->compile();
		}

	// генерирует html код поля ввода
	public function compile()
		{
		global $form_blocks;
			
		$compact = $this->compact ? " compact" : "";
		$class_str = (!is_null($this->class) OR $this->compact) ? " class='{$this->class}{$compact}'" : "";

		switch ($this->type)
			{
			case 'email' : {
				$onchange = " onchange=\"if (typeof('email_checker'=== 'function')) { email_checker(this); }\"";
				$type = 'text';
				break;
				}
			case 'phone' : {
				$onchange = " onchange=\"if (typeof('phone_checker'=== 'function')) { phone_checker(this); }\"";
				$type = 'text';
				break;
				}
			case 'password' : {
				$onchange = '';
				$type = 'password';
				break;
				}
			case 'ajax' : {
				$onchange = " onkeyup=\" $this->ajax \"";
				$type = 'text';
				break;
				}

			default : {
				$onchange = '';
				$type = 'text';
				}
			}
		
		$readonly = $this->readonly ? " readonly" : NULL;
		$value = is_array($this->value) ? get_field_by_lang($this->value, CMS_LANG, '') : $this->value;
		$value = is_scalar($value) ? (string)$value : '';
		
		$this->code = mstr_replace([
			'[TITLE]'	=> empty($this->placeholder) ? $this->label : '',
			'[COMPACT]'	=> $compact,
			'[EDITOR]'	=> $this->editor,
			'[CODE]'	=> TAB."<input{$readonly} type=\"{$type}\" id=\"{$this->element_id}\" name=\"{$this->name}\" value=\"".htmlentities(stripslashes($value))."\" ".(($this->placeholder) ? " placeholder=\"{$this->label}\"" : '')."{$class_str}{$onchange}/>",
			], TAB.$form_blocks['INPUT']['code']);
		}

	// возвращает код
	public function code()
		{
		return $this->code;
		}
	}
?>

<?php
// ================ CRC ================
// version: 1.15.02
// hash: 1a7357f3f686a4d99f1018d45224de3d8a64e96ec0067029de172bdadb1a0329
// date: 09 September 2019  5:10
// ================ CRC ================
global $autoselect_counter, $plug_css;
$autoselect_counter = (function_exists('rand_id')) ? rand_id() : 0;

//..............................................................................
// itAutoSelect : класс автоматического выбора данных по поиску
//..............................................................................
class itAutoSelect
	{
	public $code, $field_id, $type, $class, $action, $op, $placeholder, $compact, $text;
	//..............................................................................
	// конструктор класса - создает элемент автоматического выбора данных
	//..............................................................................
	public function __construct($options=NULL, $label=NULL)
		{
		global $autoselect_counter;
		
		$this->options = $options;
		$autoselect_counter++;

		if (!isset($options['field_id']))
			{
			$this->field_id = "itautoselect-$autoselect_counter";
			} else $this->field_id = $options['field_id'];

		if (!isset($options['name']))
			{
			$this->name = "itautoselect-$autoselect_counter";
			} else $this->name = $options['name'];


		if (!isset($options['type']))
			{
			$this->type = $this->options['type'] = 'main';
			} else $this->type = $this->options['type'];

		if (isset($options['class']))
			{
			$this->class = $this->options['class'];
			} else $this->class = DEFAULT_AUTOSELECT_CLASS;

		if (!isset($options['action']))
			{
			$this->action = $this->options['class'] = '/more.php';
			} else $this->action = $this->options['action'];

		if (!isset($options['op']))
			{
			$this->op = $this->options['class'] = 'as_main';
			} else $this->op = $this->options['op'];

		if (isset($options['value']))
			{
			$this->value = $options['value'];
			} else $this->value = '';

		if (isset($options['text']))
			{
			$this->text = $options['text'];
			} else $this->text = NULL;


		if (isset($options['compact']))
			{
			$this->compact = $options['compact'];
			} else $this->compact = false;

		if (!isset($options['placeholder']))
			{
			$this->placeholder = $this->options['placeholder'] = '';
			} else $this->placeholder = $this->options['placeholder'] = $options['placeholder'] ;

		$this->label = !is_null($label) ? $label : ready_val($options['label']);
		$this->editor = ready_val($options['editor']);
		
		$this->compile();
		}

	//..............................................................................
	// генерирует код календаря на основе установленных параметров и заносит в code
	//..............................................................................
	public function compile()
		{
		global $form_blocks;
		$onchange ='';
		if (isset($options['ajax']))
			{
			$this->ajax = " onchange=\"{$this->options['ajax']}\"";
			} else $this->ajax=''; 

		switch ($this->type)
			{
			case 'main' : {
				$this->ajax = '';
				$action = 'window.location.href = ui.item.link';
				break;
				}

			case 'submit' : {
				$this->ajax = "$('#{$this->form_id}').submit();";
				$action = '';
				break;
				}

			case 'input' : {
//				$onchange = " onchange=\"$('#{$this->field_id}').val($('#field-{$this->field_id}').val());\"";
				$action = "
//			alert(ui.item.id);
			$('#{$this->field_id}').val(ui.item.id);
			$('#field-{$this->field_id}').val(ui.item.value)
			";
				break;
				}
			}
		
		$compact = $this->compact ? " compact" : "";
		$class_str = (!is_null($this->class) OR $this->compact) ? " class='{$this->class}{$compact}'" : "";
		
		$result =
			TAB."<input type=\"text\"{$class_str} id=\"field-{$this->field_id}\" name=\"field-{$this->field_id}\" value=\"{$this->text}\"".(($this->placeholder) ? " placeholder=\"{$this->placeholder}\"" : "")."{$onchange}/>".
			TAB."<input type=\"hidden\" id=\"{$this->field_id}\" value=\"{$this->value}\" name=\"{$this->name}\"{$this->ajax}/>".
			TAB.minify_js("
	<script>
	$('#field-{$this->field_id}').autocomplete(
		{
		source: '{$this->action}?op={$this->op}',
		html : true,
		select: function (event, ui) 
			{
			{$action}
			return false;
			}					
		});
	</script>");

		$this->code = mstr_replace([
			'[TITLE]'	=> $this->label,
			'[COMPACT]'	=> $compact,
			'[EDITOR]'	=> $this->editor,
			'[CODE]'	=> $result,
			], TAB.$form_blocks['AUTOSELECT']['code']);	
		}

	//..............................................................................
	// возвращает код выбора даты с привязкой обработчика события ($options)
	//..............................................................................
	public function code()
		{
		return $this->code;
		}

	} //class;
?>
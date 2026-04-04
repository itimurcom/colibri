<?php
// ================ CRC ================
// version: 1.35.02
// hash: 6a6b8666d32f725d9e882b8f05bfcac2d0b51b213e130fc7b7d5603a97efee08
// date: 20 September 2019 15:39
// ================ CRC ================
global $set_counter;
$set_counter = rand_id();
//..............................................................................
// itSet2 : класс построения поля набора для формы (2.1)
//..............................................................................
class itSet2
	{
	public $code, $sel_rec, $type, $name, $element_id, $options, $value, $ajax ;

	//..............................................................................
	// конструктор класса - создает поле множественного выбора из набора данных
	//..............................................................................
	public function __construct($options=NULL)
		{
		global $set_counter, $_TIMES;
		$set_counter ++;
		
		$this->name 		= ready_val($options['name'], "set-{$set_counter}");
		$this->element_id 	= ready_val($options['element_id'], "set-{$set_counter}");
		$this->form_id		= ready_val($options['form_id']);
		$this->type 		= ready_val($options['type'], DEFAULT_SET_TYPE);
		$this->class 		= ready_val($options['class'], DEFAULT_SET_CLASS);
		$this->label		= ready_val($options['label']);
		$this->no_label		= ready_val($options['no_label'], DEFAULT_SET_NOLABEL);
		$this->compact		= ready_val($options['compact'], DEFAULT_SET_COMPACT);
		$this->ajax		= ready_val($options['ajax']);
		
		$this->array		= ready_val($options['array']);
		$this->titles		= ready_val($options['titles'], 'title');
		$this->values		= ready_val($options['values'], 'value');
		$this->colors		= ready_val($options['colors'], 'color');		
		$this->value		= ready_val($options['value']);	

		$this->compile();
		}

	//..............................................................................
	// генерирует код селектора на основе установленных параметров и заносит в code
	//..............................................................................
	public function compile()
		{		
		$compile_code = NULL;

		switch ($this->type)
			{
			case 'set' : {
				$this->ajax = '';
				break;
				}

			case 'submit' : {
				$this->ajax = "$('#{$this->form_id}').submit();";
				break;
				}
			}
			
		$compact = $this->compact ? " compact" : "";
		$class_str = (!is_null($this->class) OR $this->compact) ? " class='boxed {$this->class}{$compact}'" : "class='boxed'";
		$onchange = !is_null($this->ajax) ? " onchange=\"{$this->ajax}\"" : NULL;		

		// подготовим данные для селектора
		if ( !mempty([$this->array, $this->titles, $this->values]) AND is_array($this->array))
			{
			$index = 0;
			foreach ($this->array as $row)
				{
				$title = is_array($tmp = ready_val($row[$this->titles], "{$index}")) ? get_field_by_lang($row[$this->titles]) : get_const($tmp);
				$value = isset($row[$this->values]) ? $row[$this->values] : "autooption{$index}";
				
				$checked = (ready_val($this->value["$value"]) OR (isset($_REQUEST["{$this->name}_{$value}"]) AND ($_REQUEST["{$this->name}_{$value}"]=='on'))) ? ' checked' : '';
				
				$color_str = isset($row[$this->colors]) ? " {$row[$this->colors]}" : NULL;
				$compile_code .= 
					TAB."<div class='option boxed{$color_str}'><input type='checkbox' name='{$this->name}_{$value}'{$checked}{$onchange}>{$title}</div>";
				$index++;
				}
			$compile_code =
				TAB."<div {$class_str}>".
				$compile_code.
				TAB."</div>";
			}

		$this->code =
			($this->no_label)
				? 	$compile_code
				:	TAB."<div class=\"modal_row{$compact}\">".
						itForm2::_label_zone((array) $this).
						$compile_code.
					TAB."</div>";
		}

	//..............................................................................
	// возвращает код селектора с привязкой обраточика события ($options)
	//..............................................................................
	public function code()
		{
		return $this->code;
		}
	} // class
?>
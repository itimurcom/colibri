<?php
// ================ CRC ================
// version: 1.35.01
// hash: 9548caa004ba0593753a4ca019df723e62bb0ffec3d64aabf0eae581a9c75ed5
// date: 09 September 2019  5:10
// ================ CRC ================
global $timepicker_counter;
//$datepicker_counter = time();
$timepicker_counter = rand_id();

//..............................................................................
// itTime2 : класс выбора времени (2.1)
//..............................................................................
class itTime2
	{
	public $code;
	public $value, $type, $options, $name, $element_id, $title, $value_mysql, $time, $image, $label, $compact, $clear, $no_label;
	
	//..............................................................................
	// конструктор класса - создает элемент управления выбора даты
	//..............................................................................
	public function __construct($options=NULL)
		{
		global $timepicker_counter, $_TIMES;
		$timepicker_counter ++;
		
		$this->name 		= ready_val($options['name'], "time-{$timepicker_counter}");
		$this->element_id 	= ready_val($options['element_id'], "time-{$timepicker_counter}");
		$this->form_id		= ready_val($options['form_id']);
		$this->type 		= ready_val($options['type'], DEFAULT_TIME_TYPE);
		$this->class 		= ready_val($options['class'], DEFAULT_TIME_CLASS);
		$this->label		= ready_val($options['label']);
		$this->no_label		= ready_val($options['no_label'], DEFAULT_TIME_NOLABEL);
		$this->compact		= ready_val($options['compact'], DEFAULT_TIME_COMPACT);
		$this->ajax		= ready_val($options['ajax']);
	
		$this->array		= isset($options['array']) ? $options['array'] : $_TIMES;
		$this->titles		= ready_val($options['titles'], 'title');
		$this->values		= ready_val($options['values'], 'value');

		$this->compile();
		}

	//..............................................................................
	// генерирует код календаря на основе установленных параметров и заносит в code
	//..............................................................................
	public function compile()
		{
		switch ($this->type)
			{
			case 'submit' : {
				$this->ajax = "$('#{$this->form_id}').submit();";
//				$this->ajax = "alert('submit');";
				break;
				}

			case 'text' : {
				$this->ajax = '';
				break;
				}

			default : {
				break;
				}

			}
		
		$compact = $this->compact ? " compact" : "";
		$class_str = (!is_null($this->class) OR $this->compact) ? " class='{$this->class}{$compact}'" : "";

		$o_select = new itSelect2((array) $this);
		$compile_code = $o_select->code();
		unset($o_select);
		
		$this->code =
			($this->no_label)
				? 	$compile_code
				:	TAB."<div class=\"modal_row{$compact}\">".
						itForm2::_label_zone((array) $this).
						$compile_code.
					TAB."</div>";
		}

	//..............................................................................
	// возвращает код выюоа даты с привязкой обработчика события ($options)
	//..............................................................................
	public function code()
		{
		return $this->code;
		}

	} //class;


?>
<?php
// ================ CRC ================
// version: 1.15.03
// hash: 50f5b385c36c72863b9fbd476265e9a44d204cba5e8ccae6408176154b70f04a
// date: 27 December 2019  6:56
// ================ CRC ================

global $datepicker_counter;
//$datepicker_counter = time();
$datepicker_counter = rand_id();
//..............................................................................
// itDate : класс выбора даты
//..............................................................................
class itDate
	{
	public $code;
	private $value, $type, $options, $name, $element_id, $title, $value_mysql, $time, $image, $label, $compact, $clear;
	
	//..............................................................................
	// конструктор класса - создает элемент управления выбора даты
	//..............................................................................
	public function __construct($value=NULL, $options=NULL)
		{
		global $datepicker_counter;
		$datepicker_counter ++;
		
		if (is_array($value))
			{
			$options = $value;
			$value = ready_val($options['value']);
			}
		
		$this->options = $options;

		$this->name 		= isset($options['name']) 	? $options['name'] 		: "datetime";
		$this->element_id 	= isset($options['element_id'])	? $options['element_id']	: "datetime-{$datepicker_counter}";
		$this->type 		= isset($options['type'])	? $options['type']		: 'submit';
		$this->class 		= isset($options['class'])	? $options['class']		: NULL;
		$this->image 		= isset($options['src']) 	? $options['src'] 		: get_const('DEFAULT_DATEPICKER_IMAGE');
		$this->time		= isset($options['time']) 	? $options['time'] 		: false;
		$this->label		= isset($options['label']) 	? $options['label'] 		: NULL;
		$this->compact		= isset($options['compact'])	? $options['compact']		: false;
		$this->clear		= isset($options['clear'])	? $options['clear']		: false;
		$this->editor		= ready_val($options['editor']);		

		$time = strtotime($value);

		if (!is_null($value))
			{
			$this->value = $value;
			$this->title = strftime("%d %B %Y",$time).(($this->time) ? " ".get_time_str($this->value) : "");
			$this->value_mysql = ($this->time) ? get_mysql_datetime($time) : get_mysql_date($time);
			} else	{
				$this->value = NULL;
				$this->value_mysql = NULL;
				}

		
		$this->hour = empty($value) ? 0 : strftime("%H",strtotime($this->value));
		$this->minute = empty($value) ? 0 : strftime("%M",strtotime($this->value));
		$this->compile();
		}

	//..............................................................................
	// генерирует код календаря на основе установленных параметров и заносит в code
	//..............................................................................
	public function compile()
		{
		global $form_blocks;
		if (isset($options['ajax']))
			{
			$this->ajax = $this->options['ajax'];
			} else $this->ajax=''; 

		switch ($this->type)
			{
			case 'submit' : {
				$this->ajax = "$('#{$this->options['form_id']}').submit();";
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
		
		$compact	= ($this->compact) 	? ' compact' 		: '';
		$class		= is_null($this->class) ? "{$compact}" 		: "{$this->class} {$compact}";
		
		$class_str	= ($class!='') 		? "class='{$class}'" 	: '';

		$result = 
			TAB."<input {$class_str} id='value_{$this->element_id}' name='value_{$this->name}' type='text' value='{$this->title}' readonly>".
			TAB."<input id='{$this->element_id}' name='{$this->name}' type='hidden' value='{$this->value_mysql}' onchange=\"{$this->ajax}\" >".
			TAB.minify_js("
		<script>
		$.datepicker.setDefaults($.datepicker.regional['".get_const('CMS_LANG')."']);
		$('#value_{$this->element_id}').date".(($this->time) ? "time" : "")."picker({
			showAnim:'slide',
			changeMonth: true,
			changeYear: true,
			yearRange: '-100:+10',
			dateFormat: 'd MM yy',".

			(($this->time) ?
"			timeFormat: 'HH:mm',
			stepMinute: 5,
			controlType: 'select',
			oneLine: true,
			hour: {$this->hour},
			minute: {$this->minute},
			altFieldTimeOnly: false,"
				: "").
"			altField: '#{$this->element_id}',
			altFormat: 'yy-mm-dd',
			showOn: 'button',
			buttonImage: '{$this->image}',
			buttonImageOnly: true,
			showButtonPanel:true,
			onSelect: function()
				{
				$('#{$this->element_id}').change();
				},".
			(($this->clear) ?
"			clearEnable: true,
			" : "").		
"			});
		</script>");
		$this->code = mstr_replace([
			'[TITLE]'	=> $this->label,
			'[COMPACT]'	=> $compact,
			'[EDITOR]'	=> $this->editor,
			'[CODE]'	=> $result,
			], TAB.$form_blocks['DATE']['code']);
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
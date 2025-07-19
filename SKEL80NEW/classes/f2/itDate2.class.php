<?
global $datepicker_counter;
$datepicker_counter = rand_id();

//..............................................................................
// itDate2 : класс выбора даты (2.1)
//..............................................................................
class itDate2
	{
	public $code;
	public $value, $type, $options, $name, $element_id, $title, $value_mysql, $time, $image, $label, $compact, $clear, $no_label;
	
	//..............................................................................
	// конструктор класса - создает элемент управления выбора даты
	//..............................................................................
	public function __construct($options=NULL)
		{
		global $datepicker_counter;
		$datepicker_counter ++;
		
		$this->name 		= ready_val($options['name'], "datetime-{$datepicker_counter}");
		$this->element_id 	= ready_val($options['element_id'], "datetime-{$datepicker_counter}");
		$this->form_id		= ready_val($options['form_id']);		
		$this->type 		= ready_val($options['type'], DEFAULT_DATE_TYPE);
		$this->class 		= ready_val($options['class'], DEFAULT_DATE_CLASS);
		$this->image 		= ready_val($options['src'], DEFAULT_DATEPICKER_IMAGE);
		$this->time		= ready_val($options['time'], DEFAULT_DATE_TIME);
		$this->label		= ready_val($options['label']);
		$this->no_label		= ready_val($options['no_label'], DEFAULT_DATE_NOLABEL);
		$this->compact		= ready_val($options['compact'], DEFAULT_DATE_COMPACT);
		$this->clear		= ready_val($options['clear'], DEFAULT_DATE_CLEAR);
		$this->ajax		= ready_val($options['ajax']);
		$this->grow		= ready_val($options['grow'], DEFAULT_DATE_GROW);

		$this->value		= itForm2::_check_value($options, $this->name, mysql_now());

		$time = strtotime($this->value);
		$this->value_mysql = ($this->time) ? get_mysql_datetime($time) : get_mysql_date($time);
		$this->title = strftime("%d %B %Y", $time).(($this->time) ? " ".get_time_str($this->value) : "");				

		$this->hour = empty($this->value) ? 0 : strftime("%H", strtotime($this->value));
		$this->minute = empty($this->value) ? 0 : strftime("%M", strtotime($this->value));
		$this->compile();
		}

	//..............................................................................
	// генерирует код календаря на основе установленных параметров и заносит в code
	//..............................................................................
	public function compile()
		{
		global $form_blocks;
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
		$autogrow = $this->grow ? " autogrow" : "";
		
		$class_str = (!is_null($this->class) OR $this->compact OR $this->grow) ? " class='autogrow {$this->class}{$autogrow}{$compact}'" : NULL;
		$onchange = !is_null($this->ajax) ? " onchange=\"{$this->ajax}\"" : NULL;	

		$compile_code = 
			TAB."<div class='date2container'>".
			TAB."<input {$class_str} id='value_{$this->element_id}' name='value_{$this->name}' type='text' value='{$this->title}' readonly>".
			TAB."<input id='{$this->element_id}' name='{$this->name}' type='hidden' value='{$this->value_mysql}'{$onchange}>".
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
		</script>").
			TAB."</div>";
		
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
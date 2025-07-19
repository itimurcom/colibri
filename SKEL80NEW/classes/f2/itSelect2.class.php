<?
global $selector_counter;
$selector_counter = (function_exists('rand_id')) ? rand_id() : time();


//..............................................................................
// itSelector2 : класс построение поля выбора для формы (2.1)
//..............................................................................
class itSelect2
	{
	public $code, $sel_rec, $type, $name, $element_id, $options, $value, $ajax ;

	//..............................................................................
	// конструктор класса - создает поле выбора и управление для него:
	//..............................................................................
	public function __construct($options=NULL)
		{
		global $selector_counter;
		$selector_counter++;		

		$this->element_id 	= ready_val($options['element_id'], "itSelect-{$selector_counter}");

		$this->name 		= ready_val($options['name']);
		$this->form_id		= ready_val($options['form_id']);							
		$this->ajax 		= ready_val($options['ajax']);

		$this->array		= ready_val($options['array']);
		$this->titles		= ready_val($options['titles'], 'title');
		$this->values		= ready_val($options['values'], 'value');
		$this->show			= ready_val($options['show'], 'show');
		$this->enable		= ready_val($options['enable'], 'enable');
		$this->color		= ready_val($options['color'], 'color');
		$this->bg_color		= ready_val($options['bg_color'], 'bg_color');
		
		$this->class 		= ready_val($options['class']);		
		$this->compact		= ready_val($options['compact'], false);
		$this->type			= ready_val($options['type'], 'select');		

		$this->label 		= ready_val($options['label']);		
		$this->no_label		= ready_val($options['no_label'], DEFAULT_SELECT_NOLABEL);
		$this->value		= itForm2::_smart_value(ready_val($options['value'], isset($_REQUEST[$this->name]) ? $_REQUEST[$this->name] : ''));
		$this->compile();
		}

	//..............................................................................
	// генерирует код селектора на основе установленных параметров и заносит в code
	//..............................................................................
	public function compile()
		{
		global $_USER;
		$compile_code = NULL;
		$this->sel_rec = NULL;
		// подготовим данные для селектора
		if ( !is_null($this->array) and isset($this->titles) and isset($this->values) )
			{
			$index = 0;
			foreach ($this->array as $row)
				{
				$index++;
				$this->sel_rec[] = array (
					'title' 	=> @is_array($tmp = ready_val($row[$this->titles], "autotitle{$index}")) ? get_field_by_lang($row[$this->titles]) : get_const($tmp),
//					'value' 	=> is_array($tmp = ready_val($row[$this->values], "{$index}")) 		? get_field_by_lang($row[$this->values]) : $tmp,
					'value'		=> isset($row[$this->values]) ? $row[$this->values] : "autooption{$index}",

					'show' 	 	=> isset($row[$this->show]) 	? $row[$this->show] 		: 1,
					'enable'  	=> isset($row[$this->enable]) 	? $row[$this->enable] 		: 1,

					'color'  	=> isset($row[$this->color]) 	? " {$row[$this->color]}" 	: NULL,
					'bg_color'  	=> isset($row[$this->bg_color]) ? " {$row[$this->bg_color]}" 	: NULL,
					'class'  	=> isset($row[$this->class]) 	? " {$row[$this->class]}" 	: NULL,
					);
				}
			}

		switch ($this->type)
			{
			case 'select' : {
				break;
				}

			case 'submit' : {
				$this->ajax = "$('form[id={$this->form_id}').submit();".$this->ajax;
				break;
				}
			}

		$compact = $this->compact ? " compact" : "";
		$class_str = (!is_null($this->class) OR $this->compact) ? " class='{$this->class}{$compact}'" : "";

		if (is_array($this->sel_rec) AND count($this->sel_rec))
			{
			$onchange = !is_null($this->ajax) ? " onchange=\"{$this->ajax}\"" : NULL;
			$compile_code = 
				TAB."<select size='1' {$class_str} id='{$this->element_id}' name='{$this->name}'{$onchange}>".
				TAB."<option selected='true' disabled='true'>".get_const('F2_SELECT_DISABLED')."</option>";

			foreach ($this->sel_rec as $row) 
				{
				if ($row['show']==1)
					{
				       	$compile_code .= TAB."<option ".
						( ($this->value==$row['value']) ? 'selected ' : '').
						( ($row['enable']!=1) ? 'disabled ' : '').
						"value = '{$row['value']}' class='{$row['class']}{$row['color']}{$row['bg_color']}'>{$row['title']}</option>";

					}
				}
			$compile_code .= TAB."</select>";
			} else	{
				if ($_USER->is_logged())
					{
					$compile_code = 
						TAB."<select size='1' {$class_str} id='{$this->element_id}' name='{$this->name}'></select>";
					}
				}

		$class_str = !empty($this->class) ? " {$this->class}" : NULL;
		$this->code =
			($this->no_label)
				? 	$compile_code
				:	TAB."<div class=\"modal_row{$class_str}{$compact}\">".
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
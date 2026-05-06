<?php
global $selector_counter;
$selector_counter = (function_exists('rand_id')) ? rand_id() : time();


// itSelector2 : класс построение поля выбора для формы (2.1)
class itSelect2
	{
	public $code, $sel_rec, $type, $name, $element_id, $options, $value, $ajax, $form_id, $array, $titles, $values, $show, $enable, $color, $bg_color, $class, $compact, $label, $no_label;

	// конструктор класса - создает поле выбора и управление для него:
	public function __construct($options=NULL)
		{
		global $selector_counter;
		$selector_counter++;
		$options = is_array($options) ? $options : [];

		$this->element_id 	= ready_val(isset($options['element_id']) ? $options['element_id'] : NULL, "itSelect-{$selector_counter}");

		$this->name 		= ready_val(isset($options['name']) ? $options['name'] : NULL);
		$this->form_id		= ready_val(isset($options['form_id']) ? $options['form_id'] : NULL);							
		$this->ajax 		= ready_val(isset($options['ajax']) ? $options['ajax'] : NULL);

		$this->array		= ready_val(isset($options['array']) ? $options['array'] : NULL);
		$this->titles		= ready_val(isset($options['titles']) ? $options['titles'] : NULL, 'title');
		$this->values		= ready_val(isset($options['values']) ? $options['values'] : NULL, 'value');
		$this->show			= ready_val(isset($options['show']) ? $options['show'] : NULL, 'show');
		$this->enable		= ready_val(isset($options['enable']) ? $options['enable'] : NULL, 'enable');
		$this->color		= ready_val(isset($options['color']) ? $options['color'] : NULL, 'color');
		$this->bg_color		= ready_val(isset($options['bg_color']) ? $options['bg_color'] : NULL, 'bg_color');
		
		$this->class 		= ready_val(isset($options['class']) ? $options['class'] : NULL);		
		$this->compact		= ready_val(isset($options['compact']) ? $options['compact'] : NULL, false);
		$this->type			= ready_val(isset($options['type']) ? $options['type'] : NULL, 'select');		

		$this->label 		= ready_val(isset($options['label']) ? $options['label'] : NULL);		
		$this->no_label		= ready_val(isset($options['no_label']) ? $options['no_label'] : NULL, DEFAULT_SELECT_NOLABEL);
		$request_value = (isset($_REQUEST) AND is_array($_REQUEST) AND array_key_exists($this->name, $_REQUEST)) ? $_REQUEST[$this->name] : '';
		$this->value		= itForm2::_smart_value(ready_val(isset($options['value']) ? $options['value'] : NULL, $request_value));
		$this->compile();
		}

	// генерирует код селектора на основе установленных параметров и заносит в code
	public function compile()
		{
		global $_USER;
		$compile_code = NULL;
		$this->sel_rec = NULL;
		// подготовим данные для селектора
		if ( !is_null($this->array) and isset($this->titles) and isset($this->values) and is_array($this->array) )
			{
			$index = 0;
			foreach ($this->array as $row)
				{
				$row = is_array($row) ? $row : [];
				$index++;
				$title_value = isset($row[$this->titles]) ? $row[$this->titles] : "autotitle{$index}";
				$title = is_array($tmp = ready_val($title_value, "autotitle{$index}")) ? get_field_by_lang($title_value) : get_const($tmp);
				$this->sel_rec[] = array (
					'title' 	=> $title,
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
				if (is_object($_USER) AND method_exists($_USER, 'is_logged') AND $_USER->is_logged())
					{
					$compile_code = 
						TAB."<select size='1' {$class_str} id='{$this->element_id}' name='{$this->name}'></select>";
					}
				}

		$this->code =
			($this->no_label)
				? 	$compile_code
				:	TAB."<div class=\"modal_row{$compact}\">".
						itForm2::_label_zone((array) $this).
						$compile_code.
					TAB."</div>";
		}

	// возвращает код селектора с привязкой обраточика события ($options)
	public function code()
		{
		return $this->code;
		}
	} // class
?>

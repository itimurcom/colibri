<?php
global $set_counter;
$set_counter = rand_id();
// itSet2 : класс построения поля набора для формы (2.1)
class itSet2
	{
	public $code, $sel_rec, $type, $name, $element_id, $options, $value, $ajax, $form_id, $class, $label, $no_label, $compact, $array, $titles, $values, $colors;

	// конструктор класса - создает поле множественного выбора из набора данных
	public function __construct($options=NULL)
		{
		global $set_counter, $_TIMES;
		$set_counter ++;
		$options = is_array($options) ? $options : [];
		
		$this->name 		= ready_val(isset($options['name']) ? $options['name'] : NULL, "set-{$set_counter}");
		$this->element_id 	= ready_val(isset($options['element_id']) ? $options['element_id'] : NULL, "set-{$set_counter}");
		$this->form_id		= ready_val(isset($options['form_id']) ? $options['form_id'] : NULL);
		$this->type 		= ready_val(isset($options['type']) ? $options['type'] : NULL, DEFAULT_SET_TYPE);
		$this->class 		= ready_val(isset($options['class']) ? $options['class'] : NULL, DEFAULT_SET_CLASS);
		$this->label		= ready_val(isset($options['label']) ? $options['label'] : NULL);
		$this->no_label		= ready_val(isset($options['no_label']) ? $options['no_label'] : NULL, DEFAULT_SET_NOLABEL);
		$this->compact		= ready_val(isset($options['compact']) ? $options['compact'] : NULL, DEFAULT_SET_COMPACT);
		$this->ajax		= ready_val(isset($options['ajax']) ? $options['ajax'] : NULL);
		
		$this->array		= ready_val(isset($options['array']) ? $options['array'] : NULL);
		$this->titles		= ready_val(isset($options['titles']) ? $options['titles'] : NULL, 'title');
		$this->values		= ready_val(isset($options['values']) ? $options['values'] : NULL, 'value');
		$this->colors		= ready_val(isset($options['colors']) ? $options['colors'] : NULL, 'color');		
		$this->value		= ready_val(isset($options['value']) ? $options['value'] : NULL);	

		$this->compile();
		}

	// генерирует код селектора на основе установленных параметров и заносит в code
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
				$row = is_array($row) ? $row : [];
				$title_value = isset($row[$this->titles]) ? $row[$this->titles] : "{$index}";
				$title = is_array($tmp = ready_val($title_value, "{$index}")) ? get_field_by_lang($title_value) : get_const($tmp);
				$value = isset($row[$this->values]) ? $row[$this->values] : "autooption{$index}";
				
				$current_value = is_array($this->value) && isset($this->value["$value"])
					? $this->value["$value"]
					: NULL;
				$request_key = "{$this->name}_{$value}";
				$request_checked = (isset($_REQUEST) AND is_array($_REQUEST) AND isset($_REQUEST[$request_key]) AND ($_REQUEST[$request_key]=='on'));
				$checked = (ready_val($current_value) OR $request_checked) ? ' checked' : '';
				
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

	// возвращает код селектора с привязкой обраточика события ($options)
	public function code()
		{
		return $this->code;
		}
	} // class
?>

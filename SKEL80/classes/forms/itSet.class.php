<?php
// ================ CRC ================
// version: 1.15.02
// hash: eccf4ce81d9f1c604932a560a78a4235038b294c3e26ebb5071e2518ae2b9074
// date: 09 September 2019  5:10
// ================ CRC ================

global $set_counter;
$set_counter = (function_exists('rand_id')) ? rand_id() : time();

//..............................................................................
// itSet : класс построения поля набора для формы
//..............................................................................
class itSet
	{
	public $code, $sel_rec, $type, $name, $element_id, $options, $value, $ajax ;

	//..............................................................................
	// конструктор класса - создает поле множественного выбора из набора данных
	//..............................................................................
	public function __construct($type=DEFAULT_SELECTOR_TYPE, $options=NULL, $value='', $element_id=NULL)
		{
		global $button_counter;

		if ($element_id == NULL)
			{
			$button_counter++;
			$element_id = "itSelector-$button_counter";
			}

		if (!isset($options['name']))
			{
			$name = DEFAULT_SET_NAME;
			} else $name = $options['name']; 


		if (!isset($options['ajax']))
			{
			$ajax = "alert(\"changed {$this->element_id}\");";
			} else $ajax = $options['ajax']; 

		$this->type		= $type;
		$this->name		= $name;
		$this->element_id	= $element_id;
		$this->options  	= $options;
		$this->value		= $value;
		$this->ajax		= $ajax;

		$this->options['titles']	= (isset($options['titles'])) 	? $options['titles'] 	: 'title';
		$this->options['values']	= (isset($options['values'])) 	? $options['values'] 	: 'value';
		$this->options['show']		= (isset($options['show'])) 	? $options['show'] 	: 'show';
		$this->options['enable']	= (isset($options['enable'])) 	? $options['enable'] 	: 'enable';
		$this->options['color']		= (isset($options['color'])) 	? $options['color'] 	: 'color';
		$this->options['bg_color']	= (isset($options['bg_color'])) ? $options['bg_color'] 	: 'bg_color';
		$this->options['class']		= (isset($options['class'])) 	? " {$options['class']}": 'class';

		$this->compile();
		}

	//..............................................................................
	// генерирует код селектора на основе установленных параметров и заносит в code
	//..............................................................................
	public function compile()
		{		
		$result = '';

		switch ($this->type)
			{
			case 'set' : {
				$this->ajax = '';
				break;
				}

			case 'submit' : {
				$this->ajax = "$(\"form[id={$this->options['form']}]\").submit();";
				break;
				}
			}

		// подготовим данные для селектора
		if ( isset($this->options['array']) and isset($this->options['titles']) and isset($this->options['values']) )
			{
			foreach ($this->options['array'] as $key=>$row)
				{
				$value = get_const($row[$this->options['values']]);
				$title = get_const($row[$this->options['titles']]);
				$checked = (ready_val($this->value[$value])==true) ? ' checked' : '';
				$result .= 
					TAB."<span class='checkbox{$this->options['class']}'><input type='checkbox' name='{$this->options['name']}_{$value}'{$checked}>".
					TAB."{$title}</span>";
				}
			}

		$this->code = $result;
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

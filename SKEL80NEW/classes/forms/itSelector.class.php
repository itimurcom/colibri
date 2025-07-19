<?
global $selector_counter;
$selector_counter = (function_exists('rand_id')) ? rand_id() : time();

//..............................................................................
// itSelector : класс построение поля выбора для формы
//..............................................................................
class itSelector
	{
	public $code, $sel_rec, $type, $name, $element_id, $options, $value, $ajax ;

	//..............................................................................
	// конструктор класса - создает поле выбора и управление для него:
	//..............................................................................
	public function __construct($type=DEFAULT_SELECTOR_TYPE, $options=NULL, $value='', $element_id=NULL, $label=NULL)
		{
		global $selector_counter;
		$selector_counter++;		

		if (is_array($type))
			{
			$options		= $type;
			$this->label 		= ready_val($options['label']);
			$this->type 		= ready_val($options['type'], DEFAULT_SELECTOR_TYPE);
			$this->value 		= ready_val($options['value']);
			$element_id 		= ready_val($options['element_id']);
			} else	{
				$this->label 		= $label;
				$this->type 		= $type;
				$this->value  		= $value;
				$this->label 		= $label;
				}
		$this->options 		= $options;
				
		$this->element_id 	= is_null($element_id) ? "itSelector-{$selector_counter}" : $element_id;				
		$this->name 		= ready_val($this->options['name'], DEFAULT_SELECTOR_NAME);						
		$this->ajax 		= isset($this->options['ajax']) ? $options['ajax'] : NULL;
//			$ajax = "alert(\"changed {$this->element_id}\");";

		$this->options['titles']	= ready_val($this->options['titles'], 'title');
		$this->options['values']	= ready_val($this->options['values'], 'value');
		$this->options['show']		= ready_val($this->options['show'], 'show');
		$this->options['enable']	= ready_val($this->options['enable'], 'enable');
		$this->options['color']		= ready_val($this->options['color'], 'color');
		$this->options['bg_color']	= ready_val($this->options['bg_color'], 'bg_color');
		
		$this->class 		= isset($this->options['class'])	? $this->options['class']		: NULL;		
		$this->compact		= isset($this->options['compact'])	? $this->options['compact']		: false;
		$this->editor		= ready_val($this->options['editor']);		

		$this->compile();
		}

	//..............................................................................
	// генерирует код селектора на основе установленных параметров и заносит в code
	//..............................................................................
	public function compile()
		{
		global $form_blocks;	
		$result = '';

		// подготовим данные для селектора
		if ( isset($this->options['array']) and isset($this->options['titles']) and isset($this->options['values']) )
			{
			foreach ($this->options['array'] as $key=>$row)
				{
				$this->sel_rec[] = array (
					'title' 	=> get_const($row[$this->options['titles']]),
					'value' 	=> $row[$this->options['values']],

					'show' 	 	=> isset($row[$this->options['show']]) 		? $row[$this->options['show']] : 1,
					'enable'  	=> isset($row[$this->options['enable']]) 	? $row[$this->options['enable']] : 1,

					'color'  	=> isset($row[$this->options['color']]) 	? " {$row[$this->options['color']]}" : '',
					'bg_color'  	=> isset($row[$this->options['bg_color']]) 	? " {$row[$this->options['bg_color']]}" : '',
					'class'  	=> isset($row[$this->class]) 			? " {$row[$this->class]}" : '',
					);
				}
			}

		switch ($this->type)
			{
			case 'select' : {
//				$this->ajax = NULL;
				break;
				}

			case 'submit' : {
				$this->ajax = "$(\"form[id={$this->options['form']}]\").submit();".$this->ajax;
				break;
				}
			}

		$compact = $this->compact ? " compact" : "";
		$class_str = (!is_null($this->class) OR $this->compact) ? " class='{$this->class}{$compact}'" : "";

		if (is_array($this->sel_rec)) {
			$onchange = !is_null($this->ajax) ? " onchange='{$this->ajax}'" : "";
			$result = 
				TAB."<select size='1' {$class_str} id='{$this->element_id}' name='{$this->name}'{$onchange}>";
//			"$tab<option selected disabled>".CATEGORY_SELECT."</option>";

			foreach ($this->sel_rec as $key=>$row) 
				{
				if ($row['show']==1)
					{
				       	$result .= TAB."<option ".
						( (trim($this->value)==trim($row['value'])) ? 'selected ' : '').
						( ($row['enable']!=1) ? 'disabled ' : '').
						"value = '{$row['value']}' class='{$row['class']}{$row['color']}{$row['bg_color']}'>".$row['title']."</option>";
					}
				}
			$result .= TAB."</select>";
			}

		$this->code = mstr_replace([
			'[TITLE]'	=> $this->label,
			'[COMPACT]'	=> $compact,
			'[EDITOR]'	=> $this->editor,
			'[CODE]'	=> $result,
			], TAB.$form_blocks['SELECTOR']['code']);
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

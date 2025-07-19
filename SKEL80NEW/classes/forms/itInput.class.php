<?
global $input_counter;
$input_counter = (function_exists('rand_id')) ? rand_id() : time();

//..............................................................................
// itInput : класс построение поля ввода для формы
//..............................................................................
class itInput
	{
	public $element_id, $code, $type, $name, $value, $ajax, $class, $placeholder, $label, $compact, $form_id;

	//..............................................................................
	// конструктор класса - создает поле ввода текста
	//..............................................................................
	public function __construct($options=NULL)
		{
		global $input_counter;
		
		$this->form_id		= ready_val($options['form_id'], "");
		$this->name		= ready_val($options['name'], "input-{$input_counter}");
		$this->element_id	= ready_val($options['element_id'], "{$this->form_id}-{$this->name}");
		$this->class		= ready_val($options['class']);
		$this->value		= ready_val($options['value'], isset($_REQUEST[$this->name]) ? $_REQUEST[$this->name] : '');
		
		$this->placeholder	= ready_val($options['placeholder']);
		$this->label		= ready_val($options['label']);
		$this->compact		= ready_val($options['compact'], DEFAULT_INPUT_COMPACT);
		$this->editor		= ready_val($options['editor']);
		
		$this->type		= ready_val($options['type'], DEFAULT_INPUT_TYPE);
		$this->ajax		= ready_val($options['ajax']);
		$this->readonly		= ready_val($options['readonly'], false);		
				
		$this->compile();
		}

	//..............................................................................
	// генерирует html код поля ввода
	//..............................................................................	
	public function compile()
		{
		global $form_blocks;
			
		$compact = $this->compact ? " compact" : "";
		$class_str = (!is_null($this->class) OR $this->compact) ? " class='{$this->class}{$compact}'" : "";

		switch ($this->type)
			{
			case 'email' : {
				$onchange = " onchange=\"if (typeof('email_checker'=== 'function')) { email_checker(this); }\"";
				$type = 'text';
				break;
				}
			case 'phone' : {
				$onchange = " onchange=\"if (typeof('phone_checker'=== 'function')) { phone_checker(this); }\"";
				$type = 'text';
				break;
				}
			case 'password' : {
				$onchange = '';
				$type = 'password';
				break;
				}
			case 'ajax' : {
				$onchange = " onkeyup=\" $this->ajax \"";
				$type = 'text';
				break;
				}

			default : {
				$onchange = '';
				$type = 'text';
				}
			}
		
		$readonly = $this->readonly ? " readonly" : NULL;
		
		$this->code = mstr_replace([
			'[TITLE]'	=> empty($this->placeholder) ? $this->label : '',
			'[COMPACT]'	=> $compact,
			'[EDITOR]'	=> $this->editor,
			'[CODE]'	=> TAB."<input{$readonly} type=\"{$type}\" id=\"{$this->element_id}\" name=\"{$this->name}\" value=\"".htmlentities(stripslashes($this->value))."\" ".(($this->placeholder) ? " placeholder=\"{$this->label}\"" : '')."{$class_str}{$onchange}/>",
			], TAB.$form_blocks['INPUT']['code']);
		}

	//..............................................................................	
	// возвращает код
	//..............................................................................	
	public function code()
		{
		return $this->code;
		}
	}
?>
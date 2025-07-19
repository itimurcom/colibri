<?
global $desc_counter;
$desc_counter = (function_exists('rand_id')) ? rand_id() : time();

//..............................................................................
// itDesc2 : класс построения поля описания группы полей для формы (2.1)
//..............................................................................
class itDesc2
	{
	public $element_id, $code, $type, $name, $value, $ajax, $class, $placeholder, $label, $compact, $form_id;

	//..............................................................................
	// конструктор класса - создает поле ввода текста
	//..............................................................................
	public function __construct($options=NULL)
		{
		global $desc_counter;
		$desc_counter++;
		
		$this->form_id		= ready_val($options['form_id'], "");
		$this->element_id	= ready_val($options['element_id'], "{$this->form_id}-{$desc_counter}");
		$this->class		= ready_val($options['class']);
		
		$this->label		= ready_val($options['label']);
		$this->no_label		= ready_val($options['no_label'], DEFAULT_DESC_NOLABEL);		
		$this->compact		= ready_val($options['compact'], DEFAULT_DESC_COMPACT);
		
		$this->code 		= ready_val($options['value']);
		}
	
	//..............................................................................	
	// возвращает код
	//..............................................................................	
	public function code()
		{
		return 
			TAB."<div clas='desc'>".
			$this->code.
			TAB."</div>";
		}
	}
		
?>
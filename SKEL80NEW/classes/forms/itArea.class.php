<?
global $area_counter;
$area_counter = (function_exists('rand_id')) ? rand_id() : time();

//..............................................................................
// itArea : класс построение поля текса для формы
//..............................................................................
class itArea
	{
	public $element_id, $code, $type, $name, $value, $ajax, $class, $placeholder, $label, $compact, $form_id;

	//..............................................................................
	// конструктор класса - создает поле ввода текста
	//..............................................................................
	public function __construct($options=NULL)
		{
		global $area_counter;
		
		$this->form_id		= ready_val($options['form_id'], "");
		$this->name		= ready_val($options['name'], "area-{$area_counter}");
		$this->element_id	= ready_val($options['element_id'], "{$this->form_id}-{$this->name}");
		$this->class		= ready_val($options['class']);
		$this->value		= ready_val($options['value'], isset($_REQUEST[$this->name]) ? $_REQUEST[$this->name] : '');
		
		$this->placeholder	= ready_val($options['placeholder']);
		$this->label		= ready_val($options['label']);
		$this->compact		= ready_val($options['compact'], DEFAULT_AREA_COMPACT);
		$this->max		= ready_val($options['max']);
		$this->editor		= ready_val($options['editor']);
				
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
		
		$protection_str = !is_null($this->max)
			? TAB.minify_js(
			"<script>
				$('#{$this->element_id}').keyup(function()
					{
					if (this.value.length > {$this->max})
						{
						this.value = this.value.substr(0, {$this->max});
					if (!$(this).hasAttr('evented'))
						{
						$('<span class=\'red\'>".get_const('MAX_AREA_ALLOWED')." {$this->max}</span>').insertAfter($(this));
						$(this).attr('evented','1');
						}
					}
				});
			</script>") : "";

		$this->code = mstr_replace([
			'[TITLE]'	=> empty($this->placeholder) ? $this->label : '',
			'[COMPACT]'	=> $compact,
			'[EDITOR]'	=> $this->editor,
			'[CODE]'	=> 
				TAB."\t<textarea id=\"{$this->element_id}\" name=\"{$this->name}\"".(($this->placeholder) ? " placeholder=\"{$this->label}\"" : '')."{$class_str}>".
				htmlentities(stripslashes($this->value)).
				"</textarea>".
				$protection_str,
			], TAB.$form_blocks['AREA']['code']);
			
			
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
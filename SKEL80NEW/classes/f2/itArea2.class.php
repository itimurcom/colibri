<?
global $area_counter;
$area_counter = (function_exists('rand_id')) ? rand_id() : time();

//..............................................................................
// itArea2 : класс построения поля текста для формы (2.1)
//..............................................................................
class itArea2
	{
	public $element_id, $code, $type, $name, $value, $ajax,$placeholder, $label, $compact, $form_id;

	//..............................................................................
	// конструктор класса - создает поле ввода текста
	//..............................................................................
	public function __construct($options=NULL)
		{
		global $area_counter;
		$area_counter++;

//		if ($options['name'] == 'f2_values') {print_r($options); die;}
		
		$this->form_id		= ready_val($options['form_id'], "");
		$this->name		= ready_val($options['name'], "area-{$area_counter}");
		$this->element_id	= ready_val($options['element_id'], "{$this->form_id}-{$this->name}");
		$this->class		= "fixed";
		$this->value		= itForm2::_smart_value(ready_val($options['value'], isset($_REQUEST[$this->name]) ? $_REQUEST[$this->name] : ''), true);
		
		$this->placeholder	= ready_val($options['placeholder']);
		$this->label		= ready_val($options['label']);
		$this->no_label		= ready_val($options['no_label'], DEFAULT_AREA_NOLABEL);		
		$this->compact		= ready_val($options['compact'], DEFAULT_AREA_COMPACT);
		$this->max		= ready_val($options['max']);
		$this->compile();
		}

	//..............................................................................
	// генерирует html код поля ввода
	//..............................................................................	
	public function compile()
		{		
		$compact = $this->compact ? " compact" : "";
		$class_str = (!is_null($this->class) OR $this->compact) ? " class='{$this->class}{$compact}'" : "test";
		
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
			
		$compile_code =				
				TAB."\t<textarea id=\"{$this->element_id}\"{$class_str} name=\"{$this->name}\"".(($this->placeholder) ? " placeholder=\"".get_const($this->placeholder)."\"" : '').">".
				htmlentities(stripslashes($this->value)).
				"</textarea>".
				$protection_str;


		$this->code =
			($this->no_label)
				? 	$compile_code
				:	TAB."<div class=\"modal_row{$compact}\">".
						itForm2::_label_zone((array) $this).
						$compile_code.
					TAB."</div>";
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
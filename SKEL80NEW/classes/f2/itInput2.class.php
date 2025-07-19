<?

global $input_counter;
$input_counter = rand_id();

//..............................................................................
// itInput2 : класс построение поля ввода для формы (2.1)
//..............................................................................
class itInput2
	{
	public $element_id, $code, $type, $name, $value, $ajax, $class, $placeholder, $label, $compact, $form_id, $no_label;

	//..............................................................................
	// конструктор класса - создает поле ввода текста
	//..............................................................................
	public function __construct($options=NULL)
		{
		global $input_counter;
		$input_counter++;
		
		$this->form_id		= ready_val($options['form_id'], "");
		$this->name		= ready_val($options['name'], "input-{$input_counter}");
		$this->element_id	= ready_val($options['element_id'], "{$this->form_id}-{$this->name}");
		$this->class		= ready_val($options['class']);
		$this->value		= (isset($options['value']) AND !empty($options['value']))
			? $options['value']
			: itForm2::_check_value($options, $this->name);
		
		$this->placeholder	= ready_val($options['placeholder']);
		$this->label		= ready_val($options['label']);
		$this->no_label		= ready_val($options['no_label'], DEFAULT_INPUT_NOLABEL);
		$this->compact		= ready_val($options['compact'], DEFAULT_INPUT_COMPACT);
		$this->type		= ready_val($options['type'], DEFAULT_INPUT_TYPE);
		$this->grow		= ready_val($options['grow'], DEFAULT_INPUT_GROW);		
		$this->min		= ready_val($options['min']);				
		$this->max		= ready_val($options['max']);
		$this->multi		= ready_val($options['multi'], isset($_REQUEST['multi']) ? $_REQUEST['multi'] : 1);
		$this->ajax 		= ready_val($options['ajax']);					

		$this->readonly		= ready_val($options['readonly'], false);
		
		$this->compile();
		}

	//..............................................................................
	// генерирует html код поля ввода
	//..............................................................................	
	public function compile()
		{
		$readonly = $this->readonly ? " readonly tabIndex=-1'" : NULL;			
		$compact = $this->compact ? " compact" : "";
		$autogrow = $this->grow ? " autogrow" : "";		
		$class_str = (!is_null($this->class) OR $this->compact OR $this->grow) ? " class='{$this->class}{$autogrow}{$compact}'" : NULL;
		
		$placeholder_str = $this->placeholder ? " placeholder=\"".itForm2::_placeholder_view((array) $this)."\"" : NULL;
		
		$onchange = NULL;
		$multi_str = NULL;
		switch ($this->type)
			{
			case 'email' : {
				$onchange = " onchange=\"if (typeof('email_checker'=== 'function')) { f2_email_checker(this); }\"";
				$type = 'text';
				break;
				}
			case 'phone' : {
				$onchange = " onchange=\"if (typeof('phone_checker'=== 'function')) { f2_phone_checker(this); }\"";
				$type = 'text';
				break;
				}
			case 'password' : {
				$onchange = !is_null($this->ajax) ? " onchange=\"{$this->ajax}\"" : NULL;	
				$type = 'password';
				break;
				}
			case 'number' : {
				$onchange = !is_null($this->ajax) ? " onchange=\"{$this->ajax}\"" : NULL;
				$type = 'number';
				$multi_str = TAB."<input{$readonly} type=\"hidden\" id=\"{$this->element_id}-multi\" name=\"{$this->name}-multi\" value=\"{$this->multi}\">";
				break;
				}
			default : {
				$onchange = !is_null($this->ajax) ? " onchange=\"{$this->ajax}\"" : NULL;
				$type = 'text';
				}
			}

	
		$value_str = is_array($this->value)
			? get_field_by_lang($this->value, CMS_LANG, NULL)
			: get_const($this->value);
		$value_str = htmlentities(stripslashes($value_str));
		
		$min_str = !is_null($this->min) ? " min=\"{$this->min}\"" : NULL;
		$max_str = !is_null($this->max) ? " min=\"{$this->max}\"" : NULL;		
		
		$compile_code  = TAB."<input{$readonly} type=\"{$type}\" id=\"{$this->element_id}\" name=\"{$this->name}\" value=\"{$value_str}\"{$placeholder_str}{$class_str}{$onchange}{$min_str}{$max_str}/>".
			$multi_str;
//			(($this->type == 'number') ? "<script>$(\"#{$this->element_id}\").spinner();</script>": NULL);
		
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
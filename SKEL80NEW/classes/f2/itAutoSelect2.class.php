<?
global $autoselect_counter, $_CSS;
$autoselect_counter = rand_id();

//..............................................................................
// itAutoSelect2 : класс автоматического выбора данных по поиску (2.1)
//..............................................................................
class itAutoSelect2
	{
	public $code, $element_id, $type, $class, $action, $op, $placeholder, $compact, $text;
	//..............................................................................
	// конструктор класса - создает элемент автоматического выбора данных
	//..............................................................................
	public function __construct($options=NULL)
		{
		global $autoselect_counter;
		$autoselect_counter++;

		$this->element_id	= ready_val($options['element_id'], "autoselect-{$autoselect_counter}");
		$this->name			= ready_val($options['name'], "autoselect-{$autoselect_counter}");
		$this->type			= ready_val($options['type'], DEFAULT_AUTOSELECT_TYPE);
		$this->class 		= ready_val($options['class'], DEFAULT_AUTOSELECT_CLASS);
		$this->action 		= ready_val($options['action'], DEFAULT_AUTOSELECT_ACTION);
		$this->op 			= ready_val($options['op'], DEFAULT_AUTOSELECT_OP);
		
		// $this->value		= itForm2::_check_value($options, $this->name, NULL);
		$this->value		= ready_val($options['value']);
		
		$this->text 		= ready_val($options['text']);
		$this->compact 		= ready_val($options['compact']);
		$this->compact 		= ready_val($options['compact']);
		$this->placeholder	= ready_val($options['placeholder']);
		$this->label		= ready_val($options['label']);		
		$this->no_label		= ready_val($options['no_label'], DEFAULT_SELECT_NOLABEL);
		
		$this->ajax		=  ready_val($options['ajax']);
		$this->compile();
		}

	//..............................................................................
	// генерирует код календаря на основе установленных параметров и заносит в code
	//..............................................................................
	public function compile()
		{
		$onchange ='';
		switch ($this->type)
			{
			case 'main' : {
				$this->ajax = '';
				$action = 'window.location.href = ui.item.link';
				break;
				}

			case 'submit' : {
				$action = "$('#{$this->form_id}').submit();";
				break;
				}

			case 'input' : {
//				$onchange = " onchange=\"$('#{$this->element_id}').val($('#field-{$this->element_id}').val());\"";
				$action = !empty($this->ajax) ? $this->ajax : NULL;
				break;
				}
			}
		
		$compact = $this->compact ? " compact" : "";
		$class_str = (!is_null($this->class) OR $this->compact) ? " class='{$this->class}{$compact}'" : NULL;
		$placeholder_str = $this->placeholder ? " placeholder=\"".itForm2::_placeholder_view((array) $this)."\"" : NULL;

		$value_str = is_array($this->value)
			? get_field_by_lang($this->value, CMS_LANG, NULL)
			: get_const($this->value);		
		
		$compile_code =
			TAB."<input type=\"text\"{$class_str} id=\"field-{$this->element_id}\" name=\"field-{$this->element_id}\" value=\"{$this->text}\"{$placeholder_str}{$onchange}/>".
			TAB."<input type=\"hidden\" id=\"{$this->element_id}\" value=\"{$value_str}\" name=\"{$this->name}\"/>".
			TAB.minify_js("
	<script>
	$(document).ready(function() {
	$('#field-{$this->element_id}').autocomplete({
		source: '{$this->action}?op={$this->op}',
		html : true,
		select: function (event, ui) {
			$('#{$this->element_id}').val(ui.item.id);
			$('#field-{$this->element_id}').val(ui.item.value);
			{$action}
			return false;
			}					
		});
	});
	</script>");
		$this->code =
			($this->no_label)
				? 	$compile_code
				:	TAB."<div class=\"modal_row{$compact}\">".
						itForm2::_label_zone((array) $this).
						$compile_code.
					TAB."</div>";	
		}

	//..............................................................................
	// возвращает код выбора даты с привязкой обработчика события ($options)
	//..............................................................................
	public function code()
		{
		return $this->code;
		}

	} //class;
?>
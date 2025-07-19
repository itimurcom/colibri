<?
global $button_counter;
$button_counter = (function_exists('rand_id')) ? rand_id() : 0;
//..............................................................................
// itButton : класс построение управляющей кнопки
//..............................................................................
class itButton
	{
	public $code;
	private $title, $type, $color, $options, $element_id, $target;

	//..............................................................................
	// конструктор класса - создает кнопку и управление для нее по параметрам:
	//..............................................................................
	public function __construct($title='Ok', $type = DEFAULT_BUTTON_TYPE, $options=NULL, $color='', $element_id=NULL)
		{
		global $button_counter;
		$button_counter++;

		if (is_array($title))
			{
			$this->options 		= $title;
			$this->title 		= ready_val($this->options['title']);
			$this->type 		= ready_val($this->options['type']);
			$color			= ready_val($this->options['color'], '');
			$element_id 		= ready_val($this->options['element_id']);			
			} else	{
				$this->title 		= $title;
				$this->type 		= $type;
				$this->options  	= $options;
				}

		$this->element_id 	= is_null($element_id) ? "itButton-{$button_counter}" : $element_id;
		$this->color		= empty($color) ? (($this->type!='text') ? DEFAULT_BUTTON_COLOR : '') : $color; 

		// поправим класс
		$this->options['class'] = isset($this->options['class']) ? " {$this->options['class']}" : '';
		$this->ajax		= isset($this->options['ajax']) ?  $this->options['ajax'] : NULL;
		$this->target		= ready_val($this->options['target']);

		$this->compile();
		}

	//..............................................................................
	// генерирует код кнопки на основе установленных параметров и заносит в code
	//..............................................................................
	public function compile()
		{
		// подготовим ajax
		switch ($this->type)
			{
			case 'a' :
			case 'text' :
			case 'image' :
			case 'imajax' :	
			case 'modal' :
			case 'immodal' :
			case 'textmodal' :
			case 'close' :
			case 'textclose' :
			case 'file' :
			case 'files' :
			case 'textfile' :
			case 'textfiles' :
			case 'imfile' : 
			case 'imfiles' :
				{
				$this->ajax = (!empty($this->ajax) AND !is_null($this->ajax)) ? " onclick=\"{$this->ajax} \"" : "";
				break;					
				}
			}

		switch ($this->type)
			{
			case 'a' : {
				// поправим установки кнопки
				$this->options['href'] = (isset($this->options['href'])) ? $this->options['href'] : '#/';
				$a_title = (isset($this->options['title'])) ? " title=\"".get_const($this->options['title'])."\"" : "";
				$a_target = !is_null($this->target) ? " target='{$this->options['target']}'" : "";

				$this->code = TAB."\t<a href=\"{$this->options['href']}\" class=\"itButton bg_{$this->color} {$this->options['class']}\" id=\"{$this->element_id}\"{$this->ajax}{$a_title}{$a_target}>{$this->title}</a>";
				break;
				}

			case 'text' : {
				// поправим установки кнопки
				$this->options['href'] = (isset($this->options['href'])) ? $this->options['href'] : '#/';
				$a_title = (isset($this->options['title'])) ? " title=\"".get_const($this->options['title'])."\"" : "";
				$a_target = !is_null($this->target) ? " target='{$this->options['target']}'" : "";
				
				$this->code = TAB."\t<a href=\"{$this->options['href']}\" id=\"{$this->element_id}\" class=\"{$this->color} {$this->options['class']}\"{$this->ajax}{$a_title}{$a_target}>{$this->title}</a>";
				break;
				}

			case 'textsubmit' : {
				$a_title = (isset($this->options['title'])) ? " title=\"".get_const($this->options['title'])."\"" : "";
				if (isset($this->options['form']))
					{
					$this->ajax = "onclick = \"document.getElementById('{$this->options['form']}').submit();\"";
					}
				$this->code = TAB."\t<span class=\"submit {$this->color} {$this->options['class']}\" id=\"{$this->element_id}\"{$this->ajax}{$a_title}>{$this->title}</span>";
				break;
				}
				
			case 'ajaxtextsubmit' : {
				$a_title = (isset($this->options['title'])) ? " title=\"".get_const($this->options['title'])."\"" : "";
				if (isset($this->options['form']))
					{
					$this->ajax = "onclick = \"ajax_submit('#{$this->options['form']}', function(){ {$this->ajax} });\"";
					}
				$this->code = TAB."\t<span class=\"submit {$this->color} {$this->options['class']}\" id=\"{$this->element_id}\"{$this->ajax}{$a_title}>{$this->title}</span>";
				break;
				}


			case 'submit' : {
				$a_title = (isset($this->options['title'])) ? " title=\"".get_const($this->options['title'])."\"" : "";
				if (isset($this->options['form']))
					{
					$this->ajax = "onclick = \"document.getElementById('{$this->options['form']}').submit();\"";
					}
				$this->code = TAB."\t<span class=\"itButton submit bg_{$this->color} {$this->options['class']}".(($this->title==get_const('BUTTON_OK'))?' ok':'')."\" id=\"{$this->element_id}\"{$this->ajax}{$a_title}>{$this->title}</span>";
				break;
				}

			case 'ajaxsubmit' : {
				$a_title = (isset($this->options['title'])) ? " title=\"".get_const($this->options['title'])."\"" : "";
				if (isset($this->options['form']))
					{
					$this->ajax = "onclick = \"ajax_submit('#{$this->options['form']}', function(){ {$this->ajax}} );\"";
					}
				$this->code = TAB."\t<span class=\"itButton submit bg_{$this->color} {$this->options['class']}".(($this->title==get_const('BUTTON_OK'))?' ok':'')."\" id=\"{$this->element_id}\"{$this->ajax}{$a_title}>{$this->title}</span>";
				break;
				}

			case 'image' : {
				if (!isset($this->options['class']))
					{
					$this->options['data']['class'] = 'itButton-image';
					}

				// поправим установки кнопки
				$this->options['href'] = ($this->options['href']) ? $this->options['href'] : '#/';
				$a_target = !is_null($this->target) ? " target='{$this->options['target']}'" : "";
				
				$this->code = TAB."\t<a href=\"{$this->options['href']}\" class=\"itButton-image {$this->options['class']}\"{$this->ajax} id=\"{$this->element_id}\"{$a_target}><img src=\"{$this->options['src']}\" title=\"{$this->title}\"/></a>";
				break;
				}

			case 'imajax' : {
				if (!isset($this->options['class']))
					{
					$this->options['class'] = 'itButton-image';
					}

				$this->code = TAB."\t<span id=\"{$this->element_id}\" class=\"itButton-image {$this->options['class']}\"{$this->ajax}><img src=\"{$this->options['src']}\" title=\"{$this->title}\" /></span>";
				break;
				}

			case 'imsubmit' : {
				if (isset($this->options['form']))
					{
					$this->ajax = "onclick=\"document.getElementById('{$this->options['form']}').submit();\"";
					}

				if (!isset($this->options['class']))
					{
					$this->options['class'] = 'itButton-image';
					}

				$this->code = TAB."\t<span id=\"{$this->element_id}\" class=\"itButton-image {$this->options['class']}\"{$this->ajax}/><img src=\"{$this->options['src']}\" title=\"{$this->title}\"/></span>";
				break;
				}

			case 'modal' : {
				if (($this->options==NULL) OR !isset($this->options['form']))
					{
//					$this->ajax = "onclick=\"{$default_ajax}\"";
					$this->options['form'] = '#/';
					}
				$a_target = !is_null($this->target) ? " target='{$this->options['target']}'" : "";
				
				$this->code = TAB."\t<a href='#/' id=\"{$this->element_id}\" data-reveal-id=\"{$this->options['form']}\" class=\"itButton bg_{$this->color} {$this->options['class']}\" id=\"{$this->element_id}\"{$this->ajax}{$a_target}>{$this->title}</a>";
				break;
				}

			case 'immodal' : {
				if (($this->options==NULL) OR !isset($this->options['form']))
					{
//					$this->ajax = " onclick=\"{$default_ajax}\"";
					$this->options['form'] = '#/';
					}

				$this->code = TAB."\t<span id=\"{$this->element_id}\" data-reveal-id=\"{$this->options['form']}\" class=\"{$this->options['class']}\" id=\"{$this->element_id}\"{$this->ajax}><img src=\"{$this->options['src']}\" title=\"{$this->title}\"/></span>";
				break;
				}

			case 'textmodal' : {
				$a_title = (isset($this->options['title'])) ? " title=\"".get_const($this->options['title'])."\"" : "";

				if (($this->options==NULL) or !isset($this->options['form']))
					{
//					$this->ajax = " onclick=\"{$default_ajax}\"";
					$this->options['form'] = '#/';
					}
				$a_target = !is_null($this->target) ? " target='{$this->options['target']}'" : "";
									
				$this->code = TAB."\t<a href=\"#/\" data-reveal-id=\"{$this->options['form']}\" class=\"{$this->color} {$this->options['class']}\" id=\"{$this->element_id}\"{$this->ajax}{$a_title}{$a_target}>{$this->title}</a>";
				break;
				}


			case 'close' : {
				$this->code = TAB."\t<span class=\"itButton bg_{$this->color} close-reveal-modal {$this->options['class']}\" id=\"{$this->element_id}\"{$this->ajax}>{$this->title}</span>";
				break;
				}

			case 'textclose' : {
				$this->code = TAB."\t<span class=\"close-reveal-modal {$this->color}{$this->options['class']}\" id=\"{$this->element_id}\"{$this->ajax}>{$this->title}</span>";
				break;
				}


			case 'file' : {
				if (($this->options!=NULL) and isset($this->options['name']))
					{
					$files_field = "{$this->element_id}-files";
					$this->ajax = "onclick=\"document.getElementById('$files_field').click();\"";
					}

				if (!isset($this->options['accept']))
					{
					$this->options['accept'] = 'image/jpeg,image/png,image/gif';
					}

				$this->code = TAB."\t<span class=\"itButton bg_{$this->color} {$this->options['class']}\" id=\"{$this->element_id}\"{$this->ajax}>{$this->title}</span>".
					TAB."\t<input style=\"display: none;\" accept=\"{$this->options['accept']}\" type=\"file\" id=\"$files_field\" name=\"{$this->options['name']}\" rel-op=\"{$this->options['op']}\" rel-data='".itEditor::event_data($this->options)."' />";
				break;
				}

			case 'files' : {
				if (($this->options!=NULL) and isset($this->options['name']))
					{
					$files_field = "{$this->element_id}-files";
					$this->ajax = "onclick=\"document.getElementById('$files_field').click();\"";
					}

				if (!isset($this->options['accept']))
					{
					$this->options['accept'] = 'image/jpeg,image/png,image/gif';
					}

				$this->code = TAB."\t<span class=\"itButton bg_{$this->color} {$this->options['class']}\" id=\"{$this->element_id}\"{$this->ajax}>{$this->title}</span>".
					TAB."\t<input style=\"display: none;\" accept=\"{$this->options['accept']}\" type=\"file\" id=\"$files_field\" name=\"{$this->options['name']}\" rel-op=\"{$this->options['op']}\" rel-data='".itEditor::event_data($this->options)."' multiple />";
				break;
				}

			case 'textfile' : {
				if (($this->options!=NULL) and isset($this->options['name']))
					{
					$files_field = "{$this->element_id}-files";
					$this->ajax = "onclick=\"document.getElementById('$files_field').click();\"";
					}

				if (!isset($this->options['accept']))
					{
					$this->options['accept'] = 'image/jpeg,image/png,image/gif';
					}
				
				$this->code = TAB."\t<a href=\"#/\" class=\"{$this->color} {$this->options['class']}\" id=\"{$this->element_id}\"{$this->ajax}>{$this->title}</a>".
					TAB."\t<input style=\"display: none;\" accept=\"{$this->options['accept']}\" type=\"file\" id=\"$files_field\" name=\"{$this->options['name']}\" rel-op=\"{$this->options['op']}\" rel-data='".itEditor::event_data($this->options)."' />";
				break;
				}

			case 'textfiles' : {
				if (($this->options!=NULL) and isset($this->options['name']))
					{
					$files_field = "{$this->element_id}-files";
					$this->ajax = "onclick=\"document.getElementById('$files_field').click();\"";
					}

				if (!isset($this->options['accept']))
					{
					$this->options['accept'] = 'image/jpeg,image/png,image/gif';
					}

				$this->code = TAB."\t<a href=\"#/\' class=\"{$this->color} {$this->options['class']}\" id=\"{$this->element_id}\"{$this->ajax}>{$this->title}</a>".
					TAB."\t<input style=\"display: none;\" accept=\"{$this->options['accept']}\" type=\"file\" id=\"$files_field\" name=\"{$this->options['name']}\" rel-op=\"{$this->options['op']}\" rel-data='".itEditor::event_data($this->options)."' multiple />";
				break;
				}

			case 'imfile' : {
				if (!isset($this->options['class']))
					{
					$this->options['class'] = 'itButton-image';
					}

				if (($this->options!=NULL) and isset($this->options['name']))
					{
					$files_field = "{$this->element_id}-files";
					$this->ajax = "onclick=\"document.getElementById('$files_field').click();\"";
					}

				if (!isset($this->options['accept']))
					{
					$this->options['accept'] = 'image/jpeg,image/png,image/gif';
					}

				$this->code = TAB."\t<span id=\"{$this->element_id}\" class=\"itButton-image {$this->options['class']}\"{$this->ajax}><img src=\"{$this->options['src']}\" title=\"{$this->title}\" /></span>".
					TAB."\t<input style=\"display: none;\" accept=\"{$this->options['accept']}\" type=\"file\" id=\"$files_field\" name=\"{$this->options['name']}\" rel-op=\"{$this->options['op']}\" rel-data='".itEditor::event_data($this->options)."' />";

				break;
				}


			case 'imfiles' : {
				if (!isset($this->options['class']))
					{
					$this->options['class'] = 'itButton-image';
					}

				if (($this->options!=NULL) and isset($this->options['name']))
					{
					$files_field = "{$this->element_id}-files";
					$this->ajax = "onclick=\"document.getElementById('$files_field').click();\"";
					}

				if (!isset($this->options['accept']))
					{
					$this->options['accept'] = 'image/jpeg,image/png,image/gif';
					}

				$this->code = TAB."\t<span id=\"{$this->element_id}\" class=\"itButton-image {$this->options['class']}\"{$this->ajax}><img src=\"{$this->options['src']}\" title=\"{$this->title}\" /></span>".
					TAB."\t<input style=\"display: none;\" accept=\"{$this->options['accept']}\" type=\"file\" id=\"$files_field\" name=\"{$this->options['name']}\" rel-op=\"{$this->options['op']}\" rel-data='".itEditor::event_data($this->options)."' multiple />";

				break;
				}

			}
		}


	//..............................................................................
	// возвращает id кнопки
	//..............................................................................
	public function element_id()
		{
		return $this->element_id;
		}


	//..............................................................................
	// возвращает код кнопки с привязкой обработчика события ($options)
	//..............................................................................
	public function code()
		{
		return $this->code;
		}

	//..............................................................................
	// устанавливает цвет кнопки
	//..............................................................................
	public function set_color($color=DEFAULT_BUTTON_COLOR)
		{
		$this->color = $color;
		}
	} // class

?>
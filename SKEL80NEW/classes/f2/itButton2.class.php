<?
global $button_counter;
$button_counter = (function_exists('rand_id')) ? rand_id() : 0;

//..............................................................................
// itButton2 : класс построение управляющей кнопки для форм (версия 2.1)
//..............................................................................
class itButton2
	{
	public $code;

	//..............................................................................
	// конструктор класса - создает кнопку и управление для нее по параметрам:
	//..............................................................................
	public function __construct($options=NULL)
		{
		global $button_counter;
		$button_counter++;
		
		$this->type		= ready_val($options['type'], DEFAULT_BUTTON_TYPE);

		$this->element_id	= ready_val($options['element_id'], "itButton-{$button_counter}");
		$this->title		= ready_val($options['title'], $this->element_id);
		$this->title		= get_const($this->title);
		
		$this->hint			= ready_val($options['hint']);		

		$this->color		= ready_val($options['color'], ($this->type!='text') ? DEFAULT_BUTTON_COLOR : NULL);
		$this->class 		= ready_val($options['class']);
		$this->ajax			= ready_val($options['ajax']);
		$this->form_id		= ready_val($options['form_id'], ready_val($options['form']));
		
		$this->href			= ready_val($options['href'], "#/");
		$this->target		= ready_val($options['target']);
		$this->src			= ready_val($options['src']);
		
		$this->name			= ready_val($options['name'], $this->element_id);
		$this->accept 		= ready_val($options['accent'], DEFAULT_FILES_ACCEPT);

		$this->show 		= ready_val($options['show'], false);
		$this->compile();
		}

	//..............................................................................
	// генерирует код кнопки на основе установленных параметров и заносит в code
	//..............................................................................
	public function compile()
		{
		// подготовим данные 

		$element_str	= " id=\"{$this->element_id}\"";
		$hint_str  	= !empty($this->hint)	? " title=\"".get_const($this->hint)."\"" : NULL;
		$target_str	= !empty($this->target)	? " target=\"{$this->target}\"" : NULL;
		$show_str = $this->show ? "show_loader('ok');" : NULL;

		//  поработаем с кодом javascript на все случаи
		switch ($this->type)
			{
			case 'submit' :
			case 'textsubmit' :
			case 'imsubmit' :
				{
				$ajax_str 	= " onclick = \"{$show_str}$('#{$this->form_id}').submit();\"";
//  				$ajax_str 	= " onclick = \"document.getElementById('{$this->form_id}').submit();\"";				
				break;
				}
			case 'ajaxsubmit' :
			case 'ajaxtextsubmit' :
				{
				$ajax_str 	= " onclick = \"{$show_str}ajax_submit('#{$this->form_id}', function(){ {$this->ajax} });\"";
				break;
				}

			case 'files' : {
				$this->files_field = "{$this->element_id}-files";
				$ajax_str 	=  " onclick=\"document.getElementById('{$this->files_field}').click();\"";
				$this->rel_data = itEditor::event_data((array)$this);
				break;
				}
				
			default : {
				$ajax_str 	= !empty($this->ajax) ? " onclick=\"{$this->ajax}\"" : NULL;
				break;
				}
			}
		// соберем технические данные
		$tools_str	= 
			$element_str.
			$hint_str.
			$target_str.
			$ajax_str;

		$class_str	=
			(!empty($this->class)	? " {$this->class}" : NULL).
			(($this->title==get_const('BUTTON_OK')) ? " ok" : NULL);

		// строим код по типу кнопки
		switch ($this->type)
			{
			case 'a' : {
				$this->code = "<a href=\"{$this->href}\" class=\"itButton bg_{$this->color}{$class_str}\"{$tools_str}>{$this->title}</a>";
				break;
				}

			case 'text' : {
				$this->code = "<a href=\{$this->href}\" class=\"{$this->color}{$class_str}\"{$tools_str}>{$this->title}</a>";
				break;
				}

			case 'textsubmit' : {
				$this->code = "<span class=\"submit {$this->color}{$class_str}\"{$tools_str}>{$this->title}</span>";
				break;
				}
				
			case 'ajaxtextsubmit' : {
				$this->code = "<span class=\"submit {$this->color}{$class_str}\"{$tools_str}>{$this->title}</span>";
				break;
				}


			case 'submit' : {
				$this->code = "<span class=\"itButton submit bg_{$this->color}{$class_str}\"{$tools_str}>{$this->title}</span>";
				break;
				}

			case 'ajaxsubmit' : {
				$this->code = "<span class=\"itButton submit bg_{$this->color}{$class_str}\"{$tools_str}>{$this->title}</span>";
				break;
				}

			case 'image' : 
			case 'imajax' : {
				$this->code = "<a href=\"{$this->href}\" class=\"itButton-image{$class_str}\"{$tools_str}}><img src=\"{$this->src}\"/></a>";
				break;
				}

			case 'imsubmit' : {
				$this->code = "<a href=\"{$this->href}\" class=\"itButton-image{$class_str}\"{$tools_str}}><img src=\"{$this->src}\"/></a>";
				break;
				}

			case 'modal' : {
				$this->code = "<a href=\"#/\" data-reveal-id=\"{$this->form_id}\" class=\"itButton bg_{$this->color}{$class_str}\"{$tools_str}>{$this->title}</a>";
				break;
				}

			case 'immodal' : {
				$this->code = "<a href=\"#/\" data-reveal-id=\"{$this->form_id}\" class=\"itButton-image{$class_str}\"{$tools_str}}><img src=\"{$this->src}\"/></a>";
				break;
				}

			case 'textmodal' : {
				$this->code = "<a href=\"#/\" data-reveal-id=\"{$this->form_id}\" class=\"{$this->color}{$class_str}\"{$tools_str}>{$this->title}</a>";
				break;
				}


			case 'close' : {
				$this->code = "<span class=\"itButton close-reveal-modal bg_{$this->color}{$class_str}\"{$tools_str}>{$this->title}</span>";
				break;
				}

			case 'textclose' : {
				$this->code = "<span class=\"close-reveal-modal bg_{$this->color}{$class_str}\"{$tools_str}>{$this->title}</span>";
				break;
				}


			case 'file' : {
				$this->code = "<span class=\"itButton bg_{$this->color}{$class_str}\"{$tools_str}>{$this->title}</span>".
					"<input style=\"display: none;\" accept=\"{$this->accept}\" type=\"file\" id=\"{$this->files_field}\" name=\"{$this->name}\" rel-data=\"{$this->reldata}\" />";
				break;
				}

			case 'files' : {
				$this->code = "<span class=\"itButton bg_{$this->color}{$class_str}\"{$tools_str}>{$this->title}</span>".
				"<input style=\"display: none;\" accept=\"{$this->accept}\" type=\"file\" id=\"{$this->files_field}\" name=\"{$this->name}\" rel-data=\"{$this->reldata}\" multiple/>";
				break;
				}

			case 'textfile' : {
				$this->code = "<span class=\"{$this->color}{$class_str}\"{$tools_str}>{$this->title}</span>".
					"<input style=\"display: none;\" accept=\"{$this->accept}\" type=\"file\" id=\"{$this->files_field}\" name=\"{$this->name}\" rel-data=\"{$this->reldata}\"/>";
				break;
				}

			case 'textfiles' : {
				$this->code = "<span class=\"{$this->color}{$class_str}\"{$tools_str}>{$this->title}</span>".
					"<input style=\"display: none;\" accept=\"{$this->accept}\" type=\"file\" id=\"{$this->files_field}\" name=\"{$this->name}\" rel-data=\"{$this->reldata}\" multiple/>";
				break;
				}

			case 'imfile' : {
				$this->code = "\t<a href=\"{$this->href}\" class=\"itButton-image{$class_str}\"{$tools_str}}><img src=\"{$this->src}\"/></a>";
					"<input style=\"display: none;\" accept=\"{$this->accept}\" type=\"file\" id=\"{$this->files_field}\" name=\"{$this->name}\" rel-data=\"{$this->reldata}\"/>";
				break;
				}


			case 'imfiles' : {
				$this->code = "\t<a href=\"{$this->href}\" class=\"itButton-image{$class_str}\"{$tools_str}}><img src=\"{$this->src}\"/></a>";
					"<input style=\"display: none;\" accept=\"{$this->accept}\" type=\"file\" id=\"{$this->files_field}\" name=\"{$this->name}\" rel-data=\"{$this->reldata}\" multiple/>";
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
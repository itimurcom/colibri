<?php
// ================ CRC ================
// version: 1.15.07
// hash: 45ba3671a98e163a4662421a09290a8fe7ac35524e0a15710b6003ccea0857a7
// date: 21 May 2021 10:57
// ================ CRC ================

class itForm extends itForm2
	{
	public function __construct($var=NULL)
		{
		global $_CONTENT;
		if (!isset($_CONTENT['log'])) $_CONTENT['log'] = NULL;
		$_CONTENT['log'].= "<script> console.log('form2', '".json_encode($var)."');</script>";
		parent::__construct($var);
		}
	}

/*
global $form_count;
$form_count = rand_id();
//..............................................................................
// itForm : класс построения формы 2.0
//..............................................................................
class itForm
	{
	public $code, $action;
	private $form_id, $fields, $method, $buttons, $reCaptcha;
	public $table_name, $field_name, $rec_id, $data;

	//..............................................................................
	// конструктор класса - проверяет установку языка при создании обекта класса
	//..............................................................................	
	public function __construct($var=NULL)
		{
		global $form_count;
		$form_count ++;

		if (isset($var['rec_id']))
			{
			$this->rec_id = $options['rec_id'];
			$this->data = itMySQL::_get_rec_from_db($this->table_name, $this->rec_id);
			$this->form_id = "form-{$this->table_name}-{$this->rec_id}";
			$options = $this->data['options_xml'];
			} else 
				if (is_array($var))
					{
					$options = $var;
					$this->form_id 	= ready_val($options['name'], "form-{$form_count}");
					} else	{
						$this->form_id 	= is_null($var) ? "form-{$form_count}" : $var;
						}

		$this->table_name	= ready_val($options['table_name'], DEFAULT_FORM_TABLE);
		$this->field_name	= ready_val($options['field_name'], DEFAULT_FORMFIELD_TABLE);		
		$this->action		= ready_val($options['action'], DEFAULT_FORM_ACTION);
		$this->method		= ready_val($options['method'], DEFAULT_FORM_METHOD);
		$this->reCaptcha	= ready_val($options['reCaptcha'], DEFAULT_FORM_CAPTCHA);
		}


	//..............................................................................
	// загружает форму из данных, которые сохранены в базе данных
	//..............................................................................	
	public function from_db()
		{
		global $_USER;
		if (is_array($this->data['fields']))
			{
			foreach ($this->data['fields'] as $field_id)
				{
				if ($row = itMySQL::_get_rec_from_db($this->field_name, $field_id))
					{
					if (($row['status']=='PUBLISHED') OR $_USER->is_logged())
					switch ($row['type_id'])
						{
						case 'TITLE' :
						case 'DESC' :
						case 'INPUT' :
						case 'AREA' :
						case 'PASSWORD' :
						case 'HIDDEN' :
						case 'SELECTOR' :
						case 'AUTOSELECT' :
						case 'DATE' :
						case 'TIME' :
						case 'UPGAL' : 
						case 'FIELD' : {
							$this->fields[] = $row;
							break;
							}
						case 'BUTTON' : {
							$this->buttons[] = $row;
							break;
							}
						}
					}
				}
			}
		}

	//..............................................................................
	// устанавливает ссылку на новую обратоку формы
	//..............................................................................	
	public function action($action=NULL)
		{
		return is_null($action) ? $this->action : ($this->action = $action);
		}

	//..............................................................................
	// устанавливает флаг проверки I am not robot
	//..............................................................................	
	public function reCaptcha($value=false)
		{
		return is_null($value) ? $this->reCaptcha : ($this->reCaptcha = $value);
		}
	//..............................................................................
	// добавляет строку заголовка подгруппы формы
	//..............................................................................	
	public function add_title($title='')
		{
		$this->fields[] = [
			'id'		=> NULL,
			'type_id'	=> 'TITLE',
			'title_xml'	=> [CMS_LANG => $title],
			'ed_xml'	=> NULL,
			'name'		=> NULL,
			'options_xml'	=> NULL,
			'default'	=> NULL,
			];
						
		}

	//..............................................................................
	// добавляет скрытые параметры данных
	//..............................................................................	
	public function add_data($data=NULL)
		{
		if (is_array($data))
			{
			$this->$o_form->add_data($data));
			}
		}

	//..............................................................................
	// добавляет строку описания
	//..............................................................................	
	public function add_description($value='')
		{
		$this->fields[] = [
			'id'		=> NULL,
			'type_id'	=> 'DESC',
			'title_xml'	=> [CMS_LANG => $value],
			'ed_xml'	=> NULL,
			'name'		=> NULL,
			'options_xml'	=> NULL,
			'default'	=> NULL,
			];	

// 		$this->fields[] = array (
// 			'type' => 'description',
// 			'code' => TAB."\t$title"
// 			);		
		}

	//..............................................................................
	// добавляет поле ввода с описанием
	//..............................................................................	
	public function add_input($name='', $value='', $label='', $placeholder=false, $class=NULL)
		{
		$options = is_array($name)
			? $name
			: 	[
				'value'		=> $value,
				'name'		=> $name,
				'label'		=> $label,
				'class'		=> $class,
				'placeholder'	=> $placeholder,
				];
		
		$title = ready_val($options['label']);
		if (isset($options['label']))		
			unset($options['label']);
								
		$this->fields[] = [
			'id'		=> NULL,
			'type_id'	=> 'INPUT',
			'title_xml'	=> [ CMS_LANG => $title],
			'ed_xml'	=> NULL,
			'name'		=> ready_val($options['name']),
			'options_xml'	=> $options,
			'default'	=> NULL,
			];
		}


	//..............................................................................
	// добавляет поле ввода пароля с описанием
	//..............................................................................	
	public function add_password($name='', $value='', $label='', $placeholder=false, $class=NULL)
		{
		$options = is_array($name)
			? $name
			: 	[
				'value'		=> $value,
				'name'		=> $name,
				'label'		=> $label,
				'class'		=> $class,
				'placeholder'	=> $placeholder,
				];

		if (empty(ready_val($options['value'], '')))
			{
			$options['value'] = isset($_REQUEST[$options['name']]) ? $_REQUEST[$options['name']] : NULL;
			}

		$title = ready_val($options['label']);
		if (isset($options['label']))		
			unset($options['label']);
			
		$options['type'] = 'password';
		$this->fields[] = [
			'id'		=> NULL,
			'type_id'	=> 'PASSWORD',
			'title_xml'	=> [ CMS_LANG => $title],
			'ed_xml'	=> NULL,
			'name'		=> ready_val($options['name']),
			'options_xml'	=> $options,
			'default'	=> NULL,
			];
		}


	//..............................................................................
	// добавляет поле ввода текста с описанием
	//..............................................................................	
	public function add_area($name='', $value='', $label='', $placeholder=false, $max=NULL)
		{
		$options = is_array($name)
			? $name
			: 	[
				'value'		=> $value,
				'name'		=> $name,
				'label'		=> $label,
				'placeholder'	=> $placeholder,
				'max'		=> $max,
				];

		$title = ready_val($options['label']);
		if (isset($options['label']))		
			unset($options['label']);
		
		$this->fields[] = [
			'id'		=> NULL,
			'type_id'	=> 'AREA',
			'title_xml'	=> [ CMS_LANG => $title],
			'ed_xml'	=> NULL,
			'name'		=> ready_val($options['name']),
			'options_xml'	=> $options,
			'default'	=> NULL,
			];
		}

	//..............................................................................
	// добавляет поле ввода с описанием
	//..............................................................................	
	public function add_hidden($name='', $value='')
		{
		$options = is_array($name)
			? $name
			: 	[
				'value'		=> $value,
				'name'		=> $name,
				];

		$this->fields[] = [
			'id'		=> NULL,
			'type_id'	=> 'HIDDEN',
			'title_xml'	=> NULL,
			'ed_xml'	=> NULL,
			'name'		=> ready_val($options['name']),
			'options_xml'	=> $options,
			'default'	=> NULL,
			];
		}
		
	//..............................................................................
	// добавляет селектор класса itSelector в поле формы
	//..............................................................................	
	public function add_itSelector($type=DEFAULT_SELECTOR_TYPE, $options_var=NULL, $value='', $element_id=NULL, $label=NULL)
		{
		$options = is_array($type)
			? $type
			: array_merge($options_var, [
				'type'		=> $type,
				'value'		=> $value,
				'element_id'	=> $element_id,
				'label'		=> isset($options_var['label']) ? $options_var['label'] : $label,
				]);

		$title = ready_val($options['label']);
		if (isset($options['label']))		
			unset($options['label']);
					
		$this->fields[] = [
			'id'		=> NULL,
			'type_id'	=> 'SELECTOR',
			'title_xml'	=> [ CMS_LANG => $title ],
			'ed_xml'	=> NULL,
			'name'		=> ready_val($options['name']),
			'options_xml'	=> $options,
			'default'	=> NULL,
			];			
		}
		
	//..............................................................................
	// добавляет селектор класса itAutoSelect в поле формы
	//..............................................................................	
	public function add_itAutoSelect($options=NULL, $label=NULL)
		{
		$title = isset($options['label']) ? $options['label'] : $label;
		if (isset($options['label']))		
			unset($options['label']);
			
		$this->fields[] = [
			'id'		=> NULL,
			'type_id'	=> 'AUTOSELECT',
			'title_xml'	=> [ CMS_LANG => $title ],
			'ed_xml'	=> NULL,
			'name'		=> ready_val($options['name']),
			'options_xml'	=> $options,
			'default'	=> NULL,
			];			
		}		


	//..............................................................................
	// добавляет селектор выбора времени в поле формы
	//..............................................................................	
	public function add_timeSelector($type=DEFAULT_SELECTOR_TYPE, $value='', $element_id=NULL, $label=NULL)
		{
		global $times;
		if (is_array($times))
			{
			$options = is_array($type)
				? $type
				: 	[
					'type'		=> $type,
					'value'		=> $value,
					'element_id'	=> $element_id,
					'label'		=> isset($options_var['label']) ? $options_var['label'] : $label,
					'options'	=> $options_var,
					];				

			$options['array'] 	= $times;
			$options['titles']	= 'title';
			$options['values']	= 'value';

			$title = isset($options['label']) ? $options['label'] : $label;
			if (isset($options['label']))		
				unset($options['label']);

			$this->fields[] = [
				'id'		=> NULL,
				'type_id'	=> 'TIME',
				'title_xml'	=> [ CMS_LANG => $title ],
				'ed_xml'	=> NULL,
				'name'		=> ready_val($options['name']),
				'options_xml'	=> $options,
				'default'	=> NULL,
				];
			}
		}
		
	//..............................................................................
	// добавляет кнопку выбора даты
	//..............................................................................	
	public function add_itDate($value=NULL, $options_var=NULL)
		{
		$options = is_array($value)
			? $value
			:  array_merge($options_var, [
				'value'		=> $value,
				]);

		$title = isset($options['label']) ? $options['label'] : NULL;
		if (isset($options['label']))		
			unset($options['label']);

				
		$this->fields[] = [
			'id'		=> NULL,
			'type_id'	=> 'DATE',
			'title_xml'	=> [ CMS_LANG => $title],
			'ed_xml'	=> NULL,
			'name'		=> ready_val($options['name']),
			'options_xml'	=> $options,
			'default'	=> NULL,
			];				
		}


	//..............................................................................
	// добавляет динамическую загрузку изображений в массив полей
	//..............................................................................	
	public function add_upgal($options=NULL)
		{
		$title = isset($options['label']) ? $options['label'] : NULL;
		if (isset($options['label']))		
			unset($options['label']);
						
		$this->fields[] = [
			'id'		=> NULL,
			'type_id'	=> 'UPGAL',
			'title_xml'	=> [ CMS_LANG => $title],
			'ed_xml'	=> NULL,
			'name'		=> ready_val($options['name']),
			'options_xml'	=> $options,
			'default'	=> NULL,
			];
		}		

	//..............................................................................
	// добавляет элемент в массив полей кода
	//..............................................................................	
	public function add_field($f_code = '')
		{
		$this->fields[] = [
			'id'		=> NULL,
			'type_id'	=> 'FIELD',
			'title_xml'	=> [CMS_LANG => $f_code],
			'ed_xml'	=> NULL,
			'name'		=> NULL,
			'options_xml'	=> NULL,
			'default'	=> NULL,
			];
						
		}

	//..............................................................................
	// добавляет кнопку класса itButton в интерфейсную зону модального окна
	//..............................................................................	
	public function add_itButton($title='Ok', $type = DEFAULT_BUTTON_TYPE, $options_var=NULL,  $color='', $element_id=NULL)
		{
		$options = is_array($title)
			? $title
			: array_merge($options_var, [
				'title'		=> $title,
				'type'		=> $type,
				'element_id'	=> $element_id,
				'color'		=> $color,
				]);

		$this->buttons[] = [
			'id'		=> NULL,
			'type_id'	=> 'BUTTON',
			'title_xml'	=> NULL,
			'ed_xml'	=> NULL,
			'name'		=> ready_val($options['name']),
			'options_xml'	=> $options,
			'default'	=> NULL,
			];
								
// 		$options['form'] = $this->form_id;
// 		$this->buttons[] = new itButton($title, $type, $options, $color, $element_id);		
		}

	//..............................................................................
	// генерирует html код модального окна и заносит его в поле $code
	//..............................................................................	
	public function compile()
		{
		global $plug_js;
// 		global $captchacallback;
		$captcha_str = NULL;
		// вставим каптчу если надо
		if ($this->reCaptcha)
			{
			if (defined('RECAPTCHA_KEY'))
			{
// 			$captchacallback = ready_val($captchacallback, 0);
// 			$captchacallback++;
			$captcha_str = 
// 				(($captchacallback==1) ? 
//  					TAB."<script type='text/javascript'>function CaptchaCallback() { $('.g-recaptcha').each(function(){ grecaptcha.render(this,{'sitekey' : '".RECAPTCHA_KEY."'}); }) }</script>".
// 					TAB."<script type='text/javascript' src='https://www.google.com/recaptcha/api.js?hl=".CMS_LANG."&onload=CaptchaCallback&render=explicit'></script>" : "").
				TAB."<div class=\"buttons_div\">".
				TAB."<div id=\"{$this->form_id}-reCaptcha\" class=\"g-recaptcha\"></div>".				
				TAB."</div>".
				"";
			} else {
				if (get_const('DEBUG_ON')==1)
					{
					echo "<br/> cant find <b>reCaptcha KEY <a class='blue' target='_blank' href='https://www.google.com/recaptcha/admin'>correct</a></b>";
					}
				}
			}

		$this->code .= 
			TAB."<form id=\"{$this->form_id}\" action=\"{$this->action}\" method=\"{$this->method}\" accept-charset=\"utf-8\">".
			$this->compile_fields().
			$captcha_str.
			$this->compile_buttons().
			$this->hidden_submit().
			TAB."</form>";
		}

	//..............................................................................
	// возвращает код html полноценной формы (версия 2.0)
	//..............................................................................	
	public function compile_fields()
		{
		global $form_blocks;
		$code = NULL;
		if (is_array($this->fields))
		foreach ($this->fields as $row)
			if (isset($form_blocks[$row['type_id']]))
				{
				// передаем имя формы
				$editor_code = NULL;
				$compact = ready_val($row['options_xml']['compact'], false) ? ' compact' : '';
				if (!is_null($row['id']))
					{
					$o_ed = new itEditor([
						'table_name'	=> $this->field_name,
						'rec_id'	=> $row['id'],
						]);
					$editor_code = 
						TAB."<div class=\"more{$compact}\">".
						$o_ed->container().
						TAB."</div>";
					unset($o_ed);
					}
				
				// передаем дополнительные данные
				$row['options_xml']['form_id'] = $this->form_id;
				$row['options_xml']['label'] = get_field_by_lang($row['title_xml'],  CMS_LANG, '');
				$row['options_xml']['editor'] = $editor_code;
				
				switch ($row['type_id'])
					{
					case 'TITLE' :	{
						$code .= mstr_replace([
							'[VALUE]' => get_field_by_lang($row['title_xml']),
							'[CLASS]' => 'title',
							], TAB.$form_blocks[$row['type_id']]['code']);
						break;
						}
					case 'DESC' : {
						$code .= mstr_replace([
							'[VALUE]' => get_field_by_lang($row['title_xml']),
							'[CLASS]' => 'description',
							], TAB.$form_blocks[$row['type_id']]['code']);
						break;	
						}
						
					case 'HIDDEN' : {
						$code .= mstr_replace([
							'[VALUE]' 	=> htmlentities($row['options_xml']['value']),
							'[NAME]'	=> $row['name'],
							'[ID]'		=> "{$this->form_id}-{$row['name']}",
							], TAB.$form_blocks[$row['type_id']]['code']);
						break;
						}
					case 'FIELD' :	{
						$code .= mstr_replace([
							'[CODE]' 	=> get_field_by_lang($row['title_xml']),
							'[CLASS]' 	=> 'field',
							'[ID]'		=> "$this->form_id}-{$row['name']}",
							], TAB.$form_blocks[$row['type_id']]['code']);
						break;
						}						

					case 'INPUT' :
					case 'PASSWORD' : {
						$o_input = new itInput($row['options_xml']);
						$code .=
							TAB."<div class='modal_row'>".
							$o_input->code().
							TAB."</div>";
						unset($o_input);
						break;
						}					
					case 'AREA' : {
						$o_area = new itArea($row['options_xml']);
						$code .=
							TAB."<div class='modal_row'>".
							$o_area->code().
							TAB."</div>";
						unset($o_area);
						break;
						}
					case 'SELECTOR' :
					case 'TIME' : {
						$o_selector = new itSelector($row['options_xml']);
						$code .=
							TAB."<div class='modal_row'>".
							$o_selector->code().
							TAB."</div>";
						unset($o_selector);
						break;
						}
					case 'AUTOSELECT' : {
						$o_auto = new itAutoSelect($row['options_xml']);
						$code .=
							TAB."<div class='modal_row'>".
							$o_auto->code().
							TAB."</div>";
						unset($o_auto);
						break;
						}
						
					case 'DATE' : {
						$o_date = new itDate($row['options_xml']);
						$code .=
							TAB."<div class='modal_row'>".
							$o_date->code().
							TAB."</div>";
						unset($o_date);
						break;
						}

					case 'UPGAL' : {
						$o_upgal = new itUpGal($row['options_xml']);
						$code .=
							TAB."<div class='modal_row'>".
							$o_upgal->code().
							TAB."</div>";
						unset($o_upgal);
						break;
						}
					}
				}
		return $code;
		}			

	//..............................................................................
	// возвращает код html интерфейсной зоны кнопок
	//..............................................................................	
	private function compile_buttons()
		{
		$code = NULL;
		if (is_array($this->buttons))
			{
			$code = TAB."\t<div class=\"buttons_div\">";
			foreach ($this->buttons as $row)
				{
				$row['options_xml']['form'] = $this->form_id;
				$o_button = new itButton($row['options_xml']);
				$code .= $o_button->code();
				unset($o_button);
				}
			$code .= TAB."\t</div>";

			}
		return $code;
		}

	//..............................................................................
	// возвращает идентификатор формы
	//..............................................................................	
	public function form_id()
		{
		return $this->form_id;
		}

	//..............................................................................
	// добавляет кнопку скртытой отправки формы
	//..............................................................................	
	public function hidden_submit()
		{
		$result = TAB."\t<input type=\"submit\" class=\"hidden_submit\" tabindex=\"-1\"/>";
		return $result;
		}

	//..............................................................................
	// возвращает результат проверки reCaptcha
	//..............................................................................	
	static function check_reCaptcha()
		{
		if (!isset($_REQUEST['g-recaptcha-response'])) return NULL;
		
		$resp_json = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".get_const('RECAPTCHA_SECRET')."&response={$_REQUEST['g-recaptcha-response']}");
		$recaptcha = json_decode($resp_json, true);		
		return (!is_array($recaptcha) OR !isset($recaptcha['success'])) ? NULL : $recaptcha['success'];
		}
	
	//..............................................................................
	// возвращает код рендеринга для reCaptcha
	//..............................................................................
	public function render_reCaptcha()
		{
		return "setTimeout(function(){alert('render');grecaptcha.render('{$this->form_id}-reCaptcha', {sitekey: '".get_const('RECAPTCHA_KEY')."', theme: 'light'});}, 1000);";
		}

	//..............................................................................
	// возвращает код скомпилированной формы
	//..............................................................................	
	public function code()
		{
		return $this->code;
		}

	} //class
*/

?>
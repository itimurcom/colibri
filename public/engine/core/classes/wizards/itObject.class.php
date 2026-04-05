<?php
// ================ CRC ================
// version: 1.15.03
// hash: 5f0453f5030692adab6ff4c217a2bc58dec44267a798446c30d845b19d4a4e34
// date: 09 September 2019  5:10
// ================ CRC ================
global $object_counter;
$object_counter = (function_exists('rand_id')) ? rand_id() : time();

definition([
	'DEFAULT_ITEM_TABLE'	=> 'items',		
	'DEFAULT_OBJECT_TABLE'	=> 'objects',
	'DEFAULT_CATEGORY_TABLE'=> 'categories',
	'DEFAULT_WIZARD_TABLE'	=> 'categories',	
	'DEFAULT_WIZARD_VALUES'	=> 'values_xml',
	'DEFAULT_WIZARD_FIELD' 	=> 'wizard_xml',
	'DEFAULT_OBJECT_ACTION'	=> '/ed_field.php',
	'DEFAULT_OBJECT_METHOD'	=> 'POST',
	'DEFAULT_OBJECT_CAPTCHA'=> false,
	'DEFAULT_OBJECT_OP'	=> 'obj_form',
	'DEFAULT_CAT_FIELD'	=> 'category_id',
	]);

//..............................................................................
// itObject : класс управления объектами каталога товара
//..............................................................................
class itObject
	{
	public $table_name, $rec_id, $data, $wizard, $val_field, $wiz_field, $wiz_values, $cat_field, $code;
	public $action, $method, $reCaptcha;
	//..............................................................................
	// конструктор класса
	//..............................................................................
	public function __construct($options=NULL)
		{
		global $object_counter;
		$object_counter ++;
		
		$this->name 		= "object-{$object_counter}";
		$this->table_name	= ready_val($options['table_name'], get_const('DEFAULT_OBJECT_TABLE'));
		$this->category_name	= ready_val($options['category_name'], get_const('DEFAULT_CATEGORY_TABLE'));
		$this->rec_id		= ready_val($options['rec_id']);
		$this->data 		= itMySQL::_get_rec_from_db($this->table_name, $this->rec_id);

		$this->wiz_field	= ready_val($options['wiz_field'], DEFAULT_WIZARD_FIELD);
		$this->cat_field	= ready_val($options['cat_field'], DEFAULT_CAT_FIELD);
		$this->wiz_values	= ready_val($options['wiz_values'], DEFAULT_WIZARD_VALUES);
		
		$this->action		= ready_val($options['action'], DEFAULT_OBJECT_ACTION);
		$this->method		= ready_val($options['method'], DEFAULT_OBJECT_METHOD);
		$this->placeholder	= ready_val($options['placeholder'], get_const('DEFAULT_PLACEHOLDER'));
		$this->reCaptcha	= ready_val($options['reCaptcha'], DEFAULT_OBJECT_CAPTCHA);		
		$this->op		= ready_val($options['op'], DEFAULT_OBJECT_OP);

		$this->get_wizard();
		}

	//..............................................................................
	// получает wizard
	//..............................................................................		
	public function get_wizard()
		{
		if (isset($this->data[$this->cat_field]))
			{
			$cat_rec['parent_id'] = $this->data[$this->cat_field];
			$i=0;
			$wiz_arr=[];
			while ($cat_rec['parent_id']!=0)
				{
				$i++;
				$options = [
				'table_name'	=>	$this->category_name,
				'rec_id'	=> 	$cat_rec['parent_id'],
				];
			
				$o_wizard = new itWizard($options);
				$wiz_arr[$i] = $cat_rec = $o_wizard->ed_rec;
				unset($o_wizard);
				if ($i>5) break;
				}
			if (is_array($wiz_arr))
				{
				krsort($wiz_arr);
				foreach($wiz_arr as $key=>$row)
					{
					if (is_array($row[$this->wiz_field]))
						{
						foreach ($row[$this->wiz_field] as $wiz_key=>$wiz_row)
							{
							$this->wizard[$wiz_row['name']] = $wiz_row;
							$this->wizard[$wiz_row['name']]['value']
								= isset($this->data[$this->wiz_values][$wiz_row['name']]) ? $this->data[$this->wiz_values][$wiz_row['name']] : NULL;
							
							$value_text  = is_null($this->wizard[$wiz_row['name']]['value']) ? get_const('NO_DATA') : $this->wizard[$wiz_row['name']]['value'];
			
							// проверим наличие заголовков и отдадим редультат	
							if (!in_array($wiz_row['type'], unserialize(WIZARD_NOTITLES)))
								{
								foreach($wiz_row['titles'][CMS_LANG] as $sel_key=>$sel_row)
									{
									$sel_arr[$wiz_row['values'][$sel_key]] = 
										[
										'title'	=> get_const($sel_row),
										'value'	=> $wiz_row['values'][$sel_key],
										];	
									}
								$value_text = ready_val($sel_arr[$value_text]['title'], 'NO_DATA');
								}  
							$this->wizard[$wiz_row['name']]['text']	= $value_text;
							$this->wizard[$wiz_row['name']]['table_name']	= $this->table_name;
							$this->wizard[$wiz_row['name']]['rec_id'] = $this->rec_id;				
							}
						}
					}
				}
			}
			
		}
	
	//..............................................................................
	// сохраняет открытый объект
	//..............................................................................
	public function store()
		{
		itMySQL::_update_db_rec($this->table_name, $this->rec_id, $this->data);
		}
		
	//..............................................................................
	// разыменовывает поля указанного типа wizard для выбранного контента
	//..............................................................................	
	public function compile()
		{
		$this->get_wizard();
		$rows_code = NULL;
		if (is_array($this->data))
			{
			$rows = NULL;

			$this->code = 
				TAB."<div class='wizard'>";
			
			$this->wizard();			
			$this->code .= 
				TAB."</div>";
			}
		}

	//..............................................................................
	// возвращает скомпилированный код объекта
	//..............................................................................	
	public function code() 
		{
		return ($this->code);
		}

	//..............................................................................
	// добавляет объект
	//..............................................................................	
	static function _add($options=NULL)
		{
		global $_USER;
		$cat_field = ready_val($options['cat_field'], DEFAULT_CAT_FIELD);		
		if (is_array($options) AND isset($options[$cat_field]))
			{
			$options['table_name']	= ready_val($options['table_name'], DEFAULT_WIZARD_TABLE);
			
			$values_arr = [
				'user_id'	=> ready_val($options['user_id'], $_USER->id()),
				'avatar'	=> ready_val($options['avatar']),
				$cat_field	=> $options[$cat_field],
				'status'	=> 'PUBLISHED',
			];
			
			if (isset($options['title']))
				$values_arr['title_xml'][CMS_LANG] = $options['title'];

			if (isset($options['description']))
				$values_arr['ed_xml'][CMS_LANG] = $options['description'];
				
			if (isset($options['values']))
				$values_arr['values_xml'] = $options['values'];
			$rec_id = itMySQL::_insert_rec($options['table_name'], $values_arr);
			return $rec_id;			
			} else add_error_message('ERROR_OPTIONS_OBJECT');
		}

	//..............................................................................
	// добавляет значение поля визарда для выбранного объекта
	//..............................................................................	
	static function _update_value($options=NULL)
		{
		if (isset($options['rec_id']) AND isset($options['name']))
			{
			$options['table_name'] = ready_val($options['table_name'], DEFAULT_OBJECT_TABLE);
			$o_object = new itObject($options);
			$o_object->data[$o_object->wiz_values][$options['name']] = ready_val($options['value']);
			$o_object->store();
			unset($o_object);
			} else add_error_message('ERROR_OPTIONS_OBJECT');
		}

	//..............................................................................
	// устанавливает категорию для объекта
	//..............................................................................	
	static function _set_category($options=NULL)
		{
		$cat_field = ready_val($options['cat_field'], DEFAULT_CAT_FIELD);
		if (isset($options['rec_id']) AND isset($options['value']))
			{
			$options['table_name'] = ready_val($options['table_name'], DEFAULT_OBJECT_TABLE);
			itMySQL::_update_value_db($options['table_name'], $options['rec_id'], $options['value'], $cat_field);
			} else add_error_message('ERROR_OPTIONS_OBJECT');
		}

	//..............................................................................
	// устанавливает название для объекта по языку
	//..............................................................................	
	static function _set_title($options=NULL)
		{
		if (isset($options['rec_id']) AND isset($options['value']))
			{
			$options['table_name'] = ready_val($options['table_name'], DEFAULT_OBJECT_TABLE);
			$row = itMySQL::_get_rec_from_db($options['table_name'], $options['rec_id']);
			$row['title_xml'][CMS_LANG] = $options['value'];
			itMySQL::_update_value_db($options['table_name'], $options['rec_id'], $row['title_xml'], 'title_xml');
			} else add_error_message('ERROR_OPTIONS_OBJECT');
		}


	//..............................................................................
	// компилирует визард для объекта по его категории
	//..............................................................................	
	public function wizard()
		{
		if (is_array($this->wizard))
			{
			$row = [];
			if (function_exists('get_object_wizard_row_event'))
			foreach($this->wizard as $key=>$row)
				{
//				$row['value']		= isset($this->data[$this->wiz_values][$row['name']]) ? $this->data[$this->wiz_values][$row['name']] : NULL;
//				$row['table_name']	= $this->table_name;
//				$row['rec_id']		= $this->rec_id;				
				$rows[] = 
					TAB."<div class='row'>".
					get_object_wizard_row_event($row).
					TAB."</div>";
				}
			$this->code .= 
				TAB."<div class='list'>".
				get_object_category_event($this->data).
//				TAB."<div class=''>".get_const('OBJECT_WIZARD_TITLE')."</div>".
				implode('', $rows).
				TAB."</div>";
			}
		}

	//..............................................................................
	// создает форму для редактирования значения полей объекта
	//..............................................................................	
	public function form(&$o_modal)
		{
		global $_USER;
		$result = NULL;
		if (is_array($this->wizard))
			{
			$o_form = new itForm2([
				'action'	=> $this->action,
				'method'	=> $this->method,
				'reCaptcha'	=> $this->reCaptcha,
			]);
			foreach($this->wizard as $key=>$row)
				{
				$row['value']		= isset($this->data[$this->wiz_values][$row['name']]) ? $this->data[$this->wiz_values][$row['name']] : NULL;
				$row['table_name']	= $this->table_name;
				$row['rec_id']		= $this->rec_id;

				$placeholder = !is_null($this->placeholder) ? " placeholder=\"{$this->placeholder}\"" : "";
				
				switch ($row['type'])
					{
					case 'text' : {
						$o_form->add_input([
							'name'		=> $row['name'], 
							'value'		=> $row['value'], 
							'label'		=> get_field_by_lang($row['label'], CMS_LANG, ''), 
							'compact'	=> true,
							]);
						break;
						}
					case 'email' : {
						$o_form->add_input([
							'name'		=> $row['name'], 
							'value'		=> $row['value'], 
							'label'		=> get_field_by_lang($row['label'], CMS_LANG, ''), 
							'type'		=> 'email',
							'compact'	=> true,
							]);
						break;
						}
					case 'phone' : {
						$o_form->add_input([
							'name'		=> $row['name'], 
							'value'		=> $row['value'], 
							'label'		=> get_field_by_lang($row['label'], CMS_LANG, ''), 
							'type'		=> 'phone',
							'compact'	=> true,
							]);
						break;
						}

					case 'select' : {
						$sel_arr= [];
						foreach($row['titles'][CMS_LANG] as $sel_key=>$sel_row)
							{
							$sel_arr[$row['values'][$sel_key]] = 
								[
								'title'	=> get_const($sel_row),
								'value'	=> $row['values'][$sel_key],
								];	
							}
						$options = array (
							'array' 	=> $sel_arr,
							'titles'        => 'title',
							'values'	=> 'value',
							'name'		=> $row['name'],
							//
							'compact'	=> 1,
							'value'		=> $row['value'],
							'label'		=> get_field_by_lang($row['label']),
							);
						$o_form->add_itSelector('select', $options);
						break;
						}

					default : {
						$o_form->add_input($row['name'], $row['value'], get_field_by_lang($row['label'], CMS_LANG, ''), false, 'compact');
						break;
						}

					}
				
				}
			
			$o_form->add_data([
				'table_name'	=> $this->table_name,
				'rec_id'	=> $this->rec_id,
				$this->cat_field=> ready_val($this->data[$this->cat_field]),
				'user_id'	=> $_USER->id(),
				]);
			$o_form->add_hidden('op', $this->op);
			$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
			$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
			$o_form->compile();
			$result = $o_form->code();
			unset($o_form);								
			}
		return $result;			
		}

	//..............................................................................
	// возвращает код таблицы значений
	//..............................................................................			
	public function table()
		{
		global $wiz_types;
		if (is_array($this->wizard))
			{
			foreach ($this->wizard as $wiz_key=>$wiz_row)
				{
				$rows[] = 
					TAB."<div class='row'>".
					TAB."<div class='field p5'>".get_field_by_lang($wiz_row['label'], CMS_LANG, '')."</div>".
					TAB."<div class='field p5'>{$wiz_row['text']}</div>".
					TAB."</div>";
				}
			return	TAB."<div class='list'>".
				implode('', $rows).
				TAB."</div>";
			}
		}

	//..............................................................................
	// обрабатывает возврат формы редактирования полей объекта
	//..............................................................................			
	static function _form_update($options=NULL)
		{
//		print_r($options); die;
		$options['category_table'] 	= ready_val($options['category_table'], DEFAULT_CATEGORY_TABLE);
		$options['table_name']		= ready_val($options['table_name'], DEFAULT_OBJECT_TABLE);
		$options['wiz_field']		= ready_val($options['wiz_field'], DEFAULT_WIZARD_FIELD);

		$cat_field = ready_val($options['cat_field'], DEFAULT_CAT_FIELD);
		
		$o_object = new itObject(['table_name' => $options['table_name'], 'rec_id' => $options[$cat_field]]);
		
		foreach($o_object->wizard as $wiz_key => $wiz_row)
			{
			if (isset($options[$wiz_key]))
				{
				$o_object->data[$o_object->wiz_values][$wiz_key] = $options[$wiz_key];
				}
			}
		$o_object->store();
		unset($o_object);
		}
		
	//..............................................................................
	// возвращает количество товаров, которые соответствуют объекту 
	//..............................................................................	
	static function _count($category_id=NULL, $table_name=DEFAULT_OBJECT_TABLE, $db_prefix=DB_PREFIX)
		{
		$request = itMySQL::_request("SELECT COUNT(*) as count FROM {$db_prefix}{$table_name} WHERE `category_id`='{$category_id}'");
		return is_array($request) ? $request[0]['count'] : 0;
		}
		
	//..............................................................................
	// обработчик событий объекта
	//..............................................................................	
	static function events($url='/', $path=UPLOADS_ROOT)
		{
		return object_events($url, $path);
		}
		
	
	}
?>
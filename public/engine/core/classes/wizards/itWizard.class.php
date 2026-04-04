<?php
// ================ CRC ================
// version: 1.15.03
// hash: ee962b4b2e34f5345aa6bc5cd0f62704fb06c32804b7c4efd704e867fa744b8a
// date: 09 September 2019  5:10
// ================ CRC ================
global $wizard_counter;
$wizard_counter = (function_exists('rand_id')) ? rand_id() : time();


definition([
	'DEFAULT_ITEM_TABLE'	=> 'items',		
	'DEFAULT_OBJECT_TABLE'	=> 'objects',
	'DEFAULT_CATEGORY_TABLE'=> 'categories',
	'DEFAULT_WIZARD_TABLE'	=> 'categories',	
	'DEFAULT_WIZARD_FIELD' 	=> 'wizard_xml',
	'DEFAULT_WIZARD_TYPE'	=> 'text',
	'DEFAULT_WIZARD_NAME'	=> 'noname',
	'DEFAULT_WIZARD_LABEL'	=> NULL,
	]);

//..............................................................................
// itWizard : класс управления дополнителным полем 'wizard_xml' для контента
//..............................................................................
class itWizard
	{
	public $table_name, $rec_id, $data, $field, $code;
	//..............................................................................
	// конструктор класса
	//..............................................................................
	public function __construct($options=NULL)
		{
		global $wizard_counter;
		$wizard_counter ++;
		
		$this->name 		= "wizard-{$wizard_counter}";
		$this->table_name	= ready_val($options['table_name'], get_const('DEFAULT_WIZARD_TABLE'));
		$this->rec_id		= ready_val($options['rec_id']);
		$this->ed_rec 		= itMySQL::_get_rec_from_db($this->table_name, $this->rec_id);
		$this->field		= ready_val($options['field'], DEFAULT_WIZARD_FIELD);
		$this->data		= ready_val($this->ed_rec[$this->field]);
		if (!is_array($this->data))
			{
			$this->data = NULL;
			}
		}

	//..............................................................................
	// разыменовывает поля указанного типа wizard для выбранного контента
	//..............................................................................	
	public function compile()
		{
		$rows_code = NULL;
		if (is_array($this->data))
			{
			$rows = NULL;
			foreach ($this->data as $key => $row)
				{
				$row['table_name'] = $this->table_name;
				$row['rec_id'] = $this->rec_id;
				$row['key'] = $key;
				$rows[] = 
					TAB."<div class='row'>".
					TAB."<div class='segment p4'>".					
					TAB."<div class='field p4'>".get_wizard_name_event($row).TAB."</div>".
					TAB."<div class='field p5'>".get_wizard_type_event($row).TAB."</div>".
					TAB."</div>".
					TAB."<div class='segment p4'>".
					TAB."<div class='field p1'>".get_wizard_label_event($row).TAB."</div>".
					TAB."</div>".
					TAB."<div class='segment p4'>".
					TAB."<div class='field p1'>".get_wizard_titles_event($row).TAB."</div>".
					TAB."</div>".
					TAB."<div class='segment p4'>".
					TAB."<div class='field p7'>".get_wizard_values_event($row).TAB."</div>".
					TAB."<div class='field p2'>".(isset($row['user_id']) ? itUser::get_name($row['user_id']) : "").TAB."</div>".
					TAB."<div class='field p1'>".get_wizard_copy_event($row).TAB."</div>".
					TAB."<div class='field p1'>".get_wizard_x_event($row).TAB."</div>".
					TAB."</div>".		
					TAB."</div>";					
				}
			$rows_code = implode('',$rows);
			}
			
		$this->code = 
			TAB."<div class='wizard'>".		
			TAB."<div class='list'>".
			$rows_code.
			TAB."<div class='row'>".
			get_add_wizard_event(['table_name' => $this->table_name, 'rec_id' => $this->rec_id]).
			TAB."</div>".
			TAB."</div>".
			TAB."</div>";
			
		}

	//..............................................................................
	// возвращает скомпилированный код объекта
	//..............................................................................	
	public function code() 
		{
		return ($this->code);
		}

	//..............................................................................
	// добавляет поле
	//..............................................................................	
	static function _add($options=NULL)
		{
		global $_USER;
		if (is_array($options) AND isset($options['name']))
			{
			$options['table_name']	= ready_val($options['table_name'], DEFAULT_WIZARD_TABLE);
			$options['rec_id'] 	= ready_val($options['rec_id']);			
			$options['field'] 	= ready_val($options['field'], DEFAULT_WIZARD_FIELD);
			$options['titles'] 	= ready_val($options['titles']);			
			$options['type'] 	= ready_val($options['type'], DEFAULT_WIZARD_TYPE);
			$options['values'] 	= ready_val($options['values']);
			$options['label'] 	= ready_val($options['label']);
			$options['user_id'] 	= ready_val($options['user_id'], $_USER->id());
			
			$o_wizard = new itWizard([
				'table_name'	=> $options['table_name'],
				'rec_id'	=> $options['rec_id'],
				'field'		=> $options['field'],
				]);
				
			$o_wizard->data[] = [
				'name'		=> $options['name'],				
				'type'		=> $options['type'],
				'titles'	=> [CMS_LANG => $options['titles']],
				'values'	=> $options['values'],
				'label'		=> [CMS_LANG => $options['label']],
				'user_id'	=> $options['user_id'],
				];
			$o_wizard->store();
			unset($o_wizard);
			} else add_error_message('ERROR_OPTIONS_WIZARD');
		}


	//..............................................................................
	// добавляет поле
	//..............................................................................	
	static function _copy($options=NULL)
		{
		global $_USER;
		if (is_array($options) AND isset($options['category_id']))
			{
			$options['table_name']	= ready_val($options['table_name'], DEFAULT_WIZARD_TABLE);
			$options['field'] 	= ready_val($options['field'], DEFAULT_WIZARD_FIELD);
			$options['rec_id'] 	= ready_val($options['rec_id']);
			$options['key'] 	= ready_val($options['key']);			
			$options['user_id'] 	= ready_val($options['user_id'], $_USER->id());
						
			$o_from = new itWizard([
				'table_name'	=> $options['table_name'],
				'rec_id'	=> $options['rec_id'],
				'field'		=> $options['field'],
				]);
				
			if (isset($o_from->data[$options['key']]))
				{
				$o_to = new itWizard([
					'table_name'	=> $options['table_name'],
					'rec_id'	=> $options['category_id'],
					'field'		=> $options['field'],
					]);
				$row = $o_from->data[$options['key']];
				$row['user_id'] = $options['user_id'];
				$o_to->data[] = $row;
				$o_to->store();
				unset($o_to);
				}
			unset($o_from);								
			} else add_error_message('ERROR_OPTIONS_WIZARD');
		}

	//..............................................................................
	// сохраняет все данные визарда в поле визарда 
	//..............................................................................	
	public function store() 
		{
		itMySQL::_update_value_db($this->table_name, $this->rec_id, array_values($this->data), $this->field);
		}

	//..............................................................................
	// обновляет поле 
	//..............................................................................	
	static function _update($options)
		{
		if (is_array($options) AND isset($options['key']))
			{
			$options['table_name']	= ready_val($options['table_name'], DEFAULT_WIZARD_TABLE);
			$options['rec_id'] 	= ready_val($options['rec_id']);			
			$options['field'] 	= ready_val($options['field'], DEFAULT_WIZARD_FIELD);

			$o_wizard = new itWizard([
				'table_name'	=> $options['table_name'],
				'rec_id'	=> $options['rec_id'],
				'field'		=> $options['field'],
				]);

			$options['titles'] 	= ready_val($options['titles'], $o_wizard->data[$options['key']]['titles']);			
			$options['type'] 	= ready_val($options['type'], $o_wizard->data[$options['key']]['type']);
			$options['values'] 	= ready_val($options['values'], $o_wizard->data[$options['key']]['values']);
			$options['label'] 	= ready_val($options['label'], $o_wizard->data[$options['key']]['label']);
			
				
			$o_wizard->data[$options['key']] = [
				'title'		=> $options['title'],
				'type'		=> $options['type'],
				'values'	=> $options['values'],
				'default'	=> $options['default'],
				'name'		=> $options['name'],					
				'label'		=> $options['label'],
				];
			$o_wizard->store();
			unset($o_wizard);
			} else add_error_message('ERROR_OPTIONS_WIZARD');			
		}



	//..............................................................................
	// удаляет поле
	//..............................................................................	
	static function _remove($options)
		{
		if (is_array($options) AND isset($options['key']))
			{
			$options['table_name']	= ready_val($options['table_name'], DEFAULT_WIZARD_TABLE);
			$options['rec_id'] 	= ready_val($options['rec_id']);			
			$options['field'] 	= ready_val($options['field'], DEFAULT_WIZARD_FIELD);

			$o_wizard = new itWizard([
				'table_name'	=> $options['table_name'],
				'rec_id'	=> $options['rec_id'],
				'field'		=> $options['field'],
				]);
				
			if (isset($o_wizard->data[$options['key']]))
				{
				unset($o_wizard->data[$options['key']]);
				} else add_error_message('ERROR_NO_KEY_DATA');
			$o_wizard->store();
			unset($o_wizard);
			} else add_error_message('ERROR_OPTIONS_WIZARD');
			
		}
		
	//..............................................................................
	// меняет поле названий для визарда
	//..............................................................................	
	static function _set_titles($options=NULL)
		{
		if (is_array($options) AND isset($options['titles']))
			{
			$options['table_name']	= ready_val($options['table_name'], DEFAULT_WIZARD_TABLE);
			$options['rec_id'] 	= ready_val($options['rec_id']);			
			$options['field'] 	= ready_val($options['field'], DEFAULT_WIZARD_FIELD);
			$options['key'] 	= ready_val($options['key'], 0);
			$options['lang']	= ready_val($options['lang'], CMS_LANG);
			
			$o_wizard = new itWizard([
				'table_name'	=> $options['table_name'],
				'rec_id'	=> $options['rec_id'],
				'field'		=> $options['field'],
				]);
				
			$o_wizard->data[$options['key']]['titles'][CMS_LANG] = $options['titles'];
			$o_wizard->store();			
			unset($o_wizard);
			} else add_error_message('ERROR_SET_TITLE_WIZARD');
		}

	//..............................................................................
	// меняет название переменной поля для визарда
	//..............................................................................	
	static function _set_name($options=NULL)
		{
		if (is_array($options) AND isset($options['name']))
			{
			$options['table_name']	= ready_val($options['table_name'], DEFAULT_WIZARD_TABLE);
			$options['rec_id'] 	= ready_val($options['rec_id']);			
			$options['field'] 	= ready_val($options['field'], DEFAULT_WIZARD_FIELD);
			$options['key'] 	= ready_val($options['key'], 0);
			$options['lang']	= ready_val($options['lang'], CMS_LANG);
			
			$o_wizard = new itWizard([
				'table_name'	=> $options['table_name'],
				'rec_id'	=> $options['rec_id'],
				'field'		=> $options['field'],
				]);
				
			$o_wizard->data[$options['key']]['name'] = $options['name'];
			$o_wizard->store();			
			unset($o_wizard);
			} else add_error_message('ERROR_SET_NAME_WIZARD');
		}
	
	//..............................................................................
	// меняет тип поля для визарда
	//..............................................................................	
	static function _set_type($options=NULL)
		{
		if (is_array($options) AND isset($options['type']))
			{
			$options['table_name']	= ready_val($options['table_name'], DEFAULT_WIZARD_TABLE);
			$options['rec_id'] 	= ready_val($options['rec_id']);			
			$options['field'] 	= ready_val($options['field'], DEFAULT_WIZARD_FIELD);
			$options['key'] 	= ready_val($options['key'], 0);
			$options['lang']	= ready_val($options['lang'], CMS_LANG);
			
			$o_wizard = new itWizard([
				'table_name'	=> $options['table_name'],
				'rec_id'	=> $options['rec_id'],
				'field'		=> $options['field'],
				]);
				
			$o_wizard->data[$options['key']]['type'] = $options['type'];
			$o_wizard->store();			
			unset($o_wizard);
			} else add_error_message('ERROR_SET_TYPE_WIZARD');
		}
		
	//..............................................................................
	// меняет поле значений для визарда
	//..............................................................................	
	static function _set_values($options=NULL)
		{
		if (is_array($options) AND isset($options['values']))
			{
			$options['table_name']	= ready_val($options['table_name'], DEFAULT_WIZARD_TABLE);
			$options['rec_id'] 	= ready_val($options['rec_id']);			
			$options['field'] 	= ready_val($options['field'], DEFAULT_WIZARD_FIELD);
			$options['key'] 	= ready_val($options['key'], 0);						
			$options['lang']	= ready_val($options['lang'], CMS_LANG);
			$o_wizard = new itWizard([
				'table_name'	=> $options['table_name'],
				'rec_id'	=> $options['rec_id'],
				'field'		=> $options['field'],
				]);
				
			$o_wizard->data[$options['key']]['values'] = $options['values'];
			$o_wizard->store();			
			unset($o_wizard);
			} else add_error_message('ERROR_SET_TITLE_WIZARD');
		}
	

	//..............................................................................
	// меняет описание поля для визарда
	//..............................................................................	
	static function _set_label($options=NULL)
		{
		if (is_array($options) AND isset($options['label']))
			{
			$options['table_name']	= ready_val($options['table_name'], DEFAULT_WIZARD_TABLE);
			$options['rec_id'] 	= ready_val($options['rec_id']);			
			$options['field'] 	= ready_val($options['field'], DEFAULT_WIZARD_FIELD);
			$options['key'] 	= ready_val($options['key'], 0);
			$options['lang']	= ready_val($options['lang'], CMS_LANG);
			
			$o_wizard = new itWizard([
				'table_name'	=> $options['table_name'],
				'rec_id'	=> $options['rec_id'],
				'field'		=> $options['field'],
				]);
				
			$o_wizard->data[$options['key']]['label'][CMS_LANG] = $options['label'];
			$o_wizard->store();			
			unset($o_wizard);
			} else add_error_message('ERROR_SET_LABEL_WIZARD');
		}
		
	//..............................................................................
	// обработчик событий визарда
	//..............................................................................	
	static function events($url='/', $path=UPLOADS_ROOT)
		{
		return wizards_events($url, $path);
		}
		
	} // class

?>


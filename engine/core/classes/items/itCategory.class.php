<?php
// ================ CRC ================
// version: 1.15.03
// hash: 21ddf00717c7feddfec50431c177507111831038f060341c1a1c24bb06bc6fd2
// date: 09 September 2019  5:10
// ================ CRC ================
global $category_counter;
$category_counter = (function_exists('rand_id')) ? rand_id() : time();

definition([
	'DEFAULT_CATEGORY_TABLE'=> 'categories',
	]);

//..............................................................................
// itCategory : класс управления категориями товаров / или контента
//..............................................................................
class itCategory
	{
	public $table_name, $rec_id, $name, $data, $field;
	//..............................................................................
	// конструктор класса
	//..............................................................................
	public function __construct($options=NULL)
		{
		global 	$category_counter;
		$category_counter++;
		
		$this->name 		= "category-{$category_counter}";
		$this->table_name	= ready_val($options['table_name'], get_const('DEFAULT_CATEGORY_TABLE'));
		$this->rec_id		= ready_val($options['rec_id']);
		$this->prefix		= ready_val($options['prefix'], DB_PREFIX);
		
		$this->data 		= itMySQL::_get_rec_from_db($this->table_name, $this->rec_id);
		}

	//..............................................................................
	// сохраняет поле 
	//..............................................................................	
	public function store() 
		{
		$values = $this->data;
		unset($values['id']);
		itMySQL::_update_db_rec($this->table_name, $this->rec_id, $values);
		}
		
		

	//..............................................................................
	// сравнивает две записи категории по названию
	//..............................................................................	
	static function cmp($a, $b) {return strcmp(get_field_by_lang($a['title_xml']),get_field_by_lang($b['title_xml']));}
	
	//..............................................................................
	// возвращает дерево категорий с кнопками для управления
	//..............................................................................	
	static function prepare($status=NULL, $table_name=DEFAULT_CATEGORY_TABLE, $db_prefix=DB_PREFIX)
		{
		global $prepared_arr;
		$query = "SELECT * FROM `{$db_prefix}{$table_name}` WHERE `status`".
			(is_null($status) ?  "<>'DELETED'" : "='{$status}'");
		$request = itMySQL::_request($query);
		$cats_arr = NULL;
		
		if (is_array($request))
			{
			foreach ($request as $key=>$row)
				{
				$cats_arr[$row['parent_id']][] = $row;
				}
			}
			
		if (is_array($cats_arr))
			{
			foreach($cats_arr as $key=>$row)
				{
				usort($cats_arr[$key], 'itCategory::cmp');				
				}

			if (function_exists('get_category_row'))
				{
				itCategory::_prepare_row($cats_arr[0], $cats_arr, $deep=0);
				} else	{
					add_error_message('function <b>get_category_row()</b> not found');
					}
			}
		}	
	//..............................................................................
	// рекурсиваня функция массива категорий
	//..............................................................................	
	static function _prepare_row($node, $cats_arr, $deep)
		{
		global $prepared_arr;
		foreach ($node as $key=>$row)
			{
			$title = get_category_row($row, $deep);
			$prepared_arr['categories'][$row['id']] = [
					'title' => $title,
					'value'	=> $row['id'],
					];
			if (isset($cats_arr[$row['id']]))
				{
				itCategory::_prepare_row($cats_arr[$row['id']], $cats_arr, $deep+1);
				}			
			}
		}
		
	//..............................................................................
	// возвращает дерево категорий с кнопками для управления
	//..............................................................................	
	static function tree($status=NULL, $table_name=DEFAULT_CATEGORY_TABLE, $db_prefix=DB_PREFIX)
		{
		$query = "SELECT * FROM `{$db_prefix}{$table_name}` WHERE `status`".
			(is_null($status) ?  "<>'DELETED'" : "='{$status}'");
		$request = itMySQL::_request($query);
		$cats_arr = NULL;
		
		if (is_array($request))
			{
			foreach ($request as $key=>$row)
				{
				$cats_arr[$row['parent_id']][] = $row;
				}
			}

		if (is_array($cats_arr))
			{
			foreach($cats_arr as $key=>$row)
				{
				usort($cats_arr[$key], 'itCategory::cmp');				
				}

			$result = NULL;
			if (function_exists('get_category_tree_row'))
				{
				$result = itCategory::_tree_row($cats_arr[0], $cats_arr, $deep=0);
				} else	{
					add_error_message('function <b>get_category_tree_row()</b> not found');
					}
			return $result;
			}
		}	
	//..............................................................................
	// рекурсиваня функция дерва категорий
	//..............................................................................	
	static function _tree_row($node, $cats_arr, $deep)
		{
		$result = NULL;
		foreach ($node as $key=>$row)
			{
			$result .= get_category_tree_row($row, $deep);
			if (isset($cats_arr[$row['id']]))
				{
				$result .= itCategory::_tree_row($cats_arr[$row['id']], $cats_arr, $deep+1);
				}			
			}
		return $result;
		}
		

	//..............................................................................
	// устанавливает родительскую категорию для категории
	//..............................................................................	
	static function set_parent($category_id=NULL, $parent_id=0, $table_name=DEFAULT_CATEGORY_TABLE, $db_prefix=DB_PREFIX)
		{
		if ($category_id==$parent_id)
			{
			add_error_message('ERROR_CYCLE_PARENT');
			return;
			}
		$row = itMySQL::_get_rec_from_db($table_name, $category_id);
		if (!is_array($row))
			{
			add_error_message('ERROR_SETTING_PARENT');
			return;
			}
		
		//установим нового родителя для категории
		itMySQL::_update_value_db($table_name, $category_id, $parent_id, 'parent_id');
		}
		
	//..............................................................................
	// удаление категории
	//..............................................................................	
	static function x($category_id=NULL, $table_name=DEFAULT_CATEGORY_TABLE, $db_prefix=DB_PREFIX)
		{
		$row = itMySQL::_get_rec_from_db($table_name, $category_id);
		if (!is_array($row))
			{
			add_error_message('ERROR_REMOVEING_CATEGORY');
			return;
			}
		// обновим родительский каталог
		$query = "UPDATE `{$db_prefix}{$table_name}` SET `parent_id`='{$row['parent_id']}' WHERE `parent_id`='{$row['id']}'";
		itMySQL::_request($query);
		
		//установим нового родителя для категории
		itMySQL::_update_value_db($table_name, $category_id, 'DELETED', 'status');
		}
		
	//..............................................................................
	// обработчик событий категории
	//..............................................................................	
	static function events($url='/', $path=UPLOADS_ROOT)
		{
		return category_events($url, $path);
		}
		
		
	}
<?php
global $category_counter;
$category_counter = (function_exists('rand_id')) ? rand_id() : time();

definition([
	'DEFAULT_CATEGORY_TABLE'=> 'categories',
	]);
// itCategory : класс управления категориями товаров / или контента
class itCategory
	{
	public $table_name, $rec_id, $name, $data, $field, $prefix;

	protected static function row_value($row, $key, $default=NULL)
		{
		return (is_array($row) AND array_key_exists($key, $row)) ? $row[$key] : $default;
		}

	// конструктор класса
	public function __construct($options=NULL)
		{
		global 	$category_counter;
		$options = is_array($options) ? $options : [];
		$category_counter++;
		
		$this->name 		= "category-{$category_counter}";
		$this->table_name	= ready_val(self::row_value($options, 'table_name'), get_const('DEFAULT_CATEGORY_TABLE'));
		$this->rec_id		= ready_val(self::row_value($options, 'rec_id'));
		$this->prefix		= ready_val(self::row_value($options, 'prefix'), DB_PREFIX);
		
		$this->data 		= itMySQL::_get_rec_from_db($this->table_name, $this->rec_id);
		$this->data		= is_array($this->data) ? $this->data : [];
		}
	// сохраняет поле 
	public function store() 
		{
		if (!is_array($this->data) OR empty($this->data) OR empty($this->rec_id))
			{
			return false;
			}
		$values = $this->data;
		unset($values['id']);
		itMySQL::_update_db_rec($this->table_name, $this->rec_id, $values);
		return true;
		}
	// сравнивает две записи категории по названию
	static function cmp($a, $b) {return strcmp(get_field_by_lang(self::row_value($a, 'title_xml')),get_field_by_lang(self::row_value($b, 'title_xml')));}
	// возвращает дерево категорий с кнопками для управления
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
				if (!is_array($row)) continue;
				$cats_arr[self::row_value($row, 'parent_id', 0)][] = $row;
				}
			}
			
		if (is_array($cats_arr))
			{
			foreach($cats_arr as $key=>$row)
				{
				if (is_array($cats_arr[$key])) usort($cats_arr[$key], 'itCategory::cmp');				
				}

			if (function_exists('get_category_row') AND isset($cats_arr[0]) AND is_array($cats_arr[0]))
				{
				itCategory::_prepare_row($cats_arr[0], $cats_arr, $deep=0);
				} else if (!function_exists('get_category_row'))	{
					add_error_message('function <b>get_category_row()</b> not found');
					}
			}
		}	
	// рекурсиваня функция массива категорий
	static function _prepare_row($node, $cats_arr, $deep)
		{
		global $prepared_arr;
		if (!is_array($node)) return;
		foreach ($node as $key=>$row)
			{
			if (!is_array($row)) continue;
			$title = get_category_row($row, $deep);
			$row_id = self::row_value($row, 'id');
			if (is_null($row_id)) continue;
			$prepared_arr['categories'][$row_id] = [
					'title' => $title,
					'value'	=> $row_id,
					];
			if (isset($cats_arr[$row_id]))
				{
				itCategory::_prepare_row($cats_arr[$row_id], $cats_arr, $deep+1);
				}			
			}
		}
	// возвращает дерево категорий с кнопками для управления
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
				if (!is_array($row)) continue;
				$cats_arr[self::row_value($row, 'parent_id', 0)][] = $row;
				}
			}

		if (is_array($cats_arr))
			{
			foreach($cats_arr as $key=>$row)
				{
				if (is_array($cats_arr[$key])) usort($cats_arr[$key], 'itCategory::cmp');				
				}

			$result = NULL;
			if (function_exists('get_category_tree_row') AND isset($cats_arr[0]) AND is_array($cats_arr[0]))
				{
				$result = itCategory::_tree_row($cats_arr[0], $cats_arr, $deep=0);
				} else if (!function_exists('get_category_tree_row'))	{
					add_error_message('function <b>get_category_tree_row()</b> not found');
					}
			return $result;
			}
		}	
	// рекурсиваня функция дерва категорий
	static function _tree_row($node, $cats_arr, $deep)
		{
		$result = NULL;
		if (!is_array($node)) return $result;
		foreach ($node as $key=>$row)
			{
			if (!is_array($row)) continue;
			$row_id = self::row_value($row, 'id');
			$result .= get_category_tree_row($row, $deep);
			if (!is_null($row_id) AND isset($cats_arr[$row_id]))
				{
				$result .= itCategory::_tree_row($cats_arr[$row_id], $cats_arr, $deep+1);
				}			
			}
		return $result;
		}
	// устанавливает родительскую категорию для категории
	static function set_parent($category_id=NULL, $parent_id=0, $table_name=DEFAULT_CATEGORY_TABLE, $db_prefix=DB_PREFIX)
		{
		$category_id = (int)$category_id;
		$parent_id = (int)$parent_id;
		if ($category_id<=0 OR empty($table_name))
			{
			add_error_message('ERROR_SETTING_PARENT');
			return false;
			}
		if ($category_id==$parent_id)
			{
			add_error_message('ERROR_CYCLE_PARENT');
			return false;
			}
		$row = itMySQL::_get_rec_from_db($table_name, $category_id);
		if (!is_array($row))
			{
			add_error_message('ERROR_SETTING_PARENT');
			return false;
			}
		
		//установим нового родителя для категории
		itMySQL::_update_value_db($table_name, $category_id, $parent_id, 'parent_id');
		return true;
		}
	// удаление категории
	static function x($category_id=NULL, $table_name=DEFAULT_CATEGORY_TABLE, $db_prefix=DB_PREFIX)
		{
		$category_id = (int)$category_id;
		if ($category_id<=0 OR empty($table_name))
			{
			add_error_message('ERROR_REMOVEING_CATEGORY');
			return false;
			}
		$row = itMySQL::_get_rec_from_db($table_name, $category_id);
		if (!is_array($row))
			{
			add_error_message('ERROR_REMOVEING_CATEGORY');
			return false;
			}
		// обновим родительский каталог
		$query = "UPDATE `{$db_prefix}{$table_name}` SET `parent_id`='".self::row_value($row, 'parent_id', 0)."' WHERE `parent_id`='".self::row_value($row, 'id')."'";
		itMySQL::_request($query);
		
		//установим нового родителя для категории
		itMySQL::_update_value_db($table_name, $category_id, 'DELETED', 'status');
		return true;
		}
	// обработчик событий категории
	static function events($url='/', $path=UPLOADS_ROOT)
		{
		return category_events($url, $path);
		}
		
		
	}
?>

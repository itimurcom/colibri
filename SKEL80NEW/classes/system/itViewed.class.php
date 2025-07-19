<?
//..............................................................................
// itViewed : класс представления учета отметок о просмотре объектов
//..............................................................................
class itViewed
	{
	public $table_name, $id_of_user, $data;

	//..............................................................................
	// конструктор создает представление просмотра объекта текущим пользователем
	//..............................................................................
	public function __construct($table_name=DEFAULT_VIEW_TABLE)
		{
		$this->table_name = $table_name;
		}

	//..............................................................................
	// проверяет наличие отметки о просмотре объекта
	//..............................................................................
	static function viewed($id_of_user=NULL, $object=NULL, $object_id=NULL, $table_name=DEFAULT_VIEW_TABLE, $db_prefix=DB_PREFIX)
		{
		if (!MEMCACHED_VIEWED)
			{
			return (is_array($row = itMySQL::_request("SELECT * FROM {$db_prefix}{$table_name} WHERE `user_id`='$id_of_user' AND `object`='$object' AND `object_id`='$object_id' ORDER BY `datetime` DESC LIMIT 1")))
				? $row
				: false;
			}
		// отрабатываем через memcached
		$key = "{$object}-{$object_id}";
		if (is_array($row = itMemCache::_get($key, $id_of_user)))
			{
			return $row;
			} else	{
				if (is_array($row = itMySQL::_request("SELECT * FROM {$db_prefix}{$table_name} WHERE `user_id`='$id_of_user' AND `object`='$object' AND `object_id`='$object_id' ORDER BY `datetime` DESC LIMIT 1")))
					{
					itMemCache::_set($key, $row, $id_of_user);
					return $row;
					} else return false;
				}
		}

	//..............................................................................
	// заполняет запись о просмотре объекта
	//..............................................................................
	static function looked($id_of_user=NULL, $object='GROUP', $object_id=NULL, $table_name=DEFAULT_VIEW_TABLE)
		{
		$now = get_mysql_time_str(strtotime('now'));
		if (($action = itViewed::viewed($id_of_user, $object, $object_id, $table_name))===false)
			{
			// запись отуствует - вставим новую
			$values_arr = array(
				'user_id' 	=> $id_of_user,
				'object'	=> $object,
				'object_id'	=> $object_id,
				'datetime'	=> $now,
				);
			$rec_id = itMySQL::_insert_rec($table_name, $values_arr);
			} else	{
				// уже есть в базе - произведем замену action
				if (!NOT_UPDATE_VIEWED)
					itMySQL::_update_value_db($table_name, $action['id'], $now, 'datetime');
				}
		return $action;
		}


	} // class
?>
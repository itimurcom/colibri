<?php
// ================ CRC ================
// version: 1.15.12
// hash: 212d356225d75991719911f100b9ca39a56ffd71667c2f5d5ec9bb90df893ead
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// itMySQL : класс доступа к базе данных, таблица и полям в виде массива
//..............................................................................
class itMySQL
	{
	// закрытые переменные описывающие параметры доступа к базе данных
	public $db_server, $db_user, $db_pass, $db_name, $db_prefix, $db, $result;

	//..............................................................................
	// конструктор класса - соединяется с базой данных при создании класса
	//..............................................................................
	public function __construct($db_name=CMS_DB_NAME, $db_prefix=DB_PREFIX, $db_server=CMS_DB_SERVER, $db_user=CMS_DB_USER, $db_pass=CMS_DB_PASS, $db_port=DEFAULT_DB_PORT)
		{
		if (defined('CMS_DB_PORT')) $db_port = CMS_DB_PORT;
		if (defined('SKIP_SQL')===false)
			{
			try
				{
				$this->db = mysqli_connect($db_server, $db_user, $db_pass, $db_name, $db_port);
				} catch (Exception $e)
					{
					
					}

			if ($this->db)
				{
				$codepage = defined('CMS_DB_CODEPAGE') ? CMS_DB_CODEPAGE : 'utf8';
				mysqli_query($this->db, "SET NAMES '{$codepage}'");
				$this->db_server	= $db_server;
                	        $this->db_user 		= $db_user;
                        	$this->db_name		= $db_name;
                        	$this->db_pass		= $db_pass;
                        	$this->db_prefix	= $db_prefix;
				}
			}
		}

	//..............................................................................
	// деструктор класса - закрывает соединение, открытое в базе
	//..............................................................................
	public	function __destruct()
		{
		if ($this->db)
			{
			mysqli_close($this->db);
			}
		}

	//..............................................................................
	// возвращает id последней записи в базе
	//..............................................................................
	public function get_last_id($table_name=NULL, $db_prefix=DB_PREFIX, $database=CMS_DB_NAME)
		{
/*		try 	{
			mysqli_select_db($this->db,$table_name);
			} catch	(Exception $e)
				{
				echo mysqli_error($this->db);
				die;
				}
		try 	{
			$rec_id = mysqli_insert_id($this->db);
			} catch	(Exception $e)
				{
				echo mysqli_error($this->db);
				die;
				}
		return $rec_id;
*/
		$request = itMySQL::_request("SELECT `AUTO_INCREMENT` as lastid
		FROM  INFORMATION_SCHEMA.TABLES
		WHERE TABLE_SCHEMA = '{$database}'
		AND   TABLE_NAME   = '{$db_prefix}{$table_name}'");
		
		return (isset($request[0]) AND isset($request[0]['lastid'])) ? $request[0]['lastid'] : false;
//		return ready_val(($request[0])['lastid'], false);
		}

	//..............................................................................
	// возвращает id последней записи в базе без объекта
	//..............................................................................
	static function _last_id($table_name=NULL, $db_prefix=DB_PREFIX, $database=CMS_DB_NAME)
		{
		$db = new itMySQL();
		$result = $db->get_last_id($table_name, $db_prefix, $database);
		unset($db);
		return $result;
		}

	//..............................................................................
	// возвращает массив данных из базы по id
	//..............................................................................
	public function get_rec_from_db($table_name=NULL, $rec_id=NULL, $field='id')
		{
		// сделаем запрос
		if (($rec_id==NULL) or ($table_name==NULL)) return;

		$query = "SELECT * FROM {$this->db_prefix}$table_name";
		$query .= " WHERE `$field` = '$rec_id'";

		$request = mysqli_query($this->db, $query)
			or die(mysqli_error($this->db).'<br/><b>get_rec_from_db</b>:: '.$query);		

		if ($request_row = mysqli_fetch_assoc($request))
			{
			foreach ($request_row as $key=>$row)
				{
				$value = !empty($row) ? html_entity_decode($row, ENT_QUOTES, 'UTF-8') : $row;
				if (!doubleval($value) and ($value_rec = json_decode($value, true)))
					{
					$result[$key] = $value_rec;
					} else 	{
						$result[$key] = $value;
						}
				}

			$result['table_name'] = $table_name;
			$result['rec_id'] = $rec_id;
			return $result;
			} else	{
				// проведем запись только если зарегистрирован как администратор
				//  TODO надо переписать без использования глобального пользователя
				}

		}

	//..............................................................................
	// безобъектная версия получения записи из базы
	//..............................................................................
	static function _get_rec_from_db($table_name=NULL, $rec_id=NULL, $field='id')
		{
		$db = new itMySQL();
		$result = $db->get_rec_from_db($table_name, $rec_id, $field);
		unset($db);
		return $result;
		}
		
	//..............................................................................
	// возвращает массив данных из базы по критериям
	//..............................................................................
	public function get_arr_from_db($table_name=NULL, $condition=1, $order_by=NULL, $limit=NULL)
		{
		$res_arr = NULL;
		// сделаем запрос
		if ($table_name==NULL) return;

		$query = "SELECT * FROM {$this->db_prefix}$table_name";
		$query .= " WHERE $condition";
		if ($order_by!=NULL)
			{
			$query .= " order by $order_by";
			}

		if ($limit!=NULL)
			{
			$query .= " limit $limit";
			}

		$request = mysqli_query($this->db, $query)
			or die(mysqli_error($this->db).'<br/><b>get_arr_from_db</b>:: '.$query);		

		while ($row = mysqli_fetch_assoc($request))
			{
			foreach ($row as $key=>$value_row)
				{
				$value = html_entity_decode ($value_row, ENT_QUOTES, 'UTF-8');
				if (!doubleval($value) and ($value_rec = json_decode($value, true)))
					{
					$result[$key] = $value_rec;
					} else 	{
						$result[$key] = $value;
						}
				}

			$result['table_name'] = $table_name;
			$result['rec_id'] = ready_val($row['id'], NULL);
			$res_arr[] = $result;
			unset($result);
			}
		return $res_arr;
		}

	//..............................................................................
	// безобъектная версия получения массив данных из базы по критериям
	//..............................................................................
	static function _get_arr_from_db($table_name=NULL, $condition=1, $order_by=NULL, $limit=NULL)
		{
		$db = new itMysQL();
		$result = $db->get_arr_from_db($table_name, $condition, $order_by, $limit);
		unset($db);
		return $result;
		}
	//..............................................................................
	// записывает данные в поле таблицы
	//..............................................................................
	public function update_value_db($table_name=NULL, $rec_id=NULL, $value=NULL, $field='')
		{
		if ( ($rec_id==NULL) or ($table_name==NULL ) ) return;

		if (is_array($value))
			{
			$value = json_encode($value, JSON_ALLOWED);
			}

		if ($value!=NULL)
			{
			$value = "'".addslashes($value)."'";
			} else $value = 'NULL';

		$query = "UPDATE {$this->db_prefix}$table_name";
		$query .= " SET `$field` = $value"; // без кавычек!
		$query .= " WHERE `id` = $rec_id";

		mysqli_query($this->db, $query)
			or die(mysqli_error($this->db).'<br/><b>update_value_db</b>:: '.$query);
		}
		
	//..............................................................................
	// безобъектная версия функция обновления поля
	//..............................................................................
	static function _update_value_db($table_name=NULL, $rec_id=NULL, $value=NULL, $field='')
		{
		$db = new itMySQL();
		$db->update_value_db($table_name, $rec_id, $value, $field);
		unset($db);
		}		

	//..............................................................................
	// записывает данные в таблицу из массива
	//..............................................................................
	public function update_db_rec($table_name=NULL, $rec_id=NULL, $data=NULL)
		{
		if (($data==NULL) or (!is_array($data))) return;

		unset($data['table_name']);
		unset($data['rec_id']);
		// запаковываем в JSON
		foreach ($data as $key=>$row)
			{
			if (is_array($row))
				{
				if (count($row)!=NULL)
					{
					$row = json_encode($row, JSON_ALLOWED);
					} else $row=NULL;
				}

			$rows[] = "`{$key}` = ".
				(($row!=NULL) ? "'".addslashes($row)."'" : 'NULL');
			}

		$query = "UPDATE {$this->db_prefix}$table_name".
			" SET ".implode(', ', $rows).
			" WHERE `id` = $rec_id";

		mysqli_query($this->db, $query)
			or die(mysqli_error($this->db).'<br/><b>update_db_rec</b>:: '.$query);
		}

	//..............................................................................
	// записывает данные в таблицу из массива
	//..............................................................................
	static function _update_db_rec($table_name=NULL, $rec_id=NULL, $data=NULL)
		{
		$db = new itMySQL();
		$db->update_db_rec($table_name, $rec_id, $data);
		unset($db);
		}
	//..............................................................................
	// удаляет из базы запись с указанным ID
	//..............................................................................
	public function remove_rec_from_db($table_name=NULL, $rec_id=NULL)
		{
		$query = "DELETE FROM {$this->db_prefix}$table_name WHERE `id` = '$rec_id'";
		mysqli_query($this->db, $query)
			or die(mysqli_error($this->db).'<br/><b>remove_rec_from_db</b>:: '.$query);
		$this->reset_autoinc($table_name);
		}

	//..............................................................................
	// безобїектаня модель удаления из базы записи
	//..............................................................................
	static function _remove_rec_from_db($table_name=NULL, $rec_id=NULL)
		{
		$db = new itMySQL();
		$db->remove_rec_from_db($table_name, $rec_id);
		unset($db);
		}

	//..............................................................................
	// сбрасывает счетчика автоинкримента на последнее значение + 1
	//..............................................................................
	public function reset_autoinc($table_name)
		{
		$query = "SELECT @max := MAX(`id`)+ 1 FROM {$this->db_prefix}$table_name; 
		SET @alter_statement = concat('ALTER TABLE {$this->db_prefix}$table_name AUTO_INCREMENT = ', @max);
		PREPARE stmt FROM @alter_statement;
		EXECUTE stmt;
		DEALLOCATE PREPARE stmt;";
		$request = mysqli_multi_query($this->db, $query)
			or die(mysqli_error($this->db).'<br/><b>remove_rec_from_db</b>'.$query);
		}
		

	//..............................................................................
	// езобъектная версия функции сбраса автоинкримента на последнее значение + 1
	//..............................................................................
	static function _reset_autoinc($table_name)
		{
		$db = new itMysql();
		$db->reset_autoinc($table_name);
		unset($db);
		}

	//..............................................................................
	// возвращает массив результатов по запрсу MySQL
	//..............................................................................
	public function request($query=NULL, $array = true, &$counter=NULL)
		{
//		if (empty($query)) {debug_print_backtrace(); return;}
				
		$request = mysqli_query($this->db, $query)
			or die(mysqli_error($this->db).'<br/><b>request</b> '.$query);
		$result = NULL;
		if (($request!==true) and ($array))
			{
			while ($row = mysqli_fetch_assoc($request))
				{
				foreach ($row as $key=>$row)
					{
					$value = html_entity_decode ($row, ENT_QUOTES, 'UTF-8');
					if (!doubleval($value) and ($value_rec = json_decode($value, true)))
						{
						$line[$key] = $value_rec;
						} else 	{
							$line[$key] = $value;
							}
					}

				$result[] = $line;
				unset($line);
				}
			} else $result = $request;
		$counter = mysqli_affected_rows($this->db);
		return $result;				
		}
		
	//..............................................................................
	// безобъектная версия функции запроса к базе
	//..............................................................................
	static function _request($query='', $array = true, &$counter=NULL)
		{
//		if (empty($query)) {debug_print_backtrace(); return;}
		
		$db = new itMysQL();
		$result = $db->request($query, $array, $counter);
		unset($db);
		return $result;
		}

	//..............................................................................
	// готовит строку ключей для MySQL запроса
	//..............................................................................
	static function prepare_keys(&$values_arr, $rec_id=NULL)
		{
		if (!is_null($rec_id))
			{
			if (is_array($values_arr))
				{
				array_unshift($values_arr, 'id');
				} else $values_arr['id'] = $rec_id;
			}
		$keys = isset($values_arr['keys']) ? $values_arr['keys'] : array_keys($values_arr);
		return "( `" . implode( "`, `", $keys ) . "` )";				
		}	
	
	//..............................................................................
	// готовит строку значений для MySQL запроса
	//..............................................................................
	static function prepare_values($values_arr)
		{
		if (is_array($values_arr))
			{
			foreach ($values_arr as $key=>$row)
				{
				if (is_array($row))
					{
					if (count($row)!=NULL)
						{
						$row = json_encode($row, JSON_ALLOWED);
						} else $row=NULL;
					}					
				$values[] = !is_null($row) ? "'".addslashes($row)."'" : 'NULL';
				}
			return "( ".implode(', ', $values)." )";
			}
		}	

	//..............................................................................
	// вставляет новую запись по массиву данных
	//..............................................................................
	public function insert_rec($table_name=NULL, $values_arr=NULL, $rec_id=NULL, &$counter=NULL)
		{
		if ($table_name==NULL) return;
		
		if (!isset($values_arr['keys']))
			{
			// вставляем одну запись
			$values_str =  itMySQL::prepare_values($values_arr);
			
			} else	{
				// вставляем множественную запись
				foreach ($values_arr as $key=>$row)
					{
					if ($key!=='keys')
						{
						$values[] = itMySQL::prepare_values($row);
						}
					}
				$values_str = implode(',', $values);						
				}

		$keys_str = itMySQL::prepare_keys($values_arr, $rec_id);		
		$query = "INSERT INTO {$this->db_prefix}{$table_name} {$keys_str} VALUES {$values_str};";

		mysqli_query($this->db, $query)
			or die(mysqli_error($this->db).'<br/><b>insert_rec</b> '.$query);

		$rec_id = is_null($rec_id) ? ($this->get_last_id($table_name)-1) : $rec_id; 
		$counter = mysqli_affected_rows($this->db);

		return $rec_id;
		}

	//..............................................................................
	// безобъектная модель вставки данных
	//..............................................................................
	static function _insert_rec($table_name=NULL, $values_arr=NULL, $rec_id=NULL, &$counter=NULL)
		{
		$db = new itMysQL();
		$rec_id = $db->insert_rec($table_name, $values_arr, $rec_id, $counter);
		unset($db);
		return $rec_id;
		}

	//..............................................................................
	// безобъектная модель замены местами две записи в базе
	//..............................................................................
	static function _exchange($table_name=NULL, $rec_id='NULL', $value='NULL')
		{
		$db = new itMysQL();
		$db->exchange($table_name, $rec_id, $value);
		unset($db);
		}

	//..............................................................................
	// меняет местами две записи в базе
	//..............................................................................
	public function exchange($table_name=NULL, $rec_id='NULL', $value='NULL')
		{
		$this->update_value_db($table_name, $rec_id, -999, 'id');
		$this->update_value_db($table_name, $value, -888, 'id');
		$this->update_value_db($table_name, -999, $value, 'id');
		$this->update_value_db($table_name, -888, $rec_id, 'id');
		}
		
	//..............................................................................
	// многострочная команда
	//..............................................................................
	public function multi_request($query)
		{
		$request = mysqli_multi_query($this->db, $query)
			or die(mysqli_error($this->db).'<br/><b>multi</b>'.$query);
		return $request;
		}
		
	//..............................................................................
	// безобъектная модель вставки данных
	//..............................................................................
	static function _multi_request($query)
		{
		$db = new itMysQL();
		$request = $db->multi_request($query);
		unset($db);
		return $request;
		}
	
	//..............................................................................
	// проверяет присутствует ли таблица
	//..............................................................................
	static function _exists($tablename=NULL, $db_prefix=DB_PREFIX)
		{
		$query  = "SHOW TABLES LIKE '{$db_prefix}{$tablename}'";
		$res = itMySQL::_request($query, true, $counter);
		return ($counter > 0);
		}
	} // class

?>
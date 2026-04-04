<?php
// ================ CRC ================
// version: 1.15.09
// hash: 760023afbd5ae7034ce42f0cca5afb21ed89fccd4d024f9e01df33586ceba94b
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// itUser : класс авторизации пользователя на сайте
//..............................................................................
Class itUser
	{
	public $table_name, $rec_id, $data, $is_logged, $hash;

	//..............................................................................
	// Конструктор класса, создаем объект проверяя условия
	//..............................................................................
	public function __construct($options=NULL)
		{
		$this->table_name = isset($options['table_name']) ? $options['table_name'] : DEFAULT_USER_TABLE;
		$this->db_prefix = isset($options['db_prefix']) ? $options['db_prefix'] : DB_PREFIX;
		$this->rec_id = isset($options['rec_id']) ? $options['rec_id'] : NULL;
		$this->onerror = isset($options['onerror']) ? $options['onerror'] : NULL;
		$this->user_agent = itBrowser::_user_agent();
		$this->user_ip = get_user_ip();
		$this->session_row = NULL;
		$this->need_check = isset($options['check']) ? $options['check'] : true;
		$this->check();
		}


	//------------------------------------------------------------------------------
	// 	ОПЕРАЦИИ И ПРОВЕРКИ
	//------------------------------------------------------------------------------


	//..............................................................................
	// принудительный вход в систему
	//..............................................................................
	public function login($id_of_user=NULL)
		{
		if (intval($id_of_user))
			{
			$this->get_user_from_db($id_of_user);
			}

		$this->create_session();
		$this->is_logged = true;
		}


	//..............................................................................
	// выход из системы чистка данных
	//..............................................................................
	public function logout($table_name=DEFAULT_SESSION_TABLE)
		{
		if (isset($this->data['id']) AND $this->data['id']) 
			{
			self::_close_all($this->data['id']);
			}
		itUser::_close_session(NULL);
		$this->_hash(NULL);
		$this->is_logged = false;
		// почистим кеш
		// if (isset($_SESSION['v3checked'])) {
		// 	unset($_SESSION['v3checked']);
		// 	}
		unset($_SESSION['CACHE']);		
		unset($this->data);
		}

	//..............................................................................
	// загружает объект из базы данных используя поле `id`
	//..............................................................................
	public function get_user_from_db($id_of_user=NULL, $table_name=DEFAULT_USER_TABLE)
		{
		if ($id_of_user!=NULL)
			{
			$this->data = itMySQL::_get_rec_from_db($table_name, $id_of_user);
			if (is_null($this->data))
				{                    
				$this->logout();
				} else	{
					$this->table_name = $table_name;
					$this->rec_id = $id_of_user;
					}
			}
		}

	//..............................................................................
	// Проверка введенных данных на предмет пользователя
	//..............................................................................
	public function is_correct_user($login='', $password='')
		{		
		$query = "SELECT * FROM {$this->db_prefix}{$this->table_name} WHERE ( (`login` = '$login') or (`email` ='$login') ) and (`password` = password('$password') )";
		if (is_array($request = itMySQL::_request($query)))
			{
			$this->login($request[0]['id']);
			return true;
			}
		return false;
		}		


	//..............................................................................
	// основная проверка активности логина
	//..............................................................................
	public function check($table_name=DEFAULT_SESSION_TABLE)
		{
		if (!$this->need_check) return;
		
		definition([
			'USER_SESSION_EXPIRED'		=> 'Сессия пользователья просрочена',
			'USER_SESSION_CLOSED'		=> 'Сессия была закрыта',
			'USER_HASH_ERROR_USERID'	=> 'Даныне сессии пользователя не верны',
			'USER_HASH_ERROR_USERAGENT'	=> 'Сессия была открыта для другого браузера',
			'USER_HASH_ERROR_UUID'		=> 'Идентификатор пользователя в сессии не верный',
			'USER_HASH_ERROR_IP'		=> 'Ошибка IP адреса сессии',
			'NO_USER_SESSION_FOUND'		=> 'Не найдены данные сессии пользователя',
			]);
		
		$error = false;
		$error_message = NULL;
		$hash_data = NULL;
		
		if ( isset($_SESSION[SESSION_PREFIX.'HASH']) )
			{
			if (!is_array($hash_data = itUser::_hash_arr()))
				{
				$error_message ='USER_HASH_ERROR';
				$onerror = 'ARRAY';				
				$error = true;
				} else

			$this->session_row = (is_null($this->session_row ) ? itMySQL::_get_rec_from_db($table_name, $hash_data['session_id']) : $this->session_row);
				
			// проверим открыта ли сессия
			if (!is_null($this->session_row))
				{
				//  открыта ли сессия?
				if ($this->session_row['status']!='PUBLISHED')
					{
					$error_message ='USER_SESSION_CLOSED';
					$onerror = 'CLOSED';
					$error = true;
					} else
				//  просрочена ли сессия?
				if (mysql_now() > $hash_data['expiretime'])
					{
					$error_message ='USER_SESSION_EXPIRED';
					$onerror = 'EXPIRED';
					$error = true;
					} else
				// пользователь?	
				if ($hash_data['user_id']!=$this->session_row['user_id'])
					{
					$error_message ='USER_HASH_ERROR_USERID';
					$onerror = 'USERID';
					$error = true;
					} else
				// браузер?					
				if ($hash_data['user_agent']!=$this->user_agent)
					{
					$error_message ='USER_HASH_ERROR_USERAGENT';
					$onerror = 'USERAGENT';
					$error = true;
					} else
				// уникальный uuid?	
				if ($hash_data['uuid']!=$this->session_row['uuid'])
					{
					$error_message ='USER_HASH_ERROR_UUID';
					$onerror = 'UUID';
					$error = true;
					} else
				// IP НО Остался Браузер и пользователь?
				if ( (	$hash_data['user_ip']!=$this->user_ip) AND
						( 
						($hash_data['user_agent']!=$this->user_agent) OR ($hash_data['uuid']!=$this->session_row['uuid']) 
						)
						)
					{
					$error_message ='USER_HASH_ERROR_IP';
					$onerror = 'IP';
					$error = true;					
					}
				// все таки изменился IP!
				} else	{
					$error_message ='NO_USER_SESSION_FOUND';
					$onerror = 'SESSION_ID';
					$error = true;
					}
					
			if ($hash_data['user_ip']!=$this->user_ip)
				{	
				$this->change_ip($this->session_row['id'], $table_name);
				}					

			} else	{
				$onerror = 'OTHER';
				$error = true;
				}

				
		if ($error)
			{
			if (!is_null($this->onerror) and function_exists($func = $this->onerror))
				{
				$data = ((array) $this);
				$data['session'] = $this->session_row;
				$data['hash'] = $hash_data;
				$data['error'] = $onerror;
				$func($data);
				}
			if (!is_null($error_message))
				{
				add_error_message($error_message);
				$this->logout();
				}
			} else 			// все в норме, откроем работу с пользователем
				{
				$this->is_logged = true;
				$this->get_user_from_db($this->session_row['user_id']);
				$this->used(); // там же и хеш данные обновятся
				}

		}

	//..............................................................................
	// возвращает хэш код восстановления доступа на сайт
	//..............................................................................
	public function encrypt_hash()
		{
		// 
		$options = array (
			'user_id'	=> $this->data['id'],
			'datetime' 	=> time(),
			'user_email'	=> $this->data['email'],
			);
		return urlencode(simple_encrypt(serialize($options)));
		}

	//..............................................................................
	// возвращает массив данных хэш кода восстановления доступа на сайт
	//..............................................................................
	static function decrypt_hash($hash)
		{
		$result = @unserialize(simple_decrypt($hash));
		if (is_array($result) AND isset($result['user_id']) AND intval($result['user_id']))
			{
			$result['expired'] = time() > strtotime(EXPIRED_HASH, $result['datetime']);
			if ($result['expired'])
				{
				add_error_message(get_const(ERROR_EXPIRED_HASH));
				}
			} else add_error_message(get_const(ERROR_HASH_CODE));
		return $result;
		}


	//------------------------------------------------------------------------------
	// 	РАБОТА С ПЕРЕМЕННЫМИ
	//------------------------------------------------------------------------------


	//..............................................................................
	// флаг того, что пользователь в системе
	//..............................................................................
	public function is_logged($group_array = ['ADMIN'])
		{
		$this->check();
		if ($group_array==NULL) $group_array = ['ADMIN'];
		return (($this->is_logged) and (($group_array == 'ANY') or in_array($this->data['group_id'], $group_array)));
		}

	//..............................................................................
	// обновляет отметку последнего использования
	//..............................................................................
	public function used()
		{
		// обновим отметку в базе
		itMySQL::_update_value_db($this->table_name, $this->rec_id, mysql_now(), 'used');
		// передвинем данные просроченного хеша
		if (is_array($data = itUser::_hash_arr()))
			{
			// повторный вызов установки хеша (там же передвинется время жизни сессии)
			itUser::_hash($data);
			}
		}

	//..............................................................................
	// обновляет IP сессии
	//..............................................................................
	public function change_ip($session_id=NULL, $table_name=DEFAULT_SESSION_TABLE)
		{
		// обновим отметку в базе
		if (is_array($data = itUser::_hash_arr()))
			{
			itUser::_hash(NULL);
//			itUser::_close_session($data['session_id']);
//			$this->create_session();
			$data['session_id'] = $session_id;
			$data['user_ip'] = get_user_ip();
			itMySQL::_update_value_db($table_name, $data['session_id'], $data['user_ip'], 'user_ip');
			add_service_message('USER_SESSION_CHANGE_IP');				
			// повторный вызов установки хеша (там же передвинется время жизни сессии)
			itUser::_hash($data);
			}
		}
	//..............................................................................
	// возвращает отметку последнего использования
	//..............................................................................
	static function _used($id_of_user=NULL, $table_name=DEFAULT_USER_TABLE, $db_prefix=DB_PREFIX)
		{
		return !is_null($row = itMySQL::_get_rec_from_db($table_name, $id_of_user)) ? $row['used']	: NULL;
		}

	//..............................................................................
	// возвращает значение id пользователя
	//..............................................................................
	public function id()
		{
		return $this->rec_id;
		}

	//------------------------------------------------------------------------------
	// возвращает id пользователя по его логину
	//..............................................................................
	static function get_user_id_from_login($login_of_user=NULL, $table_name=DEFAULT_USER_TABLE, $db_prefix=DB_PREFIX)
		{
		if ($login_of_user==NULL)
			{
			return NULL;
			}

		$query = "SELECT * FROM {$db_prefix}{$table_name} WHERE ( (`login`='$login_of_user') or (`email`='$login_of_user') or (`name`='$login_of_user'))";
		$result = (is_array($request = itMySQL::_request($query))) ? $request[0]['id'] : NULL;
		return $result;
		}

	//..............................................................................
	// регистрирует пользователя и возвращает номер его ID
	//..............................................................................
	static function register_user($name_of_user=NULL, $pass_of_user=NULL, $func=NULL, $table_name = DEFAULT_USER_TABLE, $db_prefix=DB_PREFIX)
		{
		if ($name_of_user==NULL) return false;

		$email = (isEmail($name_of_user)) ? $name_of_user : NULL;

		$values_arr = array(
			'login' 	=> $name_of_user,
			'email'		=> $email,
			'name'		=> $name_of_user,
			'password'	=> sqlPassword($pass_of_user),
			'datetime'	=> get_mysql_time_str(time()),
			);
		$rec_id = itMySQL::_insert_rec($table_name, $values_arr);
		if (function_exists($func)) $func($rec_id, $table_name);
		return $rec_id;
		}

	//..............................................................................
	// возвращает имя пользователя, исходя из его данных
	//..............................................................................
	static function get_user_name($row)
		{
		$result = get_const('NO_TITLE');

		if (isset($row['name']) and ($row['name']!=''))
			{
			$result = $row['name'];
			} else

		if (isset($row['social']['name']))
			{
			$result = $row['social']['name'];
			} else

		if ($row['email']!='')
			{
			$result = $row['email'];
			} else

		if ($row['login']!='')
			{
			$result = $row['login'];
			}

		return $result;
		}

	//..............................................................................
	// возвращает имя пользователя по его ID
	//..............................................................................
	static function get_name($id_of_user=NULL)
		{
		$row = itMySQL::_get_rec_from_db('users', $id_of_user);
		return itUser::get_user_name($row);
		}
		
	//..............................................................................
	// возвращает email пользователя, исходя из его данных
	//..............................................................................
	static function get_user_email($row, $answer=true)
		{
		$result = ($answer) ? get_const('NO_SET') : $answer;

		if (isset($row['email']) and ($row['email']!=''))
			{
			$result = $row['email'];
			} else

		if (isset($row['social']['email']))
			{
			$result = $row['social']['email'];
			} else

		if (isEmail($row['login']))
			{
			$result = $row['login'];
			}
		return $result;
		}
		
	//..............................................................................
	// возвращает email пользователя по его ID
	//..............................................................................
	static function get_email($id_of_user=NULL)
		{
		$row = itMySQL::_get_rec_from_db('users', $id_of_user);
		return itUser::get_user_email($row);
		}
		
		
	//..............................................................................
	// возвращает email пользователя, исходя из его данных
	//..............................................................................
	static function get_user_phone($row)
		{
		$result = get_const('NO_SET');

		if (isset($row['phone']) and ($row['phone']!=''))
			{
			$result = $row['phone'];
			} else

		if (isset($row['social']['phone']))
			{
			$result = $row['social']['phone'];
			} else

		if (isPhone($row['login']))
			{
			$result = $row['login'];
			}
		return $result;
		}
		
	//..............................................................................
	// возвращает телефон пользователя по его ID
	//..............................................................................
	static function get_phone($id_of_user=NULL)
		{
		$row = itMySQL::_get_rec_from_db('users', $id_of_user);
		return itUser::get_user_phone($row);
		}

	//..............................................................................
	// возаращает ссылку на доступную установленную аватарку
	//..............................................................................
	static function get_user_avatar_img($rec_of_user=NULL)
		{
		if ($rec_of_user==NULL) return;

		if ($rec_of_user['avatar']=='')
			{
			if (isset($rec_of_user['social']['avatar']))
				{
				return $rec_of_user['social']['avatar'];
				}
			} else return $rec_of_user['avatar'];
		}


	//..............................................................................
	// возвращает разрешенный логин для пользователя
	//..............................................................................
	static function get_allowed_user_login($rec_of_user=NULL)
		{
		if ($rec_of_user==NULL) return;
		elseif ($rec_of_user['email']!='') 
			{
			return $rec_of_user['email'];
			}
		elseif ($rec_of_user['login']!='') 
			{
			return $rec_of_user['login'];
			}
		}


	//..............................................................................
	// возвращает дату регистрации пользователя
	//..............................................................................
	static function registered($id_of_user=NULL, $table_name = DEFAULT_USER_TABLE)
		{
		return !is_null($row = itMySQL::_get_rec_from_db($table_name, $id_of_user)) ? $row['datetime'] : NULL;
		}





	//------------------------------------------------------------------------------
	// 	РАБОТА С СЕССИЯМИ
	//------------------------------------------------------------------------------

	//..............................................................................
	// устанавливает hash код данных сессии для браузера
	//..............................................................................
	static function _hash($data=NULL)
		{
		if (is_array($data))
			{
			$data['expiretime'] = get_mysql_datetime(strtotime(EXPIRED_HASH, strtotime(itUser::_used($data['user_id']))));
			$_SESSION[SESSION_PREFIX.'HASH'] = itEditor::event_data($data);
			} else
				unset($_SESSION[SESSION_PREFIX.'HASH']);
		}

	//..............................................................................
	// возвращает массив данных, записанный на стороне сессии браузера
	//..............................................................................
	static function _hash_arr()
		{
		$result = @unserialize(simple_decrypt($_SESSION[SESSION_PREFIX.'HASH']));
		return $result;
		}

	//..............................................................................
	// создает запись про новую сессию
	//..............................................................................
	public function create_session($table_name = DEFAULT_SESSION_TABLE, $db_prefix=DB_PREFIX)
		{
		$now 	= mysql_now();
		$uuid 	= get_uuid();
		$values_arr = [
			'user_id' 	=> $this->rec_id,
			'user_ip'	=> $this->user_ip,
			'user_agent'	=> $this->user_agent,
			'datetime'	=> $now,
			'uuid'		=> $uuid,
			];

		// сбросим сессии других пользоватлей, если не даминистратор
		if (ready_val($this->data['group_id'], 'USER') != 'ADMIN')
			{
			if ($counter = itUser::_close_all($this->rec_id))
				{
//				add_service_message("закрыто {$counter} сессий");
				};
			}

		// создадим запись и дополним номером сессии в таблице
		$values_arr['session_id'] = itMySQL::_insert_rec($table_name, $values_arr);
		// запустим данные последнего использования
		$this->used();
		// пропишем массив
		itUser::_hash($values_arr);
		}

	//..............................................................................
	// безобъектная модель удаления hash-кода сессии для указанного пользователя
	//..............................................................................
	static function _close_all($id_of_user=NULL, $table_name = DEFAULT_SESSION_TABLE, $db_prefix=DB_PREFIX)
		{
		$now = mysql_now();
		$query =
			"UPDATE {$db_prefix}{$table_name} ".
			"SET `status`='CLOSE', ".
			" `closetime`='{$now}' ".
			"WHERE `user_id`='{$id_of_user}' AND `status`='PUBLISHED'";
		itMySQL::_request($query, true, $counter);
		return $counter;
		}
		
	//..............................................................................
	// безобъектная модель удаления hash-кода сессии для указанного пользователя
	//..............................................................................
	static function _close_session($session_id=NULL, $table_name = DEFAULT_SESSION_TABLE, $db_prefix=DB_PREFIX)
		{
		if (is_null($session_id))
			{
			$session_id = is_array($data = itUser::_hash_arr()) ? $data['session_id'] : NULL;
			}
			
		$now = mysql_now();
		
		$query =
			"UPDATE {$db_prefix}{$table_name} ".
			"SET `status`='CLOSE', ".
			" `closetime`='{$now}' ".
			"WHERE `id`='{$session_id}'";
		itMySQL::_request($query);
		}
		
	//..............................................................................
	// проверяет есть ли открытая сессия для пользователя
	//..............................................................................
	static function is_user_online($id_of_user=NULL, $table_name = DEFAULT_SESSION_TABLE, $db_prefix=DB_PREFIX)
		{
		$query = "SELECT * FROM {$db_prefix}{$table_name} WHERE `user_id`='{$id_of_user}' AND `status`='PUBLISHED' ORDER BY `datetime` DESC LIMIT 1";
		$request = itMySQL::_request($query);
		if (is_array($request))
			{
// 			foreach ($request as $session_row)
				{
				if ( time() > strtotime(EXPIRED_HASH,strtotime(itUser::_used($id_of_user))) ) // время с последнего использования было просрочено
					{
//					echo "CLOSE: {$id_of_user} / {$request[0]['id']}<br/>";
					itUser::_close_all($id_of_user);
					return false;
					}
				}
			return true;
			}
		}
		
	//..............................................................................
	// проверяет есть ли открытая сессия для пользователя
	//..............................................................................
	static function _is_user_online($id_of_user=NULL, $table_name = DEFAULT_SESSION_TABLE, $db_prefix=DB_PREFIX)
		{
		self::is_user_online($id_of_user, $table_name, $db_prefix);
		}

	} // class;

?>
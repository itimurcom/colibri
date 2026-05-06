<?php
if (!isset($_SESSION) OR !is_array($_SESSION))
	{
	$_SESSION = [];
	}
$_SESSION[MEMCAHCED_SESSION_KEY] = 0;
if (!isset($_SESSION[MEMCAHCED_KEY]) OR !is_array($_SESSION[MEMCAHCED_KEY]))
	$_SESSION[MEMCAHCED_KEY] = [];

// itMemCache : класс кеширования переменных в memcahced
class itMemCache
	{
	public $handle;
	// конструктор создает представление пкласс кеширования переменных в memcahce
	public function __construct($table_name=DEFAULT_VIEW_TABLE)
		{
		self::ensure_session_storage();
		if (class_exists('Memcached', false))
			{
			$this->handle = new Memcached();
			$this->handle->addServer('localhost', 11211);
			$this->handle->setOption(Memcached::OPT_SERIALIZER, Memcached::SERIALIZER_PHP);
			} else 	{
				// работаем с сессией
				$this->handle = NULL;
				}
		}
	
	// деструктор класа
	public function __destruct()
		{
		if (!is_null($this->handle))
			unset($this->handle);
		}

	static function ensure_session_storage()
		{
		if (!isset($_SESSION) OR !is_array($_SESSION))
			{
			$_SESSION = [];
			}
		if (!isset($_SESSION[MEMCAHCED_SESSION_KEY]) OR !is_numeric($_SESSION[MEMCAHCED_SESSION_KEY]))
			{
			$_SESSION[MEMCAHCED_SESSION_KEY] = 0;
			}
		if (!isset($_SESSION[MEMCAHCED_KEY]) OR !is_array($_SESSION[MEMCAHCED_KEY]))
			{
			$_SESSION[MEMCAHCED_KEY] = [];
			}
		}

	static function user_id($id_of_user=NULL)
		{
		global $_USER;
		if (!is_null($id_of_user)) return $id_of_user;
		return (is_object($_USER) AND method_exists($_USER, 'id')) ? $_USER->id() : 0;
		}

	// возвращает стандартную упаковку ключа
	static function _key($key, $id_of_user=NULL)
		{
		$id_of_user = self::user_id($id_of_user);
		return "{$id_of_user}-($key)";
		}


	// удаляет данные по ключу
	public function remove($key=NULL, $id_of_user=NULL)
		{
		$id_of_user = self::user_id($id_of_user);
		$var_key = itMemCache::_key($key, $id_of_user);
		
		if (is_null($this->handle))
			{
			self::ensure_session_storage();
			// работаем с сессией
			if (array_key_exists($var_key, $_SESSION[MEMCAHCED_KEY]))
				{
				unset($_SESSION[MEMCAHCED_KEY][$var_key]);
				return true;
				}
			} else	{
				return $this->handle->delete($var_key);
				}
		return false;
		}

	// безобъектная модель удаления данных по ключу
	static function _remove($key=NULL)
		{
		$o_memcache = new itMemCache();
		$result = $o_memcache->remove($key);
		unset($o_memcache);
		return $result;
		}		

	// безобъектная модель удаления данных по ключу
	static function _m_remove($object=NULL, $object_arr=NULL, $id_of_user=NULL)
		{
		$id_of_user = self::user_id($id_of_user);
			
		if (is_array($object_arr))
			{
			$o_memcache = new itMemCache();
			foreach ($object_arr as $object_id)
				{
				$key = "{$object}-{$object_id}";
				$o_memcache->remove($key, $id_of_user);
				}
			unset($o_memcache);
			}
		}
				
	// возвращает переменную из memcahce для пользователя
	public function get($key=NULL, $id_of_user=NULL)
		{
		$var_key = itMemCache::_key($key, $id_of_user);
		if (is_null($this->handle))
			{
			self::ensure_session_storage();
			// работаем с сессией
			if (array_key_exists($var_key, $_SESSION[MEMCAHCED_KEY]))
				{
				$_SESSION[MEMCAHCED_SESSION_KEY]++;
				return $_SESSION[MEMCAHCED_KEY][$var_key];
				} else return NULL;
			} else	{
				if (!is_null($result = $this->handle->get($var_key)))
					{
					self::ensure_session_storage();
					$_SESSION[MEMCAHCED_SESSION_KEY]++;
					}
				return $result;
				}
		}

	// безобъектная модель получения переменной
	static function _get($key=NULL)
		{
		$o_memcache = new itMemCache();
		$result = $o_memcache->get($key);
		unset($o_memcache);
		return $result;
		}		
		

	// устанавливает переменную в memcahce для пользователя
	public function set($key=NULL, $value=NULL, $id_of_user=NULL)
		{
		$var_key = itMemCache::_key($key, $id_of_user);
		if (is_null($this->handle))
			{
			self::ensure_session_storage();
			// работаем с сессией
			$_SESSION[MEMCAHCED_KEY][$var_key] = $value;
			} else	{
				if (!$this->handle->add($var_key, $value))
					{
					// перезаписываем данные
					$this->handle->replace($var_key, $value);
					}
				}

		
		}
	// безобъектная модель установки переменной
	static function _set($key=NULL, $value=NULL, $id_of_user=NULL)
		{
		$o_memcache = new itMemCache();
		$o_memcache->set($key, $value, $id_of_user);
		unset($o_memcache);
		}
		

	//  возвращает статистику сервера переменных
	public function stats()
		{
		if (is_null($this->handle)) return;
		
		return $this->handle->getStats();
		}

	// безобъектаня модель вызова ститискики
	static function _stats()
		{
		$o_memcache = new itMemCache();
		$result = $o_memcache->stats();
		unset($o_memcache);
		return $result;
		}
		
	//  возвращает массив ключей кешированных переменных
	public function keys()
		{
		self::ensure_session_storage();
		return (is_null($this->handle))
			? array_keys($_SESSION[MEMCAHCED_KEY])
			: $this->handle->getAllKeys();
		}
		

	// безобъектаня модель вызова массива ключей
	static function _keys()
		{
		$o_memcache = new itMemCache();
		$result = $o_memcache->keys();
		unset($o_memcache);
		return $result;
		}

	}

?>

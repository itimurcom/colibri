<?php
// ================ CRC ================
// version: 1.15.08
// hash: d8866369f216713b9c58e2ce342d1f9fff9af4656a4f6227428fc1c05ad7baeb
// date: 28 May 2021  4:42
// ================ CRC ================
global $settings_counter;
$settings_counter = (function_exists('rand_id')) ? rand_id() : 0;

global $plug_css;
$plug_css[] = 'class.itSettings.css';

//..............................................................................
// itSettings : класс настройки, которая хранится в базе
//..............................................................................
class itSettings
	{
	public $table_name, $rec_id, $name, $id_of_user, $value, $default;

	//..............................................................................
	// конструктор класса - создает представление настройки
	//..............................................................................
	//	name	=> название константы настройки
	// 	user_id => id пользователя (NULL для всего сайта!)
	//
	//..............................................................................
	public function __construct($name=NULL, $id_of_user=NULL, $default=NULL, $table_name=DEFAULT_SETTING_TABLE)
		{
		global $_SETTIGNS;

		$this->table_name = $table_name;

		if (!is_null($id_of_user))
			{
			$request = itMySQL::_request("SELECT * FROM `".DB_PREFIX."{$table_name}` WHERE `name`='$name' AND `user_id` = '$id_of_user' ORDER BY `id` ASC LIMIT 1");
			} else {
					$request = itMySQL::_request("SELECT * FROM `".DB_PREFIX."{$table_name}` WHERE `name`='$name' AND `user_id` IS NULL ORDER BY `id` ASC LIMIT 1");
			}

		$this->default  = (is_null($default))
				? ((defined("{$name}_DEFAULT")) ?  get_const("{$name}_DEFAULT") : NULL)
				: $default;
		if (is_array($request))
			{
			$this->rec_id 	= $request[0]['id'];
			$this->name 	= $request[0]['name'];
			$this->user_id 	= $request[0]['user_id'];
			$this->value 	= $request[0]['value'];
			} else 
				{
				$request[0] = itSettings::create($name, $id_of_user, $this->default);
				$this->value = $this->default;
				}
		}

	//..............................................................................
	// добавляет в базу запись о настройке для указанного пользователя
	//..............................................................................
	static function create($name=NULL, $id_of_user=NULL, $value=NULL, $table_name=DEFAULT_SETTING_TABLE)
		{
		$values_arr = array (
			'name' 		=> $name,
			'user_id'	=> $id_of_user,
			'value'		=> $value
			);

		$db = new itMySQL();
		$rec_id = $db->insert_rec($table_name, $values_arr);
		$result = $db->get_rec_from_db($table_name, $rec_id);
		if (!is_array($result))
			{
			add_error_message(get_const('ERROR_SETTINGS_CREATION')." : <b>$name</b>");
			return NULL;
			} else return $result;
		}

	//..............................................................................
	// возвращает значение установки настройки для конкретного пользователя
	//..............................................................................
	static function get($name=NULL, $id_of_user=NULL, $default=NULL, $table_name=DEFAULT_SETTING_TABLE)
		{
		$o_settings = new itSettings($name, $id_of_user, $default, $table_name);
		$result = $o_settings->value;
		unset($o_settings);
		return $result;
		}

	//..............................................................................
	// устанавливает значение установки настройки для конкретного пользователя
	//..............................................................................
	static function set($name=NULL, $value=NULL, $id_of_user=NULL, $table_name=DEFAULT_SETTING_TABLE)
		{
		$o_settings = new itSettings($name, $id_of_user, $table_name);
		$db = new itMySQL();
		$db->update_value_db($table_name, $o_settings->rec_id, $value, 'value');
		unset($db, $o_settings);
		}


	//..............................................................................
	// возвращает поле включения/выключения установки
	//..............................................................................
	static function get_onoff($name=NULL, $id_of_user=NULL, $ajax='', $class=DEFAULT_ONOFF_CLASS, $table_name=DEFAULT_SETTING_TABLE)
		{
		global $_USER, $_SETTINGS;
		if (!$_USER->is_logged('ANY')) return;
		
		if (is_array($name))
			{
			$data = $name;
			$data['name']		= isset($data['name']) 		? $data['name'] 	: NULL;
			$data['user_id']	= isset($data['user_id']) 	? $data['user_id'] 	: NULL;
			$data['ajax'] 		= isset($data['ajax']) 		? $data['ajax'] 	: NULL;		
			$data['class'] 		= isset($data['class'])		? $data['class'] 	: DEFAULT_ONOFF_CLASS;
			$data['table_name'] 	= isset($data['table_name'])	? $data['table_name'] 	: DEFAULT_SETTING_TABLE;
			} else	{
				$data['name']		= $name;
				$data['user_id']	= $id_of_user;
				$data['ajax'] 		= $ajax;		
				$data['class'] 		= $class;
				$data['table_name']	= $table_name;
				}

		$data['title'] = isset($data['title'])
				? $data['title']
				: (isset($_SETTINGS[$data['name']]['title'])
					? get_const($_SETTINGS[$data['name']]['title'])
					: NULL
					);

		$related_data = itEditor::event_data([
			'name'		=> $data['name'],
			'user_id'       => $data['user_id'],
			'table_name'	=> $data['table_name'],
			]);

		$o_settings = new itSettings($data['name'], $data['user_id'], $data['table_name']);
		$value = isset($data['value']) ? $data['value'] : $o_settings->value;
		$result = TAB."<div class='set'>".
			(!is_null($data['title']) ? TAB."<label>{$data['title']}</label>" : NULL).
			TAB."<span id='set-".str_replace('_','-',strtolower($data['name']))."' class='onoff {$data['class']} ".(($value==1) ? 'on' : 'off')."' rel='{$related_data}'".(($data['ajax'] !='') ? " rel-ajax=\"{$data['ajax'] }\"" : '' )."></span>".
			TAB."</div>";
		return $result;
		}

	//..............................................................................
	// производит переключение включено/выключено указанной установки
	//..............................................................................
	static function onoff($name=NULL, $id_of_user=NULL, $table_name=DEFAULT_SETTING_TABLE)
		{
		$value = NULL;
		$db = new itMySQL();
		$o_settings = new itSettings($name, $id_of_user, $table_name);
		$value = (($o_settings->value==1) ? '0' : '1');
		$db->update_value_db($table_name, $o_settings->rec_id, $value, 'value');
		unset($o_settings, $db);
		return $value;
		}

	} //class
?>
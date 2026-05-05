<?php
// удаляет все даныные из корзины модерации материалов
function killall_contents_request_value($key, $default=NULL)
	{
	return (isset($_REQUEST) AND is_array($_REQUEST) AND array_key_exists($key, $_REQUEST)) ? ready_val($_REQUEST[$key], $default) : $default;
	}

function killall_contents_request_valid_token($value)
	{
	return is_string($value) AND preg_match('/^[a-zA-Z0-9_]+$/', $value);
	}

function killall_contents_request()
	{
	global $db;
	$table_name = killall_contents_request_value('table_name');
	$status = killall_contents_request_value('status');
	if (!killall_contents_request_valid_token($table_name) OR !killall_contents_request_valid_token($status))
		{
		add_error_message('ERROR_OPTIONS_MODERATOR');
		return false;
		}
	$db_prefix = (is_object($db) AND isset($db->db_prefix)) ? $db->db_prefix : (defined('DB_PREFIX') ? DB_PREFIX : '');
	itMySQL::_request("DELETE from {$db_prefix}{$table_name} where `status` = '{$status}'");
	//сбросим счетчик на последний элемент + 1
	itMySQL::_reset_autoinc($table_name);
	if (function_exists('add_service_message'))
		add_service_message(get_const('BASKET_EMPTY_MESSAGE'));
	return true;
	}
?>
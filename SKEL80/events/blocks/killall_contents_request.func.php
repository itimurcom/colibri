<?php
// ================ CRC ================
// version: 1.15.02
// hash: 50c14c65f60dff0e8fb1dc3e4ba7b5213cb8ccd016fcd2df24104c38e0a224ce
// date: 16 September 2018 19:58
// ================ CRC ================
//..............................................................................
// удаляет все даныные из корзины модерации материалов
//..............................................................................	
function killall_contents_request()
	{
	itMySQL::_request("DELETE from {$db->db_prefix}{$_REQUEST['table_name']} where `status` = '{$_REQUEST['status']}'");
	//сбросим счетчик на последний элемент + 1
	itMySQL::_reset_autoinc($_REQUEST['table_name']);
	if (function_exists('add_service_message'))
		add_service_message(get_const('BASKET_EMPTY_MESSAGE'));
	}
?>
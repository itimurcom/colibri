<?
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
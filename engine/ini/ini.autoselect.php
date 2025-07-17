<?
//------------------------------------------------------------------------------
// ДАННЫЕ ДЛЯ ПОИСКА
//------------------------------------------------------------------------------
global $s_global_cat;

$s_global_cat['users'] = array (
	'name'		=> 'users',
	'group'		=> 'USER_LIST_TITLE',
	'table_name' 	=> 'users',
	'fields'	=> 'name,login,email,social,description',
	'condition'	=> '1',
	'func'		=> 'get_user_auto_result',
	'view'		=> 'users',
	'enabled'	=> 1
	);


$s_global_cat['items'] = array (
	'name'		=> 'products',
	'group'		=> 'PRODUCT_LIST_TITLE',
	'table_name' 	=> 'items',
	'fields'	=> 'color_xml,tags_xml,filter_xml,title_xml,ed_xml,extended_xml',
	'condition'	=> '1',
	'func'		=> 'get_product_item_result',
	'view'		=> NULL,
	'enabled'	=> 1
	);
?>
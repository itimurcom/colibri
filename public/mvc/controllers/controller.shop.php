<?
$_CONTENT['admin'] = get_admin_button_set();
global $cat_cat, $cat_more;
$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();

$plug_og['title'] 	= get_const( $cat_more[$_REQUEST['view']]['title']);
$plug_og['subtitle'] 	= get_const('CMS_NAME');
$_CONTENT['content'] 	= 
	get_colibri_block(BLOCK_SHOP, true).
	get_items_feed();
?>
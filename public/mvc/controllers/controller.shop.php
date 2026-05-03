<?php 
$_CONTENT['admin'] = get_admin_button_set();
global $cat_more;
$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();

$shop_view = isset($_REQUEST['view']) ? ready_val($_REQUEST['view']) : NULL;
$plug_og['title'] 	= isset($cat_more[$shop_view]) ? get_const($cat_more[$shop_view]['title']) : get_const('NODE_SHOP');
$plug_og['subtitle'] 	= get_const('CMS_NAME');
$_CONTENT['content'] 	= 
	get_colibri_block(BLOCK_SHOP, true).
	get_items_feed();
?>
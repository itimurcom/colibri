<?php 
function shop_controller_request_value($key, $default=NULL)
	{
	return (isset($_REQUEST) AND is_array($_REQUEST) AND array_key_exists($key, $_REQUEST)) ? ready_value($_REQUEST[$key], $default) : $default;
	}

function shop_controller_category_title($view)
	{
	global $cat_more;
	return (is_array($cat_more) AND !is_null($view) AND isset($cat_more[$view]) AND is_array($cat_more[$view]) AND isset($cat_more[$view]['title']))
		? get_const($cat_more[$view]['title'])
		: get_const('NODE_SHOP');
	}

$_CONTENT['admin'] = get_admin_button_set();
global $cat_more;
$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();

$shop_view = shop_controller_request_value('view');
$plug_og['title'] 	= shop_controller_category_title($shop_view);
$plug_og['subtitle'] 	= get_const('CMS_NAME');
$_CONTENT['content'] 	= 
	get_colibri_block(BLOCK_SHOP, true).
	get_items_feed();
?>

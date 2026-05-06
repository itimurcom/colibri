<?php 
function register_pin_controller_user_logged($scope='ANY')
	{
	global $_USER;
	return is_object($_USER) AND method_exists($_USER, 'is_logged') AND $_USER->is_logged($scope);
	}

$_CONTENT['admin'] = get_admin_button_set();
if (register_pin_controller_user_logged('ANY'))
	{
	cms_redirect_page("/".CMS_LANG.'/cabinet/');
	}


$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();

$_CONTENT['content'] = customer_ajaxpin_event($pinned);
if (register_pin_controller_user_logged('ANY'))
	{
	cms_redirect_page("/".CMS_LANG.'/cabinet/');
	}
// opengraph
$plug_og['subtitle'] 	= get_const('CMS_NAME');
$plug_og['title'] 	= get_const('NODE_PIN');
?>

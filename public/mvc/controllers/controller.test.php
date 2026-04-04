<?
$_CONTENT['admin'] = get_admin_button_set();
$data = itEditor::_redata();
global $_SETTINGS;

if ($_USER->is_logged(['ANY']))
	{
	cms_redirect_page("/".CMS_LANG.'/cabinet/');
	}


$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();
	
$_CONTENT['content'] = customer_ajaxlogin_event($login);

// opengraph
$plug_og['subtitle'] 	= get_const('CMS_NAME');
$plug_og['title'] 	= get_const('NODE_AJAX');
// echo print_rr(get_item_from_articul('S_WS_001_1'));
?>
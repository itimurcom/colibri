<?
$_CONTENT['admin'] = get_admin_button_set();
if ($_USER->is_logged('ANY'))
	{
	cms_redirect_page("/".CMS_LANG.'/cabinet/');
	}

$data = itEditor::_redata();

$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();

$_CONTENT['content'] = customer_ajaxpin_event($pinned);
if ($_USER->is_logged('ANY'))
	{
	cms_redirect_page("/".CMS_LANG.'/cabinet/');
	}
// opengraph
$plug_og['subtitle'] 	= get_const('CMS_NAME');
$plug_og['title'] 	= get_const('NODE_PIN');
?>
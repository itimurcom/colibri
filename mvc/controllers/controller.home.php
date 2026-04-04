<?
$_CONTENT['admin'] = get_admin_button_set();
$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();

$_CONTENT['content'] = 	
	get_colibri_banner_event(BLOCK_HOME).
	get_colibri_block(BLOCK_HOME, true);
	
// opengraph
$plug_og['title'] 	= get_const('CMS_NAME');
$plug_og['subtitle'] 	= get_const('CMS_NAME_EXTENDED');
?>
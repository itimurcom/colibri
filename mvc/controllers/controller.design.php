<?
$_CONTENT['admin'] = get_admin_button_set();
$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();

$_CONTENT['content'] = 	get_colibri_block(BLOCK_DESIGN, true);

// opengraph
$plug_og['subtitle'] 	= get_const('CMS_NAME');
$plug_og['title'] 	= get_const('NODE_DESIGN');
?>
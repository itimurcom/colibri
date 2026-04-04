<?
$_CONTENT['admin'] = get_admin_button_set();
$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();
/*
$o_feed = new itFeed([
	'table'		=> 'contents',
	'condition'	=> "`id`='".BLOCK_FEED_ABOUT."'",
	'onefield'	=> true,
	'name'		=> 'about',
	'params'	=> ['rec_id' => BLOCK_FEED_ABOUT],		
	]);
$o_feed->compile();

$_CONTENT['content'] = 
	TAB."<div class='block'>".
	(($row = itMySQL::_get_rec_from_db('contents', BLOCK_FEED_ABOUT))
		?	TAB."<div class='tit'>".($title = get_field_by_lang($row['title_xml'], CMS_LANG, ''))."</div>".
			(($_USER->is_logged())
				?	TAB."<div class='admin_panel_div'>".
					(function_exists('get_content_title_event') ? get_content_title_event($row) : "").
					TAB."</div>"
					: NULL).
			$o_feed->code()
		: NULL).
	TAB."</div>";
*/

$_CONTENT['content'] = 	get_colibri_block(BLOCK_ABOUT, true);

// opengraph
$plug_og['subtitle'] 	= get_const('CMS_NAME');
$plug_og['title'] 	= get_const('NODE_ABOUT');
?>
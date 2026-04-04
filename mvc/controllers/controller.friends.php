<?
$_CONTENT['admin'] = get_admin_button_set();
$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();

//get_colibri_block(BLOCK_FRIENDS, true);

$o_feed = new itFeed([
	'table'		=> 'contents',
	'condition'	=> "`id`='".BLOCK_FRIENDS."'",
	'fewer'		=> false,
	'onefield'	=> true,
	'position'	=> 0,
	'name'		=> 'friends',
	'params'	=> ['rec_id'=>BLOCK_FRIENDS],
	]);

$o_feed->compile();

$_CONTENT['content'] = 
	TAB."<div class='siterow boxed'>".
	(($row = itMySQL::_get_rec_from_db('contents', BLOCK_FRIENDS))
		?	TAB."<h1 class='tit'>".($title = get_field_by_lang($row['title_xml'], CMS_LANG, ''))."</h1>".
			(($_USER->is_logged())
				?	TAB."<div class='admin_panel_div'>".
					(function_exists('get_content_title_event') ? get_content_title_event($row) : "").
					TAB."</div>".
					get_add_onefield_editor($row)
					: NULL).
			$o_feed->code()
		: NULL).
	TAB."</div>";


// opengraph
$plug_og['subtitle'] 	= get_const('CMS_NAME');
$plug_og['title'] 	= $title;
?>
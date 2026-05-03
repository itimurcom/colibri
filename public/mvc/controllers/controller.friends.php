<?php
function friends_controller_feed()
	{
	$o_feed = new itFeed([
		'table'		=> 'contents',
		'condition'	=> "`id`='".BLOCK_FRIENDS."'",
		'fewer'		=> false,
		'onefield'	=> true,
		'position'	=> 0,
		'name'		=> 'friends',
		'need_total'	=> false,
		'params'	=> ['rec_id'=>BLOCK_FRIENDS],
		]);
	$o_feed->compile();
	return $o_feed;
	}

function friends_controller_content($o_feed, &$title)
	{
	global $_USER;
	$row = itMySQL::_get_rec_from_db('contents', BLOCK_FRIENDS);
	if (!$row)
		{
		$title = NULL;
		return TAB."<div class='siterow boxed'>".TAB."</div>";
		}

	$title = get_field_by_lang($row['title_xml'], CMS_LANG, '');
	return
		TAB."<div class='siterow boxed'>".
		TAB."<h1 class='tit'>".$title."</h1>".
		(($_USER->is_logged())
			? TAB."<div class='admin_panel_div'>".
			(function_exists('get_content_title_event') ? get_content_title_event($row) : "").
			TAB."</div>".
			get_add_onefield_editor($row)
			: NULL).
		$o_feed->code().
		TAB."</div>";
	}

$_CONTENT['admin'] = get_admin_button_set();
$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();
$o_feed = friends_controller_feed();
$_CONTENT['content'] = friends_controller_content($o_feed, $title);
unset($o_feed);
$plug_og['subtitle'] 	= get_const('CMS_NAME');
$plug_og['title'] 	= $title;
?>

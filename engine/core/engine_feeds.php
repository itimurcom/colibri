<?
//..............................................................................
// возвращает код одного редактора в ленте о друзьях
//..............................................................................
function get_friends_feed_row($row)
	{
	$result = NULL;

	$data = [
		'table_name' 	=> 'contents',
		'rec_id'	=> $row['rec_id'],
		'root'		=> $row['key'],
		'column'	=> 'ed_xml',
		'field'		=> 'editor',
		];

	$o_editor = new itEditor([
		'table_name' 	=> 'contents',
		'rec_id'	=> $row['rec_id'],
		'root'		=> $row['key'],
		'column'	=> 'ed_xml',
		'field'		=> 'editor',
		'async'		=> true,
		]);

        return
        	TAB."<div class='' id='friend-{$row['rec_id']}-{$row['key']}'>".
        	$o_editor->container().
//        	get_editor_onefield_button_set($row).
		TAB."</div>";
	}
	
//..............................................................................
// возвращает код одного редактора в ленте о нас
//..............................................................................
function get_about_feed_row($row)
	{
	$result = NULL;
	$o_editor = new itEditor([
		'table_name' 	=> 'contents',
		'rec_id'	=> $row['rec_id'],
		'root'		=> $row['key'],
		'column'	=> 'ed_xml',
		'field'		=> 'editor',
		'async'		=> true,
		]);

        return
        	TAB."<div class='' id='friend-{$row['rec_id']}-{$row['key']}'>".
        	$o_editor->container().
		TAB."</div>";
	}
?>
<?
$_CONTENT['admin'] = get_admin_button_set();
$data = itEditor::_redata();


if (!$_USER->is_logged('ANY'))
	{
	cms_redirect_page('/');
	}

transfer_wishlist();

$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();


if ($_USER->is_logged())
	{
/*
	$o_feed = new itFeed([
		'table'			=> DEFAULT_MAIL_TABLE,
		'condition'		=> "`from` ='{$_USER->data['email']}' OR `to`='{$_USER->data['email']}' AND `status` NOT IN('DELETED','SPAM')",
		'name'			=> 'mailing_history',
		'order'			=> "`id` DESC",
		'async'			=> true,
		'appear'		=> false,
		]);
	$o_feed->compile();
	$_CONTENT['content'] .=
		TAB."<div class='widget fl10 bordered rounded boxed'>".
		TAB."<span class='title'>".get_const('NODE_USERMAILS')."</span>".
			TAB."<div class='body'>".
				TAB."<div class='list admin'>".
				($o_feed->count_all() ? $o_feed->code() : TAB."<div class='field p1 center gray'>".get_const('NO_DATA')."</div>").
				TAB."</div>".
			TAB."</div>".				
		TAB."</div>";
	unset ($o_feed);
*/
	}
$_CONTENT['content'] .= 
		TAB."<div class='tit'>".get_const('NODE_CABINET')."&nbsp;<span class='blue'>{$_USER->data['email']}</span></div>".
		TAB."<div class='buttons_div'><a class='red' href='/exit'>".get_const('LOG_OUT')."</a></div>".
		TAB."<div class='widgets row boxed'>".
			TAB."<div class='widget fl30 bordered rounded boxed'>".
			TAB."<span class='title'>".get_const('NODE_USERDATA')."</span>".
				TAB."<div class='body boxed'>".
				customer_edit_event().
				TAB."</div>".			
			TAB."</div>".
			TAB."<div class='fl1 noipad'></div>".
			
			TAB."<div id='wishlist' class='fl50'>".
				wishlist(true).
			TAB."</div>".
		TAB."</div>";
		

$plug_og['subtitle'] 	= get_const('CMS_NAME');
$plug_og['title'] 	= get_const('NODE_CABINET');

?>
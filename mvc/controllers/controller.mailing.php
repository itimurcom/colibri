<?
$_CONTENT['admin'] = get_admin_button_set();
if (!$_USER->is_logged())
	{
	cms_redirect_page("/");
	}

$o_mailer = new itMailer(['num' => 10, 'force'=>true]);
unset($o_mailer);
	
$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();

$_CONTENT['content'] = 
//		TAB."<div class='row boxed'>".
		mailing_history_panel().
//		TAB."</div>";		
		"";

// opengraph
$plug_og['subtitle'] 	= get_const('CMS_NAME');
$plug_og['title'] 	= get_const('CMS_NAME_EXTENDED');

?>
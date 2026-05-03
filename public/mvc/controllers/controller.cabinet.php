<?php 
function cabinet_controller_content()
	{
	global $_USER;
	return
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
	}

$_CONTENT['admin'] = get_admin_button_set();
$data = itEditor::_redata();

if (!$_USER->is_logged('ANY'))
	{
	cms_redirect_page('/');
	}

transfer_wishlist();
$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();
$_CONTENT['content'] .= cabinet_controller_content();
$plug_og['subtitle'] 	= get_const('CMS_NAME');
$plug_og['title'] 	= get_const('NODE_CABINET');
?>

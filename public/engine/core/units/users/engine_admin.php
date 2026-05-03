<?php
function get_admin_buttons_code()
	{
	return
		TAB."<div class='admin_panel_div'>".
		get_logout_event().
		get_settings_event().
		get_mailing_event().
		get_item_add_event().
		TAB."</div>";
	}

function get_admin_moderate_code($main_moder)
	{
	return !empty($main_moder)
		? TAB."<div class='content boxed'>".
			TAB."<div class='green'>".get_const('MAIN_MODERATE_TITLE')."</div>".
			$main_moder.
			TAB."</div>"
		: NULL;
	}

function get_admin_login_code()
	{
	return TAB."<div class='login'>".get_login_event().TAB."</div>";
	}

function get_admin_button_set()
	{
	global $_USER;
	$main_moder = get_moderate_panel(['table' => 'contents', 'set' => 'contents-moderate']);
	return $_USER->is_logged()
		? get_admin_buttons_code().get_admin_moderate_code($main_moder)
		: get_admin_login_code();
	}

function get_background_css()
	{
	return NULL;
	}
?>

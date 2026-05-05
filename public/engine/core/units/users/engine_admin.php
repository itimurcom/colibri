<?php
function colibri_admin_user_is_logged($groups=NULL)
	{
	global $_USER;
	return (is_object($_USER) && method_exists($_USER, 'is_logged'))
		? $_USER->is_logged($groups)
		: false;
	}

function colibri_admin_optional_event($function_name)
	{
	return function_exists($function_name) ? $function_name() : '';
	}

function get_admin_buttons_code()
	{
	return
		TAB."<div class='admin_panel_div'>".
		colibri_admin_optional_event('get_logout_event').
		colibri_admin_optional_event('get_settings_event').
		colibri_admin_optional_event('get_mailing_event').
		colibri_admin_optional_event('get_item_add_event').
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
	return function_exists('get_login_event')
		? TAB."<div class='login'>".get_login_event().TAB."</div>"
		: '';
	}

function get_admin_button_set()
	{
	$main_moder = function_exists('get_moderate_panel')
		? get_moderate_panel(['table' => 'contents', 'set' => 'contents-moderate'])
		: '';
	return colibri_admin_user_is_logged()
		? get_admin_buttons_code().get_admin_moderate_code($main_moder)
		: get_admin_login_code();
	}

function get_background_css()
	{
	return NULL;
	}
?>

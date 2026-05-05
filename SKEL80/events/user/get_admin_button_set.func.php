<?php
// возвращает кнопки админ панели
function get_admin_button_set()
	{
	global $_USER;
	$is_logged = (is_object($_USER) && method_exists($_USER, 'is_logged')) ? $_USER->is_logged() : false;
	$main_moder = function_exists('get_moderate_panel')
		? get_moderate_panel(['table' => 'contents', 'set' => 'contents-moderate'])
		: '';

	if ($is_logged)
		{
		$result = 
			TAB."<div class='admin_panel_div'>".
			(function_exists('get_logout_event') ? get_logout_event() : '').
//			get_add_content_event().
			TAB."</div>".
			
			(!empty($main_moder) ?
				TAB."<div class='content boxed'>".
				TAB."<div class='green'>".get_const('MAIN_MODERATE_TITLE')."</div>".
				$main_moder.
				TAB."</div>" : "");

		} else 	{
			$result = function_exists('get_login_event')
				? TAB."<div class='login'>".	
					get_login_event(['captcha' => true]).
					TAB."</div>"
				: '';
			}

	return $result;
	}
?>

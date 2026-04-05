<?php
//..............................................................................
// возвращает кнопки админ панели
//..............................................................................
function get_admin_button_set()
	{
	global $_USER;	
	$main_moder = get_moderate_panel(['table' => 'contents', 'set' => 'contents-moderate']);

	if ($_USER->is_logged())
		{
		$result = 
			TAB."<div class='admin_panel_div'>".
			get_logout_event().	
			get_settings_event().
//			get_password_event($_USER->data).
//			get_add_content_event().
//			get_measurement_event().
			get_mailing_event().
// 			get_background_event().
			get_item_add_event().
			TAB."</div>".
			
			(!empty($main_moder) ?
				TAB."<div class='content boxed'>".
				TAB."<div class='green'>".get_const('MAIN_MODERATE_TITLE')."</div>".
				$main_moder.
				TAB."</div>" : "");

		} else 	{
			$result = 		
				TAB."<div class='login'>".	
				get_login_event().
				TAB."</div>";
			}

	return $result;
	}
	
//..............................................................................
// CSS заднего плана для body
//..............................................................................
function get_background_css()
		{
/*
	$filename = "/themes/".CMS_THEME."/images/bg_{$_REQUEST['controller']}.jpg";
	return file_exists($_SERVER['DOCUMENT_ROOT']."{$filename}") ? 
		TAB."<style>#wrapper
			{
			background-image:url({$filename});
			background-position:top center;
			background-repeat: no-repeat;
			background-size:cover;
			}
		</style>" : NULL;
*/
	}
?>
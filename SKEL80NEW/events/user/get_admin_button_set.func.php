<?
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
//			get_add_content_event().
			TAB."</div>".
			
			(!empty($main_moder) ?
				TAB."<div class='content boxed'>".
				TAB."<div class='green'>".get_const('MAIN_MODERATE_TITLE')."</div>".
				$main_moder.
				TAB."</div>" : "");

		} else 	{
			$result = 		
				TAB."<div class='login'>".	
				get_login_event(['captcha' => true]).
				TAB."</div>";
			}

	return $result;
	}
?>
<?
//..............................................................................
// возвращает панель модерации материалов на сайте
//..............................................................................
function get_moderate_panel($table_name = DEFAULT_MODERATOR_TABLE, $options=NULL)
	{
	global $_USER;
	if (!$_USER->is_logged()) return;

	$o_moderator = new itModerator($table_name, $options);	
	$moderator_str = $o_moderator->code();
	$result = ($moderator_str) ? TAB."<div class='moderate_div'>".$moderator_str.TAB."</div>" : '';
	unset ($o_moderator);
	return $result;
	}
?>
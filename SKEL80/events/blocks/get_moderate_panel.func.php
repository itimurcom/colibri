<?php
// ================ CRC ================
// version: 1.15.03
// hash: 5d76032a378e1264b97921c529f43d2c5d2ad02d1c21c4ae2e93e7d00f9af268
// date: 09 September 2019  5:10
// ================ CRC ================
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
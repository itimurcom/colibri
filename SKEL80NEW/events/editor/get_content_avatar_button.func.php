<?
//..............................................................................
// возвращает кнопку выбора аватарки для КОНТЕНТА
//..............................................................................
function get_content_avatar_button($row, $class=NULL) {
	global $_USER;
	if (!$_USER->is_logged()) return;
	$options = [
		'class' 		=> 'admin'.($class ? " {$class}" : NULL), 
		'name' 			=> DEFAULT_FILES_NAME, 
		'table_name' 	=> $row['table_name'],
		'rec_id' 		=> $row['rec_id'],
		'op' 			=> 'ava'
		];
	$b_files = new itButton(get_const('BUTTON_ED_AVATAR'), 'file', $options, 'gold');
	$result = $b_files->code();
	unset($b_files, $options);
	return $result;
	}
?>
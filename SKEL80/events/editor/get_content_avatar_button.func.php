<?php
// ================ CRC ================
// version: 1.15.02
// hash: 76a4f6de56ca37ea8ac22089287191bdd9a53cbc3de6e52ea8b44ab8615d8ee7
// date: 16 September 2018 19:58
// ================ CRC ================
//..............................................................................
// возвращает кнопку выбора аватарки для КОНТЕНТА
//..............................................................................
function get_content_avatar_button($row)
	{
	$options = array (
		'class' 	=> 'admin', 
		'name' 		=> DEFAULT_FILES_NAME, 
		'table_name' 	=> $row['table_name'],
		'rec_id' 	=> $row['rec_id'],
		'op' 		=> 'ava'
		);
	$b_files = new itButton(get_const('BUTTON_ED_AVATAR'), 'file', $options, 'gold');
	$result = $b_files->code();
	unset($b_files, $options);
	return $result;
	}
?>
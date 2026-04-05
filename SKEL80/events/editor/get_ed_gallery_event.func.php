<?php
// ================ CRC ================
// version: 1.15.03
// hash: 98746b2c6c3a600082e32f2d1227a290b88787624edb1710af29f9b43cca4434
// date: 09 September 2019  5:10
// ================ CRC ================
//..............................................................................
// возвращает кнопку добавления изображения                                    *
//..............................................................................
function get_ed_gallery_event($row)
	{
	$options = array (
		'class' 	=> 'admin', 
		'name' 		=> get_const('DEFAULT_FILES_NAME'), 
		'table_name' 	=> $row['table_name'],
		'rec_id' 	=> $row['rec_id'],
		'ed_key' 	=> $row['ed_key'],
		'selector' 	=> $row['selector'],
		'field' 	=> $row['field'],
		'column' 	=> $row['column'],		
		'root' 		=> $row['root'],
		'op' 		=> 'add_ed_gallery',
		);
	$b_files = new itButton(get_const('BUTTON_ED_IMAGE'), 'files', $options, 'blue');
	$result = $b_files->code();
	unset($b_files, $options);
	return $result;
	}
?>
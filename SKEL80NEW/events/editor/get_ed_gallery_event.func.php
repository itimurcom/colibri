<?
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
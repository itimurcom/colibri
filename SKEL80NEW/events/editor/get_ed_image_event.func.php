<?
//..............................................................................
// возвращает кнопку добавления изображения в галлерею			       *
//..............................................................................
function get_ed_image_event($row)
	{
	$options = array (
		'class' 	=> 'gal_add_btn', 
		'name' 		=> get_const('DEFAULT_FILES_NAME'),
		'table_name' 	=> $row['table_name'],
		'rec_id' 	=> $row['rec_id'],
		'ed_key' 	=> $row['ed_key'],
		'field' 	=> $row['field'],
		'column' 	=> $row['column'],		
		'root' 		=> $row['root'],		
		'selector' 	=> $row['selector'],
		'op' 		=> 'gal_add',
		'src'		=> "/themes/".CMS_THEME."/images/add_img_button.png",
		);
	$b_files = new itButton('', 'imfiles', $options);
	$result = $b_files->code();
	unset($b_files, $options);
	return $result;
	}
?>
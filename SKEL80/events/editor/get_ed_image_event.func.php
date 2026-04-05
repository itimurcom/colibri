<?php
// ================ CRC ================
// version: 1.15.04
// hash: d6e0941441f465331c5a2943989123fb57fcc6bd1751828cbc8b51a6c613b314
// date: 09 September 2019  5:10
// ================ CRC ================
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
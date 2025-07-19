<?
//..............................................................................
// возвращает кнопку добавления изображения в галлерею поля
//..............................................................................
function get_itimage_add_event($row)
	{
	$options = [
		'class' 	=> 'itimages_add', 
		'name' 		=> get_const('DEFAULT_FILES_NAME'),
		'table_name' 	=> $row['table_name'],
		'rec_id' 	=> $row['rec_id'],
		'field' 	=> $row['field'],
		'column' 	=> $row['column'],
		'op' 		=> 'itimages_add',
		'src'		=> "/themes/".CMS_THEME."/images/add_img_button.png",
		];
	$b_files = new itButton('', 'imfiles', $options);
	$result = $b_files->code();
	unset($b_files, $options);
	return $result;
	}
?>
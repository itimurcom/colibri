<?
//..............................................................................
// возвращает набор кнопок, которые встраиваются в ed_devider
//..............................................................................
function get_ed_buttons_set($row=NULL)
	{
	if ($row==NULL) return;
	$result = '';

	// добавим кнопку удаления поля редактора
	$result .= 
		get_ed_remove_event($row).
		get_ed_text_event($row).
		((($row['type']=='media') or ($row['type']=='gallery')) ? get_ed_zoom_event($row) : '').
//		(($row['type']=='text') ? get_ed_text_translate_event($row) : '');
		get_ed_media_event($row).
		get_ed_gallery_event($row).
//		get_ed_image_event($row).
		((($row['type']=='text') and !isset($row['avatar'])) ? get_ed_avatar_event($row) : '').
		get_down_ed_key_event($row).
		get_up_ed_key_event($row);

	$num = (str_replace ('ed_','',$row['ed_key'])+1);

	$result .= TAB."<span id='key_$num' class='ed_number'># $num</span>";
	return $result;
	}
?>
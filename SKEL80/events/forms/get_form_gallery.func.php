<?php
// ================ CRC ================
// version: 1.15.03
// hash: 7347c2f46804409dedccb3409463b0b9f611d4c3a01a0ea96e890556424927be
// date: 15 December 2018  1:20
// ================ CRC ================
//..............................................................................
// возвращает код поля галлереи для формы
//..............................................................................
function get_form_gallery($row)
	{
	$images_result = NULL;
	
	$code = ready_val($row['code']);
	$element_id = redy_val($row['element_id'], DEFAULT_UPGAL_ID);
	$field_class = ready_val($row['field'], DEFAULT_UPGAL_FIELD);

	$gallery_field = "gallery-{$element_id}{$row['id']}";
	$files_field = "file-{$element_id}{$row['id']}";
	$data_field = "{$element_id}{$row['id']}";
	
	if (is_array($images = str_getcsv($_REQUEST[$row['name']])))
		{
		foreach($images as $image_key=>$image_row)
			$images_result .= get_form_gallery_row($image_row, $data_field);
		}
		
	
	return
		// поле которое пойдет в отправке формы
		TAB."<div class='{$field_class}'>".
		TAB."<input type='hidden' id='{$data_field}' rel='{$gallery_field}' name='{$row['name']}' value='{$_REQUEST[$row['name']]}'/>".
		$code.
		// сама галлерея		
		TAB."<div class='upload_gallery fancygall' id='{$gallery_field}' rel='{$files_field}'>".
			$images_result.
		TAB."</div>".
		TAB."</div>".
		
		TAB."<div class='user_form_field'>".		
		TAB."<img class='rounded add_color_image' src='/themes/images/add_img_button.png' onclick=\"document.getElementById('$files_field').click();\"/>".
		TAB."<input class='upload_gal_btn' name='{$row['name']}-upload' style='display: none;' rel='measure{$row['id']}' accept='image/x-png,image/gif,image/jpeg' type='file' id='{$files_field}'/>".
		TAB."</div>".
		"";
	}
?>
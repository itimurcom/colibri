<?php
// возвращает код поля галлереи для формы
if (!function_exists('form_gallery_row_value'))
	{
	function form_gallery_row_value($row, $key, $default=NULL)
		{
		return (is_array($row) AND isset($row[$key])) ? $row[$key] : $default;
		}
	}

if (!function_exists('form_gallery_request_value'))
	{
	function form_gallery_request_value($key, $default='')
		{
		return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
		}
	}

if (!function_exists('form_gallery_const'))
	{
	function form_gallery_const($name, $default=NULL)
		{
		return defined($name) ? constant($name) : $default;
		}
	}

function get_form_gallery($row)
	{
	$row = is_array($row) ? $row : [];
	$images_result = NULL;

	$name = form_gallery_row_value($row, 'name', form_gallery_const('DEFAULT_UPGAL_NAME', 'images'));
	$row_id = form_gallery_row_value($row, 'id', 0);
	$code = form_gallery_row_value($row, 'code');
	$element_id = form_gallery_row_value($row, 'element_id', $name);
	$field_class = form_gallery_row_value($row, 'field', form_gallery_const('DEFAULT_UPGAL_FIELD', 'upgal'));
	$value = form_gallery_request_value($name, '');

	$gallery_field = "gallery-{$element_id}{$row_id}";
	$files_field = "file-{$element_id}{$row_id}";
	$data_field = "{$element_id}{$row_id}";

	if (is_array($images = str_getcsv($value)))
		{
		foreach($images as $image_key=>$image_row)
			{
			if ($image_row === '' OR is_null($image_row))
				{
				continue;
				}
			$images_result .= get_form_gallery_row($image_row, $data_field);
			}
		}


	return
		// поле которое пойдет в отправке формы
		TAB."<div class='{$field_class}'>".
		TAB."<input type='hidden' id='{$data_field}' rel='{$gallery_field}' name='{$name}' value='{$value}'/>".
		$code.
		// сама галлерея
		TAB."<div class='upload_gallery fancygall' id='{$gallery_field}' rel='{$files_field}'>".
			$images_result.
		TAB."</div>".
		TAB."</div>".

		TAB."<div class='user_form_field'>".
		TAB."<img class='rounded add_color_image' src='/themes/images/add_img_button.png' onclick=\"document.getElementById('$files_field').click();\"/>".
		TAB."<input class='upload_gal_btn' name='{$name}-upload' style='display: none;' rel='measure{$row_id}' accept='image/x-png,image/gif,image/jpeg' type='file' id='{$files_field}'/>".
		TAB."</div>".
		"";
	}
?>

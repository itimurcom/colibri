<?php
// обработчик событий форм
if (!function_exists('upload_gal_request_value'))
	{
	function upload_gal_request_value($key, $default=NULL)
		{
		return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
		}
	}

if (!function_exists('upload_gal_files_key'))
	{
	function upload_gal_files_key()
		{
		return defined('DEFAULT_UPGAL_FILES') ? DEFAULT_UPGAL_FILES : 'MyFiles';
		}
	}

if (!function_exists('upload_gal_has_files'))
	{
	function upload_gal_has_files($files_key)
		{
		return isset($_FILES[$files_key])
			AND isset($_FILES[$files_key]['name'])
			AND is_array($_FILES[$files_key]['name'])
			AND isset($_FILES[$files_key]['tmp_name'])
			AND is_array($_FILES[$files_key]['tmp_name']);
		}
	}

if (!function_exists('upload_gal_uploads_root'))
	{
	function upload_gal_uploads_root()
		{
		return defined('UPLOADS_ROOT') ? UPLOADS_ROOT : '';
		}
	}

if (!function_exists('upload_gal_delimiter'))
	{
	function upload_gal_delimiter()
		{
		return defined('DEFAULT_UPGAL_DELIMITER') ? DEFAULT_UPGAL_DELIMITER : '|';
		}
	}

function upload_gal_events($url, $path)
	{
	$op = upload_gal_request_value('op');
	$rel = upload_gal_request_value('rel');
	$files_key = upload_gal_files_key();

	switch ($op)
		{
		case 'upload_gal' :
			{
			$img_arr = NULL;
			$value_arr = NULL;

			if (!upload_gal_has_files($files_key))
				{
				return print json_encode(['result' => 0, 'value' => NULL, 'images' => NULL ]);
				}

			foreach ($_FILES[$files_key]['name'] as $key => $name)
				{
				if ($name === '' OR !isset($_FILES[$files_key]['tmp_name'][$key]) OR $_FILES[$files_key]['tmp_name'][$key] === '')
					{
					continue;
					}

				$clear_name = clear_file_name($name);
				$clear_name = check_uploaded_file($clear_name, $_FILES[$files_key]['tmp_name'][$key]);
				if(move_uploaded_file($_FILES[$files_key]['tmp_name'][$key], upload_gal_uploads_root().$clear_name))
					{
					$value_arr[] = function_exists('get_form_gallery_row') ? get_form_gallery_row(basename($clear_name), $rel) : add_error_message('no function get_form_gallery_row found');
					$img_arr[] = $clear_name;
					}
				}

			if (is_array($img_arr))
				{
				return print json_encode(['result' => 1, 'value' => implode('', $value_arr), 'images' => implode(upload_gal_delimiter(), $img_arr) ]);
				} else	{
					return print json_encode(['result' => 0, 'value' => NULL, 'images' => NULL ]);
					}
			break;
			}

		}
	}
?>

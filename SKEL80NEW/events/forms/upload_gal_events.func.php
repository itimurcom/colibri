<?
//..............................................................................
// обработчик событий форм
//..............................................................................	
function upload_gal_events($url, $path)
	{
	switch ($_REQUEST['op'])
		{
		case 'upload_gal' :
			{
			$img_arr = NULL;
			$value = NULL;				
			foreach ($_FILES[DEFAULT_UPGAL_FILES]['name'] as $key => $name)
				{
				$clear_name = clear_file_name($name);
				$clear_name = check_uploaded_file($clear_name, $_FILES[DEFAULT_UPGAL_FILES]["tmp_name"][$key]); 
				if(move_uploaded_file($_FILES[DEFAULT_UPGAL_FILES]["tmp_name"][$key], UPLOADS_ROOT.$clear_name))
					{ 	
					$value_arr[] = function_exists('get_form_gallery_row') ? get_form_gallery_row(basename($clear_name), $_REQUEST['rel']) : add_error_message('no function get_form_gallery_row found');
					$img_arr[] = $clear_name;
					}
				}
			
			if (is_array($img_arr))
				{
				return print json_encode(['result' => 1, 'value' => implode('', $value_arr), 'images' => implode(get_const('DEFAULT_UPGAL_DELIMITER'), $img_arr) ]);	
				} else	{
					return print json_encode(['result' => 0, 'value' => NULL, 'images' => NULL ]);						
					}
			break;
			}

		}
	}
?>
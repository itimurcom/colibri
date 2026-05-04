<?php
// обработчик событий простой галлереи поля таблицы
if (!function_exists('itimages_request_value'))
	{
	function itimages_request_value($key, $default=NULL)
		{
		return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
		}
	}

if (!function_exists('itimages_const_value'))
	{
	function itimages_const_value($name, $default=NULL)
		{
		return defined($name) ? constant($name) : $default;
		}
	}

if (!function_exists('itimages_files_key'))
	{
	function itimages_files_key()
		{
		return itimages_const_value('DEFAULT_FILES_NAME', 'MyFiles');
		}
	}

if (!function_exists('itimages_has_files'))
	{
	function itimages_has_files($files_key)
		{
		return isset($_FILES[$files_key])
			AND isset($_FILES[$files_key]['name'])
			AND is_array($_FILES[$files_key]['name'])
			AND isset($_FILES[$files_key]['tmp_name'])
			AND is_array($_FILES[$files_key]['tmp_name']);
		}
	}

if (!function_exists('itimages_uploads_root'))
	{
	function itimages_uploads_root()
		{
		return itimages_const_value('UPLOADS_ROOT', '');
		}
	}

if (!function_exists('itimages_event_data'))
	{
	function itimages_event_data($data)
		{
		$data = is_array($data) ? $data : [];
		return [
			'table_name'	=> isset($data['table_name']) ? $data['table_name'] : itimages_const_value('DEFAULT_IMAGES_TABLE'),
			'rec_id'	=> isset($data['rec_id']) ? $data['rec_id'] : NULL,
			'field'		=> isset($data['field']) ? $data['field'] : itimages_const_value('DEFAULT_IMAGES_FIELD'),
			'column'	=> isset($data['column']) ? $data['column'] : itimages_const_value('DEFAULT_IMAGES_COLUMN'),
			'key'		=> isset($data['key']) ? $data['key'] : NULL,
			'state'		=> isset($data['state']) ? $data['state'] : itimages_const_value('DEFAULT_IMAGESSTATE', 'view'),
			];
		}
	}

function itimages_events($url, $path)
	{
	$data = itimages_event_data(itEditor::_redata());
	$reload = "<script>window.location.href='{$url}';</script>";
	$op = itimages_request_value('op');

	switch ($op)
		{
		case 'itimagesstate' : {
			$state = ($data['state'] == 'view') ? 'edit' : 'view';

			$o_images = new itImages($data);
			$value = $o_images->container(['state'=>$state]);
			unset($o_images);

			return print json_encode(['result' => 1, 'value' => $value], JSON_ALLOWED);
			break;
			};

		case 'itimagesreload' : {
			$o_images = new itImages($data);
			$value = $o_images->container(['state'=>'edit']);
			unset($o_images);

			return print json_encode(['result' => 1, 'value' => $value], JSON_ALLOWED);
			break;
			};


		case 'itimages_add' : {
			$o_images = new itImages([
				'table_name'	=> $data['table_name'],
				'rec_id'	=> $data['rec_id'],
				'field'		=> $data['field'],
				'column'	=> $data['column'],
				]);

			$o_images->storage = !is_array($o_images->storage) ? [] : $o_images->storage;
			$files_key = itimages_files_key();

			if (itimages_has_files($files_key))
				{
				foreach ($_FILES[$files_key]['name'] as $key => $name)
					{
					if ($name === '' OR !isset($_FILES[$files_key]['tmp_name'][$key]) OR $_FILES[$files_key]['tmp_name'][$key] === '')
						{
						continue;
						}

					$clear_name = clear_file_name($name);
					$clear_name = check_uploaded_file($clear_name, $_FILES[$files_key]['tmp_name'][$key]);
					if(move_uploaded_file($_FILES[$files_key]['tmp_name'][$key], itimages_uploads_root().$clear_name))
						{
						$o_images->storage[] = $clear_name;
						}
					}
				}

			$o_images->store();
			unset($o_images);
//			return print json_encode(['result' => 1, 'value' => $url], JSON_ALLOWED);
			return print json_encode(['result' => 1, 'type'=>'ajax', 'value' => "itimages_reload('#".itImages::_container_id($data)."');"], JSON_ALLOWED);
			break;
			}

		case 'itimage_x' : {
			$o_images = new itImages([
				'table_name'	=> $data['table_name'],
				'rec_id'	=> $data['rec_id'],
				'field'		=> $data['field'],
				'column'	=> $data['column'],
				]);
			$o_images->gal_x($data['key']);
			$o_images->store();
			unset($o_images);
			return print json_encode(['result' => 1, 'reload'=>$reload], JSON_ALLOWED);
//			cms_redirect_page("$url");
			break;
			}

		case 'itimage_up' : {
			$o_images = new itImages([
				'table_name'	=> $data['table_name'],
				'rec_id'	=> $data['rec_id'],
				'field'		=> $data['field'],
				'column'	=> $data['column'],
				]);
			$o_images->gal_up($data['key']);
			$o_images->store();
			unset($o_images);
			return print json_encode(['result' => 1, 'reload'=>$reload], JSON_ALLOWED);
//			cms_redirect_page("$url");
			break;
			}

		case 'itimage_down' : {
			$o_images = new itImages([
				'table_name'	=> $data['table_name'],
				'rec_id'	=> $data['rec_id'],
				'field'		=> $data['field'],
				'column'	=> $data['column'],
				]);
			$o_images->gal_down($data['key']);
			$o_images->store();
			unset($o_images);
			return print json_encode(['result' => 1, 'reload'=>$reload], JSON_ALLOWED);
//			cms_redirect_page("$url");
			break;
			}


		case 'itimage_n' : {
			$o_images = new itImages([
				'table_name'	=> $data['table_name'],
				'rec_id'	=> $data['rec_id'],
				'field'		=> $data['field'],
				'column'	=> $data['column'],
				]);
			$o_images->gal_move($data['key'], itimages_request_value('new_id'));
			$o_images->store();
			unset($o_images);
			return print json_encode(['result' => 1, 'reload'=>$reload], JSON_ALLOWED);
//			cms_redirect_page("$url");
			break;
			}
		}
	}
?>

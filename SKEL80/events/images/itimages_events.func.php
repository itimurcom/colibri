<?php
// ================ CRC ================
// version: 1.15.05
// hash: 581eedef22ff1c1e137c2a0a2afc8c3cb57614134d3b233a0187fbdb84f4a300
// date: 30 September 2019  6:19
// ================ CRC ================
//..............................................................................
// обработчик событий простой галлереи поля таблицы
//..............................................................................	
function itimages_events($url, $path)
	{
	$data = itEditor::_redata();
	$reload = "<script>window.location.href='{$url}';</script>";
	
	switch ($_REQUEST['op'])
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
			
			foreach ($_FILES[DEFAULT_FILES_NAME]['name'] as $key => $name)
				{
				$clear_name = clear_file_name($name);
				$clear_name = check_uploaded_file($clear_name, $_FILES[DEFAULT_FILES_NAME]["tmp_name"][$key]); 
	                        $count=0;
				if(move_uploaded_file($_FILES[DEFAULT_FILES_NAME]["tmp_name"][$key], UPLOADS_ROOT.$clear_name))
					{ 	
					$count++; 
					$o_images->storage[] = $clear_name;
					unset($value);
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
			$o_images->gal_move($data['key'], $_REQUEST['new_id']);
			$o_images->store();
			unset($o_images);
			return print json_encode(['result' => 1, 'reload'=>$reload], JSON_ALLOWED);
//			cms_redirect_page("$url");
			break;
			}			
		}			
	}
?>
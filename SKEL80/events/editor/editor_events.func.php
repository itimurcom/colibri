<?php
function editor_events_json($payload)
	{
	return print json_encode($payload, JSON_ALLOWED);
	}

function editor_events_reload($reload)
	{
	return editor_events_json(['result' => 1, 'reload'=>$reload]);
	}

function editor_events_ajax_reload($data)
	{
	return editor_events_json(['result' => 1, 'type'=>'ajax', 'value' => "editor_edreload('#".itEditor::_container_id($data)."');"]);
	}

function editor_events_container($data, $state='edit')
	{
	$o_editor = new itEditor($data);
	$value = $o_editor->container(['state'=>$state]);
	unset($o_editor);
	return $value;
	}


function editor_events_repacked_editor($data)
	{
	return new itEditor(itEditor::_repack_data($data));
	}

function editor_events_store_and_reload($o_editor, $reload)
	{
	$o_editor->store();
	unset($o_editor);
	return editor_events_reload($reload);
	}

function editor_events_store_and_ajax_reload($o_editor, $data)
	{
	$o_editor->store();
	unset($o_editor);
	return editor_events_ajax_reload($data);
	}


function editor_events_upload_gallery_files($o_editor, $data, $ed_key)
	{
	foreach ($_FILES[DEFAULT_FILES_NAME]['name'] as $key => $name)
		{
		$clear_name = clear_file_name($name);
		$clear_name = check_uploaded_file($clear_name, $_FILES[DEFAULT_FILES_NAME]["tmp_name"][$key]);
		if (move_uploaded_file($_FILES[DEFAULT_FILES_NAME]["tmp_name"][$key], UPLOADS_ROOT.$clear_name))
			{
			$o_editor->storage[$data['selector']][$ed_key]['value'][] = $clear_name;
			$o_editor->sort_gallery($data['selector'], $ed_key);
			}
		}
	}

function editor_events_gallery_store_and_reload($data, $reload, $method, $arguments)
	{
	$o_editor = editor_events_repacked_editor($data);
	call_user_func_array([$o_editor, $method], $arguments);
	return editor_events_store_and_reload($o_editor, $reload);
	}

// обработчик событий редактора
function editor_events($url, $path)
	{	
	global $_USER;
	$data = itEditor::_redata();
	$reload = "<script>window.location.href='{$url}';</script>";


	if ( ($_REQUEST['op']=='edstate') AND !$_USER->is_logged('ANY') )
		{
		$o_editor = new itEditor($data);
		$value = $o_editor->_view();
		unset($o_editor);
		return editor_events_json(['result' => 1, 'value' => $value]);
		} else
	switch ($_REQUEST['op'])
		{
		case 'edstate' : {
			$state = ($data['state'] == 'view') ? 'edit' : 'view';
			
			$o_editor = new itEditor($data);
			$value = $_USER->is_logged() ? $o_editor->container(['state'=>$state]) : $o_editor->_view();
			unset($o_editor);
			
			return editor_events_json(['result' => 1, 'value' => $value]);
			};
			
		case 'edreload' : {
			$o_editor = new itEditor($data);
			$value = $o_editor->container(['state'=>'edit']);
			unset($o_editor);
			
			return editor_events_json(['result' => 1, 'value' => $value]);
			};						

		// itEditor - управление блоками
		case 'block' : {
			itMySQL::_update_value_db($_REQUEST['table_name'], $_REQUEST['rec_id'], $_REQUEST['content_id'], 'content_id');
			cms_redirect_page("$url");
			break;
			}

		// itEditor
		case 'ed_text' : {
			$o_editor = editor_events_repacked_editor($data);
			if (!isset($o_editor->storage[$data['selector']][$data['ed_key']]['type']))
				{
				$o_editor->storage[$data['selector']][$data['ed_key']]['type'] = 'text';
				}
			$o_editor->storage[$data['selector']] [$data['ed_key']] ['value'] [$data['lang']] = $_REQUEST['value'];
			$o_editor->store();
			unset($o_editor);

			return editor_events_json(['result' => 1, 'value' => $url]);
			}

		case 'ed_remove' : {
			$o_editor = editor_events_repacked_editor($data);
			unset($o_editor->storage[$data['selector']][$data['ed_key']]);
			if ($o_editor->sort($data['selector']))
				{
				return editor_events_store_and_reload($o_editor, $reload);
				}
			unset($o_editor);
			return editor_events_reload($reload);
			}

		case 'add_ed_text' : {
			$o_editor = editor_events_repacked_editor($data);
			$index = $o_editor->insert_field($data['selector'], $data['ed_key'], 'text', '');
			unset($o_editor);
			return editor_events_reload($reload);
			}

		case 'up_ed_field' : {
			$o_editor = editor_events_repacked_editor($data);
			$index = $o_editor->up_field($data['selector'],$data['ed_key']);
			unset($o_editor);
			return editor_events_reload($reload);
			}

		case 'down_ed_field' : {
			$o_editor = editor_events_repacked_editor($data);
			$index = $o_editor->down_field($data['selector'],$data['ed_key']);
			unset($o_editor);
			return editor_events_reload($reload);
			}


		case 'ed_remove_avatar' : {
			$o_editor = editor_events_repacked_editor($data);
	        	if (isset($o_editor->storage[$data['selector']][$data['ed_key']]['avatar'])) unset ($o_editor->storage[$data['selector']][$data['ed_key']]['avatar']);
		        if (isset($o_editor->storage[$data['selector']][$data['ed_key']]['position'])) unset ($o_editor->storage[$data['selector']][$data['ed_key']]['position']);
			$o_editor->store();
			unset($o_editor);
			return editor_events_reload($reload);			
			}

		case 'ed_add_avatar' : {
			$o_editor = editor_events_repacked_editor($data);
			$clear_name = clear_file_name($_FILES[DEFAULT_FILES_NAME]['name'][0]); //!!! [0];
			$clear_name = check_uploaded_file($clear_name, $_FILES[DEFAULT_FILES_NAME]["tmp_name"][0], $path);

			$count = 0;
			if(move_uploaded_file($_FILES[DEFAULT_FILES_NAME]["tmp_name"][0], $path.$clear_name))
				{
				$o_editor->storage[$data['selector']][$data['ed_key']]['type']='text';
//				$o_editor->storage[$data['selector']][$data['ed_key']]['value']='';
	        	        $o_editor->storage[$data['selector']][$data['ed_key']]['avatar'] = $clear_name;
			        $o_editor->storage[$data['selector']][$data['ed_key']]['position'] = 'LEFT';
			        $o_editor->storage[$data['selector']][$data['ed_key']]['zoom'] = 'SMALL';

				$o_editor->store();
				}
			unset($o_editor);
			return editor_events_ajax_reload($data);			
			}

		case 'ed_switch' : {
			$o_editor = editor_events_repacked_editor($data);
			$o_editor->storage[$data['selector']][$data['ed_key']]['position'] =
				(isset($o_editor->storage[$data['selector']][$data['ed_key']]['position']) AND ($o_editor->storage[$data['selector']][$data['ed_key']]['position'] == 'LEFT'))
					? 'RIGHT'
					: 'LEFT';
			return editor_events_store_and_reload($o_editor, $reload);
			}

		case 'ed_zoom' : {
			$o_editor = editor_events_repacked_editor($data);
			$o_editor->switch_zoom($data['selector'], $data['ed_key']);
			return editor_events_store_and_reload($o_editor, $reload);
			}

		case 'add_ed_media' : {
			if (in_array (itEdMedia::get_embed_source($_REQUEST['value']), unserialize(get_const('ALOWED_MEDIA'))) )
				{
				$o_editor = editor_events_repacked_editor($data);
				$index = $o_editor->insert_field($data['selector'], $data['ed_key'], 'media', $_REQUEST['value']);
				$o_editor->store();
				unset($o_editor);
				} else 	{
					if (function_exists('add_error_message'))					
					add_error_message(ERROR_ADD_MEDIA." <b>[{$_REQUEST['value']}]</b>");
					}
			return editor_events_reload($reload);
			}


		case 'ed_change' : {
			$o_editor = editor_events_repacked_editor($data);
			$o_editor->storage[$data['selector']][$data['ed_key']]['value'] = $_REQUEST['value'];
			$o_editor->storage[$data['selector']][$data['ed_key']]['avatar'] = itEdMedia::get_media_preview($_REQUEST['value']);
			return editor_events_store_and_reload($o_editor, $reload);
			}

		// события общего плана
		case 'ed_title' : {
			$o_editor = new itEditor($_REQUEST['table_name'], $_REQUEST['rec_id']);
			$o_editor->data['title_xml'][$_REQUEST['lang']] = $_REQUEST['value'];
			$o_editor->store();
			unset($o_editor);
			cms_redirect_page("$url");
			break;
			}
	
		case 'show_as' : {
			itMySQL::_update_value_db($_REQUEST['table_name'], $_REQUEST['rec_id'], $_REQUEST['show_as'], 'show_as');
			cms_redirect_page("$url");
			break;
			}

		case 'add_content' : {
			$values_arr = [
				'title_xml'	=> [$_REQUEST['lang'] => $_REQUEST['value']],
				'category_id'	=> $_REQUEST['category_id'],
				'lang'		=> DEFAULT_EDITOR_LANG,
				'datetime'	=> get_mysql_time_str(strtotime('now')),
				];
			$rec_id = itMySQL::_insert_rec($_REQUEST['table_name'], $values_arr);
			$url = "/".(isset($_REQUEST['name']) ? "{$_REQUEST['name']}/{$rec_id}/" : "");
			cms_redirect_page("$url");
			break;
			}

		case 'status' : {
			itModerator::set_status($_REQUEST['table_name'], $_REQUEST['rec_id'], $_REQUEST['status']);
			if (isset($_REQUEST['process']))
				{
				if (function_exists($_REQUEST['process']))
					{
					$_REQUEST['process']($_REQUEST);
					}
				}
			cms_redirect_page("$url");
			break;
			}


		case 'category' : {
			itCats::set_category($_REQUEST['table_name'], $_REQUEST['rec_id'], $_REQUEST['category_id']);
			cms_redirect_page("$url");
			break;
			}

		case 'moderate' : {
			$row = itMySQL::_get_rec_from_db($_REQUEST['table_name'], $_REQUEST['rec_id']);
			itModerator::set_status($_REQUEST['table_name'], $_REQUEST['rec_id'], ($_REQUEST['status'] = (($row['status']=='PUBLISHED') ? 'MODERATE' : 'PUBLISHED')));
			if (isset($_REQUEST['process']))
				{
				if (function_exists($_REQUEST['process']))
					{
					$_REQUEST['process']($_REQUEST);
					}
				}			
			cms_redirect_page("$url");
			break;
			}

		case 'datetime' : {
			itMySQL::_update_value_db($_REQUEST['table_name'], $_REQUEST['rec_id'], $_REQUEST['datetime'], 'datetime');			
			cms_redirect_page("$url");
			break;
			}
			
		case 'content_type' : {
			itModerator::content_type($_REQUEST['table_name'], $_REQUEST['rec_id'], $_REQUEST['content_type']);
			cms_redirect_page("$url");
			break;
			}

		case 'ava' : {
			$clear_name = clear_file_name($_FILES[DEFAULT_FILES_NAME]['name'][0]); //!!! [0];
			$clear_name = check_uploaded_file($clear_name, $_FILES[DEFAULT_FILES_NAME]["tmp_name"][0], $path);

			if(move_uploaded_file($_FILES[DEFAULT_FILES_NAME]["tmp_name"][0], $path.$clear_name))
				{
				itMySQL::_update_value_db($data['table_name'], $data['rec_id'], $clear_name, 'avatar');
				} else 	if (function_exists('add_error_message'))
						add_error_message("Error sending $clear_name");
			return editor_events_json(['result' => 1, 'value' => $url]);
				
			}
			
		case 'ava_x' : {
			// Legacy project DB may keep avatar column as NOT NULL, so remove avatar by
			// writing an empty string instead of NULL.
			itMySQL::_update_value_db($data['table_name'], $data['rec_id'], '', 'avatar');
			cms_redirect_page("$url");
			break;
			}
			
			
		case 'start_datetime' :
		case 'finish_datetime' :
			{
			itMySQL::_update_value_db($_REQUEST['table_name'], $_REQUEST['rec_id'], $_REQUEST[$_REQUEST['op']], $_REQUEST['op']);
			cms_redirect_page("$url");
			}

		// itEditor : события галлерей itEdGallery
		case 'add_ed_gallery' : {
			$o_editor = editor_events_repacked_editor($data);
			$o_editor->insert_field($data['selector'], $data['ed_key'], 'gallery', []);
			editor_events_upload_gallery_files($o_editor, $data, $data['ed_key']+1);
			return editor_events_store_and_ajax_reload($o_editor, $data);
			}


		case 'gal_add' : {
			$o_editor = editor_events_repacked_editor($data);
			editor_events_upload_gallery_files($o_editor, $data, $data['ed_key']);
			return editor_events_store_and_ajax_reload($o_editor, $data);
			}

		case 'gal_x' : {
			return editor_events_gallery_store_and_reload($data, $reload, 'gal_x_image', [$data['selector'], $data['ed_key'], $data['gallery_id']]);
			}

		case 'gal_up' : {
			return editor_events_gallery_store_and_reload($data, $reload, 'gal_up', [$data['selector'], $data['ed_key'], $data['gallery_id']]);
			}

		case 'gal_down' : {
			return editor_events_gallery_store_and_reload($data, $reload, 'gal_down', [$data['selector'], $data['ed_key'], $data['gallery_id']]);
			}


		case 'gal_n' : {
			return editor_events_gallery_store_and_reload($data, $reload, 'gal_move', [$data['selector'], $data['ed_key'], $data['gallery_id'], $_REQUEST['new_id']]);
			}
			
		case 'gal_link' : {
			return editor_events_gallery_store_and_reload($data, $reload, 'gal_link', [$data['selector'], $data['ed_key'], $data['gallery_id'], ($_REQUEST['value']) ? addhttp($_REQUEST['value']) : NULL]);
			}

		case 'gal_text' : {
			return editor_events_gallery_store_and_reload($data, $reload, 'gal_text', [$data['selector'], $data['ed_key'], $data['gallery_id'], $_REQUEST['value']]);
			}
			
		// связанные новости
		case 'related' : {
			$data['value'] = $_REQUEST['content_id'];
			itEditor::_related($data);
			cms_redirect_page("$url");
			}	
					
		case 'related_x' : {
			itEditor::_related_x($data);
			cms_redirect_page("$url");
			}			

		}			
	}
?>

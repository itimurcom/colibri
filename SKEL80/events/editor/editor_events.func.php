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

function editor_events_request_value($key, $default=NULL)
	{
	return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
	}

function editor_events_request_int($key, $default=0)
	{
	return intval(editor_events_request_value($key, $default));
	}

function editor_events_data_value($data, $key, $default=NULL)
	{
	return (is_array($data) AND isset($data[$key])) ? $data[$key] : $default;
	}

function editor_events_has_editor_data($data)
	{
	return is_array($data)
		AND !empty($data['table_name'])
		AND !empty($data['rec_id'])
		AND isset($data['selector'])
		AND isset($data['ed_key']);
	}

function editor_events_has_uploads()
	{
	return defined('DEFAULT_FILES_NAME')
		AND isset($_FILES[DEFAULT_FILES_NAME])
		AND isset($_FILES[DEFAULT_FILES_NAME]['name'])
		AND is_array($_FILES[DEFAULT_FILES_NAME]['name'])
		AND isset($_FILES[DEFAULT_FILES_NAME]['tmp_name'])
		AND is_array($_FILES[DEFAULT_FILES_NAME]['tmp_name']);
	}

function editor_events_upload_file($index=0, $path=UPLOADS_ROOT)
	{
	if (!editor_events_has_uploads()
		OR !isset($_FILES[DEFAULT_FILES_NAME]['name'][$index])
		OR !isset($_FILES[DEFAULT_FILES_NAME]['tmp_name'][$index])
		OR $_FILES[DEFAULT_FILES_NAME]['name'][$index]=='')
		{
		return NULL;
		}

	$clear_name = clear_file_name($_FILES[DEFAULT_FILES_NAME]['name'][$index]);
	$clear_name = check_uploaded_file($clear_name, $_FILES[DEFAULT_FILES_NAME]['tmp_name'][$index], $path);

	return move_uploaded_file($_FILES[DEFAULT_FILES_NAME]['tmp_name'][$index], $path.$clear_name)
		? $clear_name
		: NULL;
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
	return new itEditor(itEditor::_repack_data(is_array($data) ? $data : []));
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
	if (!editor_events_has_uploads())
		{
		return false;
		}

	$selector = editor_events_data_value($data, 'selector');
	if ($selector===NULL)
		{
		return false;
		}

	if (!isset($o_editor->storage[$selector][$ed_key]['value']) OR !is_array($o_editor->storage[$selector][$ed_key]['value']))
		{
		$o_editor->storage[$selector][$ed_key]['value'] = [];
		}

	foreach ($_FILES[DEFAULT_FILES_NAME]['name'] as $key => $name)
		{
		if ($name==='' OR !isset($_FILES[DEFAULT_FILES_NAME]['tmp_name'][$key]))
			{
			continue;
			}

		$clear_name = clear_file_name($name);
		$clear_name = check_uploaded_file($clear_name, $_FILES[DEFAULT_FILES_NAME]['tmp_name'][$key]);
		if (move_uploaded_file($_FILES[DEFAULT_FILES_NAME]['tmp_name'][$key], UPLOADS_ROOT.$clear_name))
			{
			$o_editor->storage[$selector][$ed_key]['value'][] = $clear_name;
			$o_editor->sort_gallery($selector, $ed_key);
			}
		}
	return true;
	}

function editor_events_gallery_store_and_reload($data, $reload, $method, $arguments)
	{
	if (!editor_events_has_editor_data($data))
		{
		return editor_events_reload($reload);
		}

	$o_editor = editor_events_repacked_editor($data);
	call_user_func_array([$o_editor, $method], $arguments);
	return editor_events_store_and_reload($o_editor, $reload);
	}

// обработчик событий редактора
function editor_events($url, $path)
	{
	global $_USER;
	$data = itEditor::_redata();
	$data = is_array($data) ? $data : [];
	$operation = editor_events_request_value('op');
	$reload = "<script>window.location.href='{$url}';</script>";

	if (!$operation)
		{
		return false;
		}

	if ( ($operation=='edstate') AND !$_USER->is_logged('ANY') )
		{
		$o_editor = new itEditor($data);
		$value = $o_editor->_view();
		unset($o_editor);
		return editor_events_json(['result' => 1, 'value' => $value]);
		} else
	switch ($operation)
		{
		case 'edstate' : {
			$state = (editor_events_data_value($data, 'state') == 'view') ? 'edit' : 'view';
			
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
			$table_name = editor_events_request_value('table_name');
			$rec_id = editor_events_request_int('rec_id');
			$content_id = editor_events_request_value('content_id');
			if ($table_name AND $rec_id)
				{
				itMySQL::_update_value_db($table_name, $rec_id, $content_id, 'content_id');
				}
			cms_redirect_page("$url");
			break;
			}

		// itEditor
		case 'ed_text' : {
			if (!editor_events_has_editor_data($data))
				{
				return editor_events_json(['result' => 1, 'value' => $url]);
				}
			$o_editor = editor_events_repacked_editor($data);
			$selector = editor_events_data_value($data, 'selector');
			$ed_key = editor_events_data_value($data, 'ed_key');
			$lang = editor_events_data_value($data, 'lang', get_const('CMS_LANG'));
			if (!isset($o_editor->storage[$selector][$ed_key]['type']))
				{
				$o_editor->storage[$selector][$ed_key]['type'] = 'text';
				}
			if (!isset($o_editor->storage[$selector][$ed_key]['value']) OR !is_array($o_editor->storage[$selector][$ed_key]['value']))
				{
				$o_editor->storage[$selector][$ed_key]['value'] = [];
				}
			$o_editor->storage[$selector][$ed_key]['value'][$lang] = editor_events_request_value('value', '');
			$o_editor->store();
			unset($o_editor);

			return editor_events_json(['result' => 1, 'value' => $url]);
			}

		case 'ed_remove' : {
			if (!editor_events_has_editor_data($data))
				{
				return editor_events_reload($reload);
				}
			$o_editor = editor_events_repacked_editor($data);
			$selector = editor_events_data_value($data, 'selector');
			$ed_key = editor_events_data_value($data, 'ed_key');
			if (isset($o_editor->storage[$selector][$ed_key])) unset($o_editor->storage[$selector][$ed_key]);
			if ($o_editor->sort($selector))
				{
				return editor_events_store_and_reload($o_editor, $reload);
				}
			unset($o_editor);
			return editor_events_reload($reload);
			}

		case 'add_ed_text' : {
			if (!editor_events_has_editor_data($data))
				{
				return editor_events_reload($reload);
				}
			$o_editor = editor_events_repacked_editor($data);
			$o_editor->insert_field(editor_events_data_value($data, 'selector'), editor_events_data_value($data, 'ed_key'), 'text', '');
			unset($o_editor);
			return editor_events_reload($reload);
			}

		case 'up_ed_field' : {
			if (!editor_events_has_editor_data($data))
				{
				return editor_events_reload($reload);
				}
			$o_editor = editor_events_repacked_editor($data);
			$o_editor->up_field(editor_events_data_value($data, 'selector'), editor_events_data_value($data, 'ed_key'));
			unset($o_editor);
			return editor_events_reload($reload);
			}

		case 'down_ed_field' : {
			if (!editor_events_has_editor_data($data))
				{
				return editor_events_reload($reload);
				}
			$o_editor = editor_events_repacked_editor($data);
			$o_editor->down_field(editor_events_data_value($data, 'selector'), editor_events_data_value($data, 'ed_key'));
			unset($o_editor);
			return editor_events_reload($reload);
			}


		case 'ed_remove_avatar' : {
			if (!editor_events_has_editor_data($data))
				{
				return editor_events_reload($reload);
				}
			$o_editor = editor_events_repacked_editor($data);
			$selector = editor_events_data_value($data, 'selector');
			$ed_key = editor_events_data_value($data, 'ed_key');
        	if (isset($o_editor->storage[$selector][$ed_key]['avatar'])) unset ($o_editor->storage[$selector][$ed_key]['avatar']);
	        if (isset($o_editor->storage[$selector][$ed_key]['position'])) unset ($o_editor->storage[$selector][$ed_key]['position']);
			$o_editor->store();
			unset($o_editor);
			return editor_events_reload($reload);			
			}

		case 'ed_add_avatar' : {
			if (!editor_events_has_editor_data($data))
				{
				return editor_events_ajax_reload($data);
				}
			$o_editor = editor_events_repacked_editor($data);
			$clear_name = editor_events_upload_file(0, $path);

			if($clear_name)
				{
				$selector = editor_events_data_value($data, 'selector');
				$ed_key = editor_events_data_value($data, 'ed_key');
				$o_editor->storage[$selector][$ed_key]['type']='text';
        	        $o_editor->storage[$selector][$ed_key]['avatar'] = $clear_name;
		        $o_editor->storage[$selector][$ed_key]['position'] = 'LEFT';
		        $o_editor->storage[$selector][$ed_key]['zoom'] = 'SMALL';

				$o_editor->store();
				}
			unset($o_editor);
			return editor_events_ajax_reload($data);			
			}

		case 'ed_switch' : {
			if (!editor_events_has_editor_data($data))
				{
				return editor_events_reload($reload);
				}
			$o_editor = editor_events_repacked_editor($data);
			$selector = editor_events_data_value($data, 'selector');
			$ed_key = editor_events_data_value($data, 'ed_key');
			$o_editor->storage[$selector][$ed_key]['position'] =
				(isset($o_editor->storage[$selector][$ed_key]['position']) AND ($o_editor->storage[$selector][$ed_key]['position'] == 'LEFT'))
					? 'RIGHT'
					: 'LEFT';
			return editor_events_store_and_reload($o_editor, $reload);
			}

		case 'ed_zoom' : {
			if (!editor_events_has_editor_data($data))
				{
				return editor_events_reload($reload);
				}
			$o_editor = editor_events_repacked_editor($data);
			$o_editor->switch_zoom(editor_events_data_value($data, 'selector'), editor_events_data_value($data, 'ed_key'));
			return editor_events_store_and_reload($o_editor, $reload);
			}

		case 'add_ed_media' : {
			$media_value = editor_events_request_value('value', '');
			$allowed_media = @unserialize(get_const('ALOWED_MEDIA'));
			$allowed_media = is_array($allowed_media) ? $allowed_media : [];
			if (in_array (itEdMedia::get_embed_source($media_value), $allowed_media) )
				{
				if (editor_events_has_editor_data($data))
					{
					$o_editor = editor_events_repacked_editor($data);
					$o_editor->insert_field(editor_events_data_value($data, 'selector'), editor_events_data_value($data, 'ed_key'), 'media', $media_value);
					$o_editor->store();
					unset($o_editor);
					}
				} else 	{
					if (function_exists('add_error_message'))					
					add_error_message(ERROR_ADD_MEDIA." <b>[{$media_value}]</b>");
					}
			return editor_events_reload($reload);
			}


		case 'ed_change' : {
			if (!editor_events_has_editor_data($data))
				{
				return editor_events_reload($reload);
				}
			$o_editor = editor_events_repacked_editor($data);
			$selector = editor_events_data_value($data, 'selector');
			$ed_key = editor_events_data_value($data, 'ed_key');
			$media_value = editor_events_request_value('value', '');
			$o_editor->storage[$selector][$ed_key]['value'] = $media_value;
			$o_editor->storage[$selector][$ed_key]['avatar'] = itEdMedia::get_media_preview($media_value);
			return editor_events_store_and_reload($o_editor, $reload);
			}

		// события общего плана
		case 'ed_title' : {
			$table_name = editor_events_request_value('table_name');
			$rec_id = editor_events_request_int('rec_id');
			$lang = editor_events_request_value('lang', get_const('CMS_LANG'));
			if ($table_name AND $rec_id)
				{
				$o_editor = new itEditor($table_name, $rec_id);
				if (!isset($o_editor->data['title_xml']) OR !is_array($o_editor->data['title_xml']))
					{
					$o_editor->data['title_xml'] = [];
					}
				$o_editor->data['title_xml'][$lang] = editor_events_request_value('value', '');
				$o_editor->store();
				unset($o_editor);
				}
			cms_redirect_page("$url");
			break;
			}
	
		case 'show_as' : {
			$table_name = editor_events_request_value('table_name');
			$rec_id = editor_events_request_int('rec_id');
			if ($table_name AND $rec_id)
				{
				itMySQL::_update_value_db($table_name, $rec_id, editor_events_request_value('show_as'), 'show_as');
				}
			cms_redirect_page("$url");
			break;
			}

		case 'add_content' : {
			$table_name = editor_events_request_value('table_name');
			if ($table_name)
				{
				$values_arr = [
					'title_xml'	=> [editor_events_request_value('lang', get_const('CMS_LANG')) => editor_events_request_value('value', '')],
					'category_id'	=> editor_events_request_value('category_id'),
					'lang'		=> DEFAULT_EDITOR_LANG,
					'datetime'	=> get_mysql_time_str(strtotime('now')),
					];
				$rec_id = itMySQL::_insert_rec($table_name, $values_arr);
				$url = "/".(editor_events_request_value('name') ? editor_events_request_value('name')."/{$rec_id}/" : "");
				}
			cms_redirect_page("$url");
			break;
			}

		case 'status' : {
			$table_name = editor_events_request_value('table_name');
			$rec_id = editor_events_request_int('rec_id');
			if ($table_name AND $rec_id)
				{
				itModerator::set_status($table_name, $rec_id, editor_events_request_value('status'));
				$process = editor_events_request_value('process');
				if ($process AND function_exists($process))
					{
					$process($_REQUEST);
					}
				}
			cms_redirect_page("$url");
			break;
			}


		case 'category' : {
			$table_name = editor_events_request_value('table_name');
			$rec_id = editor_events_request_int('rec_id');
			if ($table_name AND $rec_id)
				{
				itCats::set_category($table_name, $rec_id, editor_events_request_value('category_id'));
				}
			cms_redirect_page("$url");
			break;
			}

		case 'moderate' : {
			$table_name = editor_events_request_value('table_name');
			$rec_id = editor_events_request_int('rec_id');
			if ($table_name AND $rec_id)
				{
				$row = itMySQL::_get_rec_from_db($table_name, $rec_id);
				$status = (is_array($row) AND isset($row['status']) AND ($row['status']=='PUBLISHED')) ? 'MODERATE' : 'PUBLISHED';
				$_REQUEST['status'] = $status;
				itModerator::set_status($table_name, $rec_id, $status);
				$process = editor_events_request_value('process');
				if ($process AND function_exists($process))
					{
					$process($_REQUEST);
					}
				}
			cms_redirect_page("$url");
			break;
			}

		case 'datetime' : {
			$table_name = editor_events_request_value('table_name');
			$rec_id = editor_events_request_int('rec_id');
			if ($table_name AND $rec_id)
				{
				itMySQL::_update_value_db($table_name, $rec_id, editor_events_request_value('datetime'), 'datetime');
				}
			cms_redirect_page("$url");
			break;
			}
			
		case 'content_type' : {
			$table_name = editor_events_request_value('table_name');
			$rec_id = editor_events_request_int('rec_id');
			if ($table_name AND $rec_id)
				{
				itModerator::content_type($table_name, $rec_id, editor_events_request_value('content_type'));
				}
			cms_redirect_page("$url");
			break;
			}

		case 'ava' : {
			$clear_name = editor_events_upload_file(0, $path);

			$table_name = editor_events_data_value($data, 'table_name');
			$rec_id = editor_events_data_value($data, 'rec_id');
			if($clear_name AND $table_name AND $rec_id)
				{
				itMySQL::_update_value_db($table_name, $rec_id, $clear_name, 'avatar');
				} else 	if (function_exists('add_error_message'))
						add_error_message("Error sending avatar");
			return editor_events_json(['result' => 1, 'value' => $url]);
				
			}
			
		case 'ava_x' : {
			// Legacy project DB may keep avatar column as NOT NULL, so remove avatar by
			// writing an empty string instead of NULL.
			$table_name = editor_events_data_value($data, 'table_name');
			$rec_id = editor_events_data_value($data, 'rec_id');
			if ($table_name AND $rec_id)
				{
				itMySQL::_update_value_db($table_name, $rec_id, '', 'avatar');
				}
			cms_redirect_page("$url");
			break;
			}
			
			
		case 'start_datetime' :
		case 'finish_datetime' :
			{
			$table_name = editor_events_request_value('table_name');
			$rec_id = editor_events_request_int('rec_id');
			if ($table_name AND $rec_id)
				{
				itMySQL::_update_value_db($table_name, $rec_id, editor_events_request_value($operation), $operation);
				}
			cms_redirect_page("$url");
			break;
			}

		// itEditor : события галлерей itEdGallery
		case 'add_ed_gallery' : {
			if (!editor_events_has_editor_data($data))
				{
				return editor_events_ajax_reload($data);
				}
			$o_editor = editor_events_repacked_editor($data);
			$o_editor->insert_field(editor_events_data_value($data, 'selector'), editor_events_data_value($data, 'ed_key'), 'gallery', []);
			editor_events_upload_gallery_files($o_editor, $data, editor_events_data_value($data, 'ed_key')+1);
			return editor_events_store_and_ajax_reload($o_editor, $data);
			}


		case 'gal_add' : {
			if (!editor_events_has_editor_data($data))
				{
				return editor_events_ajax_reload($data);
				}
			$o_editor = editor_events_repacked_editor($data);
			editor_events_upload_gallery_files($o_editor, $data, editor_events_data_value($data, 'ed_key'));
			return editor_events_store_and_ajax_reload($o_editor, $data);
			}

		case 'gal_x' : {
			return editor_events_gallery_store_and_reload($data, $reload, 'gal_x_image', [editor_events_data_value($data, 'selector'), editor_events_data_value($data, 'ed_key'), editor_events_data_value($data, 'gallery_id')]);
			}

		case 'gal_up' : {
			return editor_events_gallery_store_and_reload($data, $reload, 'gal_up', [editor_events_data_value($data, 'selector'), editor_events_data_value($data, 'ed_key'), editor_events_data_value($data, 'gallery_id')]);
			}

		case 'gal_down' : {
			return editor_events_gallery_store_and_reload($data, $reload, 'gal_down', [editor_events_data_value($data, 'selector'), editor_events_data_value($data, 'ed_key'), editor_events_data_value($data, 'gallery_id')]);
			}


		case 'gal_n' : {
			return editor_events_gallery_store_and_reload($data, $reload, 'gal_move', [editor_events_data_value($data, 'selector'), editor_events_data_value($data, 'ed_key'), editor_events_data_value($data, 'gallery_id'), editor_events_request_int('new_id')]);
			}
			
		case 'gal_link' : {
			$value = editor_events_request_value('value');
			return editor_events_gallery_store_and_reload($data, $reload, 'gal_link', [editor_events_data_value($data, 'selector'), editor_events_data_value($data, 'ed_key'), editor_events_data_value($data, 'gallery_id'), ($value) ? addhttp($value) : NULL]);
			}

		case 'gal_text' : {
			return editor_events_gallery_store_and_reload($data, $reload, 'gal_text', [editor_events_data_value($data, 'selector'), editor_events_data_value($data, 'ed_key'), editor_events_data_value($data, 'gallery_id'), editor_events_request_value('value')]);
			}
			
		// связанные новости
		case 'related' : {
			$data['value'] = editor_events_request_value('content_id');
			itEditor::_related($data);
			cms_redirect_page("$url");
			break;
			}	
					
		case 'related_x' : {
			itEditor::_related_x($data);
			cms_redirect_page("$url");
			break;
			}

		}
	return false;
	}
?>

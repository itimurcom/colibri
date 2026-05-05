<?php
function object_event_request_value($key, $default=NULL)
	{
	return (isset($_REQUEST) AND is_array($_REQUEST) AND array_key_exists($key, $_REQUEST)) ? ready_val($_REQUEST[$key], $default) : $default;
	}

function object_event_request_array()
	{
	return (isset($_REQUEST) AND is_array($_REQUEST)) ? $_REQUEST : [];
	}

function object_event_data_value($data, $key, $default=NULL)
	{
	return (is_array($data) AND array_key_exists($key, $data)) ? $data[$key] : $default;
	}

function object_event_positive_id($value)
	{
	$value = (int)$value;
	return $value>0 ? $value : NULL;
	}

function object_event_redirect($url)
	{
	cms_redirect_page($url);
	}

// обработчик событий объектов
function object_events($url='/', $path=UPLOADS_ROOT)
	{
	$data = itEditor::_redata();
	$data = is_array($data) ? $data : [];
	$op = object_event_request_value('op');

	switch ($op)
		{
		// объекты
		case 'add_object' : {
			$data['category_id'] = object_event_request_value('category_id', 0);
			$data['title'] = object_event_request_value('value', object_event_data_value($data, 'title'));
			$rec_id = itObject::_add($data);
			if ($rec_id)
				{
				object_event_redirect("/".CMS_LANG."/object/{$rec_id}/");
				} else object_event_redirect($url);
			break;
			}
			
		case 'obj_value' : {
			if (is_null(object_event_positive_id(object_event_data_value($data, 'rec_id'))) OR empty(object_event_data_value($data, 'name')))
				{
				add_error_message('ERROR_OPTIONS_OBJECT');
				object_event_redirect($url);
				break;
				}
			$data['value'] = object_event_request_value('value');
			itObject::_update_value($data);
			object_event_redirect($url);
			break;
			}
			
		case 'obj_category': {
			if (is_null(object_event_positive_id(object_event_data_value($data, 'rec_id'))))
				{
				add_error_message('ERROR_OPTIONS_OBJECT');
				object_event_redirect($url);
				break;
				}
			$data['value'] = object_event_request_value('value');
			itObject::_set_category($data);
			object_event_redirect($url);
			break;
			}
		case 'obj_title': {
			if (is_null(object_event_positive_id(object_event_data_value($data, 'rec_id'))))
				{
				add_error_message('ERROR_OPTIONS_OBJECT');
				object_event_redirect($url);
				break;
				}
			$data['value'] = object_event_request_value('value');
			itObject::_set_title($data);
			object_event_redirect($url);
			break;
			}
			
		case 'obj_form' : {
			$request_data = object_event_request_array();
			$data = array_merge($request_data, $data);
			unset($data['data']);
			itObject::_form_update($data);
			object_event_redirect($url);
			break;
			}
///// ТОВАР!
		case 'item_add' : {
			$data['object_id'] = object_event_request_value('object_id');
			$data['subtitle'] = object_event_request_value('subtitle');
			$data['price'] = object_event_request_value('price');
			$rec_id = itItem::_add($data);
			if ($rec_id)
				{
				object_event_redirect("/".CMS_LANG."/item/{$rec_id}/");
				} else object_event_redirect($url);
			break;
			}
		
		}
	}
?>

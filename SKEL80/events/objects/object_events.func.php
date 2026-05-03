<?php
function object_event_request_value($key)
	{
	return isset($_REQUEST[$key]) ? ready_val($_REQUEST[$key]) : NULL;
	}

// обработчик событий объектов
function object_events($url='/', $path=UPLOADS_ROOT)
	{
	$data = itEditor::_redata();		
	switch (object_event_request_value('op'))
		{
		// объекты
		case 'add_object' : {
			$data['category_id']	= object_event_request_value('category_id');
			$rec_id = itObject::_add($data);
			cms_redirect_page("/".CMS_LANG."/object/{$rec_id}/");	
			break;
			}
			
		case 'obj_value' : {
			$data['value']	= object_event_request_value('value');
			itObject::_update_value($data);
			cms_redirect_page("$url");	
			break;
			}
			
		case 'obj_category': {
			$data['value']	= object_event_request_value('value');
			itObject::_set_category($data);
			cms_redirect_page("$url");	
			break;
			}
		case 'obj_title': {
			$data['value']	= object_event_request_value('value');
			itObject::_set_title($data);
			cms_redirect_page("$url");	
			break;
			}
			
		case 'obj_form' : {		
			$data = array_merge(is_array($_REQUEST) ? $_REQUEST : [], $data);
			unset($data['data']);			
			itObject::_form_update($data);
			cms_redirect_page("$url");	
			break;
			}
///// ТОВАР!
		case 'item_add' : {
			$data['object_id']	= object_event_request_value('object_id');
			$data['subtitle']	= object_event_request_value('subtitle');
			$data['price']		= object_event_request_value('price');
			$rec_id = itItem::_add($data);
			cms_redirect_page("/".CMS_LANG."/item/{$rec_id}/");	
			break;
			}
		
		}
	}
?>
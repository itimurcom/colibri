<?php
// ================ CRC ================
// version: 1.15.02
// hash: e330e91f72f374a1235cde1ca551ae9ca2e195bf5e7cd7045afd16eb0b39c43e
// date: 16 September 2018 19:58
// ================ CRC ================
//..............................................................................
// обработчик событий объектов
//..............................................................................
function object_events($url='/', $path=UPLOADS_ROOT)
	{
	$data = itEditor::_redata();		
	switch ($_REQUEST['op'])
		{
		// объекты
		case 'add_object' : {
			$data['category_id']	= ready_val($_REQUEST['category_id']);
			$rec_id = itObject::_add($data);
			cms_redirect_page("/".CMS_LANG."/object/{$rec_id}/");	
			break;
			}
			
		case 'obj_value' : {
			$data['value']	= ready_val($_REQUEST['value']);
			itObject::_update_value($data);
			cms_redirect_page("$url");	
			break;
			}
			
		case 'obj_category': {
			$data['value']	= ready_val($_REQUEST['value']);
			itObject::_set_category($data);
			cms_redirect_page("$url");	
			break;
			}
		case 'obj_title': {
			$data['value']	= ready_val($_REQUEST['value']);
			itObject::_set_title($data);
			cms_redirect_page("$url");	
			break;
			}
			
		case 'obj_form' : {		
			$data = array_merge($_REQUEST, $data);
			unset($data['data']);			
			itObject::_form_update($data);
			cms_redirect_page("$url");	
			break;
			}
///// ТОВАР!
		case 'item_add' : {
			$data['object_id']	= ready_val($_REQUEST['object_id']);
			$data['subtitle']	= ready_val($_REQUEST['subtitle']);
			$data['price']		= ready_val($_REQUEST['price']);
			$rec_id = itItem::_add($data);
			cms_redirect_page("/".CMS_LANG."/item/{$rec_id}/");	
			break;
			}
		
		}
	}
?>
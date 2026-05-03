<?php
function wizard_event_request_value($key)
	{
	return isset($_REQUEST[$key]) ? ready_val($_REQUEST[$key]) : NULL;
	}

// обработчик событий визардов, объектов и товаров
function wizards_events($url='/', $path=UPLOADS_ROOT)
	{
	$data = itEditor::_redata();		
	switch (wizard_event_request_value('op'))
		{
		// ВИЗАРДЫ
		case 'wiz_add' : {
			$data['name']	= wizard_event_request_value('name');
			$data['label']	= wizard_event_request_value('label');
			$data['type']	= wizard_event_request_value('type');			
			$data['titles'] = explode("\r\n", (string)wizard_event_request_value('titles'));
			$data['values'] = explode("\r\n", (string)wizard_event_request_value('values'));
			itWizard::_add($data);
			cms_redirect_page("$url");
			}	

		case 'wiz_x' : {
			itWizard::_remove($data);
			cms_redirect_page("$url");
			}	

		case 'wiz_titles' : {
			$data['titles'] = explode("\r\n", (string)wizard_event_request_value('value'));
			itWizard::_set_titles($data);
			cms_redirect_page("$url");
			}	

		case 'wiz_values' : {
			$data['values'] = explode("\r\n", (string)wizard_event_request_value('value'));
			itWizard::_set_values($data);
			cms_redirect_page("$url");
			}	

		case 'wiz_name' : {
			$data['name'] = wizard_event_request_value('value');
			itWizard::_set_name($data);
			cms_redirect_page("$url");
			}
		
		case 'wiz_type' : {
			$data['type'] = wizard_event_request_value('value');
			itWizard::_set_type($data);
			cms_redirect_page("$url");
			}

		case 'wiz_label' : {
			$data['label'] = wizard_event_request_value('value');
			itWizard::_set_label($data);
			cms_redirect_page("$url");
			}

		case 'wiz_copy' : {
			$data['category_id']	= wizard_event_request_value('category_id');
			itWizard::_copy($data);
			cms_redirect_page("$url");
			}	
		}
	}
?>
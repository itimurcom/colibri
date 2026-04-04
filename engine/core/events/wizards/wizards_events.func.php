<?php
// ================ CRC ================
// version: 1.15.02
// hash: 3f85b4e282a49ae519435793906b8fe5196c5d14b62dfee97b06d2903f2eb895
// date: 16 September 2018 19:58
// ================ CRC ================
//..............................................................................
// обработчик событий визардов, объектов и товаров
//..............................................................................
function wizards_events($url='/', $path=UPLOADS_ROOT)
	{
	$data = itEditor::_redata();		
	switch ($_REQUEST['op'])
		{
		// ВИЗАРДЫ
		case 'wiz_add' : {
			$data['name']	= ready_val($_REQUEST['name']);
			$data['label']	= ready_val($_REQUEST['label']);
			$data['type']	= ready_val($_REQUEST['type']);			
			$data['titles'] = explode("\r\n",$_REQUEST['titles']);
			$data['values'] = explode("\r\n",$_REQUEST['values']);
			itWizard::_add($data);
			cms_redirect_page("$url");
			}	

		case 'wiz_x' : {
			itWizard::_remove($data);
			cms_redirect_page("$url");
			}	

		case 'wiz_titles' : {
			$data['titles'] = explode("\r\n",$_REQUEST['value']);
			itWizard::_set_titles($data);
			cms_redirect_page("$url");
			}	

		case 'wiz_values' : {
			$data['values'] = explode("\r\n",$_REQUEST['value']);
			itWizard::_set_values($data);
			cms_redirect_page("$url");
			}	

		case 'wiz_name' : {
			$data['name'] = $_REQUEST['value'];
			itWizard::_set_name($data);
			cms_redirect_page("$url");
			}
		
		case 'wiz_type' : {
			$data['type'] = $_REQUEST['value'];
			itWizard::_set_type($data);
			cms_redirect_page("$url");
			}

		case 'wiz_label' : {
			$data['label'] = $_REQUEST['value'];
			itWizard::_set_label($data);
			cms_redirect_page("$url");
			}

		case 'wiz_copy' : {
			$data['category_id']	= ready_val($_REQUEST['category_id']);
			itWizard::_copy($data);
			cms_redirect_page("$url");
			}	
		}
	}
?>
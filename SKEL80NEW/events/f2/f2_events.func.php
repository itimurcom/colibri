<?
//..............................................................................
// обработчик событий редактируемой формы (2.1)
//..............................................................................
function f2_events($url, $path)
	{	
	$data = itEditor::_redata();
	$reload = "<script>window.location.href='{$url}';</script>";

	switch ($_REQUEST['op'])
		{
		case 'f2_change' : {
			itForm2::_change($_REQUEST);
			return print json_encode(['result' => 1]);
			break;
			}

		case 'f2_field' : {
			$data['kind'] = $_REQUEST['kind'];
			$data['name'] = NULL;
			itForm2::_insert_field($data);
			return print json_encode(['result' => 1]);
			break;
			}
			
		case 'f2_edstate' : {
			$state = ($data['state'] == 'view') ? 'edit' : 'view';
			
			$o_form2 = new itForm2($data);
			$value = $o_form2->container(['state'=>$state]);
			unset($o_form2);
			
			return print json_encode(['result' => 1, 'value' => $value], JSON_ALLOWED);
			break;
			};
			
		case 'f2_edreload' : {
			$o_form2 = new itForm2($data);
			$value = $o_form2->container(['state'=>'edit']);
			unset($o_form2);
			
			return print json_encode(['result' => 1, 'value' => $value], JSON_ALLOWED);
			break;
			};
		
		case 'up_f2_field' : {
			itForm2::_up_field($data);
			return print json_encode(['result' => 1, 'show'=> false], JSON_ALLOWED);
			break;
			}	

		case 'down_f2_field' : {
			itForm2::_down_field($data);
			return print json_encode(['result' => 1, 'show'=> false], JSON_ALLOWED);
			break;
			}
		case 'f2_x' : {
			itForm2::_field_x($data);
			return print json_encode(['result' => 1, 'show'=> false], JSON_ALLOWED);
			break;
		
			}
		}
	}
?>
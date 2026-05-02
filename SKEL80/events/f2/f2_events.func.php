<?php
// обработчик событий редактируемой формы (2.1)
function f2_events_response($payload, $flags=JSON_ALLOWED)
	{
	return print json_encode($payload, $flags);
	}

function f2_events_ok($extra=[])
	{
	return f2_events_response(array_merge(['result' => 1], $extra));
	}

function f2_events_container($data, $state)
	{
	$o_form2 = new itForm2($data);
	$value = $o_form2->container(['state'=>$state]);
	unset($o_form2);
	return $value;
	}

function f2_events_reload($data, $state='edit')
	{
	return f2_events_ok(['value' => f2_events_container($data, $state)]);
	}

function f2_events($url, $path)
	{
	$data = itEditor::_redata();

	switch ($_REQUEST['op'])
		{
		case 'f2_change' :
			itForm2::_change($_REQUEST);
			return f2_events_response(['result' => 1], 0);

		case 'f2_field' :
			$data['kind'] = $_REQUEST['kind'];
			$data['name'] = NULL;
			itForm2::_insert_field($data);
			return f2_events_response(['result' => 1], 0);

		case 'f2_edstate' :
			$state = ($data['state'] == 'view') ? 'edit' : 'view';
			return f2_events_reload($data, $state);

		case 'f2_edreload' :
			return f2_events_reload($data, 'edit');

		case 'up_f2_field' :
			itForm2::_up_field($data);
			return f2_events_ok(['show'=> false]);

		case 'down_f2_field' :
			itForm2::_down_field($data);
			return f2_events_ok(['show'=> false]);

		case 'f2_x' :
			itForm2::_field_x($data);
			return f2_events_ok(['show'=> false]);
		}
	}
?>

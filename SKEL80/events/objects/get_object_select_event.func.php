<?php
// возвращает событие редактирования значения поля выборки объекта
function get_object_select_event_row_value($row, $key, $default=NULL)
	{
	return (is_array($row) AND array_key_exists($key, $row)) ? $row[$key] : $default;
	}

function get_object_select_event_can_edit()
	{
	global $_USER, $_RIGHTS;
	return is_object($_USER) AND method_exists($_USER, 'is_logged') AND $_USER->is_logged(isset($_RIGHTS['EDIT']) ? $_RIGHTS['EDIT'] : NULL);
	}

function get_object_select_event($row)
	{
	global $_USER;
	if (!is_array($row)) return '';
	$sel_arr = [];
	$titles = isset($row['titles'][CMS_LANG]) AND is_array($row['titles'][CMS_LANG]) ? $row['titles'][CMS_LANG] : [];
	foreach($titles as $sel_key=>$sel_row)
		{
		if (!isset($row['values'][$sel_key])) continue;
		$sel_arr[$row['values'][$sel_key]] = 
			[
			'title'	=> get_const($sel_row),
			'value'	=> $row['values'][$sel_key],
			];	
		}
	$value = get_object_select_event_row_value($row, 'value');
	$value_field = isset($sel_arr[$value]['title']) ? $sel_arr[$value]['title'] : get_const('NO_DATA');
			
	if (get_object_select_event_can_edit())
		{
		$table_name = get_object_select_event_row_value($row, 'table_name');
		$rec_id = (int)get_object_select_event_row_value($row, 'rec_id', get_object_select_event_row_value($row, 'id', 0));
		$name = get_object_select_event_row_value($row, 'name');
		if (empty($table_name) OR $rec_id<=0 OR empty($name)) return $value_field;
		
		$o_modal = new itModal();
		$o_modal->set_size('medium');
		$o_modal->set_animation('fadeAndUp');

		$o_form = new itForm2();
		$o_form->add_title(str_replace('[VALUE]', get_field_by_lang(get_object_select_event_row_value($row, 'label')), get_const('QUERY_OBJECT_TITLE')));

		if (count($sel_arr))
			{
			$options = array (
				'array' 	=> $sel_arr,
				'titles'        => 'title',
				'values'	=> 'value',
				'name'		=> 'value',
				);

			$o_form->add_selector('select', $options, $value, NULL, get_const('QUERY_OBJECT_SELECT_VALUE'));
			} else $o_form->add_hidden('value', $value);
		
		$o_form->add_data([
			'table_name' 	=> $table_name,
			'rec_id'	=> $rec_id,
			'name'		=> $name,
			'user_id'	=> (is_object($_USER) AND method_exists($_USER, 'id')) ? $_USER->id() : NULL,
			]);
		$o_form->add_hidden('op', 'obj_value');
		$o_form->add_button(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
		$o_form->add_button(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
		$o_form->compile();

		$o_modal->add_field($o_form->code());
		$o_modal->compile();

		$o_button = new itButton($value_field, 'textmodal', ['form' => $o_modal->form_id()], '' );
		$result = $o_button->code().$o_modal->code();
		unset($o_button, $o_form, $o_modal);
		} else $result = $value_field;
	return $result;
	}
?>
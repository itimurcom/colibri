<?php
// ================ CRC ================
// version: 1.15.05
// hash: 9ded36d228a41ba1b044291f4c8242b3c240c83ca20812c1a6a5fea957224f14
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает событие редактирования значения поля выборки объекта
//..............................................................................
function get_object_select_event($row)
	{

	global $wiz_types, $_USER, $_RIGHTS;
	
	if (isset($row['titles'][CMS_LANG]) AND is_array($row['titles'][CMS_LANG]))
		{
		foreach($row['titles'][CMS_LANG] as $sel_key=>$sel_row)
			{
			$sel_arr[$row['values'][$sel_key]] = 
				[
				'title'	=> get_const($sel_row),
				'value'	=> $row['values'][$sel_key],
				];	
			}
		}
			
	if ($_USER->is_logged($_RIGHTS['EDIT']))
		{	
		$o_modal = new itModal();
		$o_modal->set_size('medium');
		$o_modal->set_animation('fadeAndUp');

		$o_form = new itForm2();
		$o_form->add_title(str_replace('[VALUE]', get_field_by_lang($row['label']), get_const('QUERY_OBJECT_TITLE')));

		if (isset($sel_arr))
			{
			$options = array (
				'array' 	=> $sel_arr,
				'titles'        => 'title',
				'values'	=> 'value',
				'name'		=> 'value',
				);

			$o_form->add_itSelector('select', $options, $row['value'], NULL, get_const('QUERY_OBJECT_SELECT_VALUE'));
			} else $value_field = get_const('NO_DATA');
		
		$o_form->add_data([
			'table_name' 	=> $row['table_name'],
			'rec_id'	=> $row['rec_id'],
			'name'		=> $row['name'],
			'user_id'	=> $_USER->id(),
			]);
		$o_form->add_hidden('op', 'obj_value');
		$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
		$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
		$o_form->compile();

		$o_modal->add_field($o_form->code());
		$o_modal->compile();

		$value_field = !isset($sel_arr[$row['value']]['title']) ? get_const('NO_DATA') : $sel_arr[$row['value']]['title'];
		$o_button = new itButton($value_field, 'textmodal', ['form' => $o_modal->form_id()], '' );
		$result = $o_button->code().$o_modal->code();
		unset($o_button, $o_form, $o_modal);
		} else $result = ready_val($sel_arr[$row['value']]['title']);
	return $result;
	}
?>
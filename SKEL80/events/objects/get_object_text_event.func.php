<?php
// ================ CRC ================
// version: 1.15.05
// hash: 50800c523c2be215cc5e2e2d4d93b9aab3e6ebe90180e0c966875cc711d8b9da
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает событие редактирования значения текстового поля объекта
//..............................................................................
function get_object_text_event($row)
	{
	global $wiz_types, $_USER, $_RIGHTS;
	
	$value_field = empty($row['value']) ? get_const('NO_DATA') : $row['value'];
	
	if ($_USER->is_logged($_RIGHTS['EDIT']))
		{	
		$o_modal = new itModal();
		$o_modal->set_size('medium');
		$o_modal->set_animation('fadeAndUp');

		$o_form = new itForm2();
		$o_form->add_title(str_replace('[VALUE]', get_field_by_lang($row['label']), get_const('QUERY_OBJECT_TITLE')));		

		$o_form->add_input([
			'name'	=> 'value', 
			'value'	=> $row['value'],
			]);
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

		$o_button = new itButton($value_field, 'textmodal', ['form' => $o_modal->form_id()], '' );
		$result = $o_button->code().$o_modal->code();
		unset($o_button, $o_form, $o_modal);
		} else $result = $value_field;
	return $result;
	}
?>
<?php
// ================ CRC ================
// version: 1.15.03
// hash: c3a471ce5b6eb8193a858367ec5a6bb3fc9b08d2a9adb46df2bfc11e773074af
// date: 09 September 2019  5:10
// ================ CRC ================
//..............................................................................
// возвращает кнопку изменения списка значений поля визарда
//..............................................................................
function get_wizard_values_event($row)
	{
	global $_USER, $lang_cat;

	if (in_array($row['type'], unserialize(get_const('WIZARD_NOVALUES')))) return;
	
	$row['values'] = ready_val($row['values']);
	
	$o_modal = new itModal();
	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndUp');

	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', "#{$row['key']}<br/>({$lang_cat[CMS_LANG]['name_orig']})", get_const('QUERY_WIZ_VALUES')));

 	$field_value = is_array($row['values']) ? implode("\n", $row['values']) : $row['values']; 	
	$o_form->add_area('value', $field_value);

	$o_form->add_data([
		'table_name' 	=> $row['table_name'],
		'rec_id'	=> $row['rec_id'],
		'key'		=> $row['key'],
		'user_id'	=> $_USER->id(),
		'op'		=> 'wiz_values',
		]);
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

 	$field_value = is_array($row['values']) ? implode("<br/>", $row['values']) : $row['values'];
 	$field_value = empty($field_value) ? get_const('NO_DATA') : $field_value; 	

	$o_button = new itButton($field_value, 'textmodal', ['form' => $o_modal->form_id()], '' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);

	wiz_cut($result, $field_value);
	return $result;
	}
?>
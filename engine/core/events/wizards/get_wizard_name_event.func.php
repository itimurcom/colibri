<?php
// ================ CRC ================
// version: 1.15.03
// hash: 853502bbf44155e661224cf16aec61a6dbf8050a50247e721ade82e8078a31e6
// date: 09 September 2019  5:10
// ================ CRC ================
//..............................................................................
// возвращает кнопку изменения переменной поля визарда
//..............................................................................
function get_wizard_name_event($row)
	{
	global $_USER, $lang_cat;

	$o_modal = new itModal();
	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndUp');

	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', "#{$row['key']}<br/>({$lang_cat[CMS_LANG]['name_orig']})", get_const('QUERY_WIZ_NAME')));

	$o_form->add_input([
		'name'	=> 'value',
		'label'	=> get_const('QUERY_WIZ_NAME_LABEL'),
		'value'	=> $row['name'],
		'compact'	=> true,
		]);
	$o_form->add_data([
		'table_name' 	=> $row['table_name'],
		'rec_id'	=> $row['rec_id'],
		'key'		=> $row['key'],
		'user_id'	=> $_USER->id(),
		'op'		=> 'wiz_name',
		]);
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(empty($row['name']) ? DEFAULT_WIZARD_NAME : $row['name'], 'textmodal', ['form' => $o_modal->form_id()], '' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
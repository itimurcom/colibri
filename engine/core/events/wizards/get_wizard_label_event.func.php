<?php
// ================ CRC ================
// version: 1.15.03
// hash: a9db5d06931c61c2df43580fadbdede511344d820a4316bb658509f453732c40
// date: 09 September 2019  5:10
// ================ CRC ================
//..............................................................................
// возвращает кнопку изменения названия поля визарда
//..............................................................................
function get_wizard_label_event($row)
	{
	global $_USER,$lang_cat;

	$row['title'] = ready_val($row['titles'], '');
	
	$o_modal = new itModal();
	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndUp');

	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', $lang_cat[CMS_LANG]['name_orig'], get_const('WIZARD_LABEL_QUERY')));

	$o_form->add_input([
		'name'		=> 'value',
		'value'		=> get_field_by_lang(ready_val($row['label']), CMS_LANG, ''),
		'label'		=> get_const('WIZARD_LABEL_QUERY_LABEL'),
		'compact'	=> true,
		]);
	$o_form->add_data([
		'table_name' 	=> $row['table_name'],
		'rec_id'	=> $row['rec_id'],
		'key'		=> $row['key'],
		'lang'		=> CMS_LANG,
		'user_id'	=> $_USER->id(),
		'op'		=> 'wiz_label',
		]);
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(get_field_by_lang($row['label']), 'textmodal', ['form' => $o_modal->form_id()], '' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
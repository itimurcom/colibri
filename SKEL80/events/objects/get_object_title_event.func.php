<?php
// ================ CRC ================
// version: 1.15.05
// hash: f5728a85361fa3ad1134266f2294effea25df85554fd191aadbd777b6ad5f75e
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку изменения названия
//..............................................................................
function get_object_title_event($row)
	{
	global $lang_cat, $prepared_arr, $_USER;
	
	if (!$_USER->is_logged()) return get_field_by_lang($row['title_xml']);

	$o_modal = new itModal();
	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndPop');
	
	$o_form = new itForm2();
	$o_form->add_title( str_replace ('[VALUE]', $lang_cat[CMS_LANG]['name_orig'], get_const('ADD_OBJECT_LABEL')));
	
	$o_form->add_input([
		'name'	=> 'value',
		'value'	=> get_field_by_lang($row['title_xml'], CMS_LANG, ''),
		'label'	=> get_const('QUERY_OBJECT_TITLE_VALUE'),
		]);
	
	$o_form->add_data([
		'table_name' 	=> $row['table_name'],
		'rec_id' 	=> $row['rec_id'],
		'op'		=> 'obj_title',
		]);
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(get_field_by_lang($row['title_xml']), 'textmodal', ['form' => $o_modal->form_id()], '' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;	
	}
?>
<?php
// ================ CRC ================
// version: 1.15.04
// hash: 78d4de1a71bbc0360f03595ef42f8e3e6d965d9872b7ad2dbc18bf353716c110
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку удаления категории
//..............................................................................
function get_category_x_event($row, $table_name=DEFAULT_CATEGORY_TABLE)
	{
	global $lang_cat;
	$o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndPop');
	
	$o_form = new itForm2();
	$o_form->add_title(str_replace ('[VALUE]', "#{$row['id']} <b>".get_field_by_lang($row['title_xml'])."</b>", get_const('QUERY_REMOVE_CATEGORY')));
	$o_form->add_data([
		'table_name' 	=> $table_name,
		'rec_id' 	=> $row['id'],
		'op'		=> 'category_x'
		]);
	$o_form->add_itButton(get_const('BUTTON_REMOVE'), 'submit', ['form' => $o_form->form_id()], 'red' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton("<b>&#10007;</b>", 'textmodal', [ 'class'=>'treebtn', 'form' => $o_modal->form_id()], 'red' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;	
	}
?>
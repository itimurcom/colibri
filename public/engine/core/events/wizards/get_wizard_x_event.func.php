<?php
// возвращает кнопку удаления поля визарда для категории
function get_wizard_x_event($row)
	{
	global $lang_cat, $_USER;
	$o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndPop');
	
	$o_form = new itForm2();
	$o_form->add_title(str_replace ('[VALUE]', get_field_by_lang($row['label']), get_const('QUERY_REMOVE_WIZARD')));
	$o_form->add_data([
		'table_name' 	=> $row['table_name'],
		'rec_id'	=> $row['rec_id'],
		'key'		=> $row['key'],
		'lang'		=> CMS_LANG,
		'user_id'	=> $_USER->id(),
		'op'		=> 'wiz_x',
		]);

	$o_form->add_button(get_const('BUTTON_REMOVE'), 'submit', ['form' => $o_form->form_id()], 'red' );	
	$o_form->add_button(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton("<b>&#10007;</b>", 'textmodal', [ 'class'=>'treebtn', 'form' => $o_modal->form_id()], 'red' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;	
	}
?>
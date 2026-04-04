<?php
// ================ CRC ================
// version: 1.15.05
// hash: 1e2346d2fb8f65eef96e5a8a7a2acbd80ef0bc9430d9d462a6d4333983954fde
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку для удаления связанной новости
//..............................................................................
function get_related_x_event($row, $table_name=DEFAULT_CONTENT_TABLE)
	{
	global $_USER;
	if (!$_USER->is_logged(itEditor::moderators())) return;

	$o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndPop');

	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', get_field_by_lang($row['title_xml']), get_const('RELATED_CONTENT_REMOVE')));

	$o_form->add_data([
		'table_name' 	=> $table_name,
		'rec_id' 	=> $row['rec_id'],
		'field' 	=> ready_val($row['field'], DEFAULT_RELATED_FIELD),
		'content_id' 	=> $row['id'],
		'op'		=> 'related_x',
		]);	
	$o_form->add_itButton(get_const('BUTTON_REMOVE'), 'submit', ['form' => $o_form->form_id()], 'red' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(get_const('BUTTON_ED_REMOVE'), 'modal', ['class'=>'x', 'form' => $o_modal->form_id()], 'red' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return 
		TAB."<div class='x_div'>".
		$result.
		TAB."</div>";
	}
?>
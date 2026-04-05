<?php
// ================ CRC ================
// version: 1.15.04
// hash: 8e2254554869342336f0d0cd6fc6be8eee0a5f8760b37a5162a89c4b3a3f524a
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку удаления контента заданного типа
//..............................................................................
function get_moderate_killall_event($arr)
	{
	global $statuses;

	$row = current($arr);
	if (!is_array($row)) return;

	$table_name = $row['table_name'];
	$status = $row['status'];
	$title = get_const('STATUS_'.$row['status']);
	
	$o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndPop');

	
	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', $title, get_const('QUERY_KILLALL')));
	$o_form->add_data([
		'table_name' 	=> $table_name,
		'status'	=> $status,
		'op'		=> 'killall',
		]);
	
	$o_form->add_itButton(get_const('BUTTON_REMOVE'), 'submit', ['form' => $o_form->form_id()], 'red' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(BUTTON_KILLALL, 'textmodal', ['class' => '', 'form' => $o_modal->form_id()], 'red' );

	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
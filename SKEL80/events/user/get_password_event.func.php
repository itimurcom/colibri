<?php
// ================ CRC ================
// version: 1.15.05
// hash: 9fd57fae4ceb4cceabac8dc6db80c58fe7de99ee7d52506231c4abdb3de1e403
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку смены пароля
//..............................................................................
function get_password_event($row, $table_name=DEFAULT_USER_TABLE)
	{	
	global $_USER;

	if (!$_USER->is_logged('ANY') or (($row['id']!=$_USER->id())) and !$_USER->is_logged()) return;

	$o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndPop');

	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', $row['login'], "<b>".get_const('QUERY_NEW_PASSWORD')."</b>"));
//	$o_form->add_password('old_password', '', get_const('OLD_PASSWORD_LABEL'));
	$o_form->add_password('new_password', '', get_const('NEW_PASSWORD_LABEL'));
	$o_form->add_password('new_password2', '', get_const('NEW_PASSWORD2_LABEL'));
	
	$o_form->add_data([
		'table_name' 	=> $table_name,
		'rec_id'	=> $row['id'],
		'op'		=> 'password',
		]);

	$o_form->add_itButton(BUTTON_OK, 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(BUTTON_CANCEL, 'close', ['form' => $o_modal->form_id()], 'green' );
	$o_form->compile();
	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(get_const('BUTTON_PASSWORD'), 'modal', ['class' => 'admin', 'form' => $o_modal->form_id()], 'green' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
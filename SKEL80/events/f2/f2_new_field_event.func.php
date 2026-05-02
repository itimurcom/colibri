<?php
// возвращает код кнопки смены режима редактора формы (версия 2.1)
function f2_new_field_event($row)
	{
	definition([
		'QUERY_NEW_FILED'	=> 'Выберите тип поля',
		]);
	global $form2_defaults;

	list($o_modal, $o_form) = f2_control_modal_form($row, 'QUERY_NEW_FILED');
	$o_form->add_selector([
		'array'	=> $form2_defaults,
		'name'	=> 'kind',
		]);

	f2_control_add_data($o_form, $row, 'f2_field');
	f2_control_modal_buttons($o_form, $o_modal, $row, 'BUTTON_OK', 'blue');
	return f2_control_modal_code($o_modal, $o_form, 'BUTTON_PLUS_FILED', 'green');
	}
?>

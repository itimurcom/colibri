<?php
// возвращает кнопку для удаления поля
function f2_x_field_event($row)
	{
	definition([
		'QUERY_ED_REMOVE'	=> "Желаете <b class='red'>удалить</b> поле <b class='blue'>[VALUE]</b>?",
		]);
	if ($row['last_field']=='0') return;

	list($o_modal, $o_form) = f2_control_modal_form($row, 'QUERY_ED_REMOVE');
	f2_control_add_data($o_form, $row, 'f2_x');
	f2_control_modal_buttons($o_form, $o_modal, $row, 'BUTTON_REMOVE', 'red');
	return f2_control_modal_code($o_modal, $o_form, 'BUTTON_ED_REMOVE', 'red');
	}
?>

<?php
// возвращает кнопку перемещения поля формы вниз
function f2_down_field_event($row)
	{
	return ($row['ed_key'] == $row['last_field']) ? NULL : f2_control_ajax_button($row, 'down_f2_field', 'BUTTON_DOWN', 'gray');
	}
?>

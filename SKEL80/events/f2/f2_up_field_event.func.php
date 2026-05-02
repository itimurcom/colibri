<?php
// возвращает кнопку перемещения поля формы вверх
function f2_up_field_event($row)
	{
	return ($row['ed_key'] == 0) ? NULL : f2_control_ajax_button($row, 'up_f2_field', 'BUTTON_UP', 'gray');
	}
?>

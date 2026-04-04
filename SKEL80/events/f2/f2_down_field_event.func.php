<?php
// ================ CRC ================
// version: 1.35.02
// hash: a39d9f7635c161cf9a55a5dd55dd895b02a5074315f6eca7da74dafe1c2c0ea6
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку перемещения поля формы вниз
//..............................................................................
function f2_down_field_event($row)
	{
	if ($row['ed_key'] == $row['last_field']) return;

	$o_form = new itForm2();

	$row['op'] = 'down_f2_field';
	$o_form->add_data($row);

	$o_button = new itButton(get_const('BUTTON_DOWN'), 'ajaxsubmit', ['class' => 'admin', 'form' => $o_form->form_id(), 'ajax'=>"f2_edreload('#".itForm2::_container_id($row)."');"], 'gray' );
	$result = $o_form->_view().$o_button->code();
	unset($o_button, $o_form);
	return $result;	
	}
?>
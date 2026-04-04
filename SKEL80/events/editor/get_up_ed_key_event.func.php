<?php
// ================ CRC ================
// version: 1.15.04
// hash: 194a3cbad7652af571c8c0a3d226c009c2d9064aaa3932b5c738a1feb76e8b67
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку перемещения поля вверх                                    *
//..............................................................................
function get_up_ed_key_event($row)
	{
	if ($row['ed_key'] == 0) return;

	$o_form = new itForm2();

	$row['op'] = 'up_ed_field';
	$o_form->add_data($row);
	$o_form->compile();

	$o_button = new itButton(get_const('BUTTON_UP'), 'ajaxsubmit', ['class' => 'admin', 'form' => $o_form->form_id(), 'ajax'=>"editor_edreload('#".itEditor::_container_id($row)."');"], 'gray' );	
	$result = $o_form->code().$o_button->code();
	unset($o_button, $o_form);
	return $result;	
	}
?>
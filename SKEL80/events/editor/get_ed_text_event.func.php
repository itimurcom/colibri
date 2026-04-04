<?php
// ================ CRC ================
// version: 1.15.04
// hash: e3fc48babbca026892680425cea7d11c2c7d77f4b54ed2a33e34135d7ea322e8
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку для добавления тектового поля			       *
//..............................................................................
function get_ed_text_event($row)
	{	
	$o_form = new itForm2();
	$row['op'] = 'add_ed_text';
	$o_form->add_data($row);
	$o_form->compile();

	$o_button = new itButton(get_const('BUTTON_ED_TEXT'), 'ajaxsubmit', ['class' => 'admin', 'form' => $o_form->form_id(), 'ajax'=>"editor_edreload('#".itEditor::_container_id($row)."');"], 'blue' );	
	$result = $o_form->code().$o_button->code();
	unset($o_button, $o_form);
	return $result;	
	}
?>
<?
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
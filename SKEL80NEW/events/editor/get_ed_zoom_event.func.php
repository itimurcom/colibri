<?
//..............................................................................
// возвращает кнопку переключения размера аватарки в тексте                    *
//..............................................................................
function get_ed_zoom_event($row)
	{	
	$o_form = new itForm2();

	$row['op'] = 'ed_zoom';
	$o_form->add_data($row);
	$o_form->compile();

	$o_button = new itButton(get_const('BUTTON_ED_SWITCH'), 'ajaxsubmit', ['class' => 'admin', 'form' => $o_form->form_id(), 'ajax'=>"editor_edreload('#".itEditor::_container_id($row)."');"], 'brown' );	
	$result = $o_form->code().$o_button->code();
	unset($o_button, $o_form);
	return $result;	
	}
?>
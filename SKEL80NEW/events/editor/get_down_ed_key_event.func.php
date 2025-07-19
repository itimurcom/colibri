<?
//..............................................................................
// возвращает кнопку перемещения поля вверх                                    *
//..............................................................................
function get_down_ed_key_event($row)
	{
	if ($row['ed_key'] == $row['last_field']) return;

	$o_form = new itForm2();

	$row['op'] = 'down_ed_field';
	$o_form->add_data($row);
	$o_form->compile();

	$o_button = new itButton(get_const('BUTTON_DOWN'), 'ajaxsubmit', ['class' => 'admin', 'form' => $o_form->form_id(), 'ajax'=>"editor_edreload('#".itEditor::_container_id($row)."');"], 'gray' );	
	$result = $o_form->code().$o_button->code();
	unset($o_button, $o_form);
	return $result;	
	}
?>
<?
//..............................................................................
// возвращает кнопку перемещения поля формы вверх
//..............................................................................
function f2_up_field_event($row)
	{
	if ($row['ed_key'] == 0) return;

	$o_form = new itForm2();

	$row['op'] = 'up_f2_field';
	$o_form->add_data($row);

	$o_button = new itButton(get_const('BUTTON_UP'), 'ajaxsubmit', ['class' => 'admin', 'form' => $o_form->form_id(), 'ajax'=>"f2_edreload('#".itForm2::_container_id($row)."');"], 'gray' );
	$result = $o_form->_view().$o_button->code();
	unset($o_button, $o_form);
	return $result;	
	}
?>
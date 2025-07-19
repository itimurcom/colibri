<?
//..............................................................................
// возвращает кнопку перемещения изображения галлереи вверх по списку         *
//..............................................................................
function get_gal_up_event($row, $gallery_id)
	{
	if ($gallery_id==0) return;
	$o_form = new itForm2();

	$row['gallery_id'] = $gallery_id; 
	$row['op'] = 'gal_up';
	$o_form->add_data($row);	

	$o_form->compile();

	$o_button = new itButton(get_const('BUTTON_LEFT'), 'ajaxsubmit', ['class' => 'admin', 'form' => $o_form->form_id(), 'ajax'=>"editor_edreload('#".itEditor::_container_id($row)."');"], 'gray' );	
	$result = $o_form->code().$o_button->code();
	unset($o_button, $o_form);
	return $result;	
	}
?>
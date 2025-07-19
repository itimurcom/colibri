<?php
// ================ CRC ================
// version: 1.15.04
// hash: 4ee6db860b1f266b695765be3c51c654677a943a585b83846d82d19152211663
// date: 21 May 2021 10:57
// ================ CRC ================
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
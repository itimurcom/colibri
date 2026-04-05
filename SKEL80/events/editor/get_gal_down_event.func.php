<?php
// ================ CRC ================
// version: 1.15.04
// hash: 1eebca1cd788dc9de0ad19ed3e57b78c8c3162860330d0b00c606f19e905bdd2
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку перемещения изображения галлереи вниз по списку           *
//..............................................................................
function get_gal_down_event($row, $gallery_id)
	{
	if ($gallery_id == (count($row['value'])-1)) return;
	$o_form = new itForm2();

	$row['gallery_id'] = $gallery_id; 
	$row['op'] = 'gal_down';
	$o_form->add_data($row);
	$o_form->compile();

	$o_button = new itButton(get_const('BUTTON_RIGHT'), 'ajaxsubmit', ['class' => 'admin', 'form' => $o_form->form_id(), 'ajax'=>"editor_edreload('#".itEditor::_container_id($row)."');"], 'gray' );	
	$result = $o_form->code().$o_button->code();
	unset($o_button, $o_form);
	return $result;	
	}
?>
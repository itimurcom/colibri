<?php
// ================ CRC ================
// version: 1.35.02
// hash: 2e6c7147141a30c5fe6eafc9eef4d82cb0681fc5fd21214445d835066a7a52c0
// date: 20 September 2019 15:39
// ================ CRC ================
//..............................................................................
// возвращает поле ввода телефона
//..............................................................................
function _f2_phone_view(&$row)
	{
	$o_input = new itInput2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;

	}
?>
<?php
// ================ CRC ================
// version: 1.35.02
// hash: a8115d19c8e46f598449e83ef31f206fd6a3c1dbda0ca0b2a718410c0ef813b0
// date: 20 September 2019 15:39
// ================ CRC ================
//..............................................................................
// возвращает редактор поля ввода телефона
//..............................................................................
function _f2_phone_edit(&$row)
	{
	$o_input = new itInput2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;

	}
?>
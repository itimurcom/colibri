<?php
// ================ CRC ================
// version: 1.35.02
// hash: a6831b663c0c9486a9744017d13e37ea34a3c282228295eb5ac968bb017a56fa
// date: 20 September 2019 15:39
// ================ CRC ================
//..............................................................................
// возвращает поле ввода
//..............................................................................
function _f2_input_view(&$row)
	{
	$o_input = new itInput2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;
	}
?>
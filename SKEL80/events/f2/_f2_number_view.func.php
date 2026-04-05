<?php
// ================ CRC ================
// version: 1.35.02
// hash: ba7333770978b7b1ec57616d45099c68044bb60745639edebb43039614252bd9
// date: 20 September 2019 15:39
// ================ CRC ================
//..............................................................................
// возвращает поле ввода цифр
//..............................................................................
function _f2_number_view(&$row)
	{
	$o_input = new itInput2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;
	}
?>
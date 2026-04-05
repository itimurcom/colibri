<?php
// ================ CRC ================
// version: 1.35.02
// hash: baf798a650bcf600cc0961c44aade0f46e99acc97ad0ad771b6bc6049c54afb7
// date: 20 September 2019 15:39
// ================ CRC ================
//..............................................................................
// возвращает поле ввода почтового адресса
//..............................................................................
function _f2_email_view(&$row)
	{
	$o_input = new itInput2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;
	}
?>
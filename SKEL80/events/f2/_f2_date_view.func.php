<?php
// ================ CRC ================
// version: 1.35.02
// hash: 6daddcd63c932426101e471e04db7bea674e022447dc5ae101f8d31b26da8859
// date: 20 September 2019 15:39
// ================ CRC ================
//..............................................................................
// возвращает поле даты
//..............................................................................
function _f2_date_view(&$row)
	{
	$o_input = new itDate2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;
	}
?>
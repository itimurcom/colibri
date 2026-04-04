<?php
// ================ CRC ================
// version: 1.35.02
// hash: 35e146584926af19e4b2c21ed9ffbbed07e0b4cf5e448fc23fbaba9b0b282230
// date: 10 March 2021  9:27
// ================ CRC ================
//..............................................................................
// возвращает поле времени
//..............................................................................
function _f2_time_view(&$row)
	{
	$o_input = new itTime2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;
	}
?>
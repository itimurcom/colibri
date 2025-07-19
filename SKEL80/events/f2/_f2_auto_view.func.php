<?php
// ================ CRC ================
// version: 1.35.03
// hash: 47e8ad7d469db0a53e1968cb443488569d1149693359f3b81bb702ab6d21e546
// date: 09 September 2020 16:28
// ================ CRC ================
//..............................................................................
// возвращает поле поля поиска по набору
//..............................................................................
function _f2_auto_view(&$row)
	{
	$o_input = new itAutoSelect2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;
	unset($o_input);
	return $result;
	}
?>
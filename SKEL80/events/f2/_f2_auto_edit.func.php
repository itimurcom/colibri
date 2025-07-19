<?php
// ================ CRC ================
// version: 1.35.02
// hash: 941fe5dedc521d49f6240e88e3bce791de2b8ad04ed6ed5c04affe3428ff2fed
// date: 20 September 2019 15:39
// ================ CRC ================
//..............................................................................
// возвращает редактор поля поиска по набору
//..............................................................................
function _f2_auto_edit($row)
	{
	$o_input = new itAutoSelect2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;
	}
?>
<?php
// ================ CRC ================
// version: 1.35.02
// hash: 2c800c72d63aba3ecb3a9d2853cf1754f9965a990143f04fb409749ba612ec52
// date: 20 September 2019 15:39
// ================ CRC ================
//..............................................................................
// возвращает поле выборки
//..............................................................................
function _f2_set_view(&$row)
	{
	$o_input = new itSet2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;
	}
?>
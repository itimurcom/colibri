<?php
// ================ CRC ================
// version: 1.35.02
// hash: 56f08e175405c2caf4d46e1ad8c0ac6d54b3ff52487bb25566afcf4b930ea2a3
// date: 20 September 2019 15:39
// ================ CRC ================
//..............................................................................
// возвращает редактор поля воода
//..............................................................................
function _f2_area_edit(&$row)
	{
	$o_input = new itArea2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;
	}
?>
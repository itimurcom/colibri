<?php
// ================ CRC ================
// version: 1.35.02
// hash: a9fdfa6ee2500830f8c30f9eff85a730ee8d3a3299b8aec12b36eafef29503be
// date: 20 September 2019 15:39
// ================ CRC ================
//..............................................................................
// возвращает редактор поля ввода
//..............................................................................
function _f2_input_edit(&$row)
	{
	$o_input = new itInput2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;
	}
?>
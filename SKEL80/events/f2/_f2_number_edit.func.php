<?php
// ================ CRC ================
// version: 1.35.02
// hash: fdacdca107d03f9d821f60a268c35a82f317276620ffc0fad79fc8b214313a3e
// date: 20 September 2019 15:39
// ================ CRC ================
//..............................................................................
// возвращает редактор поля ввода цифр
//..............................................................................
function _f2_number_edit(&$row)
	{
	$o_input = new itInput2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;
	}
?>
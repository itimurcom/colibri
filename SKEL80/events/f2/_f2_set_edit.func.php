<?php
// ================ CRC ================
// version: 1.35.02
// hash: aa3de3155cfd67fc62dabdd837f84e65a5dee2b3f47392654d606b3eae188232
// date: 20 September 2019 15:39
// ================ CRC ================
//..............................................................................
// возвращает редактор поля выборки
//..............................................................................
function _f2_set_edit(&$row)
	{
	$o_input = new itSet2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;
	}
?>
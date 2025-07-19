<?php
// ================ CRC ================
// version: 1.35.02
// hash: b83fd7238ed356f2c003534ade8c17bb801cbdb4f2fb4a8e4b9385f1392c1a3d
// date: 20 September 2019 15:39
// ================ CRC ================
//..............................................................................
// возвращает редактор поля даты
//..............................................................................
function _f2_date_edit(&$row)
	{
	$o_input = new itDate2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;
	}
?>
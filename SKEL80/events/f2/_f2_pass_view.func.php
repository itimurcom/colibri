<?php
// ================ CRC ================
// version: 1.35.02
// hash: 56c3683503a2828b9832a769c4b58b1b02139bc04abdacb1d3726256400ef181
// date: 20 September 2019 15:39
// ================ CRC ================
//..............................................................................
// возвращает поле воода пороля
//..............................................................................
function _f2_pass_view(&$row)
	{
	// поправим установки
	$row['type'] = 'password';
	
	$o_input = new itInput2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;
	}
?>
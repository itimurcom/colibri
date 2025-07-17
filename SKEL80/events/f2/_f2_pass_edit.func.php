<?php
// ================ CRC ================
// version: 1.35.02
// hash: 2a70593341ba3748b61355b2af9f792f0ceda6ca9816c13d5d2c8ce90c363fb8
// date: 20 September 2019 15:39
// ================ CRC ================
//..............................................................................
// возвращает редактор поля воода пороля
//..............................................................................
function _f2_pass_edit(&$row)
	{
	// поправим установки
	$row['type'] 		= 'password';
	
	$o_input = new itInput2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;
	}
?>
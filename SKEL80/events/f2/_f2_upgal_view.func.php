<?php
// ================ CRC ================
// version: 1.35.02
// hash: 742409eac94635f5962f0a30ab2717c0340540b52e823fa1da84a15a3c7eb7db
// date: 10 March 2021  9:27
// ================ CRC ================
//..............................................................................
// возвращает поле галлереи изображений
//..............................................................................
function _f2_upgal_view(&$row)
	{
	$o_upgal = new itUpGal2($row);
	$result = $o_upgal->code();
	$row['element'] = $o_upgal->element_id;
	$row['result'] = $result;	
	unset($o_upgal);
	return $result;
	}
?>
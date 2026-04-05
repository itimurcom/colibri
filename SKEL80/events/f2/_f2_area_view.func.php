<?php
// ================ CRC ================
// version: 1.35.02
// hash: d67169f23e85dc65c0add548e50413c03ef2317780897e8e4b77d3033b188de5
// date: 20 September 2019 15:39
// ================ CRC ================
//..............................................................................
// возвращает поле воода
//..............................................................................
function _f2_area_view(&$row)
	{
	$o_input = new itArea2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;
	}
?>
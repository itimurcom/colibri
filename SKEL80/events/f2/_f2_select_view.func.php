<?php
// ================ CRC ================
// version: 1.35.02
// hash: 3b44d94f07fc70f97c278510c65e41c65df0b5b7d3faa22e62f967279b215afb
// date: 20 September 2019 15:39
// ================ CRC ================
//..............................................................................
// возвращает поле селектора списка
//..............................................................................
function _f2_select_view(&$row)
	{
	$o_input = new itSelect2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	return $result;
	}
?>
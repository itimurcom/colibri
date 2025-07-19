<?
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
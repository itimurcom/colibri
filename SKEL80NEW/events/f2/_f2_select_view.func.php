<?
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
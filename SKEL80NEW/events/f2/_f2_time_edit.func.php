<?
//..............................................................................
// возвращает оедактор поля времени
//..............................................................................
function _f2_time_edit(&$row)
	{
	$o_input = new itTime2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;
	}
?>
<?
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
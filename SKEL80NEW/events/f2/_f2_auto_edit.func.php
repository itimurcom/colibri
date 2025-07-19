<?
//..............................................................................
// возвращает редактор поля поиска по набору
//..............................................................................
function _f2_auto_edit($row)
	{
	$o_input = new itAutoSelect2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;
	}
?>
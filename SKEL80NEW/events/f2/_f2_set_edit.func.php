<?
//..............................................................................
// возвращает редактор поля выборки
//..............................................................................
function _f2_set_edit(&$row)
	{
	$o_input = new itSet2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;
	}
?>
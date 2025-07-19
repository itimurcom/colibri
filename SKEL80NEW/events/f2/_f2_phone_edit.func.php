<?
//..............................................................................
// возвращает редактор поля ввода телефона
//..............................................................................
function _f2_phone_edit(&$row)
	{
	$o_input = new itInput2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;

	}
?>
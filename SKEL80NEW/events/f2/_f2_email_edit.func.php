<?
//..............................................................................
// возвращает редактор поля ввода почтового адреса
//..............................................................................
function _f2_email_edit(&$row)
	{
	$o_input = new itInput2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;

	}
?>
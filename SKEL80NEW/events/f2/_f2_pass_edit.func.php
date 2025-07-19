<?
//..............................................................................
// возвращает редактор поля воода пороля
//..............................................................................
function _f2_pass_edit(&$row)
	{
	// поправим установки
	$row['type'] 		= 'password';
	
	$o_input = new itInput2($row);
	$result = $o_input->code();
	$row['element'] = $o_input->element_id;
	$row['result'] = $result;	
	unset($o_input);
	return $result;
	}
?>
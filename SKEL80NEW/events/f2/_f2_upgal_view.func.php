<?
//..............................................................................
// возвращает поле галлереи изображений
//..............................................................................
function _f2_upgal_view(&$row)
	{
	$o_upgal = new itUpGal2($row);
	$result = $o_upgal->code();
	$row['element'] = $o_upgal->element_id;
	$row['result'] = $result;	
	unset($o_upgal);
	return $result;
	}
?>
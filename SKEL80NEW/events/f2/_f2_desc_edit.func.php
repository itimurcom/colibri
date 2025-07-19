<?
//..............................................................................
// возвращает редакто поля описания данных
//..............................................................................
function _f2_desc_edit(&$row)
	{
	$row['element'] = "{$row['form_id']}-desc-{$row['ed_key']}";		
	$o_desc = new itDesc2($row);
	$result = $o_desc->code();
	unset($o_desc);
	return $result;
/*	return is_array($row['value'])
		? get_field_by_lang($row['value'], CMS_LANG, 'NO_DATA')
		: get_const($row['value']);
*/	}
?>
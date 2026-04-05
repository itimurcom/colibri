<?php
// ================ CRC ================
// version: 1.35.02
// hash: bb5d8c173d73b2e062e418958209e407c36430cb33bede94facada29d982037c
// date: 10 March 2021  9:27
// ================ CRC ================
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
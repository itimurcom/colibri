<?php
// ================ CRC ================
// version: 1.35.03
// hash: b5ce48fcb188f303f6f1827b63045476b48c7939d807b710cc0d25f8b4024365
// date: 10 March 2021  9:27
// ================ CRC ================
//..............................................................................
// возвращает блок кода
//..............................................................................
function _f2_code_view(&$row)
	{
	$row['element'] = "{$row['form_id']}-code-{$row['ed_key']}";
	$result = is_array($row['value'])
		? get_field_by_lang($row['value'], CMS_LANG, 'NO_DATA')
		: get_const($row['value']);
	$result =  isset($row['element_id'])
		? TAB."<div id='{$row['element_id']}'>".$result.TAB."</div>"
		: $result; 
	return $result;
	}
?>
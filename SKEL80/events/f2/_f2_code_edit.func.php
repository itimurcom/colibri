<?php
// ================ CRC ================
// version: 1.35.03
// hash: 7bc08983ed7c9a5e86a2a7f454ff91ea5fac45bad92340fe9a6676bb5b551230
// date: 10 March 2021  9:27
// ================ CRC ================
//..............................................................................
// возвращает редактор блока кода
//..............................................................................
function _f2_code_edit(&$row)
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
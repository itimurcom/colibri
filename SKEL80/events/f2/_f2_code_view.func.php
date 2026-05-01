<?php
// возвращает блок кода
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
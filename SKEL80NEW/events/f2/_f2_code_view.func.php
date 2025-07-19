<?
//..............................................................................
// возвращает блок кода
//..............................................................................
function _f2_code_view(&$row)
	{
	$row['element'] = isset($row['element_id']) ? $row['element_id'] : "{$row['form_id']}-code-{$row['ed_key']}";
	$class = isset($row['class']) ? " class='{$row['class']}'" : NULL;
	$result = is_array($row['value'])
		? get_field_by_lang($row['value'], CMS_LANG, 'NO_DATA')
		: get_const($row['value']);
	$result =  isset($row['element_id'])
		? TAB."<div{$class} id='{$row['element_id']}'>".$result.TAB."</div>"
		: $result; 
	return $result;
	}
?>
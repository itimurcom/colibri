<?php
// ================ CRC ================
// version: 1.35.02
// hash: bdbcba3e0ad89e7d3524273fef1e70684571c62d4c68e85ef83093ab760d5e38
// date: 10 March 2021  9:27
// ================ CRC ================
//..............................................................................
// возвращает внешний вид заголовка формы
//..............................................................................
function _f2_title_view(&$row)
	{
	$row['element'] = "{$row['form_id']}-title-{$row['ed_key']}";
	return !empty($value_str = (is_array($row['value'])
			? get_field_by_lang($row['value'], CMS_LANG, 'NO_DATA')
			: get_const($row['value'])))
		? $value_str
		: get_const('NO_TITLE');
	}
?>
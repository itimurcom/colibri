<?php
// ================ CRC ================
// version: 1.35.02
// hash: 6d57cb2159399c9ba7d270005c87ef9df18e554ba2e30d506eecb46273496e30
// date: 10 March 2021  9:27
// ================ CRC ================
//..............................................................................
// возвращает редактор внешнего вида заголовка формы
//..............................................................................
function _f2_title_edit(&$row)
	{
	$row['element'] = "{$row['form_id']}-title-{$row['ed_key']}";
	return !empty($value_str = (is_array($row['value'])
			? get_field_by_lang($row['value'], CMS_LANG, 'NO_DATA')
			: get_const($row['value'])))
		? $value_str
		: get_const('NO_TITLE');
	}
?>
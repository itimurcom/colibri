<?
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
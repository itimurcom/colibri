<?
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
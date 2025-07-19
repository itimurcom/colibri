<?
//..............................................................................
// вовзращает значение поля для конкретного языка
//..............................................................................
function get_field_by_lang($field_rec=NULL, $lang=CMS_LANG, $no_title='NO_TITLE')
	{
	if (isset($field_rec[$lang]) and ($field_rec[$lang]!=''))
		{
		return $field_rec[$lang];
		} else return get_const(ready_val($no_title));
	}
?>
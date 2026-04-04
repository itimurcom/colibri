<?php
// ================ CRC ================
// version: 1.35.02
// hash: 9d347bb10a82bca27a9ecafc76005548aa42c3cc30ea38cce8f1f1260cf56d73
// date: 09 September 2019  7:09
// ================ CRC ================
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
<?php
// ================ CRC ================
// version: 1.35.03
// hash: 4e1e975780437d2fab1fd1e3b1e8a4782173ccedaa59c49ebffd52f89f534b30
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// проверяет или текст JSON
//..............................................................................
function isJson($json_string)
	{
	if (is_array($json_string)) {
		return false;
		}
	return is_object(json_decode($json_string)) OR is_array(json_decode($json_string));
	}
?>
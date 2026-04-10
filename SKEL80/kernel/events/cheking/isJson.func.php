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
	if (is_array($json_string) || is_object($json_string) || is_null($json_string))
		{
		return false;
		}

	$json_string = trim((string)$json_string);
	if ($json_string === '' || !in_array($json_string[0], ['{', '['], true))
		{
		return false;
		}

	json_decode($json_string, true);
	return json_last_error() === JSON_ERROR_NONE;
	}
?>

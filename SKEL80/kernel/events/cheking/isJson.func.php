<?php
// проверяет или текст JSON
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

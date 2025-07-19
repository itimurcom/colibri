<?
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
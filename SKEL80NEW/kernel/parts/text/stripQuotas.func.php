<?
//..............................................................................
// убирает все кавычки
//..............................................................................
function stripQuotas($text)
	{
	return str_replace(['\"',"'",'"'],'',$text);
	}
?>
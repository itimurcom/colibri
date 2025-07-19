<?
//..............................................................................
// возвращает массовую замену в строке по ключам
//..............................................................................
function mstr_replace($options=NULL, $var=NULL)	{
	return is_array($options) ? str_replace(array_keys($options), array_values($options), $var) : NULL;
	}
?>
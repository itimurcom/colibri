<?
//..............................................................................
// возвращает данных места вызоа функции
//..............................................................................
function debug_point($text=NULL, $backtrace=NULL)
	{
	if (!is_array($backtrace)) return;
	$result = NULL;
	foreach ($backtrace as $caller)
		{
		$result .= "<br/><font color='blue'>file:</font>&nbsp;<small>{$caller['file']}</small><br/><font color='blue'>line:</font>&nbsp;{$caller['line']}<br/>";
		}
	
	return "{$text}{$result}";
	}
?>
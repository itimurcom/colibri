<?php
// ================ CRC ================
// version: 1.35.02
// hash: f718c9dd5d2ec0ac3b8bf80750da493b9821dcf6809ad53b87cdd747bd710021
// date: 09 September 2019  7:09
// ================ CRC ================
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
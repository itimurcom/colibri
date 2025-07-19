<?
//..............................................................................
// возвращает укороченную строчку + троеточие текста для вставки в поля
//..............................................................................
function cut_str($str, $max=DEFAULT_STR_CUT, $forced=false)
	{
	return get_str_cut($str, $max, $forced);
	}

function get_str_cut($str, $max=DEFAULT_STR_CUT, $forced=false)
	{
	if ($forced==false)
		{
		while ( $max<(mb_strlen($str,'UTF-8')) and (mb_substr($str,$max,1)!=' ') )
			{
			$max++;
			}
		}
	
	if (mb_strlen($str,'UTF-8') > $max)
		{
		return mb_substr($str,0,$max,'UTF-8')."...";
		} else return $str;
	}
?>
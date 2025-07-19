<?
//..............................................................................
// возвращает локальное количество дней
//..............................................................................
function local_days_str($days=0)
	{
	$days = intval($days);

	definition([
		'DAY_ONE'	=> 'день',
		'DAY_TWO'	=> 'дня',
		'DAY_ALL'	=> 'дней',			
		]);

	if ($days == 1)
		{
		return "1 ".get_const('DAY_ONE');
        	}
        	
        if ( in_array($days, [2,3,4]) )
        	{ 
		return "{$days} ".get_const('DAY_TWO');
		}
	
	return "$days ".get_const('DAY_ALL');
	}
?>
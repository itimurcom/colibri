<?php
// ================ CRC ================
// version: 1.45.02
// hash: a763567b5ee2b794c0550bd28fd4f8b5a0991f667703f3a1d3ccc892fa1107e1
// date: 01 August 2020 17:27
// ================ CRC ================
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
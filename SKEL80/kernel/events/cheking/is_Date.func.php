<?php
// ================ CRC ================
// version: 1.35.02
// hash: 9a256b857ecc9892e0ca65ce4a7d4a95db805d2d12e0e227f58a6e0d143f78d3
// date: 09 September 2019  7:09
// ================ CRC ================
//..............................................................................
// проверяет является ли текст датой
//..............................................................................
function is_Date($str)
	{ 
        $str = str_replace('/', '-', $str);     
        $stamp = strtotime($str);
        if (is_numeric($stamp))
        	{
		$month = date( 'm', $stamp ); 
	    	$day   = date( 'd', $stamp ); 
	    	$year  = date( 'Y', $stamp ); 
		return checkdate($month, $day, $year); 
        	}  
        return false; 
	}

//..............................................................................
// заглушка
//..............................................................................
function isDate($str)
	{
	return is_Date($str);
	}
?>
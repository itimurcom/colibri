<?
//..............................................................................
// возвращает случайный набор символов определенной длины
//..............................................................................
function random_str($length = 1)
	{
	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++)
		{
        	$randomString .= $characters[rand(0, $charactersLength - 1)];
    		}
    	return $randomString;
	}
?>
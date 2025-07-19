<?
//..............................................................................
// определения типа картинки по названию файла
//..............................................................................
function get_picture_tech_type($addr)
	{
	global $pic_tech;
	$name = basename($addr);
	foreach ($pic_tech as $key=>$row)
		{	
		if (strstr($name,$row['name']))
			{
			return $key;
			}
		}

	}
?>
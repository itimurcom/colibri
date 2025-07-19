<?
//..............................................................................
// определения типа картинки по названию файла
//..............................................................................
function get_picture_tech($addr)
	{
	global $pic_tech;
	$name = basename($addr);
	foreach ($pic_tech as $row)
		{	
		if (strstr($name,$row['name']))
			{
			return $row['name'];
			}
		}

	}
?>
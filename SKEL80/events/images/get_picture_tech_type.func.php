<?php
// ================ CRC ================
// version: 1.15.02
// hash: cf55fcaf66cf8c1fd7ca6b4e07cc833e13c7f89f244d03585bc72db9fd4151ff
// date: 16 September 2018 19:58
// ================ CRC ================
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
<?php
// ================ CRC ================
// version: 1.15.02
// hash: 13d75e677d15bec703da96321fc13210894564564816498bf815ded768147751
// date: 16 September 2018 19:58
// ================ CRC ================
//..............................................................................
// возвращает оригинальное название картинки
//..............................................................................
function clear_picture_tech($picture_name)
	{

	if ($tech = get_picture_tech($picture_name))
		{
		$picture_name = str_replace($tech,'',$picture_name);
		}

	return $picture_name;
	}
?>
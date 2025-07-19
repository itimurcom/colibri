<?php
// ================ CRC ================
// version: 1.15.02
// hash: 4434b648361ca8aa6e2c7ca1dcb49db80a3096020c8482fb18f9c12f6c0b7e65
// date: 16 September 2018 19:58
// ================ CRC ================
//..............................................................................
// любое изображение из массива изображений "нет картинки"
//..............................................................................
function get_random_noavatar()
	{
	global $no_avatar;
	$index = rand (1, count($no_avatar));
	$result = "themes/".CMS_THEME."/images/".$no_avatar[ $index -1 ];
	return $result;
	}
?>
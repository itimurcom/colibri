<?php
// ================ CRC ================
// version: 1.15.02
// hash: f5b9c4724c403db23a3f97fa3d255da22411f2ba6898867bf56fa58dd3fac0f1
// date: 16 September 2018 19:58
// ================ CRC ================
//..............................................................................
// функция заглушка для объектной модели itResizer
//..............................................................................
function magic_resizer($input_image_name, $output_image_name, $new_x, $new_y, $crop=0, $logo_name='', $quality = 100, $logo_place=NULL)
	{
	$o_resizer = new itResizer($input_image_name, $output_image_name, $new_x, $new_y, $crop, $logo_name, $quality, $logo_place);
	$o_resizer->compile();
	unset($o_resizer);
	return $output_image_name;
	}
?>
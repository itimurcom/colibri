<?php
// ================ CRC ================
// version: 1.15.04
// hash: 6173a567db0ab638e53c03277ee4a3ea289a8d4b057bd88761dc2c4f31e580d2
// date: 17 September 2019 17:56
// ================ CRC ================
//..............................................................................
// отображает изображение, которое передается из скрипта
//..............................................................................
function show_image($file_name='')
	{
	$image_info = getimagesize($file_name);

	// определим тип изображения
 	$image_type 	= $image_info[2];

	// создадим изображение того типа, которому соотсветстует файл изображения
	if ( $image_type == IMAGETYPE_JPEG )
		{
         	$img = imagecreatefromjpeg($file_name);
		}
	elseif ( $image_type == IMAGETYPE_GIF )
		{
		$img = imagecreatefromgif($file_name);
		imageAlphaBlending($img, false);
		imageSaveAlpha ($img, true);
		}
	elseif ( $image_type == IMAGETYPE_PNG )
		{
		$img = imagecreatefrompng($file_name);
		imageAlphaBlending($img, false);
		imageSaveAlpha ($img, true);		
		}

//header('Cache-Control: no-cache');
header('Pragma: public');
header('Cache-Control: max-age=86400');
header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));		
	
	// выводим изоражение в файл заданного качества (для jpeg изображений) и того же типа, что и входное изображение
	if ( $image_type == IMAGETYPE_JPEG )
		{
		header("Content-Type: image/jpeg");   
         	imagejpeg($img);
         	}
	elseif ( $image_type == IMAGETYPE_GIF )
		{
		header("Content-Type: image/gif");   
         	imagegif($img);
		}
	elseif ( $image_type == IMAGETYPE_PNG )
		{
		header("Content-Type: image/png");   
		imagepng($img);
		}

	}
?>
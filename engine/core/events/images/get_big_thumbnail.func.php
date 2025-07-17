<?php
// ================ CRC ================
// version: 1.15.03
// hash: 4257219908460d7bd3e8d33c85a27cba6b18d26f63ab018fbe8308222bf584b6
// date: 24 March 2019 19:20
// ================ CRC ================
//..............................................................................
// возвращает код картинки нужного типа с возможностью открыть на полный экран
//.............................................................. ................
function get_big_thumbnail($file_name='', $class='', $gallery_name='gallery', $title='', $type='EDIMAGE', $seo=NULL)
	{
	global $bigimg_num;

	$caption = isset($file_name['caption']) ? $file_name['caption'] : NULL;
	
	if (is_array($file_name))
		{
		// получили массив
		$class 		= ready_val($file_name['class'], NULL);
		$gallery_name	= ready_val($file_name['gallery'], 'gallery');
		$title		= ready_val($file_name['title'], NULL);
		$type		= ready_val($file_name['type'], 'EDIMAGE');
		$seo 		= ready_val($file_name['seo'], NULL);
		$file_name	= ready_val($file_name['src'], NULL);
		}


	$thumbnail = get_thumbnail($file_name, $type);
	$bigimage  = get_thumbnail($file_name, 'ITEM_BIG');

	$rect = getimagesize(PICTURE_ROOT.basename($bigimage));

	if ($title!='')
		{
		$title = htmlentities($title,ENT_QUOTES,'UTF-8');
		}

	$seo_str = !is_null($seo) ? $seo_str =" title='{$seo}'" : NULL;

	$result = TAB."<a class=\"fancybox\" rel=\"{$gallery_name}\" href=\"{$bigimage}\" data-size=\"{$rect[0]}x{$rect[1]}\" data-caption=\"{$caption}\" title=\"{$title}\">".
		TAB."<img class=\"{$class}\" src=\"{$thumbnail}\" alt=\"{$title}\"{$seo_str}/>".
		TAB."</a>";
	return $result;
	}
?>
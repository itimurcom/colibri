<?
//..............................................................................
// возвращает код картинки нужного типа с возможностью открыть на полный экран
//.............................................................. ................
function get_big_thumbnail($file_name='', $class='', $gallery_name='gallery', $title='', $type='EDIMAGE', $seo=NULL)
	{
	global $bigimg_num;
	
	if (is_array($file_name)) {
		// получили массив
		$class 		= ready_val($file_name['class'], NULL);
		$gallery_name	= ready_val($file_name['gallery'], 'gallery');
		$title		= ready_val($file_name['title'], NULL);
		$type		= ready_val($file_name['type'], 'EDIMAGE');
		$seo 		= ready_val($file_name['seo'], NULL);
	
		$force		= ready_val($file_name['force'], NULL);
		$f_name		= ready_val($file_name['f_name'], NULL);
		$f_thumb	= ready_val($file_name['f_thumb'], NULL);

		$file_name	= ready_val($file_name['src'], NULL);
		}

	$caption = isset($file_name['caption']) ? $file_name['caption'] : NULL;

	$thumbnail = $force ? $f_thumb	: get_thumbnail($file_name, $type);
	$bigimage  = $force ? $f_name	: get_thumbnail($file_name, 'ITEM_BIG');

	$rect = $force ?  getimagesize($f_name) : getimagesize(PICTURE_ROOT.basename($bigimage));

	$rect[0] *= 2;
	$rect[1] *= 2;

	if ($title!='') {
		$title = htmlentities($title,ENT_QUOTES,'UTF-8');
		}

	$seo_str = !is_null($seo) ? $seo_str =" title='{$seo}'" : NULL;

	$result = TAB."<a class=\"fancybox\" rel=\"{$gallery_name}\" href=\"{$bigimage}\" data-size=\"{$rect[0]}x{$rect[1]}\" data-caption=\"{$caption}\" title=\"{$title}\">".
		TAB."<img class=\"fancy-img {$class}\" src=\"{$thumbnail}\" title=\"{$title}\" alt=\"{$title}\"{$seo_str}/>".
		TAB."</a>";
	return $result;
	}
?>
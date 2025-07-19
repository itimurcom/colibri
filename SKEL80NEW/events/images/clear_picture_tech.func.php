<?
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
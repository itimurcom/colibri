<?
//..............................................................................
// удаляет символы, которые не используются в названиях файла
//..............................................................................
function clear_file_name($name)
	{
	// уберем нечинаемые символы и предустановки
	$base_name = str_replace(['https://','http://','https://www.','http://www.','www.','//','/'],'',$name);
	$split_arr = explode('?', $base_name);
	$base_name = (is_array($split_arr) AND (count($split_arr)>1))
		? $split_arr[0]
		: $base_name;

	$tech = get_picture_tech($base_name);

	if ($tech!='')
		{
		$base_name = str_replace($tech,'',$base_name);
		}
	

	$filename = preg_replace("~[^a-zA-Z0-9\-\.\,\_\(\)]~", '-', $base_name);

	if (get_file_extension($filename)==false)
		{
		$filename = "$filename.jpg";
		}
	return $filename;
	}
?>
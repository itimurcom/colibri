<?
//..............................................................................
// удаляет все файлы аватарок для изображения
//..............................................................................
function remove_avatars_for_image($name)
	{
	global $pic_tech;

	foreach ($pic_tech as $key => $row)
		{
		$file_name = PICTURE_ROOT."/".basename(get_picture_name($name, $key));
		@unlink($file_name);
		}
	}
?>
<?
//..............................................................................
// возвращает web-ссылку на картинку нужного типа и размера
//..............................................................................
function get_thumbnail($original=NULL, $type='ED_AVATAR', $base64=false)
	{ 
	global $pic_tech; 
	
	if (!isset($pic_tech[$type])) $type='USER_AVATAR';
	
 	$original = empty($original) ? get_random_noavatar() : $original;

	$uploads_file = clear_file_name($original);
	$thumbnail_file = get_picture_name($uploads_file, $type);

	// проверим есть ли аватарка готовая
	if (file_exists(PICTURE_ROOT.$thumbnail_file))
		{
		// файл присутствует - возвращаем результат без преобразования
		$result = $thumbnail_file;
		} else


	// аватарки нет - создадим ее
	if (!file_exists(UPLOADS_ROOT.$uploads_file))
		{
		// файла нет в uploads
		if (file_exists($original))
			{
			// относительный адрес на сайте или no_avatar
			$result = get_avatar($original, $type);
			} else	{	
				if (addr_exists($original))
					{
					// это гребанный адрес в сети копируем
					curl_copy($original, $uploads_file);
					$result = get_avatar($uploads_file, $type);
					} else	{
						// это не адрес и не файл в uploads - тогда на хер
						$result = get_avatar($original, $type);
						}
				}
		} else	{
			// файла в uploads но аватарки нет
			$result = get_avatar(UPLOADS_ROOT.$uploads_file, $type);
			}

	return PICTURE_HTTP.$result;
	}
?>
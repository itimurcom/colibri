<?
//..............................................................................
// корректирует имя загруженного файла и исключает повторную загрузку
//..............................................................................
function check_uploaded_file($clear_name='', $tmp_name='')
	{
	$number=0;
	// проверяем есть ли уже точно такой же такой файл и меняем название в случае чего
	if (file_exists(UPLOADS_ROOT.$clear_name))
		{
		if (md5_file(UPLOADS_ROOT.$clear_name) != md5_file($tmp_name))
			{
			// файлы разные просто совпали имена!
			while (file_exists(UPLOADS_ROOT.$clear_name) and (md5_file(UPLOADS_ROOT.$clear_name) != md5_file($tmp_name)))
				{
				$number++;
				$clear_name = add_dashed_number(remove_dashed_number($clear_name),$number);
				}				
			}
		}
	return $clear_name;
	}
?>
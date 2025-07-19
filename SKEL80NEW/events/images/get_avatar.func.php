<?
//..............................................................................
// создает эскиз нужного типа из папки uploads (файл должен присутствовать)
//..............................................................................
function get_avatar($file_input, $type='ED_AVATAR')
	{
	// проверяем входной фал на наличие и доступность для чтения
	global $pic_tech;
	$type = isset($pic_tech[$type]) ? $type : array_keys($pic_tech)[0];
	
	if (!file_exists($file_input))
		{
		$file_input = UPLOADS_ROOT.$file_input;
		}

	if (!file_exists($file_input))
		{
		$file_input = get_random_noavatar();
		}

	$file_output = get_picture_name(basename($file_input), $type);		

	// без проверок сразу поверх даже если есть файл - избежим проблем
	magic_resizer($file_input,
		PICTURE_ROOT.$file_output,
		$pic_tech[$type]['sx'],
		$pic_tech[$type]['sy'],
		$pic_tech[$type]['crop'],
		ready_val($pic_tech[$type]['logo']),
		$pic_tech[$type]['quality'],
		ready_val($pic_tech[$type]['place']));
        return $file_output;
	}
?>
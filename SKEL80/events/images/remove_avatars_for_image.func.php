<?php
// ================ CRC ================
// version: 1.15.02
// hash: 92f3a863c41017a0e5a5fecd773adcbf83bbb4aa3190972e2ed797872f610d0e
// date: 16 September 2018 19:58
// ================ CRC ================
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
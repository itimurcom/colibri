<?php
// ================ CRC ================
// version: 1.35.02
// hash: 3409ffdb0f80defe3d3c4f2ff9ca8183f7b5a41a674dbecf9b8668031cb23890
// date: 09 September 2019  7:09
// ================ CRC ================
//..............................................................................
// запись строки в лог файл
//..............................................................................
function write_log($str, $log_file=LOG_FILE)
	{
	if ($str==NULL)
		{
		file_put_contents($log_file, date('d F Y (H:i)', time()));
	        } elseif (is_array($str))
			{
			file_put_contents($log_file, "\n\t".print_r($str, true));
			} else file_put_contents($log_file, "\n\t"."\n$str", FILE_APPEND);
	}
?>
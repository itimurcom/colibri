<?
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
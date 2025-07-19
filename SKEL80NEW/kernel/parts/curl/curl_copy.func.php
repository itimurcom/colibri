<?
//..............................................................................
// копирует файл при помощи curl запросов
//..............................................................................
function curl_copy($input_file, $output_file)
	{
	$ch = curl_init($input_file);
	$fp = fopen(UPLOADS_ROOT.$output_file, 'wb');
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_exec($ch);
	curl_close($ch);
	fclose($fp);
	return $output_file;
	}
?>
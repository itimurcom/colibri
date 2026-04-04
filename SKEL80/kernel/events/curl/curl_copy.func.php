<?php
// ================ CRC ================
// version: 1.35.02
// hash: ffc7c451a2fbcc08023e3997cfd44ab2af7407a5bf38058514455748e1f6e3ba
// date: 09 September 2019  7:09
// ================ CRC ================
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
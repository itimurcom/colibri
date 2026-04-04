<?php
// ================ CRC ================
// version: 1.35.02
// hash: 8dea8d1e0955ef34144f84c94baa11cc38a1e44943d966b9f96d138a816b7eb1
// date: 09 September 2019  7:09
// ================ CRC ================
//..............................................................................
// возвращает файл при помощи curl запросов
//..............................................................................
function curl_file_get_contents($url)
	{
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);

	$data = curl_exec($ch);
	curl_close($ch);

	return $data;
	}
?>
<?php
// ================ CRC ================
// version: 1.35.02
// hash: d14ec951c3e0dd26ef3af13076f7c4ddac3e7a9865bd952bcd0107284416c505
// date: 09 September 2019  7:09
// ================ CRC ================
//..............................................................................
// возвращает результат GET запроса, эмулирующего работу браузера
//..............................................................................
function http_get($url, $params, $parse = true)
	{
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url . '?' . urldecode(http_build_query($params)));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);

        curl_close($curl);
        if ($parse)
		{
		$result = json_decode($result, true);
		}
	return $result;
	}
?>
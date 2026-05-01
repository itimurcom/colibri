<?php
// возвращает результат GET запроса, эмулирующего работу браузера
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
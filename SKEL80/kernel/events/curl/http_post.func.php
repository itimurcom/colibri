<?php
// ================ CRC ================
// version: 1.35.02
// hash: 5695dd8ba0bdf40afe20d3311d750954cbb8613018e01ce8ffd1ccc8cf24c0d9
// date: 09 September 2019  7:09
// ================ CRC ================
//..............................................................................
// возвращает результат POST запроса, эмулирующего работу браузера
//..............................................................................
function  http_post($url, $params, $parse = true)
	{
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/11.0.696.71 Safari/534.24");  

        $result = curl_exec($curl);

        curl_close($curl);
        if ($parse)
		{
		$result = json_decode($result, true);
		}
        return $result;
	}
?>
<?
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
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X x.y; rv:42.0) Gecko/20100101 Firefox/42.0");  

        $result = curl_exec($curl);

        curl_close($curl);
        if ($parse)
		{
		$result = json_decode($result, true);
		}
        return $result;
	}
?>
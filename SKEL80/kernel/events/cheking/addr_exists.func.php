<?php
// проверяет наличие файла (страницы) на любом сайте в интернет сети
function addr_exists($url=NULL)
	{
	$headers = @get_headers($url);

	// проверяем ли ответ от сервера с кодом 200 - ОК
	return strpos(ready_val($headers[0]), '200');
	}
?>
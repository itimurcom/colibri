<?php
// ================ CRC ================
// version: 1.49.02
// hash: db6435238fee877dbe25f8dd8376959af04e93186295101bc7b77b9ccd350b56
// date: 10 March 2021  9:27
// ================ CRC ================
//..............................................................................
// возвращает ID товара по его человекопонятной ссылке
//..............................................................................
function get_item_id_by_url($url)
	{
	$request = itMySQL::_request("SELECT `id` FROM `".DB_PREFIX."items` WHERE `url_xml` LIKE '%{$url}%'");
	return isset($request[0]) ? $request[0]['id'] : NULL;
	}
?>
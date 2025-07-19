<?
//..............................................................................
// возвращает ID товара по его человекопонятной ссылке
//..............................................................................
function get_item_id_by_url($url)
	{
	$request = itMySQL::_request("SELECT `id` FROM `".DB_PREFIX."items` WHERE `url_xml` LIKE '%{$url}%'");
	return isset($request[0]) ? $request[0]['id'] : NULL;
	}
?>
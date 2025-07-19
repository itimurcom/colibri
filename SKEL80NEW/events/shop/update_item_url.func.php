<?
//..............................................................................
// обновляет человекопонятную ссылку товара
//..............................................................................
function update_item_url(&$item_rec)
	{
	$item_rec['url_xml'][CMS_LANG] = get_item_url($item_rec);
	itMySQL::_update_value_db('items', $item_rec['id'], $item_rec['url_xml'], 'url_xml');
	}
?>
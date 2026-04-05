<?php
// ================ CRC ================
// version: 1.49.02
// hash: 156e34f865482511bafd94fbd2c8275a832681a559bbf4cdfab554dc8d1de6c6
// date: 10 March 2021  9:27
// ================ CRC ================
//..............................................................................
// обновляет человекопонятную ссылку товара
//..............................................................................
function update_item_url(&$item_rec)
	{
	$item_rec['url_xml'][CMS_LANG] = get_item_url($item_rec);
	itMySQL::_update_value_db('items', $item_rec['id'], $item_rec['url_xml'], 'url_xml');
	}
?>
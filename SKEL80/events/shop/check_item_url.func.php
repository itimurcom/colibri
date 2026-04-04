<?php
// ================ CRC ================
// version: 1.49.02
// hash: 740e25ea16c4be54c71c39ab96204debe9a6ce7f401129328e93ebeea6f9bfe2
// date: 10 March 2021  9:27
// ================ CRC ================
//..............................................................................
// проверяет человекопонятную ссылку товара
//..............................................................................
function check_item_url(&$item_rec)
	{
	if (is_null($item_rec['url_xml']) OR !isset($item_rec['url_xml'][CMS_LANG]) OR ($item_rec['url_xml'][CMS_LANG]==='') OR ($item_rec['url_xml'][CMS_LANG]===$item_rec['id'].'-'))
		{
		update_item_url($item_rec);
		return true;
		}
	}
?>
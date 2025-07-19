<?
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
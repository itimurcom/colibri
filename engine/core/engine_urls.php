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
	
//..............................................................................
// обновляет человекопонятную ссылку товара
//..............................................................................
function update_item_url(&$item_rec)
	{
	$item_rec['url_xml'][CMS_LANG] = get_item_url($item_rec);
	itMySQL::_update_value_db('items', $item_rec['id'], $item_rec['url_xml'], 'url_xml');
	}
	

//..............................................................................
// возвращает человекопонятную ссылку товара из полей его записи
//..............................................................................
function get_item_url($item_rec)
	{
	global $cat_cat, $cat_relations;

	$title_str 	= ($title_str = get_field_by_lang($item_rec['title_xml'],CMS_LANG,'')) ? "" : "-{$title_str}";
	$tag_str 	= ($tag_str = get_field_by_lang($item_rec['tag_xml'],CMS_LANG,'')) ? "" : "-{$tag_str}";
//	$articul_str 	= "-".str_replace("_",'-',get_item_articul_str($item_rec));
	$articul_str 	= '';
	return translit_url("{$item_rec['id']}{$articul_str}{$title_str}{$tag_str}-".get_const($cat_cat[$cat_relations[$item_rec['category_id']]]['title']));
	}
	
//..............................................................................
// возвращает ID товара по его человекопонятной ссылке
//..............................................................................
function get_item_id_by_url($url)
	{
	$request = itMySQL::_request("SELECT `id` FROM `colibri_items` WHERE `url_xml` LIKE '%{$url}%'");
	return isset($request[0]) ? $request[0]['id'] : NULL;
	}
?>
<?php
//..............................................................................
// генерирует ммассив товаров в поиске
//..............................................................................
function as_item_arr($table_name=DEFAULT_ITEM_TABLE, $db_prefix=DB_PREFIX)
	{
	$articul = strip_tags($_REQUEST['term']);
	$search_str = $articul;
	if ($search_str=='') return;

	
//	$search_str = mb_strtolower($search_str,'UTF-8');
        $result = NULL;
	$match_str = "MATCH (`color_xml` ,`tags_xml` ,`filter_xml` ,`title_xml`, `ed_xml`, `extended_xml`) AGAINST ('$search_str' IN ".AGAINST_MODE." MODE)"; 

	$query = "SELECT *, {$match_str} as relev FROM {$db_prefix}{$table_name}".
		" WHERE (`serie` LIKE '%{$_REQUEST['term']}%' OR {$match_str} OR (1 ".get_articul_sql($articul, false).")) AND (`status`='PUBLISHED' OR `status`='ACTIVE')".
		" ORDER BY `datetime` DESC, relev DESC LIMIT 0,".MAX_SEARCH;			

	if (is_array($request = itMySQL::_request($query)))
		{
		foreach ($request as $row)
			{
			$result[] = get_item_auto_result($row);
			}
		}
	return $result;
	}



//..............................................................................
// обрабатывает массив результата поиска товара
//..............................................................................
function get_item_auto_result($row=NULL)
	{
	if (isset($row['images']) and is_array($row['images']))
		{
		$image = $row['images'][0];
		} else $image=NULL;

	$articul_str = get_item_articul($row);
	$label_str =
		TAB."\t<div class='global_avatar'><img src='".get_thumbnail($image,'ADV_AVATAR')."' alt=''/></div>".
		TAB."\t<div class='global_field'>{$articul_str}</span>";

	$result = array (
			'category' 	=> 'ITEM_LIST_TITLE',
			'id'		=> $row['id'],
			'label' 	=> $label_str,
			'value' 	=> $articul_str,
			'link'		=> '/'.CMS_LANG.'/items/'.$row['id'].'/',
			'relev'		=> $row['relev']
			);	
	return $result;
	}
?>
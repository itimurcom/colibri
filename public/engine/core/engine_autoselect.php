<?php
// guarded request read for autocomplete entrypoints
function autoselect_request_value($key, $default='')
	{
	return (isset($_REQUEST) AND is_array($_REQUEST) AND array_key_exists($key, $_REQUEST))
		? ready_value($_REQUEST[$key], $default)
		: $default;
	}

// keeps legacy SQL string construction, but prevents broken quotes in autocomplete search values
function autoselect_sql_value($value='')
	{
	return addslashes((string)$value);
	}

// генерирует массив товаров в поиске
function as_item_arr($table_name=DEFAULT_ITEM_TABLE, $db_prefix=DB_PREFIX)
	{
	$term = autoselect_request_value('term', '');
	$articul = strip_tags((string)$term);
	$search_str = $articul;
	if ($search_str==='') return [];

	$sql_search_str = autoselect_sql_value($search_str);
	$sql_term = autoselect_sql_value($term);
	$result = [];
	$match_str = "MATCH (`color_xml` ,`tags_xml` ,`filter_xml` ,`title_xml`, `ed_xml`, `extended_xml`) AGAINST ('$sql_search_str' IN ".AGAINST_MODE." MODE)";

	$query = "SELECT *, {$match_str} as relev FROM {$db_prefix}{$table_name}".
		" WHERE (`serie` LIKE '%{$sql_term}%' OR {$match_str} OR (1 ".get_articul_sql($articul, false).")) AND (`status`='PUBLISHED' OR `status`='ACTIVE')".
		" ORDER BY `datetime` DESC, relev DESC LIMIT 0,".MAX_SEARCH;

	if (is_array($request = itMySQL::_request($query)))
		{
		foreach ($request as $row)
			{
			if (!is_array($row)) continue;
			$result[] = get_item_auto_result($row);
			}
		}
	return $result;
	}
// обрабатывает массив результата поиска товара
function get_item_auto_result($row=NULL)
	{
	if (!is_array($row)) return NULL;

	$image = (isset($row['images']) AND is_array($row['images']) AND isset($row['images'][0])) ? $row['images'][0] : NULL;
	$id = ready_value($row['id'] ?? NULL, 0);
	$relev = ready_value($row['relev'] ?? NULL, 0);

	$articul_str = get_item_articul($row);
	$label_str =
		TAB."\t<div class='global_avatar'><img src='".get_thumbnail($image,'ADV_AVATAR')."' alt=''/></div>".
		TAB."\t<div class='global_field'>{$articul_str}</span>";

	$result = array (
			'category' 	=> 'ITEM_LIST_TITLE',
			'id'		=> $id,
			'label' 	=> $label_str,
			'value' 	=> $articul_str,
			'link'		=> '/'.CMS_LANG.'/items/'.$id.'/',
			'relev'		=> $relev
			);
	return $result;
	}
?>

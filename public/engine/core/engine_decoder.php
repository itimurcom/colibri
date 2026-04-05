<?
//..............................................................................
// возвращает код запроса для указанного артикула
//..............................................................................
function get_articul_sql($articul_str, $force_R=true)
	{
	$articul = strtoupper($articul_str);
	$result = NULL;


	// перед проверкой на магазин уберем из проверки код типа изделия
	// проверяем на магазин
	if ($is_shop = (strpos($articul,'S_')===0) )
		{
		$result .= " AND `is_shop`='1'";	
		$articul = substr($articul, 2);
		} else
			{
			$result .= " AND `is_shop`<>'1'";
			}

	// проверяем на репликацию	
	if ($is_replicant = ( ($_tmp = str_replace('R_', '', $articul)) != $articul ))
		{
		$result .= $force_R ? " AND `is_replicant`='1'" : NULL;	
		$articul = $_tmp;
		} else
			{
			$result .= $force_R ? " AND `is_replicant`<>'1'" : NULL;
			}
		
	
	// разбиваем на серию
	if (is_array($_art = explode('_', trim($articul))) AND !empty($_art[0]))
		{
		$index = !($category_id = get_category_id_by_letter($_art[0])) ? 0 : 1;
		// в зависимости от репликации
		$result .= 
			($index ? " AND `category_id` = '{$category_id}'" : NULL).
			(isset($_art[$index]) ? " AND `serie` LIKE ('%{$_art[$index]}%')" : NULL).
				(isset($_art[$index+1]) ? " AND `version` = '{$_art[$index+1]}'" : NULL);
		}

// 	echo $result;
	return $result;
	}
//..............................................................................
// возвращает запись товара по артикулу
//..............................................................................
function get_item_from_articul($articul, $table_name=DEFAULT_ITEM_TABLE, $db_prefix=DB_PREFIX)
	{
	$query = "SELECT * FROM {$db_prefix}{$table_name} WHERE 1 ".get_articul_sql($articul)." LIMIT 1";
	$request = itMySQL::_request($query);
	return is_array($request) ? $request[0] : NULL;
	}

//..............................................................................
// возвращает номер категории по букве, -1 для репликанта или false если нет
//..............................................................................
function get_category_id_by_letter($letter='')
	{
	global $cat_cat;
	$letter = strtoupper($letter);	
	if ($letter=='R')
		{
		return -1;
		}
		
	if ($letter=='S' AND (strlen($letter)==1))
		{
		return -2;
		}

	foreach ($cat_cat as $key=>$row)
		{
		if ($letter==strtoupper($row['letter'])) return $row['id'];
		}
	return false;
	}	
?>
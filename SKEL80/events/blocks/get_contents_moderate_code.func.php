<?php
// генерирует html код группы модерируемых документов контента
function get_contents_moderate_code_row_value($row, $key, $default=NULL)
	{
	return (is_array($row) && array_key_exists($key, $row)) ? $row[$key] : $default;
	}

function get_contents_moderate_code($arr)
	{
	$result = NULL;
	if (is_array($arr))
		{
		$i=1;
		foreach ($arr as $key=>$row)
			{
			if (!is_array($row)) continue;
			$datetime = get_contents_moderate_code_row_value($row, 'datetime', time());
			$rec_id = (int)get_contents_moderate_code_row_value($row, 'id', get_contents_moderate_code_row_value($row, 'rec_id', 0));
			if ($rec_id<=0) continue;
			$title_xml = get_contents_moderate_code_row_value($row, 'title_xml', '');
			$result .=
			TAB."\t<div class='row'>".
				TAB."<div class='segment p5'>".
					TAB."\t<div class='field p1 center'>".($i++)."</div>".
					TAB."<span class='field p2 center'><small>".get_local_date_str($datetime).BR.get_time_str($datetime)."</small></span>".
					TAB."\t<div class='field p5 center'><a href='/".CMS_LANG."/material/{$rec_id}/'>".get_field_by_lang($title_xml)."</a></div>".
				TAB."</div>".	
				TAB."<div class='segment p5'>".
					(function_exists('get_content_type_event') ? TAB."\t<div class='field p2 center'>".get_content_type_event($row)."</div>" : "").
					(function_exists('get_category_event') ? TAB."\t<div class='field p2 center'>".get_category_event($row)."</div>" : "").
					(function_exists('get_status_event') ? TAB."\t<div class='field p2 right'>".get_status_event($row)."</div>" : "").
				TAB."</div>".					
			TAB."\t</div>";
			}
		}
	return $result;
	}
?>

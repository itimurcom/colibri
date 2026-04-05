<?php
// ================ CRC ================
// version: 1.15.03
// hash: f49a30a24a3e01866d4219a1aeb29aa21be2b39fb1eea8daa86f5b3b9fcf7c82
// date: 09 September 2020 16:28
// ================ CRC ================
//..............................................................................
// генерирует html код группы модерируемых документов контента
//..............................................................................	
function get_contents_moderate_code($arr)
	{
	$result = NULL;
	if (is_array($arr))
		{
		$i=1;
		foreach ($arr as $key=>$row)
			{
			$result .=
			TAB."\t<div class='row'>".
				TAB."<div class='segment p5'>".
					TAB."\t<div class='field p1 center'>".($i++)."</div>".
					TAB."<span class='field p2 center'><small>".get_local_date_str($row['datetime']).BR.get_time_str($row['datetime'])."</small></span>".
					TAB."\t<div class='field p5 center'><a href='/".CMS_LANG."/material/{$row['id']}/'>".get_field_by_lang($row['title_xml'])."</a></div>".
				TAB."</div>".	
				TAB."<div class='segment p5'>".
					(function_exists('get_content_type_event') ? TAB."\t<div class='field p2 center'>".get_content_type_event($row)."</div>" : "").
					(function_exists('get_category_event') ? TAB."\t<div class='field p2 center'>".get_category_event($row)."</div>" : "").
					TAB."\t<div class='field p2 right'>".get_status_event($row)."</div>".
				TAB."</div>".					
			TAB."\t</div>";
			}
		}
	return $result;
	}
?>
<?php
// возвращает код одного пункта выбора языка
function get_lang_row($row)
	{
	global $lang_delay;
	if (!is_array($row)) return '';

	$lang_delay = (int)ready_value($lang_delay ?? 0, 0) + 250;
	$short = ready_value($row['short'] ?? NULL, '');
	if ($short==='') return '';
	$name_orig = ready_value($row['name_orig'] ?? NULL, $short);

	return TAB."<div class='lang_div boxed rounded glass animated bounceInLeft delay-{$lang_delay}".(($short==CMS_LANG) ? ' selected' :'')."'><a class='boxed' href='".itLang::change_link($short)."' title='{$name_orig}'>{$short}</a></div>";
	}
	

/*
// возвращает код одного пункта выбора языка
function get_iphone_lang_row($row)
	{
	return TAB."<a class='nav_button rounded".(($row['short']==CMS_LANG) ? ' selected' :'')."' href='".itLang::change_link($row['short'])."' title='{$row['name_orig']}'>{$row['name_orig']}</a>";
	}
*/
	

/*
// возвращает код меню языков для iphone
function get_iphone_lang_menu($show_current=true)
	{
	global $lang_cat;
	$result = NULL;
	$current_lang = ready_value($_REQUEST['lang'] ?? NULL, CMS_LANG);
	foreach ($lang_cat as $key=>$row)
		{
		// пропускаем текущий язык, если нет принуждения
		if ((($show_current) or ($row['short']!=$current_lang)) and ($row['allowed']==1))
			{
			$result .= get_iphone_lang_row($row);
			}
		}
	return 
		TAB."<div class='float_langdiv iphoneonly'>".
		TAB."<div class='navigation_div'>".
		$result.
		TAB."</div>".
		TAB."</div>";
	}
*/
?>

<?
//..............................................................................
// виджет социальных кнопок
//..............................................................................
function social_links_panel()
	{
	global $_SETTINGS;
	$social_str = NULL;
	if (is_array($social_arr = explode(',', 'FB,IG,TW,VK,OK')))
	foreach($social_arr as $social)
		{
		if (isset($_SETTINGS[$social.'_PAGE']) AND !empty($link = $_SETTINGS[$social.'_PAGE']['value']))
			$social_str .= TAB."<a class='soc_btn' href='{$_SETTINGS[$social.'_PAGE']['value']}' target='_blank'><img src='/themes/".CMS_THEME."/images/open_".strtolower($social).".png'></a>";
		}
	return $social_str
		? 
// 			TAB."<div class='tit small'>".get_const('SOCIAL_NETWORKS')."</div>".
			TAB."<div class='social-menu'>".
				$social_str.
			TAB."</div>"
		: NULL;
	}
//..............................................................................
// настройки социальных страниц
//..............................................................................
function get_social_links_panel()
	{
	global $soc_net;
	
	$o_form = new itForm2([
		'class'	=> 'yellow',
		]);
		
	$o_form->add_data([
		'table_name'	=> DEFAULT_SETTING_TABLE,
		'op'		=> 'settings',
		]);
		
	if (is_array($social_arr = explode(',', 'FB,IG,TW,VK,OK')))
	foreach($social_arr as $social)
		{
		$o_form->add_input([
			'label'		=> "<span class='soc_btn' style='float:right;'><img src='/themes/".CMS_THEME."/images/open_".strtolower($social).".png'></span>",
			'name'		=> $social.'_PAGE',
			'value'		=> itSettings::get($social.'_PAGE'),
			'compact'	=> true,
			'more'		=> false,
			]);
		}
	
	$o_form->add_button([
		'title' => 'Сохранить',
		'type'	=> 'submit',
		]);

	$o_form->compile();
	$result =
		TAB."<div class='calculator big'>".
		$o_form->_view().
		TAB."</div>";		
	unset($o_form);
	return $result;
	}
?>
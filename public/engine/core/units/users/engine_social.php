<?php
function colibri_social_networks()
	{
	return explode(',', 'FB,IG,TW');
	}

function colibri_social_icon($social)
	{
	return '/themes/'.CMS_THEME.'/images/open_'.strtolower($social).'.png';
	}

function social_links_panel()
	{
	global $_SETTINGS;
	$social_str = NULL;
	foreach(colibri_social_networks() as $social)
		{
		if (isset($_SETTINGS[$social.'_PAGE']) AND !empty($link = $_SETTINGS[$social.'_PAGE']['value']))
			{
			$social_str .= TAB."<a class='soc_btn' href='{$link}' target='_blank'><img src='".colibri_social_icon($social)."'></a>";
			}
		}
	return $social_str ? TAB."<div class='social-menu'>".$social_str.TAB."</div>" : NULL;
	}

function get_social_links_panel()
	{
	$o_form = new itForm2([
		'class'	=> 'yellow',
		]);
	$o_form->add_data([
		'table_name'	=> DEFAULT_SETTING_TABLE,
		'op'		=> 'settings',
		]);

	foreach(colibri_social_networks() as $social)
		{
		$o_form->add_input([
			'label'		=> "<span class='soc_btn' style='float:right;'><img src='".colibri_social_icon($social)."'></span>",
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
	$result = TAB."<div class='calculator big'>".$o_form->_view().TAB."</div>";
	unset($o_form);
	return $result;
	}
?>

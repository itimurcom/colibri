<?php
function colibri_menu_request_value($key)
	{
	return isset($_REQUEST[$key]) ? ready_val($_REQUEST[$key]) : NULL;
	}

function colibri_menu_lang()
	{
	return defined('CMS_LANG') ? CMS_LANG : (function_exists('get_const') ? get_const('DEFAULT_LANG') : 'ru');
	}

function colibri_menu_theme()
	{
	return defined('CMS_THEME') ? CMS_THEME : 'default';
	}

function colibri_menu_row_value($row, $key, $default=NULL)
	{
	if (!is_array($row)) return $default;
	return array_key_exists($key, $row) ? $row[$key] : $default;
	}

function colibri_menu_has_value($value)
	{
	return isset($value) && $value!=='';
	}

function colibri_menu_row_is_visible($row)
	{
	$show = colibri_menu_row_value($row, 'show');
	return is_array($row) && $show;
	}

function colibri_menu_const_value($name)
	{
	return colibri_menu_has_value($name) ? get_const($name) : NULL;
	}

function colibri_menu_user_logged_any()
	{
	global $_USER;
	return is_object($_USER) && method_exists($_USER, 'is_logged') && $_USER->is_logged('ANY');
	}

function colibri_menu_link($row)
	{
	$controller = colibri_menu_row_value($row, 'controller');
	$view = colibri_menu_row_value($row, 'view');

	return "/".colibri_menu_lang()."/".
		(colibri_menu_has_value($controller) ? "{$controller}/" : "").
		((!is_null($view) AND colibri_menu_has_value($view) AND ($controller!=$view)) ? "{$view}/" : "");
	}

function colibri_menu_is_selected($row)
	{
	return (
		(colibri_menu_request_value('view')==colibri_menu_row_value($row, 'view'))
		OR
		((colibri_menu_request_value('controller')==colibri_menu_row_value($row, 'controller')) AND is_null(colibri_menu_row_value($row, 'view')))
		);
	}

function colibri_menu_selected_class($row)
	{
	return colibri_menu_is_selected($row) ? ' selected' : NULL;
	}
function get_menus_block()
	{
	return
		TAB."<div class='siterow empty boxed'>".
			TAB."<div class='flex iphonecolumn'>".
				TAB."<div class='left20 boxed'>".
					TAB."<a class='logo' href='/'><img src='/themes/".colibri_menu_theme()."/images/top_left_logo.png'></a>".
					TAB."<div class='animatedParent noiphone'>".
						itLang::compile(true).
					TAB."</div>".
				TAB."</div>".

				TAB."<div class='right80'>".
					TAB."<a href='/'><img class='lettering' src='/themes/".colibri_menu_theme()."/images/ateliecolibri-".colibri_menu_lang().".png'></a>".
					TAB."<div class='explain'>".get_const('EXPLAIN_BRAND')."</div>".
				TAB."</div>".
			TAB."</div>".
		TAB."</div>".

 		TAB."<div class='siterow empty boxed'>".

			TAB."<div class='flex'>".
				TAB."<div class='left20 closed moremenu boxed'>".
					get_left_navigation().
				TAB."</div>".

				TAB."<div class='right80 boxed closed moremenu'>".
					TAB."<div class='flex vertical'>".
						get_catalog_navigation().
						get_subcatalog_navigation().
					TAB."</div>".
				TAB."</div>".
			TAB."</div>".

		TAB."</div>". //site_row
		"";
	}

function get_catalog_navigation()
	{
	global $cat_cat;

	$result = NULL;
	if (is_array($cat_cat))
		{
		$result = TAB."<div class='node_menu boxed'>";
		foreach ($cat_cat as $key=>$row)
			{
			$result .= get_category_node($row);
			}
		$result .= TAB."</div>";
		}
	return $result;
	}

function get_category_node($row)
	{
	if (!colibri_menu_row_is_visible($row)) return NULL;

	return get_category_node_code($row, colibri_menu_request_value('view')==colibri_menu_row_value($row, 'view'));
	}

function get_category_node_code($row, $selected=false)
	{
	if (!is_array($row)) return NULL;

	$selected = $selected ? ' selected' : NULL;

	$link = colibri_menu_link($row);
	$avatar = colibri_menu_row_value($row, 'avatar');
	$title = colibri_menu_const_value(colibri_menu_row_value($row, 'title'));

	return
		TAB."<div class='rounded glass boxed coll_node {$selected}'>".
		(colibri_menu_has_value($avatar)
			? TAB."<a href='{$link}'><img class='avatar' src='/themes/".colibri_menu_theme()."/images/{$avatar}'></a>"
			: NULL).
			TAB."<a class='link' href='{$link}'>".$title."</a>".
		TAB."</div>";
	}

function get_subcatalog_navigation()
	{
	global $cat_more;

	$result = NULL;
	if (is_array($cat_more))
		{
		$result = TAB."<div class='node_menu sub boxed'>";
		foreach ($cat_more as $key=>$row)
			{
			$result .= get_subcategory_node($row);
			}
		$result .= TAB."</div>";
		}
	return $result;
	}

function get_subcategory_node($row)
	{
	if (!colibri_menu_row_is_visible($row)) return NULL;

	$link = colibri_menu_link($row);
	$avatar = colibri_menu_row_value($row, 'avatar');
	$title = colibri_menu_const_value(colibri_menu_row_value($row, 'title'));

	$selected = colibri_menu_selected_class($row);

	return
		TAB."<div class='coll_node animatedParent small glass boxed rounded{$selected}'>".
		(colibri_menu_has_value($avatar)
			? TAB."<a href='{$link}'><img class='avatar' src='/themes/".colibri_menu_theme()."/images/{$avatar}'></a>"
			: NULL).
		TAB."<a class='link' href='{$link}'>".$title."</a>".
		( (colibri_menu_row_value($row, 'hot'))
			?  TAB."<div class='hot animated growIn'></div>"
			: NULL).
		TAB."</div>";

	}

function get_left_navigation()
	{
	global $a_menu;

	if (!is_array($a_menu)) $a_menu = [];

	if (colibri_menu_user_logged_any())
		{
		$a_menu['register'] = [
			'title'		=> 'NODE_CABINET',
			'controller'	=> 'cabinet',
			'view'		=> NULL,
			'show'		=> 2,
			'class'		=> 'register-btn online',
			];
		}

	$result = NULL;
	foreach ($a_menu as $key=>$row)
		{
		if (colibri_menu_row_is_visible($row))
			{
			$result .= get_navigation_row($row);
			}
		}

	if ($result)
		{
		$result = TAB."<div class='navigation_div boxed'>".
			$result.
			TAB."</div>";
		}
	return $result.
		social_links_panel();
	}

function get_navigation_row($row)
	{
	$link = colibri_menu_link($row);

	$selected = (colibri_menu_row_value($row, 'controller')==colibri_menu_request_value('controller')) ? " selected" : NULL;
	$class_value = colibri_menu_row_value($row, 'class');
	$class = colibri_menu_has_value($class_value) ? " {$class_value}" : NULL;
	$title = colibri_menu_const_value(colibri_menu_row_value($row, 'title'));

	return 	TAB."<a class='nav_button boxed rounded{$selected}{$class}' href='{$link}'>".$title."</a>";
	}

function get_footer_navigation()
	{
	$result = NULL;
	global $cat_cat;
	global $a_menu;
	global $cat_more;

	if (is_array($a_menu))
		{
		$result .= TAB."<div class='footer_menu c1 boxed noiphone'>";
		foreach ($a_menu as $key=>$row)
			if (is_array($row) AND colibri_menu_row_value($row, 'show')==2) $result .= get_footer_link($row);
		$result .= TAB."</div>";
		}

	if (is_array($cat_cat))
		{
		$result .= TAB."<div class='footer_menu c2 boxed noiphone'>";
		foreach ($cat_cat as $key=>$row)
			if (colibri_menu_row_is_visible($row)) $result .= get_footer_link($row);
		$result .= TAB."</div>";
		}

	if (is_array($cat_more))
		{
		$result .= TAB."<div class='footer_menu c2 boxed noiphone'>";
		foreach ($cat_more as $key=>$row)
			if (is_array($row) AND colibri_menu_row_value($row, 'show')==2) $result .= get_footer_link($row);
		$result .= TAB."</div>";
		}

	$o_block = new itBlock(BLOCK_COPY, [
		'no_data'	=> true,
		'no_title'	=> true,
		'no_related'	=> true,
		'no_lang'	=> true,
		'edclass'	=> 'dashed_blue',
		]);
	$o_block->compile();

	$result .= TAB."<div class='footer_menu c2 boxed copy'>".
		$o_block->editor->container().
		TAB."</div>";
	return $result;
	}

function get_footer_link($row)
	{
	$link = colibri_menu_link($row);

	$selected = colibri_menu_selected_class($row);
	$title = colibri_menu_const_value(colibri_menu_row_value($row, 'title'));

	return TAB."<div class='link{$selected}'><a href='{$link}'>".$title."</a></div>";
	}
?>

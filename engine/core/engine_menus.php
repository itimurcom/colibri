<?php
//..............................................................................
// панель меню для всего проекта
//..............................................................................
function get_menus_block()
	{
	return 
		//ряд логотипов
// 		get_iphone_lang_menu(true).
// 		get_iphone_menu().
		TAB."<div class='siterow empty boxed'>".
			TAB."<div class='flex iphonecolumn'>".
				TAB."<div class='left20 boxed'>".
					TAB."<a class='logo' href='/'><img src='/themes/".CMS_THEME."/images/top_left_logo.png'></a>".
					TAB."<div class='animatedParent noiphone'>".
						itLang::compile(true).
					TAB."</div>".
				TAB."</div>".
				
				TAB."<div class='right80'>".
					TAB."<a href='/'><img class='lettering' src='/themes/".CMS_THEME."/images/ateliecolibri-".CMS_LANG.".png'></a>".
					TAB."<div class='explain'>".get_const('EXPLAIN_BRAND')."</div>".
				TAB."</div>".
			TAB."</div>".
		TAB."</div>".				
 		
 		TAB."<div class='siterow empty boxed'>".

/*
		TAB."<div class='navigation_div boxeded iphoneonly'>".
			TAB."<span class='nav_button toggle boxed rounded' onclick=\"$('.moremenu').toggleClass('closed');$(this).toggleClass('selected');\"/>".get_const('MOBILE_MENU')."</span>".
		TAB."</div>".
*/
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
	
//..............................................................................
// меню каталога товаров
//..............................................................................
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
	
//..............................................................................
// один элемент меню
//..............................................................................
function get_category_node($row)
	{
	if (!ready_val($row['show'])) return;
	
	return get_category_node_code($row, ($_REQUEST['view']==$row['view']));			
	}
	
//..............................................................................
// код одного элемента меню
//..............................................................................
function get_category_node_code($row, $selected=false)
	{
	$selected = $selected ? ' selected' : NULL;

	$link = "/".CMS_LANG."/".
		($row['controller'] ? "{$row['controller']}/" : "").
		((!is_null($row['view']) AND ($row['controller']!=$row['view']))  ? "{$row['view']}/" : "");
	
	return 
		TAB."<div class='rounded glass boxed coll_node {$selected}'>".
		(isset($row['avatar'])
			? TAB."<a href='{$link}'><img class='avatar' src='/themes/".CMS_THEME."/images/{$row['avatar']}'></a>"
			: NULL).
			TAB."<a class='link' href='{$link}'>".get_const($row['title'])."</a>".
		TAB."</div>";	
	}
	
//..............................................................................
// меню каталога товаров
//..............................................................................
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
	
//..............................................................................
// меню каталога товаров
//..............................................................................
function get_subcategory_node($row)
	{
	if (!ready_val($row['show'])) return;
	
	$link = "/".CMS_LANG."/".
		($row['controller'] ? "{$row['controller']}/" : "").
		((!is_null($row['view']) AND ($row['controller']!=$row['view']))  ? "{$row['view']}/" : "");

	$selected = ( 
			( $_REQUEST['view']==$row['view'] ) 
				OR 
			( ($_REQUEST['controller']==$row['controller']) AND is_null($row['view']) ) 
		)
		? ' selected' : NULL;

	return 
		TAB."<div class='coll_node animatedParent small glass boxed rounded{$selected}'>".
		(isset($row['avatar'])
			? TAB."<a href='{$link}'><img class='avatar' src='/themes/".CMS_THEME."/images/{$row['avatar']}'></a>"
			: NULL).
		TAB."<a class='link' href='{$link}'>".get_const($row['title'])."</a>".
		( (isset($row['hot']) AND $row['hot'])
			?  TAB."<div class='hot animated growIn'></div>"
			: NULL).
		TAB."</div>";

	} 

//..............................................................................
// меню в левой части
//..............................................................................
function get_left_navigation()
	{
	global $a_menu, $_USER;
	
	if ($_USER->is_logged('ANY'))
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
		if ($row['show'])
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

//..............................................................................
// одна срока меню
//..............................................................................
function get_navigation_row($row)
	{
	$link = "/".CMS_LANG."/".
		($row['controller'] ? "{$row['controller']}/" : "").
		((!is_null($row['view']) AND ($row['controller']!=$row['view']))  ? "{$row['view']}/" : "");
		
	$selected = ($row['controller']==$_REQUEST['controller']) ? " selected" : NULL;
	$class = isset($row['class']) ? " {$row['class']}" : NULL;
	
	return 	TAB."<a class='nav_button boxed rounded{$selected}{$class}' href='{$link}'>".get_const($row['title'])."</a>";
	}


function get_footer_navigation()
	{
	$result = NULL;
	global $cat_cat;
	global $a_menu;
	global $cat_more;
		
	// меню топовое
	if (is_array($a_menu))
		{
		$result .= TAB."<div class='footer_menu c1 boxed noiphone'>";
		foreach ($a_menu as $key=>$row)
			if ($row['show']==2) $result .= get_footer_link($row);
		$result .= TAB."</div>";
		}

	// меню типов товаров
	if (is_array($cat_cat))
		{
		$result .= TAB."<div class='footer_menu c2 boxed noiphone'>";
		foreach ($cat_cat as $key=>$row)
			if ($row['show']) $result .= get_footer_link($row);
		$result .= TAB."</div>";
		}
		
	// меню подгрупп
	if (is_array($cat_more))
		{
		$result .= TAB."<div class='footer_menu c2 boxed noiphone'>";
		foreach ($cat_more as $key=>$row)
			if ($row['show']==2) $result .= get_footer_link($row);
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
//		TAB.get_block_content_event($o_block->data).
		TAB."</div>";		
	return $result;
	}

function get_footer_link($row)
	{
	$link = "/".CMS_LANG."/".
		($row['controller'] ? "{$row['controller']}/" : "").
		((!is_null($row['view']) AND ($row['controller']!=$row['view']))  ? "{$row['view']}/" : "");

	$selected = ( ( $_REQUEST['view']==$row['view'] ) 
			OR 
		( ($_REQUEST['controller']==$row['controller']) AND is_null($row['view']) ) 
		)
		? ' selected' : NULL;

	return TAB."<div class='link{$selected}'><a href='{$link}'>".get_const($row['title'])."</a></div>";	
	}
?>
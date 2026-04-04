<?
//..............................................................................
// меню для мобильых устройств
//..............................................................................
function get_iphone_menu()
	{
	global $_CELL, $_USER, $plug_og;
	if ($_USER->is_logged('ANY'))
		{
		$_CELL['register'] = [
			'title'		=> 'NODE_CABINET',
			'controller'	=> 'cabinet',
			'view'		=> NULL,
			'show'		=> 2,
			'class'		=> 'register-btn online',
			];
		}
	$result = NULL;
	foreach ($_CELL as $key=>$row)
		{
		if (is_null($row))
			{
			$result .= BR;
			} else
		if ($row['show'])
			{
			$result .= ready_val($row['cell']) ? get_cell_node_code($row) : get_celular_row($row);
			}
		}

	$cell_title = ready_val($plug_og['title']);

	return	TAB."<div class='celular-nav iphoneonly bordered'>".
			TAB."<script>function burger(){
				$('#burger').toggleClass('close');
				$('#cellular-menu').slideToggle('slow');
				window.scrollTo(0,0);
				}</script>".
			TAB."<div class='burger-btn' id='burger' onclick='burger();'></div>".
			TAB."<div class='cell-title'><div>{$cell_title}</div></div>".
		TAB."</div>".
		TAB."<div id='cellular-menu' class='mobile-menu bordered boxed'>".
			$result.
			TAB."<center>".social_links_panel().get_cell_lang_menu().TAB."</center>".			
		TAB."</div>".
		"";
	}

//..............................................................................
// ряд мобильного меню
//..............................................................................
function get_celular_row($row)
	{
	$link = "/".CMS_LANG."/".
		($row['controller'] ? "{$row['controller']}/" : "").
		((!is_null($row['view']) AND ($row['controller']!=$row['view']))  ? "{$row['view']}/" : "");

	$selected = ( 
			( !is_null($row['view']) AND ($_REQUEST['view']==$row['view']) ) 
				OR 
			( ($_REQUEST['controller']==$row['controller']) AND is_null($row['view']) ) 
		)
		? ' selected' : NULL;
	$class_str = isset($row['class']) ? " class='{$row['class']}'" : NULL;
	return 
		TAB."<div class='row boxed bordered{$selected}'><a{$class_str} href='{$link}'>".get_const($row['title'])."</a></div>";
	}

//..............................................................................
// код пункта выбора языка мобильного
//..............................................................................
function get_cell_lang_row($row)
	{
	$selected = ($row['short']==CMS_LANG) ? ' selected' : NULL;
	return 
		TAB."<div class='lang_div boxed rounded bordered{$selected}'>".
			TAB."<a class='boxed' href='".itLang::change_link($row['short'])."' title='{$row['name_orig']}'>{$row['short']}</a>".
		TAB."</div>";
	}
	
//..............................................................................
// возвращает код меню языков для iphone
//..............................................................................
function get_cell_lang_menu($show_current=true)
	{
	global $lang_cat;
	$result = NULL;
	foreach ($lang_cat as $key=>$row)
		{
		// пропускаем текущий язык, если нет принуждения
		if ((($show_current) OR ($row['short']!=$_REQUEST['lang'])) and ($row['allowed']==1))
			{
			$result .= get_cell_lang_row($row);
			}
		}
	return 
		TAB."<div class=''>".
		$result.
		TAB."</div>";
	}


//..............................................................................
// код одного элемента меню
//..............................................................................
function get_cell_node_code($row)
	{
	$link = "/".CMS_LANG."/".
		($row['controller'] ? "{$row['controller']}/" : "").
		((!is_null($row['view']) AND ($row['controller']!=$row['view']))  ? "{$row['view']}/" : "");

	$selected = ( 
			( !is_null($row['view']) AND ($_REQUEST['view']==$row['view']) ) 
				OR 
			( ($_REQUEST['controller']==$row['controller']) AND is_null($row['view']) ) 
		)
		? ' selected' : NULL;
	
	return 
		TAB."<div class='rounded glass boxed bordered coll_node {$selected}'>".
		(isset($row['avatar'])
			? TAB."<a href='{$link}'><img class='avatar' src='/themes/".CMS_THEME."/images/{$row['avatar']}'></a>"
			: NULL).
			TAB."<a class='link boxed' href='{$link}'>".get_const($row['title'])."</a>".
		TAB."</div>";	
	}
?>
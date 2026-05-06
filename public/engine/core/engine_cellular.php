<?php 
// меню для мобильых устройств
function cellular_request_value($key, $default=NULL)
	{
	return (isset($_REQUEST) AND is_array($_REQUEST) AND array_key_exists($key, $_REQUEST))
		? ready_value($_REQUEST[$key], $default)
		: $default;
	}

function cellular_row_value($row, $key, $default=NULL)
	{
	return (is_array($row) AND array_key_exists($key, $row)) ? ready_value($row[$key], $default) : $default;
	}

function cellular_is_selected_row($row)
	{
	$view = cellular_row_value($row, 'view');
	$controller = cellular_row_value($row, 'controller', '');
	$current_view = cellular_request_value('view');
	$current_controller = cellular_request_value('controller');

	return (
		(!is_null($view) AND ($current_view == $view))
		OR
		(($current_controller == $controller) AND is_null($view))
	);
	}

function cellular_build_link($row)
	{
	$controller = cellular_row_value($row, 'controller', '');
	$view = cellular_row_value($row, 'view');
	return "/".CMS_LANG."/".
		($controller ? "{$controller}/" : "").
		((!is_null($view) AND ($controller != $view)) ? "{$view}/" : "");
	}

function get_iphone_menu()
	{
	global $_CELL, $_USER, $plug_og;
	if (!is_array($_CELL)) $_CELL = [];
	if (is_object($_USER) AND method_exists($_USER, 'is_logged') AND $_USER->is_logged('ANY'))
		{
		$_CELL['register'] = [
			'title' 	=> 'NODE_CABINET',
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
			continue;
			}
		if (!is_array($row)) continue;
		if (cellular_row_value($row, 'show'))
			{
			$result .= cellular_row_value($row, 'cell') ? get_cell_node_code($row) : get_celular_row($row);
			}
		}

	$cell_title = is_array($plug_og) ? ready_value($plug_og['title'] ?? NULL) : NULL;

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
// ряд мобильного меню
function get_celular_row($row)
	{
	if (!is_array($row)) return NULL;
	$link = cellular_build_link($row);
	$selected = cellular_is_selected_row($row) ? ' selected' : NULL;
	$class = cellular_row_value($row, 'class');
	$class_str = $class ? " class='{$class}'" : NULL;
	$title = cellular_row_value($row, 'title', '');
	return 
		TAB."<div class='row boxed bordered{$selected}'><a{$class_str} href='{$link}'>".get_const($title)."</a></div>";
	}
// код пункта выбора языка мобильного
function get_cell_lang_row($row)
	{
	if (!is_array($row)) return NULL;
	$short = cellular_row_value($row, 'short', '');
	if ($short === '') return NULL;
	$name_orig = cellular_row_value($row, 'name_orig', $short);
	$selected = ($short == CMS_LANG) ? ' selected' : NULL;
	return 
		TAB."<div class='lang_div boxed rounded bordered{$selected}'>".
			TAB."<a class='boxed' href='".itLang::change_link($short)."' title='{$name_orig}'>{$short}</a>".
		TAB."</div>";
	}
// возвращает код меню языков для iphone
function get_cell_lang_menu($show_current=true)
	{
	global $lang_cat;
	$result = NULL;
	$current_lang = cellular_request_value('lang', CMS_LANG);
	if (!is_array($lang_cat)) $lang_cat = [];
	foreach ($lang_cat as $key=>$row)
		{
		if (!is_array($row)) continue;
		$short = cellular_row_value($row, 'short', '');
		$allowed = intval(cellular_row_value($row, 'allowed', 0));
		// пропускаем текущий язык, если нет принуждения
		if ((($show_current) OR ($short != $current_lang)) AND ($allowed == 1))
			{
			$result .= get_cell_lang_row($row);
			}
		}
	return 
		TAB."<div class=''>".
		$result.
		TAB."</div>";
	}
// код одного элемента меню
function get_cell_node_code($row)
	{
	if (!is_array($row)) return NULL;
	$link = cellular_build_link($row);
	$selected = cellular_is_selected_row($row) ? ' selected' : NULL;
	$avatar = cellular_row_value($row, 'avatar');
	$title = cellular_row_value($row, 'title', '');
	return 
		TAB."<div class='rounded glass boxed bordered coll_node {$selected}'>".
		($avatar
			? TAB."<a href='{$link}'><img class='avatar' src='/themes/".CMS_THEME."/images/{$avatar}'></a>"
			: NULL).
			TAB."<a class='link boxed' href='{$link}'>".get_const($title)."</a>".
		TAB."</div>";	
	}
?>

<?php 
$_CONTENT['admin'] = get_admin_button_set();
if (!$_USER->is_logged())
	{
	cms_redirect_page("/");
	}

function settings_backup_panel()
	{
	return
		TAB."<div class='tit'>Резервне копіювання</div>".
		"<center>".
		"<div class='gray'>налаштовано автоматичне створення архіву: щоденно о 03:00</div>".BR.
		"<a class='green' href='".CMS_CURRENT_BASE_URL."/colibri.bak.zip'>посилання на актуальний архів</a>".BR.BR.
		(file_exists(SERVER_ROOT_DEBUG.'/colibri.bak.zip')
			? "<div class='gray'>останній раз архів був змінений: <b class='yellow'>" . date ("F d Y H:i:s", filemtime(SERVER_ROOT_DEBUG.'/colibri.bak.zip'))."</b>.</div>"
			: NULL).
		"</center>";
	}

function settings_left_panel()
	{
	return
		TAB."<div class='left50 boxed'>".
		TAB."<div class='tit'>Налаштування проєкту</div>".
		get_settings_panel().
		TAB."<div class='tit'>Мови</div>".
		get_lang_panel().
		TAB."<div class='tit'>Поштові скриньки</div>".
		get_smtp_settings_panel().
		TAB."<div class='tit'>Пароль адміністратора</div>".
		get_password_panel().
		TAB."</div>";
	}

function settings_right_panel()
	{
	return
		TAB."<div class='right50 boxed'>".
		get_measurement_panel().
		TAB."<div class='tit'>Сторінки у соціальних мережах</div>".
		get_social_links_panel().
		settings_backup_panel().
		TAB."</div>";
	}

$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();
$_CONTENT['content'] = TAB."<div class='siterow boxed'>".settings_left_panel().settings_right_panel().TAB."</div>";

$plug_og['subtitle'] 	= get_const('CMS_NAME');
$plug_og['title'] 	= get_const('CMS_NAME_EXTENDED');
?>

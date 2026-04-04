<?
$_CONTENT['admin'] = get_admin_button_set();
if (!$_USER->is_logged())
	{
	cms_redirect_page("/");
	}
	
$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();

$_CONTENT['content'] = 
		TAB."<div class='siterow boxed'>".
			TAB."<div class='left50 boxed'>".
			TAB."<div class='tit'>Настройки проекта</div>".
				get_settings_panel().
			TAB."<div class='tit'>Мови</div>".				
				get_lang_panel().

			TAB."<div class='tit'>Почтовые ящики</div>".
				get_smtp_settings_panel().
			TAB."<div class='tit'>Пароль администратора</div>".
				get_password_panel().
			TAB."</div>".
			TAB."<div class='right50 boxed'>".
			get_measurement_panel().
			TAB."<div class='tit'>Страницы в социальных сетях</div>".
				get_social_links_panel().
			TAB."<div class='tit'>Резервное копирование</div>".
				"<center>".
				"<div class='gray'>настроено автоматическое создание архива: ежедневвно в 03:00</div>".BR.
				"<a class='green' href='https://atelier-colibri.com/colibri.bak.zip'>ссылка на актуальный архив</a>".BR.BR.
				(file_exists(SERVER_ROOT_DEBUG.'/colibri.bak.zip')
					? "<div class='gray'>последний раз архив был изменен: <b class='yellow'>" . date ("F d Y H:i:s", filemtime(SERVER_ROOT_DEBUG.'/colibri.bak.zip'))."</b>.</div>"
					: NULL).
				"</center>".
			TAB."</div>".
		TAB."</div>";		

// opengraph
$plug_og['subtitle'] 	= get_const('CMS_NAME');
$plug_og['title'] 	= get_const('CMS_NAME_EXTENDED');

?>
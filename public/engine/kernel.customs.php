<?php
/* установки пользователя - после подключения "ядра" */
global $_USER, $_SETTINGS;
	// email адрес администратора сайта
	//.............................................................................
	define('SITE_ADMIN_EMAIL_DEFAULT', 'ateliecolibri@gmail.com');
	$_SETTINGS['SITE_ADMIN_EMAIL'] = array (
		'name'		=> 'SITE_ADMIN_EMAIL',
		'title'		=> 'SITE_ADMIN_EMAIL_TITLE',
		'type'		=> 'text',
		'default'	=> SITE_ADMIN_EMAIL_DEFAULT
		);
	$_SETTINGS['SITE_ADMIN_EMAIL']['value'] = itSettings::get('SITE_ADMIN_EMAIL', NULL);

	// пользователь входа SMTP
	//.............................................................................
	define('SITE_SMTP_USER_DEFAULT', 'robot@'.$_SERVER['SERVER_NAME']);
	$_SETTINGS['SITE_SMTP_USER'] = array (
		'name'		=> 'SITE_SMTP_USER',
		'title'		=> 'SITE_SMTP_USER_TITLE',
		'type'		=> 'text',
		'default'	=> SITE_SMTP_USER_DEFAULT
		);
	$_SETTINGS['SITE_SMTP_USER']['value'] = itSettings::get('SITE_SMTP_USER', NULL);


	// пароль входа SMTP
	//.............................................................................
	define('SITE_SMTP_PASSWORD_DEFAULT', 'robotcolibri');
	$_SETTINGS['SITE_SMTP_PASSWORD'] = array (
		'name'		=> 'SITE_SMTP_PASSWORD',
		'title'		=> 'SITE_SMTP_PASSWORD_TITLE',
		'type'		=> 'text',
		'default'	=> SITE_SMTP_PASSWORD_DEFAULT
		);
	$_SETTINGS['SITE_SMTP_PASSWORD']['value'] = itSettings::get('SITE_SMTP_PASSWORD', NULL);


	// страница facebook
	//.............................................................................
	define('FB_PAGE_DEFAULT', 'https://www.facebook.com/rhythmicgymnasticsleotard/');
	$_SETTINGS['FB_PAGE'] = array (
		'name'		=> 'FB_PAGE',
		'title'		=> 'FB_PAGE_TITLE',
		'type'		=> 'text',
		'default'	=> FB_PAGE_DEFAULT
		);
	$_SETTINGS['FB_PAGE']['value'] = itSettings::get('FB_PAGE', NULL);	

	// страница В контакте
	//.............................................................................
	define('VK_PAGE_DEFAULT', '');
	$_SETTINGS['VK_PAGE'] = array (
		'name'		=> 'VK_PAGE',
		'title'		=> 'VK_PAGE_TITLE',
		'type'		=> 'text',
		'default'	=> VK_PAGE_DEFAULT
		);
	$_SETTINGS['VK_PAGE']['value'] = itSettings::get('VK_PAGE', NULL);	

	// страница Инстаграм
	//.............................................................................
	define('IG_PAGE_DEFAULT', 'https://www.instagram.com/atelie_colibri/');
	$_SETTINGS['IG_PAGE'] = array (
		'name'		=> 'IG_PAGE',
		'title'		=> 'IG_PAGE_TITLE',
		'type'		=> 'text',
		'default'	=> IG_PAGE_DEFAULT
		);
	$_SETTINGS['IG_PAGE']['value'] = itSettings::get('IG_PAGE', NULL);	

	// страница Одноклассники
	//.............................................................................
	define('OK_PAGE_DEFAULT', '');
	$_SETTINGS['OK_PAGE'] = array (
		'name'		=> 'OK_PAGE',
		'title'		=> 'OK_PAGE_TITLE',
		'type'		=> 'text',
		'default'	=> IG_PAGE_DEFAULT
		);
	$_SETTINGS['OK_PAGE']['value'] = itSettings::get('OK_PAGE', NULL);	

	// страница Твиттер
	//.............................................................................
	define('TW_PAGE_DEFAULT', '');
	$_SETTINGS['TW_PAGE'] = array (
		'name'		=> 'TW_PAGE',
		'title'		=> 'TW_PAGE_TITLE',
		'type'		=> 'text',
		'default'	=> IG_PAGE_DEFAULT
		);
	$_SETTINGS['TW_PAGE']['value'] = itSettings::get('TW_PAGE', NULL);	





	//.............................................................................
	define('DISCOUNT_DEFAULT', 0);
	$_SETTINGS['DISCOUNT'] = [
		'name'		=> 'DISCOUNT',
		'title'		=> 'DISCOUNT_TITLE',
		'type'		=> 'number',
		'default'	=> 0
		];
	$_SETTINGS['DISCOUNT']['value'] = itSettings::get('DISCOUNT', NULL);
	
	define('EUR_DEFAULT', .9);
	$_SETTINGS['EUR'] = [
		'name'		=> 'EUR',
		'title'		=> 'EUR_TITLE',
		'type'		=> 'number',
		'default'	=> .9
		];
	$_SETTINGS['EUR']['value'] = itSettings::get('EUR', NULL);

	define('UAH_DEFAULT', 28);
	$_SETTINGS['UAH'] = [
		'name'		=> 'UAH',
		'title'		=> 'UAH_TITLE',
		'type'		=> 'number',
		'default'	=> 28
		];
	$_SETTINGS['UAH']['value'] = itSettings::get('UAH', NULL);

	define('RUR_DEFAULT', "-");
	$_SETTINGS['RUR'] = [
		'name'		=> 'RUR',
		'title'		=> 'RUR_TITLE',
		'type'		=> 'number',
		'default'	=> 28
		];
	$_SETTINGS['RUR']['value'] = itSettings::get('RUR', NULL);

	define('TAX_DEFAULT', 0);
	$_SETTINGS['TAX'] = [
		'name'		=> 'TAX',
		'title'		=> 'TAX_TITLE',
		'type'		=> 'number',
		'default'	=> 0
		];
	$_SETTINGS['TAX']['value'] = itSettings::get('TAX', NULL);

?>
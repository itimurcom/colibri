<?php
// shared runtime configuration is initialized inside SKEL80/run.php after bootstrap helpers are loaded

if (function_exists('set_time_limit'))
	{
	@set_time_limit(600);
	}

define ('ENGINE_VERSION_VAL','0.2.135.01.beta');
define ('ENGINE_VERSION_DATE','25 May 2021');

define('ENGINE_VERSION',ENGINE_VERSION_VAL." ".ENGINE_VERSION_DATE);

// project overlay contract is declared in public/engine/overlay_contract.php
// it is loaded by SKEL80/run.php after USER_ENGINE_PATH is resolved

// путь к SKELETON
// require "engine/core/skeleton/run.php";
$skel80RunPath = dirname(__DIR__, 2).'/SKEL80/run.php';
if (!is_file($skel80RunPath))
	{
	die('Shared SKEL80 runtime not found: '.$skel80RunPath);
	}
require $skel80RunPath;

// установим данные email для отправки
define ('DEFAULT_ADMIN_EMAIL', $_SETTINGS['SITE_ADMIN_EMAIL']['value']);
define ('DEFAULT_ADMIN_NAME', "admin<".DEFAULT_ADMIN_EMAIL.">");
define ('DEFAULT_PIN_TABLE' , 'pins');

// редактор текста
//$plug_js[] 	= "/engine/js/ckeditor/ckeditor.js";
if ($_USER->is_logged('ANY'))
	{
	$plug_js[]	= "https://cdn.ckeditor.com/4.12.1/full/ckeditor.js";
	}

$plug_meta[] = [
	'name'		=> 'viewport',
	'content'	=> 'width=device-width, initial-scale=1, maximum-scale=2, user-scalable=yes',
	];

// $plug_meta[] = [
// 	'name'		=> 'keywords',
// 	'content'	=> CMS_KEYS,
// 	];
	
$plug_og['description'] = CMS_DESCRIPTION;


// установки для мобильных устройств
$plug_media[1600] = 'ipad';
//$plug_media[640] = 'iphone_horizontal';
$plug_media[720] = 'iphone';

?>
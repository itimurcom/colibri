<?php
// ini_set('error_reporting', E_ALL);
// error_reporting(E_ALL);

//ini_set('display_errors', 1);
set_time_limit(600);

define ('ENGINE_VERSION_VAL','0.2.135.01.beta');
define ('ENGINE_VERSION_DATE','25 May 2021');

define('ENGINE_VERSION',ENGINE_VERSION_VAL." ".ENGINE_VERSION_DATE);

// путь к SKELETON
// require "engine/core/skeleton/run.php";
// require "./SKEL80NEW/run.php";
require "./SKEL80NEW/run.php";

definition($constants = [
	'DEFAULT_ITEM_TABLE'	=> 'items',		
	'DEFAULT_OBJECT_TABLE'	=> 'objects',
	'DEFAULT_CATEGORY_TABLE'=> 'categories',
	'DEFAULT_WIZARD_TABLE'	=> 'categories',
	]);

// установим данные email для отправки
define ('DEFAULT_ADMIN_EMAIL', $_SETTINGS['SITE_ADMIN_EMAIL']['value']);
define ('DEFAULT_ADMIN_NAME', "admin<".DEFAULT_ADMIN_EMAIL.">");
define ('DEFAULT_PIN_TABLE' , 'pins');

// редактор текста
//$plug_js[] 	= "/engine/js/ckeditor/ckeditor.js";
// if ($_USER->is_logged('ANY'))
// 	{
// 	$plug_js[]	= "https://cdn.ckeditor.com/4.12.1/full/ckeditor.js";
// 	}

$_CDN['jquery']['ver']		= '3.5.1';
$_CDN['jquery']['ui'] 		= '1.12.1';
$_CDN['photoswipe']['ver']	= '4.1.3';
$_CDN['bxslider']['ver']	= '4.2.15';

if ($_USER->is_logged('ANY')) {
    $_CDN['ck_editor']['ver'] = '4.12.1';
	// $_CDN['ck_editor']['ver'] = '4.25.1';
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

$_CONTENT['support'] = support();

// установки для мобильных устройств
$plug_media[1600] = 'ipad';
//$plug_media[640] = 'iphone_horizontal';
$plug_media[720] = 'iphone';

?>
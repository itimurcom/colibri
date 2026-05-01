<?php
//
//	ЗАЖИГАНИЕ : плавный запуск ядра фреймворка SKELETON
//
require_once 'kernel/core.php';
require_once 'kernel/runtime_compat.php';
require_once 'kernel/runtime_boundaries.php';
require_once 'kernel/runtime_contract.php';
skel80_runtime_configure();
skel80_runtime_get_boundaries();
skel80_runtime_get_contract();
// Запускаем сессию
if (session_status() !== PHP_SESSION_ACTIVE)
	{
	if (!session_start())
		{
		@session_regenerate_id(true);
		session_start();
		}
	}

definition([
	'TAB'		=> "\n\t",
	'BR'		=> "<br/>",
	'CLEARTEXT'	=> '</b></font>',
	]);

// установим основные пути для работы скриптов
$config_candidates = [];

if (!empty($_SERVER['DOCUMENT_ROOT']))
	{
	$config_candidates[] = rtrim($_SERVER['DOCUMENT_ROOT'], '/').'/config.php';
	}

$config_candidates[] = dirname(__DIR__).'/config.php';
$config_candidates[] = dirname(__DIR__).'/public/config.php';

$inc_arr = get_included_files();
if (isset($inc_arr[1]) AND strpos($inc_arr[1], 'kernel.php') !== false)
	{
	$config_candidates[] = dirname(dirname($inc_arr[1])).'/config.php';
	}

foreach (array_unique($config_candidates) as $config_file)
	{
	if (is_file($config_file))
		{
		include_once($config_file);
		break;
		}
	}

skel80_runtime_configure();
skel80_runtime_register_handlers();

// PHASE: bootstrap.paths
set_skeleton_user_ways();
set_skeleton_core_ways();

// PHASE: bootstrap.overlay.contract
skel80_runtime_load_overlay_contract(USER_ENGINE_PATH);

// настраиваем пути пользователя
if (file_exists(USER_ENGINE_PATH.'kernel.path.php'))
	include USER_ENGINE_PATH.'kernel.path.php';

// Настраиваем пути
definition([
	'SERVER_ROOT_DEBUG'	=> $_SERVER['DOCUMENT_ROOT'],
	'SERVER_HTTP_DEBUG'	=> get_request_http(),
	'PICTURE_PATH'		=> 'img',	// папка хранения аватарок
	'UPLOADS_PATH'		=> 'uploads',	// папка хранения файлов для загрузки
	]);
//register_skeleton_images_ways();

// PHASE: bootstrap.classes
//зарегистрируем последовательность поиска классов
register_classes_folder(USER_CLASSES_PATH);			// движка
register_classes_folder(SKELETON_CLASSES_PATH);			// ядра
register_classes_autoload();

// PHASE: bootstrap.engine
// задекларируем функции
register_skleton_engine(USER_CORE_PATH); 			// подключаем пользовательский движок
register_events_folder(SKELETON_CORE_PATH."kernel/events/");	// системные

// PHASE: bootstrap.const
// установим константы
register_skeleton_user_const(USER_ENGINE_PATH."ini/");		// пред-константы пользователя
register_skeleton_core_const(SKELETON_EVENTS_PATH);		// пост-константы ядра

// попробуем настроить основные константы если их нет в движке
definition([
	'ENCRYPT_PHRASE'	=> 'Hy6J7SCDFg771A1z',

	'SESSION_PREFIX'	=> 'SKEL_',
	'SESSION_TIME'		=> 10*10*60,
	'HASH_LEN'		=> 64,
	'USE_LANG_SESSION'	=> 1,	
	
	'DEFAULT_STR_CUT'	=> 140,
	'TRANSLIT_URL_LEN'	=> 128,
	'ALLOWED_TAGS'		=> "<b></b><i></i><u></u><a></a><p></p><br><br/><img>",
	'JSON_ALLOWED'		=> (JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
	'JS_VER'		=> rand_id(),
	'DEFAULT_OG_IMAGE'	=> 'themes/default/images/default_og.jpg',
	'SKIP_MARKUP'		=> 1,
	'DEFAULT_LANG'		=> 'ru',
	]);


// PHASE: bootstrap.ini
// соберем все установки по проекту
register_skeleton_core_ini(SKELETON_EVENTS_PATH);		// настройки ядра
register_skeleton_user_ini(USER_ENGINE_PATH."ini/");		// пост-настройки пользователя

// PHASE: bootstrap.functions
// TOFIX: временно!!!
include (SKELETON_CORE_PATH."kernel/engine_functions.php");

register_events_folder(USER_EVENTS_PATH);			// движка
register_events_folder(SKELETON_EVENTS_PATH);			// ядра

// PHASE: bootstrap.router
// запускаем роутер
$o_router = new itRouter(); 	// в itLang подключаются kernel.customs.php перед языками
unset($o_router);

// PHASE: bootstrap.common
// стандартные языковые настройки
register_skeleton_core_common(SKELETON_EVENTS_PATH);

// PHASE: bootstrap.user
global $_USER;
// подключим проверку пользователя
$_USER = new itUser();

// PHASE: bootstrap.customs
register_skeleton_user_custom(USER_ENGINE_PATH."ini/");		// пост-настройки пользователя

// подготовим массивы для работы модератора
if (!defined('NO_PREPARED_ARR') AND function_exists('prepare_global_arrays')) prepare_global_arrays();
?>
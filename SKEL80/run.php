<?php
// ================ CRC ================
// version: 1.35.07
// hash: ce028544794debebb999cacf771a3afd9742148e3207a4d83194079ee867eb55
// date: 28 May 2021  4:42
// ================ CRC ================
//-----------------------------------------------------------------------------
//
//	ЗАЖИГАНИЕ : плавный запуск ядра фреймворка SKELETON
//
//-----------------------------------------------------------------------------
require_once 'kernel/runtime_contract.php';

skel80_runtime_enter_phase('core.primitives');
require_once 'kernel/core.php';
skel80_runtime_enter_phase('session.bootstrap');
// Запускаем сессию
if (!session_start())
	{
	session_regenerate_id();
	session_start();
	}

skel80_runtime_enter_phase('constants.bootstrap');
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

skel80_runtime_enter_phase('config.resolve');
foreach (array_unique($config_candidates) as $config_file)
	{
	if (is_file($config_file))
		{
		include_once($config_file);
		break;
		}
	}

skel80_runtime_enter_phase('paths.user');
set_skeleton_user_ways();
skel80_runtime_enter_phase('paths.core');
set_skeleton_core_ways();

// настраиваем пути пользователя
if (file_exists(USER_ENGINE_PATH.'kernel.path.php'))
	include USER_ENGINE_PATH.'kernel.path.php';

skel80_runtime_enter_phase('paths.runtime_defaults');
// Настраиваем пути
definition([
	'SERVER_ROOT_DEBUG'	=> $_SERVER['DOCUMENT_ROOT'],
	'SERVER_HTTP_DEBUG'	=> get_request_http(),
	'PICTURE_PATH'		=> 'img',	// папка хранения аватарок
	'UPLOADS_PATH'		=> 'uploads',	// папка хранения файлов для загрузки
	]);
//register_skeleton_images_ways();

//зарегистрируем последовательность поиска классов
skel80_runtime_enter_phase('classes.register');
register_classes_folder(USER_CLASSES_PATH);			// движка
register_classes_folder(SKELETON_CLASSES_PATH);			// ядра
register_classes_autoload();

// задекларируем функции
skel80_runtime_enter_phase('engine.register');
register_skleton_engine(USER_CORE_PATH); 			// подключаем пользовательский движок
skel80_runtime_enter_phase('events.core.pre');
register_events_folder(SKELETON_CORE_PATH."kernel/events/");	// системные

// установим константы
skel80_runtime_enter_phase('const.user.pre');
register_skeleton_user_const(USER_ENGINE_PATH."ini/");		// пред-константы пользователя
skel80_runtime_enter_phase('const.core.post');
register_skeleton_core_const(SKELETON_EVENTS_PATH);		// пост-константы ядра

// попробуем настроить основные константы если их нет в движке
skel80_runtime_enter_phase('defaults.core');
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


// соберем все установки по проекту
skel80_runtime_enter_phase('ini.core');
register_skeleton_core_ini(SKELETON_EVENTS_PATH);		// настройки ядра
skel80_runtime_enter_phase('ini.user.post');
register_skeleton_user_ini(USER_ENGINE_PATH."ini/");		// пост-настройки пользователя

// TOFIX: временно!!!
skel80_runtime_enter_phase('functions.core.compat');
include (SKELETON_CORE_PATH."kernel/engine_functions.php");

skel80_runtime_enter_phase('events.user');
register_events_folder(USER_EVENTS_PATH);			// движка
skel80_runtime_enter_phase('events.core.post');
register_events_folder(SKELETON_EVENTS_PATH);			// ядра

// запускаем роутер
skel80_runtime_enter_phase('router.bootstrap');
$o_router = new itRouter(); 	// в itLang подключаются kernel.customs.php перед языками
unset($o_router);

// стандартные языковые настройки
skel80_runtime_enter_phase('common.core');
register_skeleton_core_common(SKELETON_EVENTS_PATH);

global $_USER;
// подключим проверку пользователя
skel80_runtime_enter_phase('user.bootstrap');
$_USER = new itUser();

skel80_runtime_enter_phase('custom.user.post');
register_skeleton_user_custom(USER_ENGINE_PATH."ini/");		// пост-настройки пользователя

// подготовим массивы для работы модератора
skel80_runtime_enter_phase('prepared_arrays.finalize');
if (!defined('NO_PREPARED_ARR') AND function_exists('prepare_global_arrays')) prepare_global_arrays();
?>
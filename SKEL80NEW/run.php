<?
// [ CRC ]
// [ CRC ]
//-----------------------------------------------------------------------------
//
//	ЗАЖИГАНИЕ : плавный запуск ядра фреймворка SKELETON
//
//-----------------------------------------------------------------------------
require_once 'kernel/core.php';

// Запускаем сессию
if (!session_start()) {
	session_regenerate_id();
	session_start();
	}

definition([
	'TAB'		=> "\n\t",
	'TAB2'		=> "\n\t\t",
	'TAB3'		=> "\n\t\t\t",
	'TAB4'		=> "\n\t\t\t\t",
	'TAB5'		=> "\n\t\t\t\t\t",
	'BR'		=> "<br/>",
	'CLEARTEXT'	=> '</b></font>',
	]);

define('DOCUMENT_ROOT', dirname(get_included_files()[0]));
include_once(DOCUMENT_ROOT.'/config.php');
definition([
	'SERVER_ROOT_DEBUG'	=> DOCUMENT_ROOT,
	'SERVER_HTTP_DEBUG'	=> get_request_https(),	
	'PICTURE_PATH'		=> 'img',				// папка хранения аватарок
	'UPLOADS_PATH'		=> 'uploads',			// папка хранения файлов для загрузки
	]);

set_skeleton_user_ways();
set_skeleton_core_ways();

// настраиваем пути пользователя
if (file_exists(USER_ENGINE_PATH.'kernel.path.php'))
	include USER_ENGINE_PATH.'kernel.path.php';

// Настраиваем пути
//зарегистрируем последовательность поиска классов
register_classes_folder(USER_CLASSES_PATH);			// движка
register_classes_folder(SKELETON_CLASSES_PATH);			// ядра
register_classes_autoload();

// задекларируем функции
register_skleton_engine(USER_CORE_PATH); 			// подключаем пользовательский движок
register_parts_folder(SKELETON_CORE_PATH."kernel/parts/");	// системные

// установим константы
register_skeleton_user_const(USER_ENGINE_PATH."ini/");		// пред-константы пользователя
register_skeleton_core_const(SKELETON_EVENTS_PATH);		// пост-константы ядра

// попробуем настроить основные константы если их нет в движке
definition([
	// 'ENCRYPT_PHRASE'	=> 'Hy6J7SCDFg771A1z',
	'ENCRYPT_PHRASE'	=> '5hd95jdJ95hazm2v',

	'SESSION_PREFIX'	=> 'SKEL_',
	'SESSION_TIME'		=> 10*10*60,
	'HASH_LEN'		=> 64,
	'USE_LANG_SESSION'	=> 1,	
	
	'DEFAULT_STR_CUT'	=> 158,
	'TRANSLIT_URL_LEN'	=> 128,
	'ALLOWED_TAGS'		=> "<b></b><i></i><u></u><a></a><p></p><br><br/><img>",
	'JSON_ALLOWED'		=> (ENT_QUOTES | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
	'JS_VER'		=> rand_id(),
	'DEFAULT_OG_IMAGE'	=> 'themes/default/images/default_og.jpg',
	'SKIP_MARKUP'		=> 1,
	'DEFAULT_LANG'		=> 'ru',
	]);

// соберем все установки по проекту
register_skeleton_core_ini(SKELETON_EVENTS_PATH);		// настройки ядра
register_skeleton_user_ini(USER_ENGINE_PATH."ini/");		// пост-настройки пользователя

// TOFIX: временно!!!
include (SKELETON_CORE_PATH."kernel/engine_functions.php");

register_parts_folder(USER_EVENTS_PATH);			// движка
register_parts_folder(SKELETON_EVENTS_PATH);			// ядра

// запускаем роутер
$o_router = new itRouter(); 	// в itLang подключаются kernel.customs.php перед языками
unset($o_router);

// стандартные языковые настройки
register_skeleton_core_common(SKELETON_EVENTS_PATH);

global $_USER;
// подключим проверку пользователя
$_USER = new itUser();

register_skeleton_user_custom(USER_ENGINE_PATH."ini/");		// пост-настройки пользователя

// подготовим массивы для работы модератора
if (!defined('NO_PREPARED_ARR') AND function_exists('prepare_global_arrays')) prepare_global_arrays();
?>
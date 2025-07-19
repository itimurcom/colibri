<?
//-----------------------------------------------------------------------------
//
//	CORE : набор основных примитивных функций для инициплизации
//
//-----------------------------------------------------------------------------

//..............................................................................
// подключает функции пользовательского движка
//..............................................................................
function register_skleton_engine($path) {
	foreach (glob($path."engine_*.php") as $partical) {
		include $partical;
		}

		if (is_array($dir_arr = glob($path."*", GLOB_ONLYDIR))) {
		foreach ($dir_arr as $folder)
			{
			foreach ( ($dirs = glob($folder. '/*', GLOB_ONLYDIR)) as $dirname) {
				register_skleton_engine($dirname);
				}

			foreach (glob("{$folder}/engine_*.php") as $filename) {
				include $filename;
				}

			if (file_exists("{$folder}/events")) {
				core_load_func("{$folder}/events");
				}

			if (file_exists("{$folder}/parts")) {
				core_load_func("{$folder}/parts");
				}

			}
		}
	}

//..............................................................................
// автоматически загружает пользовательские настройки
//..............................................................................
function register_skeleton_user_ini($path)
	{
	if (is_dir($path) AND is_array($dir_arr = glob($path."ini.*.php")))
		{
		foreach ($dir_arr as $ini) {
			include $ini;
			}
		}
	}	

//..............................................................................
// загружает пользовательские ПОСТ настройки (после роутера и $_USER)
//..............................................................................
function register_skeleton_user_custom($path)
	{
	if (is_dir($path) AND is_array($dir_arr = glob($path."custom.*.php")))
		{
		foreach ($dir_arr as $ini)
			{
			include $ini;
			}
		}
	}	


//..............................................................................
// автоматически загружает установки фреймворка
//..............................................................................
function register_skeleton_core_ini($path)
	{
	if (is_array($dir_arr = glob($path."*", GLOB_ONLYDIR)))
		{
		foreach ($dir_arr as $folder)
			{
			if (file_exists($ini = $folder."/ini/ini.php"))
				{
				include $ini;
				}
			}
		}
	}

//..............................................................................
// автоматически загружает константы фреймворка
//..............................................................................
function register_skeleton_core_const($path)
	{
	if (is_array($dir_arr = glob($path."*", GLOB_ONLYDIR)))
		{
		foreach ($dir_arr as $folder)
			{
			if (file_exists($const = $folder."/ini/const.php"))
				{
				include $const;
				}
			}
		}
	}
	
//..............................................................................
// автоматически загружает константы пользователя
//..............................................................................
function register_skeleton_user_const($path)
	{
		// echo $path; die;
	if (is_dir($path) AND is_array($dir_arr = glob($path."const.*.php")))
		{
		foreach ($dir_arr as $const)
			{
			include $const;
			}
		}
	}

//..............................................................................
// декларируем функции рекурсивные по пути
//..............................................................................
function register_parts_folder($path)	
	{
    	if (is_array($dir_arr = glob($path."*", GLOB_ONLYDIR)))
    		{
	    	foreach ($dir_arr as $folder)
			{
			foreach ( ($dirs = glob($folder. '/*', GLOB_ONLYDIR)) as $dirname) 
				{
				register_parts_folder($dirname);
				}
			core_load_func($folder);
			}
		}
	}

//..............................................................................
// загрузка функции если она не определена
//..............................................................................
function core_load_func($folder) {
	foreach (glob("{$folder}/*.func.php") as $filename)	{
		$func = str_replace('.func.php', '', basename($filename));
		if (!function_exists($func)) {
			include $filename;
			}
		}
	}

//..............................................................................
// настройка пути для поиска классов внутри указанного каталога
//..............................................................................
function register_classes_folder($path)
	{
	$res_path = NULL;
	if (is_dir($path))
		{
		$res_path .=  PATH_SEPARATOR.$path;
		// в подкаталогах
		if (is_array($dir_arr = glob($path."*", GLOB_MARK | GLOB_ONLYDIR)))
			{
			$res_path .= PATH_SEPARATOR.implode(PATH_SEPARATOR, array_values($dir_arr));
			}
		set_include_path(get_include_path().$res_path);			
		}
	}
	
//..............................................................................
// автоматически загружает все классы
//..............................................................................
function register_classes_autoload()
	{
	// You can use this trick to make autoloader look for commonly used "My.class.php" type filenames
	spl_autoload_extensions('.class.php');

	// Use default autoload implementation
    	spl_autoload_register(function ($class)
		{
		if ( (include "{$class}.class.php")==false)
			{
			echo ("<font color=red>Error loading class : <b>{$class}</b></font><br/>");die;
			} 
		});
	}

//..............................................................................
// устанавливает константы из массива данных, если они не установлены
//..............................................................................
function definition($array)
	{
	if (is_array($array))
		{
		foreach ($array as $key=>$row)
			{
			if (!defined($key))
				{
				define($key, $row);
				}
			}
		}	
	}

//..............................................................................
// возвращает разыменование константы если есть или само название если нету
//..............................................................................
function get_const($name='') {
	if (str_contains($name, '::') OR is_array($name)) return $name;
	if (defined($name))
		{
		return constant($name);
		} else 	{
			if (defined('BETA_ENABLED') AND BETA_ENABLED AND function_exists('add_error_message'))
				{
				add_error_message("Constant <b>$name</b> not setted.", true);
				}
			return $name;
			}
	}

//..............................................................................
// возвращает значение переменной, если она остановлена
//..............................................................................
function ready_val(&$val, $default=NULL) {
	return isset($val) ? $val : (!is_null($default) ? get_const($default) : NULL);	
	}

//..............................................................................
// возвращает индекс для начала списка элементов
//..............................................................................
function rand_id()
	{
	return rand(10000000,99999999);
	}

//..............................................................................
// поверяет все ли переменные, которые переданы - empty
//..............................................................................
function mempty(...$args)
	{
	foreach($args as $value)
		if(empty($value)) continue;
			else return false;
	return true;
	}

//..............................................................................
// поверяет все ли переменные, которые переданы - NULL
//..............................................................................
function mis_null(...$args)
	{
	foreach($args as $value)
		if(is_null($value)) continue;
			else return false;
	return true;
	}
	
//..............................................................................
// удобный вывод массива
//..............................................................................
function print_rr($var) { return pprint_r($var); }
function pprint_r($var)
	{
	return 
		TAB."<code><pre>".print_r($var, true).TAB."</pre></code>";
	}

//..............................................................................
// рекурсивный glob для списка файлов
//..............................................................................
function rglob($pattern, $flags = 0) {
    $files = glob($pattern, $flags); 
    foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
        $files = array_merge($files, rglob($dir.'/'.basename($pattern), $flags));
    }
    return $files;
}


//..............................................................................
// установка путей для фреймворка
//..............................................................................
function set_skeleton_core_ways()
	{
	// установлено пользователем SKELETON_CORE_PATH?
	if (!defined('SKELETON_CORE_PATH'))
		define('SKELETON_CORE_PATH', dirname(__DIR__)."/");

	// установим остальные пути
	foreach(explode(',', 'CLASSES,EVENTS,JS,CSS') as $part)
		{
		// установлено пользователем?
		if (!defined("SKELETON_{$part}_PATH"))
			define("SKELETON_{$part}_PATH", SKELETON_CORE_PATH.strtolower($part)."/");	
		}
	}


//..............................................................................
// установка пути на движок пользовательского сайта
//..............................................................................
function set_skeleton_user_ways()
	{
	// установлено пользователем в kernel.php?
	if (!defined('USER_ENGINE_PATH'))
		{	
		$inc_arr = get_included_files();
		if (isset($inc_arr[1]) AND strpos($inc_arr[1], 'kernel.php'))
			{
			// найден путь загрузки ядра пользовательского проекта
			define('USER_ENGINE_PATH', str_replace("kernel.php","", $inc_arr[1]));
			}
		}

	// установим путь к пользовательским скриптам
	if (!defined('USER_JS_PATH'))
		{	
		define('USER_JS_PATH', USER_ENGINE_PATH."js/");
		}

	// установим путь к пользовательскому ядру
	if (!defined('USER_CORE_PATH'))
		{	
		define('USER_CORE_PATH', USER_ENGINE_PATH."core/");
		}

	// установим остальные пути
	foreach(explode(',', 'CLASSES,EVENTS') as $part)
		{
		// установлено пользователем?
		if (!defined("USER_{$part}_PATH"))
			define("USER_{$part}_PATH", USER_CORE_PATH.strtolower($part)."/");	
		}

	// установим путь к контроллерам
	if (!defined('CONTROLLER_DIR'))
		{	
		define('CONTROLLER_DIR', DOCUMENT_ROOT."/mvc/controllers/");
		}

	// установим путь к видам
	if (!defined('VIEW_DIR'))
		{	
		define('VIEW_DIR', DOCUMENT_ROOT."/mvc/views/");
		}

	//путь к теме
	if (!defined('THEME_CSS_PATH'))
		{	
		define('THEME_CSS_PATH', 'themes/'.CMS_THEME.'/css/');
		}

	//путь к таблицам стилей пользователя
	if (!defined('USER_CSS_PATH'))
		{	
		define('USER_CSS_PATH', DOCUMENT_ROOT.'/themes/'.CMS_THEME.'/css/');
		}
	}
	
//..............................................................................
// автоматически загружает настройки фреймворка которые не были установлены
//..............................................................................
function register_skeleton_core_common($path)
	{
	if (is_array($dir_arr = glob($path."*", GLOB_ONLYDIR)))
		{
		foreach ($dir_arr as $folder)
			{
			if (file_exists($default = $folder."/ini/common.php"))
				{
				include $default;
				}
			}
		}
	}

//..............................................................................
// возвращает адрес сайта страницы
//..............................................................................
function get_request_https()
	{
	return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://".$_SERVER['HTTP_HOST'];
	}
?>
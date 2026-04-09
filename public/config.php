<?php

if (!function_exists('skel80_runtime_env'))
	{
	function skel80_runtime_env($name, $default = NULL)
		{
		$value = getenv($name);
		return ($value !== false) ? $value : $default;
		}
	}

if (!function_exists('skel80_project_config_apply_file'))
	{
	function skel80_project_config_apply_file(array $config, $file)
		{
		if (!is_file($file))
			{
			return $config;
			}

		$loaded = include $file;
		if (is_array($loaded))
			{
			$config = array_replace($config, $loaded);
			}

		return $config;
		}
	}

if (!function_exists('skel80_project_config_define'))
	{
	function skel80_project_config_define($name, $value)
		{
		if (!defined($name))
			{
			define($name, $value);
			}
		}
	}

$project_config = [
	'CMS_DB_SERVER' => 'localhost',
	'CMS_DB_USER' => 'colibri',
	'CMS_DB_PASS' => 'cqD4Rv8hSY8BjHtb',
	'CMS_DB_NAME' => 'colibri',
	'CMS_DB_CODEPAGE' => 'utf8mb4',
	'DB_PREFIX' => 'colibri_',
	'SITE_NAME' => 'colibrinew',
	'BETA_ENABLED' => 0,
	'SKIP_MINIFY' => 1,
	'USE_CAPTCHA' => false,
	'CMS_AUTHOR' => 'itimur.com',
	'CMS_THEME' => 'default',
	'DEFAULT_LANG' => 'en',
	'CATEGORY_FOR_SALE' => '6',
	'ALOW_JIVOSITE' => NULL,
	'DENY_JIVOSITE' => 'settings,mailing',
	'CMS_RUNTIME_ENV' => skel80_runtime_env('CMS_RUNTIME_ENV', 'dev'),
	'CMS_LOG_ERRORS' => 1,
	'CMS_DISPLAY_ERRORS' => NULL,
	'CMS_RUNTIME_LOG_FILE' => __DIR__.'/logs/php-runtime.log',
	'CMS_DEFAULT_TIMEZONE' => skel80_runtime_env('CMS_DEFAULT_TIMEZONE', 'Europe/Kyiv'),
	'CMS_ERROR_REPORTING' => E_ALL,
];

foreach ([__DIR__.'/config.secrets.php', __DIR__.'/config.secrets.local.php'] as $config_file)
	{
	$project_config = skel80_project_config_apply_file($project_config, $config_file);
	}

$env_map = [
	'CMS_DB_SERVER',
	'CMS_DB_USER',
	'CMS_DB_PASS',
	'CMS_DB_NAME',
	'CMS_DB_CODEPAGE',
	'DB_PREFIX',
	'SITE_NAME',
	'CMS_THEME',
	'DEFAULT_LANG',
	'CATEGORY_FOR_SALE',
	'ALOW_JIVOSITE',
	'DENY_JIVOSITE',
	'CMS_RUNTIME_ENV',
	'CMS_LOG_ERRORS',
	'CMS_DISPLAY_ERRORS',
	'CMS_RUNTIME_LOG_FILE',
	'CMS_DEFAULT_TIMEZONE',
	'CMS_ERROR_REPORTING',
];

foreach ($env_map as $env_name)
	{
	$env_value = skel80_runtime_env($env_name, NULL);
	if ($env_value !== NULL)
		{
		$project_config[$env_name] = $env_value;
		}
	}

if ($project_config['CMS_DISPLAY_ERRORS'] === NULL)
	{
	$project_config['CMS_DISPLAY_ERRORS'] = ($project_config['CMS_RUNTIME_ENV'] === 'prod') ? 0 : 1;
	}

// установки сервера и базы данных
skel80_project_config_define('CMS_DB_SERVER', $project_config['CMS_DB_SERVER']);
skel80_project_config_define('CMS_DB_USER', $project_config['CMS_DB_USER']);
skel80_project_config_define('CMS_DB_PASS', $project_config['CMS_DB_PASS']);
skel80_project_config_define('CMS_DB_NAME', $project_config['CMS_DB_NAME']);
skel80_project_config_define('CMS_DB_CODEPAGE', $project_config['CMS_DB_CODEPAGE']);
skel80_project_config_define('DB_PREFIX', $project_config['DB_PREFIX']);

// адрес и название проекта
skel80_project_config_define('SITE_NAME', $project_config['SITE_NAME']);

// runtime baseline
skel80_project_config_define('CMS_RUNTIME_ENV', $project_config['CMS_RUNTIME_ENV']);
skel80_project_config_define('CMS_LOG_ERRORS', $project_config['CMS_LOG_ERRORS']);
skel80_project_config_define('CMS_DISPLAY_ERRORS', $project_config['CMS_DISPLAY_ERRORS']);
skel80_project_config_define('CMS_RUNTIME_LOG_FILE', $project_config['CMS_RUNTIME_LOG_FILE']);
skel80_project_config_define('CMS_DEFAULT_TIMEZONE', $project_config['CMS_DEFAULT_TIMEZONE']);
skel80_project_config_define('CMS_ERROR_REPORTING', $project_config['CMS_ERROR_REPORTING']);

// включение лога запросов
skel80_project_config_define('BETA_ENABLED', $project_config['BETA_ENABLED']);
skel80_project_config_define('SKIP_MINIFY', $project_config['SKIP_MINIFY']);
skel80_project_config_define('USE_CAPTCHA', $project_config['USE_CAPTCHA']);

// установки CMS
skel80_project_config_define('CMS_AUTHOR', $project_config['CMS_AUTHOR']);
skel80_project_config_define('CMS_THEME', $project_config['CMS_THEME']);
skel80_project_config_define('DEFAULT_LANG', $project_config['DEFAULT_LANG']);

if (!defined('SERVER_ROOT_DEBUG')) {
	define('SERVER_ROOT_DEBUG', __DIR__);
}

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$http_host = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$full_url = $protocol . '://' . $http_host;
skel80_project_config_define('SERVER_HTTP_DEBUG', $full_url.'/');

// категории товаров на продажу (через запятую)
skel80_project_config_define('CATEGORY_FOR_SALE', $project_config['CATEGORY_FOR_SALE']);

// контроллеры, по которым есть чат поддержки
skel80_project_config_define('ALOW_JIVOSITE', $project_config['ALOW_JIVOSITE']);
skel80_project_config_define('DENY_JIVOSITE', $project_config['DENY_JIVOSITE']);
?>

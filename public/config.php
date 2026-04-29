<?php

$cms_config = [
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
    'ALOW_JIVOSITE' => null,
    'DENY_JIVOSITE' => 'settings,mailing',

    'CMS_ERROR_REPORTING' => E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_USER_DEPRECATED,
    'CMS_DISPLAY_ERRORS' => true,
    'CMS_LOG_ERRORS' => true,
    'CMS_DEFAULT_TIMEZONE' => 'Europe/Kyiv',
    'CMS_RUNTIME_LOG_FILE' => __DIR__.'/logs/php-runtime.log',
];

foreach (['config.local.php', 'config.secrets.php', 'config.secrets.local.php'] as $overlay_file)
    {
    $overlay_path = __DIR__.'/'.$overlay_file;
    if (is_file($overlay_path))
        {
        $overlay = include $overlay_path;
        if (is_array($overlay))
            {
            $cms_config = array_replace($cms_config, $overlay);
            }
        }
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
    'CMS_RUNTIME_LOG_FILE',
    'CMS_DEFAULT_TIMEZONE',
];

foreach ($env_map as $env_key)
    {
    $env_value = getenv($env_key);
    if ($env_value !== false && $env_value !== '')
        {
        $cms_config[$env_key] = $env_value;
        }
    }

foreach ($cms_config as $key => $value)
    {
    if (!defined($key))
        {
        define($key, $value);
        }
    }

if (!defined('SERVER_ROOT_DEBUG'))
    {
    define('SERVER_ROOT_DEBUG', __DIR__);
    }

$protocol = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['SERVER_PORT']) && (string)$_SERVER['SERVER_PORT'] === '443')) ? 'https' : 'http';
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';

if (!defined('SERVER_HTTP_DEBUG'))
    {
    define('SERVER_HTTP_DEBUG', $protocol.'://'.$host.'/');
    }

$host_no_port = preg_replace('/:\d+$/', '', $host);

if (!defined('CMS_CURRENT_SCHEME'))
    {
    define('CMS_CURRENT_SCHEME', $protocol);
    }

if (!defined('CMS_CURRENT_HOST'))
    {
    define('CMS_CURRENT_HOST', $host);
    }

if (!defined('CMS_CURRENT_HOST_NO_PORT'))
    {
    define('CMS_CURRENT_HOST_NO_PORT', $host_no_port);
    }

if (!defined('CMS_CURRENT_EMAIL_DOMAIN'))
    {
    define('CMS_CURRENT_EMAIL_DOMAIN', $host_no_port ?: 'localhost');
    }

if (!defined('CMS_CURRENT_BASE_URL'))
    {
    define('CMS_CURRENT_BASE_URL', CMS_CURRENT_SCHEME.'://'.CMS_CURRENT_HOST);
    }

if (!defined('CMS_CURRENT_BASE_URL_SLASH'))
    {
    define('CMS_CURRENT_BASE_URL_SLASH', CMS_CURRENT_BASE_URL.'/');
    }
?>

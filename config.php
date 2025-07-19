<?

// установки сервера и базы данных
define('CMS_DB_SERVER', 'localhost');
define('CMS_DB_USER', 'colibri');
define('CMS_DB_PASS', 'cqD4Rv8hSY8BjHtb');
define('CMS_DB_NAME', 'colibri2');
define('CMS_DB_CODEPAGE', 'utf8mb4');
define('DB_PREFIX','colibri_');

// адрес и название проекта
define ('SITE_NAME', 'colibrinew');

// включение лога заросов
define ('BETA_ENABLED', 0);
define ('USE_CAPTCHA', true);

// установки CMS
define('CMS_AUTHOR','itimur.com');
define('CMS_THEME','default');
define('DEFAULT_LANG','en');

define ('SERVER_ROOT_DEBUG', dirname(__FILE__));
define ('SERVER_HTTP_DEBUG', 'https://atelier-colibri.com');

// категории товаров на продажу (через зяпятую)
// 1
// 2
// 3
// 4
// 5
// 6
define('CATEGORY_FOR_SALE', '6');

// контроллеры, по которым есть чат поддержки
// define('ALOW_JIVOSITE', 'home,about,contacts,info,delivery,items');
define('ALOW_JIVOSITE', NULL);
// запрет чатв поддержки или NULL
define('DENY_JIVOSITE', 'settings,mailing');
// define('DENY_JIVOSITE', NULL);
?>

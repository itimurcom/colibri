<?php
// ================ CRC ================
// version: 1.35.03
// hash: 5eb3ab1c32adbcdf9362b4766b647664e2b3e09108ae39f82a700127e71233fe
// date: 17 September 2019 17:56
// ================ CRC ================
definition([
	// itHeader
	'DEFAULT_DESC_LEN'		=> 155,
	'ASYNC_JS_GROUPS'		=> "boot,user,it,jquery,lang", //boot,user,it,jquery,lang
	
	// itRouter
	'DEFAULT_ROUTER_CONTROLLER'	=> 'main',
	'DEFAULT_ROUTER_VIEW'		=> 'main',
	
	// itSettigs
	'DEFAULT_SETTING_TABLE'	=> 'settings',
	'DEFAULT_ONOFF_CLASS'	=> 'onoff',
	
	// itLang
	'LANG_DIR'	=> $_SERVER['DOCUMENT_ROOT'].'/languages/',

	// itErrorMsg
	'DEFAULT_ERROR_COLOR'	=> 'red',
	
	// itFocus
	'DEFAULT_FOCUS_COLOR'	=> 'red',
	
	// itMemCache
	'MEMCAHCED_SESSION_KEY' 	=> 'memcache_hits',
	'MEMCAHCED_KEY'			=> 'CACHE',
	'MEMCAHCEVER'			=> '1.02',
	
	// itMySQL
	'CMS_DB_SERVER'		=> 'localhost',
	'DEFAULT_DB_PORT'	=> '3306',	
	]);
?>
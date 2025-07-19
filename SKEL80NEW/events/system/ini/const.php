<?
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
	'LANG_DIR'	=> DOCUMENT_ROOT.'/languages/',

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
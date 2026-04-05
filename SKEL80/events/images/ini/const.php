<?php
// ================ CRC ================
// version: 1.35.03
// hash: 193c1778e22cfcfbef2a830caf7317bcb798929f9db74a3539f37f0220b19d09
// date: 12 September 2019 17:55
// ================ CRC ================
definition([
	'DEFAULT_IMAGES_TABLE'	=> 'contents',
	'DEFAULT_IMAGES_FIELD'	=> 'images',
	'DEFAULT_IMAGES_COLUMN'	=> 'images',
	'DEFAULT_IMAGES_CLASS'	=> 'gallery_avatar',

	'DEFAULT_IMAGES_TYPE'	=> 'slider', // slider | gallery
	'DEFAULT_IMAGES_IMGTYPE'=> 'ITEM_SHOT',

	'DEFAULT_IMAGES_MODE'	=> 'fade',
	'DEFAULT_IMAGES_PAUSE'	=> '6000',
	'DEFAULT_IMAGESSTATE'	=> 'view',
	'DEFAULT_IMAGES_EDCLASS'=> 'default',
	
	// itResizer
	'LOGO_POSITION'		=> 'RANDOM_BOTTOM', // TOP, TOP_LEFT, TOP_RIGHT, BOTTOM, BOTTOM_LEFT, BOTTOM_RIGHT, CENTER, RANDOM_TOP, RANDOM_BOTTOM, RANDOM_ALL	
	]);

	definition([
		'PICTURE_ROOT'		=> SERVER_ROOT_DEBUG."/".PICTURE_PATH."/",
		'PICTURE_HTTP'		=> SERVER_HTTP_DEBUG."/".PICTURE_PATH."/",
		'UPLOADS_ROOT'		=> SERVER_ROOT_DEBUG."/".UPLOADS_PATH."/",
		'UPLOADS_HTTP'		=> SERVER_HTTP_DEBUG."/".UPLOADS_PATH."/",
		]);

?>
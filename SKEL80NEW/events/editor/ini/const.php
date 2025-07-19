<?
//------------------------------------------------------------------------------
//
// СТАНДАРТНЫЕ НАСТРОЙКИ ДЛЯ РЕДАКТОРА
//
//------------------------------------------------------------------------------
definition([
	'DEFAULT_CONTENT_TABLE' => 'contents',
	'DEFAULT_EDITOR_TABLE'	=> 'contents',
	'DEFAULT_CONTENT_COLUMN'=> 'ed_xml',	
	'DEFAULT_CONTENT_FIELD'	=> 'ed_xml',
	'DEFAULT_FILES_NAME'	=> 'MyFiles',
	'DEFAULT_VIEW_FIELD'	=> 'views',
	'DEFAULT_RELATED_FIELD'	=> 'related_xml',
	
	'DEFAULT_NOLANG'	=> false,
	'DEFAULT_NOTITLE'	=> false,
	'DEFAULT_NODATE'	=> false,
	'DEFAULT_NOAVATAR'	=> true,
	'DEFAULT_NOMODERATE'	=> false,
	'DEFAULT_NORELATED'	=> true,
	'DEFAULT_EDCOMFORT'	=> false,
	'DEFAULT_NOCACHE'	=> false,
	'DEFAULT_EDSTATE'	=> 'view', // view | edit
	'DEFAULT_EDASYNC'	=> true,
	'DEFAULT_EDCLASS'	=> 'default',
	
	'DEFAULT_EDSTORAGE_STATUS'	=> 'PUBLISHED',	
	// включаем или выключаем типы блоков
	'ENABLE_ED_AUDIO'	=> 1,
	'ENABLE_ED_VIDEO'	=> 1,
	'ENABLE_ED_IMAGE'	=> 1,
	'ENABLE_ED_GALLERY'	=> 1,
	'ENABLE_ED_SLIDER'	=> 1,
	'ENABLE_ED_AVATAR'	=> 1,
	'ENABLE_ED_TRANSLATE' 	=> 0,
		
	'DEFAULT_DESCRIPTION_CUT'	=> 320,
	'DEFAULT_RELATED_FIELD'		=> 'related_xml',	
	'DEFAULT_ALLLANG_COLOR'		=> 'brown',
	'ED_DEVIDER'			=> TAB."<div class='ed_devider[EDIT]'>[BUTTONS]</div>",

//	'DEFAULT_SLIDER_COMMON' => "minSlides: 1, maxSlides: 1,  moveSlides : 1, mode : 'horizontal', useCSS : false",		
//	'DEFAULT_SLIDER_MIDDLE' => "minSlides: 3, maxSlides: 3, slideWidth: 360, slideMargin: 12, moveSlides : 1, mode : 'horizontal', useCSS : false",
//	'DEFAULT_SLIDER_SMALL'	=> "minSlides: 5, maxSlides: 5, slideWidth: 360 ,slideMargin: 12, moveSlides : 1, mode : 'horizontal', useCSS : false",
	'TEXT_AVATAR_BIG'		=> 1,
	'DEFAULT_MIXCLOUD_LIGHT'	=> 0,

	'DEFAULT_EDITOR_LANG'	=> get_const('CMS_LANG', 'ua'),
	
	// установки размеров и проигрывателей по умолчанию
	'SOUNDCLOUD_ZOOM'	=> 'SMALL',
	'MIXCLOUD_ZOOM'		=> 'FULL',
	'TUBE_ZOOM'			=> 'FULL',
	'VIMEO_ZOOM'		=> 'FULL',
	'NONE_ZOOM'			=> 'SMALL',
	'SOUNCLOUD_CLIENT'	=> 'f26641bf2ce610a2ad3602b3d273ba55',
	'VIMEO_COLOR'		=> '0000ff',
	
	// itViewed
	'NOT_UPDATE_VIEWED' 	=> 1,
	'MEMCACHED_VIEWED'		=> 1,
	
	// itEdText
	'TEXT_AVATAR_BIG'	 => 1,	

	//  itEdGallery
	'DEFAULT_SLIDER_COMMON' 	=> "minSlides: 1, maxSlides: 1,  moveSlides : 1, mode : 'horizontal', useCSS : false, responsive: true",		
	'DEFAULT_SLIDER_MIDDLE' 	=> "minSlides: 3, maxSlides: 3, slideWidth: 360, slideMargin: 12, moveSlides : 1, mode : 'horizontal', useCSS : false, responsive: true",
	'DEFAULT_SLIDER_SMALL'		=> "minSlides: 5, maxSlides: 5, slideWidth: 360 ,slideMargin: 12, moveSlides : 1, mode : 'horizontal', useCSS : false, responsive: true",
	'DEFAULT_SLIDER_PAUSE'		=> 6000,
	'TEXT_AVATAR_BIG' 			=> 1,
	'DEFAULT_F_CAPTION_FUNC'	=> 'get_gal_f_caption',
	
	// itCats
	'DEFAULT_PARENT_ID'		=> 0,
	'DEFAULT_CATS_TABLE'	=> 'cats',
	'DEFAULT_TOP_LEVEL'		=> 2,
	]);

?>
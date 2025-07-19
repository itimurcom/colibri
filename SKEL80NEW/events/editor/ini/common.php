<?

definition([
	'GAL_UP'			=> '&#9650;',
	'GAL_DOWN'			=> '&#9660;',
	'GAL_LEFT'			=> '&#9664;',
	'GAL_RIGHT'			=> '&#9654;',
	'GAL_DOC'			=> '&#128394;',
	'GAL_LINK'			=> '&#128279;',

	'BUTTON_UP'			=> '&#9650;',
	'BUTTON_DOWN'		=> '&#9660;',
	'BUTTON_LEFT'		=> '&#9664;',
	'BUTTON_RIGHT'		=> '&#9654;',
	'BUTTON_N'			=> '#',
	'BUTTON_ED_REMOVE'	=> '<b>&#215;</b>',

	'ALOWED_MEDIA'			=> serialize(str_getcsv('TUBE,VIMEO,SOUNDCLOUD,MIXCLOUD')),
	'GALLERY_ZOOMS'			=> serialize([2,3,4,5,11,13,15]), // 2,3,4,5,6,11,13,15
	'DEFAULT_GALLERY_ZOOM'	=>  2,
	]);


switch (get_const(CMS_LANG))
	{
	case 'en' : {
		definition([
			'BUTTON_PLUS_FILED'	=> '&#8627; поле',

			// статусы
			'STATUS_MODERATE'	=> 'moderation',
			'STATUS_PUBLISHED'	=> 'published',
			'STATUS_DELETED'	=> 'in trash',

			// кнопки администратора
			'BUTTON_ED_TEXT'	=> '&#8626; text',
			'BUTTON_ED_GALLERY'	=> '&#8626; gallery',
			'BUTTON_ED_SWITCH'	=> '&#9712; size',
			'BUTTON_ED_AVATAR'	=> '&#9703; avatar',
			'BUTTON_ED_AVATAR_REMOVE'	=> '&#215; avatar',
			'BUTTON_ED_SWITCH_LEFT'		=> '&#9664; avatar',
			'BUTTON_ED_SWITCH_RIGHT'	=> 'avatar &#9654;',

			'BUTTON_ED_AUDIO'	=> '&#8626; audio',
			'BUTTON_ED_VIDEO'	=> '&#8626; video',
			'BUTTON_ED_MEDIA'	=> '&#8626; media',
			'BUTTON_ED_IMAGE'	=> '&#8626; photo',
			'BUTTON_ED_CHANGE'	=> '&#9776; change',
			
			'BUTTON_MODERATE'	=> 'on moderation',
			'BUTTON_ED_TITLE'	=> 'Title',
				
			'BUTTON_OK'		=> 'Ok',
			'BUTTON_CANCEL'	=> 'Cancel',
			'BUTTON_ADD'	=> 'Add',
			'BUTTON_REMOVE'	=> 'Remove',
			]);		
		break;
		}
	default: {
		definition([
			'BUTTON_PLUS_FILED'	=> '&#8627; поле',

			// статусы
			'STATUS_MODERATE'	=> 'на модерации',
			'STATUS_PUBLISHED'	=> 'опубликовано',
			'STATUS_DELETED'	=> 'в корзине',

			// кнопки администратора
			'BUTTON_ED_TEXT'	=> '&#8626; текст',
			'BUTTON_ED_GALLERY'	=> '&#8626; галлерея',
			'BUTTON_ED_SWITCH'	=> '&#9712; размер',
			'BUTTON_ED_AVATAR'	=> '&#9703; аватар',
			'BUTTON_ED_AVATAR_REMOVE'	=> '&#215; аватар',
			'BUTTON_ED_SWITCH_LEFT'		=> '&#9664; аватар',
			'BUTTON_ED_SWITCH_RIGHT'	=> 'аватар &#9654;',

			'BUTTON_ED_AUDIO'	=> '&#8626; аудио',
			'BUTTON_ED_VIDEO'	=> '&#8626; видео',
			'BUTTON_ED_MEDIA'	=> '&#8626; медиа',
			'BUTTON_ED_IMAGE'	=> '&#8626; фото',
			'BUTTON_ED_CHANGE'	=> '&#9776; изменить',
			
			'BUTTON_MODERATE'	=> 'На модерацию',
			'BUTTON_ED_TITLE'	=> 'Название',
				
			'BUTTON_OK'		=> 'Ok',
			'BUTTON_CANCEL'	=> 'Отменa',
			'BUTTON_ADD'	=> 'Добавить',
			'BUTTON_REMOVE'	=> 'Удалить',
			]);
		break;
		}
	}
?>
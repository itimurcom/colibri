<?
// подкинем случайную аватарку
global $no_avatar;

$no_avatar[] = 'noavatar_red.png';
$no_avatar[] = 'noavatar_green.png';
$no_avatar[] = 'noavatar_blue.png';

// Определяем массив спецификаций картинок :)
global $pic_tech;
$pic_tech['ED_NEWS'] = array (
	'sx'	=> 908,
	'sy' 	=> 220,
	'name'	=> 'ednews_',
	'crop'	=> 1,
	'quality'=> 100,
	'logo'	=> '');	

$pic_tech['ED_AVATAR'] = array (
	'sx'	=> 320,
	'sy' 	=> 240,
	'name'	=> 'edavatar_',
	'crop'	=> 1,
	'quality'=> 100,
	'logo'	=> '');		


$pic_tech['OG_AVATAR'] = array (
	'sx'	=> 640,
	'sy' 	=> 480,
	'name'	=>'avatarog_',
	'crop'	=> 1,
	'quality'=> 90,
	'place'=> 'CENTER',
	'logo' => "/themes/".CMS_THEME."/images/logo_og.png");

$pic_tech['EDIMAGE'] = array (
	'sx'	=> 1024,
	'sy' 	=> 506,
	'name'	=>'edimage_',
	'crop'	=> 0,
	'quality'=> 100,
	'logo' => '');

$pic_tech['EDIMAGE_SMALL'] = array (
	'sx'	=> 1024,
	'sy' 	=> 276,
	'name'	=>'edimagesmall_',
	'crop'	=> 1,
	'quality'=> 100,
	'logo' => '');

$pic_tech['USER_AVATAR'] = array (
	'sx'	=> 64,
	'sy' 	=> 64,
	'name'	=>'uava_',
	'crop'	=> 1,
	'quality'=> 100,
	'logo' => '');

$pic_tech['USER_PHOTO'] = array (
	'sx'	=> 480,
	'sy' 	=> 480,
	'name'	=>'uphoto_',
	'crop'	=> 1,
	'quality'=> 100,
	'logo' => '');

$pic_tech['NEWS_AVATAR'] = array (
	'sx'	=> 320,
	'sy' 	=> 240,
	'name'	=>'newsava_',
	'crop'	=> 0,
	'quality'=> 100,
	'logo' 	=> '');

$pic_tech['NEWS_AVATAR_BIG'] = array (
	'sx'	=> 640,
	'sy' 	=> 480,
	'name'	=>'newsavabig_',
	'crop'	=> 0,
	'quality'=> 100,
	'logo' 	=> '');

$pic_tech['ITEM_SHOT'] = array (
	'sx'	=> 480,
	'sy' 	=> 480,
	'name'	=>'itemshot_',
	'crop'	=> 1,
	'quality'=> 100,
	'logo' => '');

$pic_tech['ITEM_BIG'] = array (
	'sx'	=> 1400,
	'sy' 	=> 1400,
	'name'	=>'itembig_',
	'crop'	=> 0,
	'quality'=> 100,
	'logo' => '');

$pic_tech['ADMIN_AVATAR'] = array (
	'sx'	=> 82, //2.75
	'sy' 	=> 30,
	'name'	=>'advavatar_',
	'crop'	=> 1,
	'quality'=> 100,
	'logo' => '');

$pic_tech['RELATED_AVATAR'] = array (
	'sx'	=> 288,
	'sy' 	=> 192,
	'name'	=>'relavatar_',
	'crop'	=> 1,
	'quality'=> 100,
	'logo' => '');

// Слайдеры по старому

$pic_tech['SLIDER_COMMON'] = array (
	'sx'	=> 800,
	'sy' 	=> 320,
	'name'	=>'slidercommon_',
	'crop'	=> 1,
	'quality'=> 100,
	'logo' => '');

$pic_tech['SLIDER_MIDDLE'] = array (
	'sx'	=> 240,
	'sy' 	=> 180,
	'name'	=>'slidermiddle_',
	'crop'	=> 1,
	'quality'=> 100,
	'logo' => '');

$pic_tech['SLIDER_SMALL'] = array (
	'sx'	=> 240,
	'sy' 	=> 200,
	'name'	=>'slidersmall_',
	'crop'	=> 1,
	'quality'=> 100,
	'logo' => '');
	
// Галлереи изображений
$pic_tech['GALLINE'] = array (
	'sx'	=> 800,
	'sy' 	=> 480,
	'name'	=>'galline_',
	'crop'	=> 1,
	'quality'=> 90,
	'logo' => '');
                      
$pic_tech['GALLINE_MIDDLE'] = array (
	'sx'	=> 180,
	'sy' 	=> 240,
	'name'	=>'gallinemiddle_',
	'crop'	=> 1,
	'quality'=> 90,
	'logo' => '');

$pic_tech['GALLINE_SMALL'] = array (
	'sx'	=> 180,
	'sy' 	=> 240,
	'name'	=>'gallinesmall_',
	'crop'	=> 1,
	'quality'=> 90,
	'logo' => '');

$pic_tech['GALLINE_ULTRA'] = array (
	'sx'	=> 120,
	'sy' 	=> 160,
	'name'	=>'gallineultra_',
	'crop'	=> 1,
	'quality'=> 100,
	'logo' => '');
	
$pic_tech['SERIE_AVATAR'] = array (
	'sx'	=> 180,
	'sy' 	=> 240,
	'name'	=>'serieavatar_',
	'crop'	=> 1,
	'quality'=> 100,
	'logo'	=>'');

$pic_tech['UPLOADED_AVA'] = array (
	'sx'	=> 64,
	'sy' 	=> 64,
	'name'	=>'upgalava_',
	'crop'	=> 1,
	'quality'=> 100,
	'logo'	=>'');

$pic_tech['GAL_MAIL'] = array (
	'sx'	=> 640,
	'sy' 	=> 480,
	'name'	=>'galmail_',
	'crop'	=> 1,
	'quality'=> 100,
	'logo' => '');

$pic_tech['BACKGROUND'] = array (
	'sx'	=> 1280,
	'sy' 	=> 720,
	'name'	=> 'bg_',
	'crop'	=> 1,
	'quality'=> 80,
	'logo'	=> '');	
?>
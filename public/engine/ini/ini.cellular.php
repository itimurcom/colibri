<?
//------------------------------------------------------------------------------
//
//	МЕНЮ МОБИЛНОГО УСТРОЙСТВА
//
//------------------------------------------------------------------------------
global $_CELL;
define('CELL_BUTTONS', 0);

/*
Главная 
Заказ
Все предложения

ХГ купальники 
ХГ комбинезоны 
Акробатика
Фк
Водные виды спорта

Новые предложения
Эконом предложения
Магазин
Аксессуары
Ткани
Эскизы

Информация
Доставка и оплата 
О нас
Наши друзья
контакты
*/

$_CELL['home'] = [
	'title'		=> 'NODE_HOME',
	'controller'	=> 'home',
	'view'		=> NULL,
	'show'		=> 1,
	];
	
$_CELL['register'] = [
	'title'		=> 'NODE_REGISTER',
	'controller'	=> 'register',
	'view'		=> NULL,
	'class'		=> 'register-btn',
	'show'		=> 1,
	];

$_CELL['order'] = [
	'title'		=> 'NODE_ORDER',
	'controller'	=> 'order',
	'view'		=> NULL,
	'show'		=> 1,
	];
	
$_CELL['items'] = [
	'title'		=> 'NODE_GALLERY',
	'controller'	=> 'items',
	'view'		=> 'items',
	'show'		=> 1,
	];

$_CELL[] = NULL;



$_CELL['gymnastics'] = [
	'controller'	=> 'items',
	'view'		=> 'gymnastics',
	'title' => 'NODE_GYMNASTICS',
	'avatar'=> 'node_rg.png',	
	'cell'	=> CELL_BUTTONS,
	'show'	=> 1,
	];


$_CELL['unitards'] = [
	'controller'	=> 'items',
 	'view'		=> 'unitards',
	'title' => 'NODE_UNITARDS',
	'avatar'=> 'node_unitards.png',	
	'cell'	=> CELL_BUTTONS,
	'show'	=> 1,
	];

$_CELL['skating'] = [
	'controller'	=> 'items',
	'view'		=> 'skating',
	'title' => 'NODE_SKATING',
	'avatar'=> 'node_skating.png',	
	'cell'	=> CELL_BUTTONS,
	'show'	=> 1,
	];

$_CELL['acrobatics'] = [
	'controller'	=> 'items',
	'view'		=> 'acrobatics',
	'title' => 'NODE_ACROBATICS',
	'avatar'=> 'node_acrobatics.png',
	'cell'	=> CELL_BUTTONS,
	'show'	=> 1,
	];

$_CELL['watersports'] = [
	'controller'	=> 'items',
	'view'		=> 'watersports',
	'title' => 'NODE_WATERSPORTS',
	'avatar'=> 'node_watersports.png',	
	'cell'	=> CELL_BUTTONS,
	'show'	=> 1,
	];

$_CELL[] = NULL;

$_CELL['new'] = [
	'controller'	=> 'items',
	'view'		=> 'new',
	'title' => 'NODE_NEW',
	'show'	=> 1,
	];

$_CELL['econom'] = [
	'controller'	=> 'items',
	'view'		=> 'econom',
	'title' => 'NODE_ECONOM',
	'show'	=> 1,
	];
	
$_CELL['shop'] = [
	'title'		=> 'NODE_SHOP',
	'controller'	=> 'shop',
	'view'		=> NULL,
	'hot'		=> true,
	'show'		=> 1,
	];	

$_CELL['accessories'] = [
	'controller'	=> 'items',
	'view'		=> 'accessories',
	'title' => 'NODE_ACCESSORIES',
	'avatar'=> 'node_accessories.png',	
	'cell'	=> CELL_BUTTONS,
	'show'	=> 1,
	];

$_CELL['fabric'] = [
	'title'		=> 'NODE_FABRIC',
	'controller'	=> 'fabric',
	'view'		=> NULL,
	'show'		=> 1,
	];

$_CELL['design'] = [
	'title'		=> 'NODE_DESIGN',
	'controller'	=> 'design',
	'view'		=> NULL,
	'show'		=> 1,
	];

$_CELL[] = NULL;


$_CELL['info'] = [
	'controller'	=> 'info',
	'view'		=> NULL,
	'title' => 'NODE_INFO',
	'show'	=> 1,
	];

$_CELL['delivery'] = [
	'title'		=> 'NODE_DELIVERY',
	'controller'	=> 'delivery',
	'view'		=> NULL,
	'show'		=> 1,
	];
	
$_CELL['about'] = [
	'title'		=> 'NODE_ABOUT',
	'controller'	=> 'about',
	'view'		=> NULL,
	'show'		=> 1,
	];

$_CELL['friends'] = [
	'title'		=> 'NODE_FRIENDS',
	'controller'	=> 'friends',
	'view'		=> NULL,
	'show'		=> 1,
	];

$_CELL['contacts'] = [
	'title'		=> 'NODE_CONTACTS',
	'controller'	=> 'contacts',
	'view'		=> NULL,
	'show'		=> 1,
	];

?>
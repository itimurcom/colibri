<?php
//------------------------------------------------------------------------------
//
//	МАССИВЫ ДАННЫХ ДЛЯ РАБОТЫ ВЕРХНЕГО МЕНЮ
//
//------------------------------------------------------------------------------
global $a_menu;
// верхнее меню
$a_menu['home'] = [
	'title'		=> 'NODE_HOME',
	'controller'	=> 'home',
	'view'		=> NULL,
	'show'		=> 2,
	'sitemap'	=> 1,
	];

$a_menu['about'] = [
	'title'		=> 'NODE_ABOUT',
	'controller'	=> 'about',
	'view'		=> NULL,
	'show'		=> 2,
	'sitemap'	=> 1,
	];

$a_menu['items'] = [
	'title'		=> 'NODE_GALLERY',
	'controller'	=> 'items',
	'view'		=> NULL,
	'show'		=> 2,
	'sitemap'	=> 1,
	];
	
$a_menu['shop'] = [
	'title'		=> 'NODE_SHOP',
	'controller'	=> 'shop',
	'view'		=> NULL,
	'hot'		=> true,
	'show'		=> 2,
	'sitemap'	=> 1,
	];
	
$a_menu['replicants'] = [
	'title'		=> 'NODE_REPLICANTS',
	'controller'	=> 'items',
	'view'		=> 'replicants',
	'show'		=> 0,
	'sitemap'	=> 1,
	];

$a_menu['order'] = [
	'title'		=> 'NODE_ORDER',
	'controller'	=> 'order',
	'view'		=> NULL,
	'show'		=> 1,
	'sitemap'	=> 1,
	];

$a_menu['delivery'] = [
	'title'		=> 'NODE_DELIVERY',
	'controller'	=> 'delivery',
	'view'		=> NULL,
	'show'		=> 2,
	'sitemap'	=> 1,
	];

$a_menu['friends'] = [
	'title'		=> 'NODE_FRIENDS',
	'controller'	=> 'friends',
	'view'		=> NULL,
	'show'		=> 2,
	'sitemap'	=> 1,
	];

$a_menu['contacts'] = [
	'title'		=> 'NODE_CONTACTS',
	'controller'	=> 'contacts',
	'view'		=> NULL,
	'show'		=> 2,
	'sitemap'	=> 1,
	];

$a_menu['register'] = [
	'title'		=> 'NODE_REGISTER',
	'controller'	=> 'register',
	'view'		=> NULL,
	'show'		=> 2,
	'class'		=> 'register-btn',
	'sitemap'	=> 1,
	];
	
$a_menu['design'] = [
	'title'		=> 'NODE_DESIGN',
	'controller'	=> 'design',
	'view'		=> NULL,
	'show'		=> 0,
	'sitemap'	=> 1,
	];

$a_menu['fabric'] = [
	'title'		=> 'NODE_FABRIC',
	'controller'	=> 'fabric',
	'view'		=> NULL,
	'show'		=> 0,
	'sitemap'	=> 1,
	];

$a_menu['info'] = [
	'title'		=> 'NODE_INFO',
	'controller'	=> 'info',
	'view'		=> NULL,
	'show'		=> 0,
	'sitemap'	=> 1,
	];

$a_menu['buy'] = [
	'title'		=> 'NODE_BUY',
	'controller'	=> 'buy',
	'view'		=> NULL,
	'show'		=> 0,
	'sitemap'	=> 1,
	];

//..............................................................................
// основное меню выбора товаров 
//..............................................................................
global $cat_cat, $cat_relations;

$cat_relations = [
	1	=> 'gymnastics',
	2	=> 'skating',
// 	3	=> 'aerobics',
	3	=> 'unitards',
	4	=> 'acrobatics',
// 	5	=> 'swimming',
	5	=> 'watersports',
// 	6	=> 'uniform',
	6	=> 'accessories',	
	];

$cat_cat['home'] = [
	'id'	=> 0,
	'link' 	=> 'all',
	'title' => 'NODE_ALL',
	'name'	=> 'NAME_ALL',
	'controller'	=> 'home',
	'view'		=> NULL,
	'avatar'=> '',
	'letter'=> 'A',
	'show'	=> 0,
	];

$cat_cat['gymnastics'] = [
	'id'	=> 1,
	'controller'	=> 'items',
	'view'		=> 'gymnastics',
	'title' => 'NODE_GYMNASTICS',
	'name'	=> 'NAME_GYMNASTICS',
	'avatar'=> 'node_rg.png',
	'letter'=> 'K',
	'show'	=> 1,
	'sitemap'	=> 1,
	];

$cat_cat['unitards'] = [
	'id'	=> 3,
	'controller'	=> 'items',
// 	'view'		=> 'aerobics',
 	'view'		=> 'unitards',
	'title' => 'NODE_UNITARDS',
	'name'	=> 'NAME_UNITARDS',	
// 	'avatar'=> 'node_aerobics.png',
	'avatar'=> 'node_unitards.png',
// 	'letter'=> 'SA',
	'letter'=> 'UN',
	'show'	=> 1,
	'sitemap'	=> 1,
	];

$cat_cat['skating'] = [
	'id'	=> 2,
	'controller'	=> 'items',
	'view'		=> 'skating',
	'title' => 'NODE_SKATING',
	'name'	=> 'NAME_SKATING',
	'avatar'=> 'node_skating.png',
	'letter'=> 'F',
	'show'	=> 1,
	'sitemap'	=> 1,
	];


$cat_cat['acrobatics'] = [
	'id'	=> 4,
	'controller'	=> 'items',
	'view'		=> 'acrobatics',
	'title' => 'NODE_ACROBATICS',
	'name'	=> 'NAME_ACROBATICS',
	'avatar'=> 'node_acrobatics.png',
	'letter'=> 'SAk',
	'show'	=> 1,
	'sitemap'	=> 1,
	];

$cat_cat['watersports'] = [
	'id'	=> 5,
	'controller'	=> 'items',
	'view'		=> 'watersports',
	'title' => 'NODE_WATERSPORTS',
	'name'	=> 'NAME_WATERSPORTS',
	'avatar'=> 'node_watersports.png',
// 	'letter'=> 'SP',
	'letter'=> 'WS',
	'show'	=> 1
	];

$cat_cat['accessories'] = [
	'id'	=> 6,
	'controller'	=> 'items',
	'view'		=> 'accessories',
	'title' => 'NODE_ACCESSORIES',
	'name'	=> 'NAME_ACCESSORIES',
	'avatar'=> 'node_accessories.png',
	'letter'=> 'U',
	'show'	=> 1,
	'sitemap'	=> 1,
	];

//..............................................................................
// вспомогательное меню 
//..............................................................................
global $cat_more;
$cat_more['new'] = [
	'id'	=> 11,
	'controller'	=> 'items',
	'view'		=> 'new',
	'title' => 'NODE_NEW',
	'avatar'=> 'sub_new.png',
	'show'	=> 2,
	'sitemap'	=> 1,
	];

$cat_more['econom'] = [
	'id'	=> 12,
	'controller'	=> 'items',
	'view'		=> 'econom',
	'title' => 'NODE_ECONOM',
	'avatar'=> 'sub_econom.png',
	'show'	=> 2,
	'sitemap'	=> 1,
	];

/*
$cat_more[13] = [
	'id'	=> 13,
	'controller'	=> 'items',
	'view'		=> 'replicants',
	'title' => 'NODE_REPLICANTS',
	'avatar'=> 'sub_copy.png',
	'show'	=> 0
	];
*/	
$cat_more['shop'] = [
	'id'	=> 13,
	'controller'	=> 'shop',
	'view'		=> NULL,
	'title' => 'NODE_SHOP',
	'avatar'=> 'sub_shop.png',
	'hot'	=> 1,
	'show'	=> 1,
	'sitemap'	=> 1,
	];


$cat_more['design'] = [
	'id'	=> 14,
	'controller'	=> 'design',
	'view'		=> NULL,
	'title' => 'NODE_DESIGN',
	'avatar'=> 'sub_sketches.png',
	'show'	=> 2,
	'sitemap'	=> 1,
	];

$cat_more['fabric'] = [
	'id'	=> 15,
	'controller'	=> 'fabric',
	'view'		=> NULL,
	'title' => 'NODE_FABRIC',
	'avatar'=> 'sub_fabric.png',
	'show'	=> 2,
	'sitemap'	=> 1,
	];

$cat_more['info'] = [
	'id'	=> 16,
	'controller'	=> 'info',
	'view'		=> NULL,
	'title' => 'NODE_INFO',
	'avatar'=> 'sub_info.png',
	'show'	=> 2,
	'sitemap'	=> 1,
	];

$cat_more['order'] = [
	'id'	=> 17,
	'controller'	=> 'order',
	'view'		=> NULL,
	'title' => 'NODE_ORDER',
	'avatar'=> 'sub_order.png',
	'show'	=> 2,
	'sitemap'	=> 1,
	];
?>
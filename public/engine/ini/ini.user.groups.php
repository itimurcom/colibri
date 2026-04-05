<?php
//------------------------------------------------------------------------------
//
//	ГРУППЫ ПОЛЬЗОВАТЕЛЕЙ
//
//------------------------------------------------------------------------------

global $groups_of_user;
$groups_of_user['ADMIN'] = array
	(
	'title' 	=> 'TITLE_GROUP_ADMIN',
	'value' 	=> 'ADMIN',
	'enable'	=> 1,
	);

$groups_of_user['USER']= array
	(
	'title' 	=> 'TITLE_GROUP_USER',
	'value' 	=> 'USER',
	'enable'	=> 1,
	);

$groups_of_user['PARTNER'] = array
	(
	'title' 	=> 'TITLE_GROUP_PARTNER',
	'value' 	=> 'PARTNER',
	'enable'	=> 1,
	);

$groups_of_user['MODERATOR'] = array
	(
	'title' 	=> 'TITLE_GROUP_MODERATOR',
	'value' 	=> 'MODERATOR',
	'enable'	=> 1,
	);

//------------------------------------------------------------------------------
//
//	ПРАВА ГРУПП
//
//------------------------------------------------------------------------------	
global $_RIGHTS;
$_RIGHTS['EDIT'] = ['ADMIN','MODERATOR'];
?>
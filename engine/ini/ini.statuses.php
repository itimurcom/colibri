<?php
//------------------------------------------------------------------------------
//
//	МАССИВ СТАТУСОВ ЗАПИСЕЙ
//
//------------------------------------------------------------------------------
global $statuses;

$statuses['PUBLISHED'] = array
	(
	'name' 		=> 'published',
	'title'         => 'STATUS_PUBLISHED',
	'state'		=> 'open',
	'open'		=> NULL,
	'close'		=> NULL,
	'killall'	=>  0,
	'show'		=> 1
	);

$statuses['MODERATE'] = array
	(
	'name' 		=> 'moderate',
	'title'         => 'STATUS_MODERATE',
	'state'		=> 'open',
	'open'		=> 'blue',
	'close'		=> 'darkgreen',
	'killall'	=>  0,
	'show'		=> 1
	);

$statuses['DELETED'] = array
	(
	'name' 		=> 'deleted',
	'title'         => 'STATUS_DELETED',
	'state'		=> 'close',
	'open'		=> 'red',
	'close'		=> 'red',
	'killall'	=>  1,
	'show'		=> 1
	);
?>
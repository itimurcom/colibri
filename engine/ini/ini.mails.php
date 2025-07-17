<?
//------------------------------------------------------------------------------
//
//	МАССИВ СТАТУСОВ EMAIL СООБЩЕНИЙ
//
//------------------------------------------------------------------------------
global $mailers;

$mailers['PREPARED'] = array
	(
	'name' 		=> 'prepared',
	'title'         => 'STATUS_PREPARED',
	'state'		=> 'open',
	'show'		=> 1,
	'color'		=> 'fiolet',
	);


$mailers['WAIT'] = array
	(
	'name' 		=> 'wait',
	'title'         => 'STATUS_WAIT',
	'state'		=> 'open',
	'show'		=> 1,
	'color'		=> 'black',
	);


$mailers['SEND'] = array
	(
	'name' 		=> 'send',
	'title'         => 'STATUS_SEND',
	'state'		=> 'open',
	'show'		=> 1,
	'color'		=> 'blue',
	);

$mailers['RECIEVED'] = array
	(
	'name' 		=> 'recieved',
	'title'         => 'STATUS_RECIEVED',
	'state'		=> 'open',
	'show'		=> 1,
	'color'		=> 'green',
	);

$mailers['ERROR'] = array
	(
	'name' 		=> 'error',
	'title'         => 'STATUS_ERROR',
	'state'		=> 'open',
	'show'		=> 1,
	'color'		=> 'red',
	);

$mailers['SPAM'] = array
	(
	'name' 		=> 'spam',
	'title'         => 'STATUS_SPAM',
	'state'		=> 'open',
	'show'		=> 1,
	'color'		=> 'red',
	);

$mailers['NOSPAM'] = array
	(
	'name' 		=> 'nospam',
	'title'         => 'STATUS_NOSPAM',
	'state'		=> 'open',
	'show'		=> 1,
	'color'		=> 'green',
	);

$mailers['DELETED'] = array
	(
	'name' 		=> 'error',
	'title'         => 'STATUS_DELETED',
	'state'		=> 'open',
	'show'		=> 1,
	'color'		=> 'red',
	);
?>
<?php
define ('FIRST_AVATAR_ONLY', true);
//-----------------------------------------------------------------------------
//
// КОЛИЧЕТСВО ОТОБРАЖАЕМОЙ ИНФОРМАЦИИ В ПОТОКЕ ЛЕНТЫ
//
//-----------------------------------------------------------------------------
define ('FEED_NUMBER', serialize ( array(
	'contents' 	=> 5,
	'items' 	=> 15,
	'mailing_history'	=> 20,
	)));

// начально количество данных
define ('FEED_START', serialize ( array(
	'contents' 	=> 5,
	'items' 	=> 15,
	'mailing_history'	=> 20,
	)));

define ('DEFAULT_FEED_DATA', serialize ( array(
	'table' 	=> 'contents',
	'condition' 	=> '1',
	'order' 	=> '`datetime` DESC',
	'position' 	=> '0',
	)));
?>
<?php
// ================ CRC ================
// version: 1.35.03
// hash: f096e167e85ea4fcebef44ffe2718e82041e5f88d153554a4754f196b525b48f
// date: 28 May 2021  4:42
// ================ CRC ================
definition([
	// itBlock
	'DEFAULT_BLOCK_TABLE'	=> 'blocks',
	'DEFAULT_NOLANG'	=> false,
	'DEFAULT_NOTITLE'	=> false,	
	'DEFAULT_NODATE'	=> false,
	'DEFAULT_NOAVATAR'	=> true,
	'DEFAULT_NOMODERATE'	=> false,
	'DEFAULT_NORELATED'	=> true,
	'DEFAULT_EDCLASS'	=> 'default',
	
	// itModerator
	'MODERATED_STATUSES' 		=> serialize(['MODERATE', 'DELETED']),
	'NOT_PUBLISHED_STATUSES'	=> "'PUBLISHED','EDIT'",
	'DEFAULT_MODERATOR_TABLE'	=> 'contents',
	'DEFAULT_STATUS_FIELD'		=> 'status',
	'DEFAULT_MODERATOR_ORDER'	=> "`datetime` DESC",
	
	// itFeed
	'DEFAULT_FEED_NUM' 	=> 5,
	'FEED_VALUE'		=> 'feed_data',
	'FEED_LIMIT' 		=> 10000,
	'FEED_START'		=> serialize(['contents' => 5]),
	'FEED_NUMBER'		=> serialize(['contents' => 5]),
	'FEED_LOOP'		=> serialize(['contents' => 0]),
	'DEFAULT_FEED_APPEAR'	=> NULL,
	'DEFAULT_MORE_STATE'	=> false,
	]);
?>
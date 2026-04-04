<?php
// ================ CRC ================
// version: 1.35.02
// hash: 3ac0552dba928e5d4808e3e8aea1ac7143cedcf7f2bc4be35b1a049f7712195d
// date: 09 September 2019  7:09
// ================ CRC ================
//..............................................................................
// добавяет сообщение об ошибке в массив ошибок
//..............................................................................
function add_service_message($message='No Message.', $color=NULL, $keep=false)
	{
	if (!isset($_SESSION['error']) OR !is_array($_SESSION['error']) OR (array_search($message, array_column($_SESSION['error'],'msg'))===false))
	$_SESSION['error'][] = [
		'msg'	=> $message,
		'color' => is_null($color) ? ((defined('SERVICE_MESSAGE_COLOR') ? get_const('SERVICE_MESSAGE_COLOR') : 'blue')) : $color,
		'keep'	=> $keep,
		];
	}
?>
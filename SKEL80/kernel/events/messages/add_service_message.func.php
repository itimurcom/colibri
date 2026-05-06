<?php
// добавяет сообщение об ошибке в массив ошибок
function add_service_message($message='No Message.', $color=NULL, $keep=false)
	{
	if (!isset($_SESSION['error']) OR !is_array($_SESSION['error']))
		{
		$_SESSION['error'] = [];
		}

	$messages = [];
	foreach ($_SESSION['error'] as $row)
		{
		if (is_array($row) AND isset($row['msg'])) $messages[] = $row['msg'];
		}

	if (array_search($message, $messages)===false)
		{
		$_SESSION['error'][] = [
			'msg'	=> $message,
			'color' => is_null($color) ? ((defined('SERVICE_MESSAGE_COLOR') ? get_const('SERVICE_MESSAGE_COLOR') : 'blue')) : $color,
			'keep'	=> $keep,
			];
		}
	}
?>

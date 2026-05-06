<?php
// добавяет сообщение об ошибке в массив ошибок
function add_error_message($message='Error', $color=NULL, $keep=false, $debug=false)
	{
	if ($debug)
		{
		$trace = debug_backtrace();
		$debug = (isset($trace[1]) AND is_array($trace[1])) ? $trace[1] : [];
		$message = $message." File:".basename(isset($debug['file']) ? $debug['file'] : '')." Line:".(isset($debug['line']) ? $debug['line'] : '');
		}

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
		$_SESSION['error'][] = array(
			'msg' => $message,
			'color' => is_null($color) ? ((defined('DEFAULT_ERROR_COLOR') ? get_const('DEFAULT_ERROR_COLOR') : 'red')) : $color,
			'keep'	=> $keep,
			);
		}
	}
?>

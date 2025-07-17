<?php
// ================ CRC ================
// version: 1.35.02
// hash: 9dd9c460dff198d1eed164764e72663471a3f11e8a404f27a4c75e310a7cc100
// date: 09 September 2019  7:09
// ================ CRC ================
//..............................................................................
// добавяет сообщение об ошибке в массив ошибок
//..............................................................................
function add_error_message($message='Error', $color=NULL, $keep=false, $debug=false)
	{
	if ($debug)
		{	
		$debug = debug_backtrace()[1];
		$message = "$message File:".basename($debug['file'])." Line:{$debug['line']}";
		}
		
	if (!isset($_SESSION['error']) OR !is_array($_SESSION['error']) OR (array_search($message, array_column($_SESSION['error'],'msg'))===false))
	$_SESSION['error'][] = array(
		'msg' => $message,
		'color' => is_null($color) ? ((defined('DEFAULT_ERROR_COLOR') ? get_const('DEFAULT_ERROR_COLOR') : 'red')) : $color,			
		'keep'	=> $keep,
		);
	
	
	}
?>
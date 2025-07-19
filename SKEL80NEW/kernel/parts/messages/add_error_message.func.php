<?
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
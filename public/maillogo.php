<?php 
define('NO_PREPARED_ARR', 1);
include ("engine/kernel.php");

function maillogo_request_value($key='', $default=NULL)
	{
	return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
	}

$mail_payload = maillogo_request_value('mail_id');
if (!empty($mail_payload))
	{
	$data = @unserialize(simple_decrypt($mail_payload), ['allowed_classes' => false]);
	if (is_array($data) AND isset($data['rec_id']))
		{
		itMySQL::_update_value_db('mails', $data['rec_id'], 'RECIEVED', 'status');
		}
	}

$document_root = isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT']!==''
	? rtrim($_SERVER['DOCUMENT_ROOT'], '/')
	: __DIR__;
$logo_path = $document_root."/themes/default/images/top_left_logo.png";

if (!file_exists($logo_path))
	{
	if (!headers_sent())
		{
		http_response_code(404);
		}
	return NULL;
	}

show_image($logo_path);
?>

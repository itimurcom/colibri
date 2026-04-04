<?
define('NO_PREPARED_ARR', 1);
include ("engine/kernel.php");


if (isset($_REQUEST['mail_id']) AND !empty($_REQUEST['mail_id']))
	{
	$data = @unserialize(simple_decrypt($_REQUEST['mail_id']));
	if (isset($data['rec_id']))
		itMySQL::_update_value_db('mails', $data['rec_id'], 'RECIEVED', 'status');
	}
show_image($_SERVER['DOCUMENT_ROOT']."/themes/default/images/top_left_logo.png");
?>
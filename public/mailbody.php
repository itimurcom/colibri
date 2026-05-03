<?php
include ("engine/kernel.php");

if (!$_USER->is_logged())
	{
	cms_redirect_page("/");
	}

$mail_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
if ($mail_id>0)
	{
	if ($mail = itMySQL::_get_rec_from_db('mails', $mail_id))
		{
		echo $mail['message'];
		}
	}
?>

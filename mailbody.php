<?php
include ("engine/kernel.php");

if (!$_USER->is_logged())
	{
	cms_redirect_page("/");
	}
if (isset($_REQUEST['id']))
	{
	if ($mail = itMySQL::_get_rec_from_db('mails', $_REQUEST['id']))
		{
		echo $mail['message'];
		}
	}
?>
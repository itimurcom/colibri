<?php
include ("engine/kernel.php");

if (isset($_USER) AND is_object($_USER) AND method_exists($_USER, 'logout'))
	{
	$_USER->logout();
	}

cms_smart_redirect('/');
?>
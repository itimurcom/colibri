<?php
include ("engine/kernel.php");
global $user;

if (isset ($_REQUEST['op']) and ($_REQUEST['op']=='login'))
	{
	itUserReg::oauth();
	} 

$o_ureg = new itUserReg();

if (isset($_SESSION['http_referer']))
	{
	$referer = str_replace("/".CMS_LANG."/", "/".itLang::get_lang()."/", $_SESSION['http_referer']);
	// вернемся на нужную страницу
	unset ($_SESSION['http_referer']);
	

	// адрес перехода после удачного логина пользователя
	if (defined('DEFAULT_USER_LOGIN_PAGE'))
		{
		cms_redirect_page(get_const('DEFAULT_USER_LOGIN_PAGE'));
		} else cms_redirect_page($referer);
	}
?>
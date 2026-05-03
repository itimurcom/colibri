<?php
include ("engine/kernel.php");
global $user;

function soclogin_request_value($key='', $default=NULL)
	{
	return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
	}

if (soclogin_request_value('op')=='login')
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

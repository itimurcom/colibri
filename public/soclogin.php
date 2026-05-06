<?php
include ("engine/kernel.php");
global $user;

function soclogin_request_value($key='', $default=NULL)
	{
	return (isset($_REQUEST) AND is_array($_REQUEST) AND array_key_exists($key, $_REQUEST)) ? $_REQUEST[$key] : $default;
	}

if (soclogin_request_value('op')=='login')
	{
	itUserReg::oauth();
	} 

$o_ureg = new itUserReg();

if (isset($_SESSION['http_referer']))
	{
	$referer_value = is_string($_SESSION['http_referer']) ? $_SESSION['http_referer'] : '/';
	$referer = str_replace("/".CMS_LANG."/", "/".itLang::get_lang()."/", $referer_value);
	// вернемся на нужную страницу
	unset ($_SESSION['http_referer']);
	

	// адрес перехода после удачного логина пользователя
	if (defined('DEFAULT_USER_LOGIN_PAGE'))
		{
		cms_redirect_page(get_const('DEFAULT_USER_LOGIN_PAGE'));
		} else cms_redirect_page($referer);
	}
?>

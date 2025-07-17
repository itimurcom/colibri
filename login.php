<?php
include ("engine/kernel.php");
$data = itEditor::_redata();

// print_r($_REQUEST); die;

global $_USER;

if ($_USER->is_logged())
	{
	cms_smart_redirect();
	} else {
		// проверяем есть ли обработка каптчи
		if ( (get_const('USE_CAPTCHA')==true) ) {
			if (!isset($_SESSION['v3checked']['score']))
				{
				itForm2::_reCaptcha();
				// echo $_SESSION['v3checked']['score']; die;
				}
			}
		if (isset($_SESSION['v3checked']['score']) AND ($_SESSION['v3checked']['score']<0.5) ) {
			add_error_message(get_const('EROOR_CAPTCHA_SCORE').": ".$_SESSION['v3cheked']['score']);
			cms_smart_redirect();
			}

		if (isset($_REQUEST['user_login']) AND isset($_REQUEST['user_password'])
			AND $_USER->is_correct_user($_REQUEST['user_login'], $_REQUEST['user_password']) )
			{
			if ($_USER->is_logged('ANY') AND isset($_REQUEST['url']))
				{
				cms_redirect_page($_REQUEST['url']);
				} else	{
					if (defined('DEFAULT_USER_LOGIN_PAGE'))
						{
						cms_redirect_page(get_const('DEFAULT_USER_LOGIN_PAGE'));
						} else cms_smart_redirect();
					}
			} else {
				$_SESSION['focus']['color'] = 'blue';

				if ((!isset($_REQUEST['user_login'])) or ($_REQUEST['user_login']==''))
					{
					$_SESSION['focus']['element'] = 'user_login';
					$_SESSION['user_login'] = $_REQUEST['user_login'];
					} else 	{
						$_SESSION['focus']['element'] 	= 'user_password';
						$_SESSION['user_login'] 	= $_REQUEST['user_login'];
						$_SESSION['user_password'] 	= $_REQUEST['user_password'];
						}

				add_error_message(str_replace('[VALUE]', $_REQUEST['user_login'], MES_ERROR_LOGGING));
				cms_smart_redirect();
				}

		}
?>
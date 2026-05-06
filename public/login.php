<?php
include ("engine/kernel.php");
$data = itEditor::_redata();

// print_r($_REQUEST); die;

global $_USER;

function login_user_runtime()
	{
	global $_USER;
	return (isset($_USER) AND is_object($_USER)) ? $_USER : NULL;
	}

function login_request_value($key='', $default='')
	{
	return (isset($_REQUEST) AND is_array($_REQUEST) AND array_key_exists($key, $_REQUEST)) ? $_REQUEST[$key] : $default;
	}

function login_request_has($key='')
	{
	return (isset($_REQUEST) AND is_array($_REQUEST) AND array_key_exists($key, $_REQUEST));
	}

function login_session_value($path=[], $default=NULL)
	{
	$value = $_SESSION;
	foreach ((array)$path as $key)
		{
		if (!is_array($value) || !isset($value[$key]))
			{
			return $default;
			}
		$value = $value[$key];
		}
	return $value;
	}

$_USER = login_user_runtime();
if (is_object($_USER) AND $_USER->is_logged())
	{
	cms_smart_redirect();
	} else {
		// проверяем есть ли обработка каптчи
		if ( (get_const('USE_CAPTCHA')==true) ) {
			if (is_null(login_session_value(['v3checked', 'score'])))
				{
				itForm2::_reCaptcha();
				// echo $_SESSION['v3checked']['score']; die;
				}
			}
		$captcha_score = login_session_value(['v3checked', 'score']);
		if (!is_null($captcha_score) AND ($captcha_score<0.5) ) {
			add_error_message(get_const('EROOR_CAPTCHA_SCORE').": ".$captcha_score);
			cms_smart_redirect();
			}

		$user_login = login_request_value('user_login');
		$user_password = login_request_value('user_password');
		$has_login = login_request_has('user_login');
		$has_password = login_request_has('user_password');

		$redirect_url = login_request_value('url', NULL);

		if (is_object($_USER) AND $has_login AND $has_password
			AND $_USER->is_correct_user($user_login, $user_password) )
			{
			if (is_object($_USER) AND $_USER->is_logged('ANY') AND !is_null($redirect_url))
				{
				cms_redirect_page($redirect_url);
				} else	{
					if (defined('DEFAULT_USER_LOGIN_PAGE'))
						{
						cms_redirect_page(get_const('DEFAULT_USER_LOGIN_PAGE'));
						} else cms_smart_redirect();
					}
			} else {
				if (!isset($_SESSION['focus']) OR !is_array($_SESSION['focus'])) $_SESSION['focus'] = [];
				$_SESSION['focus']['color'] = 'blue';

				if (!$has_login OR $user_login=='')
					{
					$_SESSION['focus']['element'] = 'user_login';
					$_SESSION['user_login'] = $user_login;
					} else 	{
						$_SESSION['focus']['element'] 	= 'user_password';
						$_SESSION['user_login'] 	= $user_login;
						$_SESSION['user_password'] 	= $user_password;
						}

				add_error_message(str_replace('[VALUE]', $user_login, MES_ERROR_LOGGING));
				cms_smart_redirect();
				}

		}
?>

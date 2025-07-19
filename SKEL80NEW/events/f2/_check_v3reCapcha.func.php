<?
//..............................................................................
// проверка score после каптчи
//..............................................................................
function _check_v3reCaptcha()
    {
    if (!isset($_SESSION['v3checked']['score'])) {
		itForm2::_reCaptcha();
		}		
	if (!isset($_SESSION['v3checked']['score']) OR ($_SESSION['v3checked']['score']<0.5) ) {
		add_error_message(get_const('EROOR_CAPTCHA_SCORE').": ".$_SESSION['v3cheked']['score']);
		cms_redurect_page('/');
		}
    }
?>
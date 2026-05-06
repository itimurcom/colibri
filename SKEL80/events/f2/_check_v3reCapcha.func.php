<?php 
// проверка score после каптчи
function _check_v3reCaptcha()
    {
    if (!defined('USE_CAPTCHA') OR USE_CAPTCHA!=true) {
        return true;
        }
    if (!isset($_SESSION) OR !is_array($_SESSION)) {
        $_SESSION = [];
        }

    if (!isset($_SESSION['v3checked']) OR !is_array($_SESSION['v3checked']) OR !isset($_SESSION['v3checked']['score'])) {
        itForm2::_reCaptcha();
        }

    $score = (isset($_SESSION['v3checked']) AND is_array($_SESSION['v3checked']) AND isset($_SESSION['v3checked']['score']))
        ? floatval($_SESSION['v3checked']['score'])
        : 0;

    if ($score<0.5) {
        add_error_message(get_const('EROOR_CAPTCHA_SCORE').": ".$score);
        cms_redurect_page('/');
        }
    }
?>
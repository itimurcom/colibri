<?
//..............................................................................
// проверят текст на предмет корректного ввода email
//..............................................................................
function isEmail($email)
	{
	return filter_var($email,FILTER_VALIDATE_EMAIL);
//	return preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $email);
	}
?>
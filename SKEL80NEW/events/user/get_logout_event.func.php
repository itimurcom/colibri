<?
//..............................................................................
// возвращает кнопку выхода из системы
//..............................................................................
function get_logout_event($groups='ANY')
	{
	global $_USER;

	if (!$_USER->is_logged($groups)) return;

	$o_button = new itButton(get_const('NODE_LOGOUT'), 'a', ['class' => 'admin', 'href' => '/exit/'], 'red' );
	$result = $o_button->code();
	unset($o_button);

	return $result;
	}
?>
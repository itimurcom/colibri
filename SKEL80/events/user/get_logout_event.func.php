<?php
// ================ CRC ================
// version: 1.15.04
// hash: dc1fda3ceaae73ab93d48c6d71513589926198c0507e6a0a2402962b4f7a2216
// date: 01 August 2020 16:29
// ================ CRC ================
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
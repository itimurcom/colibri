<?php
//
// СТАНДАРТНЫЕ ОБРАБОТЧКИКИ ДЛЯ РЕДАКТОРА
//
definition($constants = [
	'WIZ_CUT_LINES'	=> 10,
	'WIZARD_NOTITLES' => serialize(str_getcsv("text,email,phone,desc,descimal")),
	'WIZARD_NOVALUES' => serialize(str_getcsv("text,email,phone,desc,descimal")),
	]);
?>
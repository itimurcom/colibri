<?php
// ================ CRC ================
// version: 1.15.03
// hash: ad3e64286e5ac604220a00f886a20592cd1956b23ca1d8d376103bbd97429e01
// date: 09 September 2019  5:10
// ================ CRC ================
//------------------------------------------------------------------------------
//
// СТАНДАРТНЫЕ ОБРАБОТЧКИКИ ДЛЯ РЕДАКТОРА
//
//------------------------------------------------------------------------------
definition($constants = [
	'WIZ_CUT_LINES'	=> 10,
	'WIZARD_NOTITLES' => serialize(str_getcsv("text,email,phone,desc,descimal")),
	'WIZARD_NOVALUES' => serialize(str_getcsv("text,email,phone,desc,descimal")),
	]);
?>
<?php
define('RECAPTCHA_KEY', '6LcuxIUaAAAAACJX9WJLPsPu0IjAH61jdkuMixzM');
define('RECAPTCHA_SECRET', '6LcuxIUaAAAAAH6E-v_ZLaudIrQrvtLiKI0lvobo');

//------------------------------------------------------------------------------
// ФОРМЫ ДЛЯ РАЗМЕЩЕНИЯ НА СТРАНИЦАХ
//------------------------------------------------------------------------------
define ('DEFAULT_MORE_STATE', false);

define ('FORM2_CONTACTS',	1);
define ('FORM2_ORDER',		2);
define ('FORM2_BUY',		3);
define ('FORM2_CALC',		4);
define('FORM2_ORDER_NEW', 	5);

define('FORM2_MEASUREMENT', 	10);
define('FORM2_MEASUREMENT2', 	11);
define('FORM2_MEASUREMENT3', 	12);
define('FORM2_MEASUREMENT4', 	13);
define('FORM2_MEASUREMENT5', 	14);

define('FORM2_REGISTER', 100);

global $_MEASURMENT;

$_MEASURMENT[FORM2_MEASUREMENT] =
	[
	'form_id'	=> FORM2_MEASUREMENT,
	'color'		=> 'blue',
	'mailcolor'	=> 'blue',
	];

$_MEASURMENT[FORM2_MEASUREMENT2] =
	[
	'form_id'	=> FORM2_MEASUREMENT2,
	'color'		=> 'green',	
	'mailcolor'	=> 'green',
	];

$_MEASURMENT[FORM2_MEASUREMENT3] =
	[
	'form_id'	=> FORM2_MEASUREMENT3,
	'color'		=> 'brown',	
	'mailcolor'	=> 'maroon',
	];

$_MEASURMENT[FORM2_MEASUREMENT4] =
	[
	'form_id'	=> FORM2_MEASUREMENT4,
	'color'		=> 'gold',
	'mailcolor'	=> 'goldenrod',		
	];

$_MEASURMENT[FORM2_MEASUREMENT5] =
	[
	'form_id'	=> FORM2_MEASUREMENT5,
	'color'		=> 'fiolet',
	'mailcolor'	=> 'blueviolet',	
	];

?>
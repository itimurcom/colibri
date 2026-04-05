<?php
// ================ CRC ================
// version: 1.35.04
// hash: d85169e5dcfba04bd7045fccdb93a625405874f6ff9ccbcbceee48769907aa5b
// date: 10 March 2021  9:27
// ================ CRC ================
global $plug_css;
$plug_css[] = 'class.itForm.css';
$plug_css[] = 'class.itForm2.css';
$plug_css[] = 'class.itUpGal.css';
	
//-----------------------------------------------------------------------------
//
//	УСТАНОВКИ ВРЕМЕННЫХ ИНТЕРВАЛОВ
//
//-----------------------------------------------------------------------------
global $_TIMES;
$_TIMES = [
	'00:00' => ['title' => '00:00', 'value' => '00:00'],
	'00:30' => ['title' => '00:30', 'value' => '00:30'],
	'01:00' => ['title' => '01:00', 'value' => '01:00'],
	'01:30' => ['title' => '01:30', 'value' => '01:30'],
	'02:00' => ['title' => '02:00', 'value' => '02:00'],
	'02:30' => ['title' => '02:30', 'value' => '02:30'],
	'03:00' => ['title' => '03:00', 'value' => '03:00'],
	'03:30' => ['title' => '03:30', 'value' => '03:30'],
	'04:00' => ['title' => '04:00', 'value' => '04:00'],
	'04:30' => ['title' => '04:30', 'value' => '04:30'],
	'05:00' => ['title' => '05:00', 'value' => '05:00'],
	'05:30' => ['title' => '05:30', 'value' => '05:30'],
	'06:00' => ['title' => '06:00', 'value' => '06:00'],
	'06:30' => ['title' => '06:30', 'value' => '06:30'],
	'07:00' => ['title' => '07:00', 'value' => '07:00'],
	'07:30' => ['title' => '07:30', 'value' => '07:30'],
	'08:00' => ['title' => '08:00', 'value' => '08:00'],
	'08:30' => ['title' => '08:30', 'value' => '08:30'],
	'09:00' => ['title' => '09:00', 'value' => '09:00'],
	'09:30' => ['title' => '09:30', 'value' => '09:30'],
	'10:00' => ['title' => '10:00', 'value' => '10:00'],
	'10:30' => ['title' => '10:30', 'value' => '10:30'],
	'11:00' => ['title' => '11:00', 'value' => '11:00'],
	'11:30' => ['title' => '11:30', 'value' => '11:30'],
	'12:00' => ['title' => '12:00', 'value' => '12:00'],
	'12:30' => ['title' => '12:30', 'value' => '12:30'],
	'13:00' => ['title' => '13:00', 'value' => '13:00'],
	'13:30' => ['title' => '13:30', 'value' => '13:30'],
	'14:00' => ['title' => '14:00', 'value' => '14:00'],
	'14:30' => ['title' => '14:30', 'value' => '14:30'],
	'15:00' => ['title' => '15:00', 'value' => '15:00'],
	'15:30' => ['title' => '15:30', 'value' => '15:30'],
	'16:00' => ['title' => '16:00', 'value' => '16:00'],
	'16:30' => ['title' => '16:30', 'value' => '16:30'],
	'17:00' => ['title' => '17:00', 'value' => '17:00'],
	'17:30' => ['title' => '17:30', 'value' => '17:30'],
	'18:00' => ['title' => '18:00', 'value' => '18:00'],
	'18:30' => ['title' => '18:30', 'value' => '18:30'],
	'19:00' => ['title' => '19:00', 'value' => '19:00'],
	'19:30' => ['title' => '19:30', 'value' => '19:30'],
	'20:00' => ['title' => '20:00', 'value' => '20:00'],
	'20:30' => ['title' => '20:30', 'value' => '20:30'],
	'21:00' => ['title' => '21:00', 'value' => '21:00'],
	'21:30' => ['title' => '21:30', 'value' => '21:30'],
	'22:00' => ['title' => '22:00', 'value' => '22:00'],
	'22:30' => ['title' => '22:30', 'value' => '22:30'],
	'23:00' => ['title' => '23:00', 'value' => '23:00'],
	'23:30' => ['title' => '23:30', 'value' => '23:30'],
	];
	

global $form2_defaults;

$form2_defaults['TITLE'] = [
	'value'		=> 'TITLE',
	'title'		=> 'F2_TITLE',	
	'default'	=> [
		'value'	=> NULL,
		],
	'enable' 	=> 1,	
	'show' 		=> 1,	
	];

$form2_defaults['HIDDEN'] = [
	'value'		=> 'HIDDEN',
	'title'		=> 'F2_HIDDEN',
	'default'	=> [
		'name'	=> NULL,
		'value'	=> NULL,
		],
	'enable' 	=> 1,	
	'show' 		=> 0,	
	];

$form2_defaults['DESC'] = [
	'value'		=> 'DESC',
	'title'		=> 'F2_DESC',	
	'default'	=> [
		'value'	=> NULL,
		],
	'enable' 	=> 1,	
	'show' 		=> 1,	
	];

$form2_defaults['CODE'] = [
	'value'		=> 'CODE',
	'title'		=> 'F2_CODE',		
	'default'	=> [
		'value'	=> NULL,
		],
	'enable' 	=> 1,	
	'show' 		=> 1,	
	];

$form2_defaults['INPUT'] = [
	'value'		=> 'INPUT',
	'title'		=> 'F2_INPUT',		
	'default'	=> [
		'name'		=> NULL,
		'value'		=> NULL,
		'label'		=> NULL,
		'placeholder'	=> false,
		'class'		=> NULL,
		],
	'enable' 	=> 1,	
	'show' 		=> 1,	
	];


$form2_defaults['NUMBER'] = [
	'value'		=> 'NUMBER',
	'title'		=> 'F2_NUMBER',		
	'default'	=> [
		'name'		=> NULL,
		'value'		=> NULL,
		'label'		=> NULL,
		'placeholder'	=> false,
		'class'		=> NULL,
		'min'		=> 0,
		],
	'enable' 	=> 1,	
	'show' 		=> 1,	
	];

$form2_defaults['PASS'] = [
	'value'		=> 'PASS',
	'title'		=> 'F2_PASS',	
	'default'	=> [
		'name'		=> NULL,
		'value'		=> NULL,
		'label'		=> NULL,
		'placeholder'	=> false,
		'class'		=> NULL,
		],
	'enable' 	=> 1,	
	'show' 		=> 1,	
	];

$form2_defaults['PHONE'] = [
	'value'		=> 'PHONE',
	'title'		=> 'F2_PHONE',	
	'default'	=> [
		'name'		=> NULL,
		'value'		=> NULL,
		'label'		=> NULL,
		'placeholder'	=> false,
		'class'		=> NULL,
		],
	'enable' 	=> 1,	
	'show' 		=> 1,	
	];

$form2_defaults['EMAIL'] = [
	'value'		=> 'EMAIL',
	'title'		=> 'F2_EMAIL',	
	'default'	=> [
		'name'		=> NULL,
		'value'		=> NULL,
		'label'		=> NULL,
		'placeholder'	=> false,
		'class'		=> NULL,
		],
	'enable' 	=> 1,	
	'show' 		=> 1,	
	];

$form2_defaults['AREA'] = [
	'value'		=> 'AREA',
	'title'		=> 'F2_AREA',	
	'default'	=> [
		'name'		=> NULL,
		'value'		=> NULL,
		'label'		=> NULL,
		'placeholder'	=> false,
		'class'		=> NULL,
		],
	'enable' 	=> 1,	
	'show' 		=> 1,	
	];

$form2_defaults['SELECT'] = [
	'value'		=> 'SELECT',
	'title'		=> 'F2_SELECT',	
	'default'	=> [
		'type'		=> DEFAULT_SELECTOR_TYPE,
		'options'	=> NULL,
		'value'		=> NULL,
		'element_id'	=> NULL,
		'label'		=> NULL
		],
	'enable' 	=> 1,	
	'show' 		=> 1,	
	];

$form2_defaults['AUTO'] = [
	'value'		=> 'AUTO',
	'title'		=> 'F2_AUTO',	
	'default'	=> [
		'options'	=> NULL,
		'label'		=> NULL
		],
	'enable' 	=> 1,	
	'show' 		=> 1,	
	];


$form2_defaults['DATE'] = [
	'value'		=> 'DATE',
	'title'		=> 'F2_DATE',	
	'default'	=> [
		'options'	=> NULL,
		'value'		=> NULL,
		],
	'enable' 	=> 1,	
	'show' 		=> 1,	
	];

$form2_defaults['TIME'] = [
	'value'		=> 'TIME',
	'title'		=> 'F2_TIME',	
	'default'	=> [
		'options'	=> NULL,
		'value'		=> NULL
		],
	'enable' 	=> 1,	
	'show' 		=> 1,	
	];

$form2_defaults['SET'] = [
	'value'		=> 'SET',
	'title'		=> 'F2_SET',	
	'default'	=> [
		'type'		=> DEFAULT_SELECTOR_TYPE,
		'options'	=> NULL,
		'value'		=> NULL,
		'element_id'	=> false,
		'label'		=> NULL
		],
	'enable' 	=> 1,	
	'show' 		=> 1,	
	];

$form2_defaults['UPGAL'] = [
	'value'		=> 'UPGAL',
	'title'		=> 'F2_UPGAL',	
	'default'	=> [
		'options'	=> NULL,
		],
	'enable' 	=> 1,	
	'show' 		=> 1,	
	];
?>
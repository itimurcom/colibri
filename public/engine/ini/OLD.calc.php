<?
global $_SETTINGS;
$calc_cat['ITEM_CALC']['fields']['quantity'] = array
	(
	'name'		=> 'quantity',
	'title' 	=> CALC_ITEM_QUANTITY,
	'type' 		=> 'select',
	'options'	=> '1,2,3,4,5,6,7,8,9,10',
	'values'	=> '1,2,3,4,5,6,7,8,9,10',
	'default'	=> '0',
	'show'		=> 1
	);

//..........
// кристалы 
//..........

// Стразы SWAROVSKI (Австрия )
// Crystal (10.00 $/100шт)
$calc_cat['ITEM_CALC']['fields']['swarowski_crystal'] = array
	(
	'name'		=> 'swarowski_crystal',
	'title' 	=> FORM_TITLE_SWAROWSKI_CRYSTAL,
	'type' 		=> 'select',
	'options'      	=> '0,100,200,300,400,500,750,1000,1500,2000,2500,3000,5000',
	'values'       	=> '0,'.($_SETTINGS['default_swarowski']['value']).','.
				($_SETTINGS['default_swarowski']['value']*2).','.
				($_SETTINGS['default_swarowski']['value']*3).','.
				($_SETTINGS['default_swarowski']['value']*4).','.
				($_SETTINGS['default_swarowski']['value']*5).','.
				($_SETTINGS['default_swarowski']['value']*7.5).','.
				($_SETTINGS['default_swarowski']['value']*10).','.
				($_SETTINGS['default_swarowski']['value']*15).','.
				($_SETTINGS['default_swarowski']['value']*20).','.
				($_SETTINGS['default_swarowski']['value']*25).','.
				($_SETTINGS['default_swarowski']['value']*30).','.
				($_SETTINGS['default_swarowski']['value']*50),
	'default'	=> '0',
	'show'		=> 1
	);

// Стразы SWAROVSKI (Австрия)
// AB и цветные (12.00 $/100шт)
$calc_cat['ITEM_CALC']['fields']['swarowski_ab'] = array
	(
	'name'		=> 'swarowski_ab',
	'title' 	=> FORM_TITLE_SWAROWSKI_AB,
	'type' 		=> 'select',
	'options'      	=> '0,100,200,300,400,500,750,1000,1500,2000,2500,3000,5000',
	'values'       	=> '0,'.($_SETTINGS['default_swarowski_ab']['value']).','.
				($_SETTINGS['default_swarowski_ab']['value']*2).','.
				($_SETTINGS['default_swarowski_ab']['value']*3).','.
				($_SETTINGS['default_swarowski_ab']['value']*4).','.
				($_SETTINGS['default_swarowski_ab']['value']*5).','.
				($_SETTINGS['default_swarowski_ab']['value']*7.5).','.
				($_SETTINGS['default_swarowski_ab']['value']*10).','.
				($_SETTINGS['default_swarowski_ab']['value']*15).','.
				($_SETTINGS['default_swarowski_ab']['value']*20).','.
				($_SETTINGS['default_swarowski_ab']['value']*25).','.
				($_SETTINGS['default_swarowski_ab']['value']*30).','.
				($_SETTINGS['default_swarowski_ab']['value']*50),
	'default'	=> '0',
	'show'		=> 1
	);

// Средние стразы SWAROVSKI
// Форма, капля, овал, круг, восьмигранник (1.00$/шт)
$calc_cat['ITEM_CALC']['fields']['swarowski_middle'] = array
	(
	'name'		=> 'swarowski_middle',
	'title' 	=> FORM_TITLE_SWAROWSKI_MIDDLE,
	'type' 		=> 'number',
	'options'      	=> '0',
	'values'       	=> $_SETTINGS['default_swarowski_middle']['value'],
	'default'	=> '0',
	'show'		=> 1
	);


// Большие стразы SWAROVSKI
// Форма ромб, капля, овал, треугольник, прямоугольник (2.00 $/шт)
$calc_cat['ITEM_CALC']['fields']['swarowski_big'] = array
	(
	'name'		=> 'swarowski_big',
	'title' 	=> FORM_TITLE_SWAROWSKI_BIG,
	'type' 		=> 'number',
	'options'      	=> '0',
	'values'       	=> $_SETTINGS['default_swarowski_large']['value'],
	'default'	=> '0',
	'show'		=> 1
	);

// DMS
// ss16\ss20 хрустальные (4.00 $/шт)
$calc_cat['ITEM_CALC']['fields']['dms_hrustal'] = array
	(
	'name'		=> 'dms_hrustal',
	'title' 	=> FORM_TITLE_DMS_HRUSTAL,
	'type' 		=> 'select',
	'options'      	=> '0,100,200,300,400,500,750,1000,1500,2000,2500,3000,5000',
	'values'       	=> '0,'.($_SETTINGS['default_dms_hrustal']['value']).','.
				($_SETTINGS['default_dms_hrustal']['value']*2).','.
				($_SETTINGS['default_dms_hrustal']['value']*3).','.
				($_SETTINGS['default_dms_hrustal']['value']*4).','.
				($_SETTINGS['default_dms_hrustal']['value']*5).','.
				($_SETTINGS['default_dms_hrustal']['value']*7.5).','.
				($_SETTINGS['default_dms_hrustal']['value']*10).','.
				($_SETTINGS['default_dms_hrustal']['value']*15).','.
				($_SETTINGS['default_dms_hrustal']['value']*20).','.
				($_SETTINGS['default_dms_hrustal']['value']*25).','.
				($_SETTINGS['default_dms_hrustal']['value']*30).','.
				($_SETTINGS['default_dms_hrustal']['value']*50),
	'default'	=> '0',
	'show'		=> 1
	);

// DMS
// ss16\ss20 AB или цвет (5.00 $/шт)
$calc_cat['ITEM_CALC']['fields']['dms_ab'] = array
	(
	'name'		=> 'dms_ab',
	'title' 	=> FORM_TITLE_DMS_AB,
	'type' 		=> 'select',
	'options'      	=> '0,100,200,300,400,500,750,1000,1500,2000,2500,3000,5000',
	'values'       	=> '0,'.($_SETTINGS['default_dms_ab']['value']).','.
				($_SETTINGS['default_dms_ab']['value']*2).','.
				($_SETTINGS['default_dms_ab']['value']*3).','.
				($_SETTINGS['default_dms_ab']['value']*4).','.
				($_SETTINGS['default_dms_ab']['value']*5).','.
				($_SETTINGS['default_dms_ab']['value']*7.5).','.
				($_SETTINGS['default_dms_ab']['value']*10).','.
				($_SETTINGS['default_dms_ab']['value']*15).','.
				($_SETTINGS['default_dms_ab']['value']*20).','.
				($_SETTINGS['default_dms_ab']['value']*25).','.
				($_SETTINGS['default_dms_ab']['value']*30).','.
				($_SETTINGS['default_dms_ab']['value']*50),
	'default'	=> '0',
	'show'		=> 1
	);

// ЗЕРКАЛА
// Пришиваемые акриловые зеркала – Малые = 0,5$
$calc_cat['ITEM_CALC']['fields']['acrylic_small'] = array
	(
	'name'		=> 'acrylic_small',
	'title' 	=> FORM_TITLE_ACRYLIC_SMALL,
	'type' 		=> 'number',
	'options'      	=> '0',
	'values'       	=> $_SETTINGS['default_acrylic_small']['value'],
	'default'	=> '0',
	'show'		=> 1
	);

// Пришиваемые акриловые зеркала – Большие = 1$
$calc_cat['ITEM_CALC']['fields']['acrylic_large'] = array
	(
	'name'		=> 'acrylic_large',
	'title' 	=> FORM_TITLE_ACRYLIC_LARGE,
	'type' 		=> 'number',
	'options'      	=> '0',
	'values'       	=> $_SETTINGS['default_acrylic_large']['value'],
	'default'	=> '0',
	'show'		=> 1
	);


// Декорирование жемчугом 
// Разного диаметра (4.00 $/100шт)
$calc_cat['ITEM_CALC']['fields']['swarowski_zhemch'] = array
	(
	'name'		=> 'swarowski_zhemch',
	'title' 	=> FORM_TITLE_SWAROWSKI_ZHEMCH,
	'type' 		=> 'select',
	'options'      	=> '0,100,200,300,400,500,750,1000,1500,2000,2500,3000,5000',
	'values'       	=> '0,'.($_SETTINGS['default_zhemch']['value']).','.
				($_SETTINGS['default_zhemch']['value']*2).','.
				($_SETTINGS['default_zhemch']['value']*3).','.
				($_SETTINGS['default_zhemch']['value']*4).','.
				($_SETTINGS['default_zhemch']['value']*5).','.
				($_SETTINGS['default_zhemch']['value']*7.5).','.
				($_SETTINGS['default_zhemch']['value']*10).','.
				($_SETTINGS['default_zhemch']['value']*15).','.
				($_SETTINGS['default_zhemch']['value']*20).','.
				($_SETTINGS['default_zhemch']['value']*25).','.
				($_SETTINGS['default_zhemch']['value']*30).','.
				($_SETTINGS['default_zhemch']['value']*55),
	'default'	=> '0',
	'show'		=> 1
	);


//.................
// способ доставки
//.................
$calc_cat['ITEM_CALC']['fields']['delivery'] = array
	(
	'name'		=> 'delivery',
	'title' 	=> CALC_ITEM_DELIVERY,
	'type' 		=> 'radio',
	'options'	=> CALC_OPTIONS_DELIVERY,
	'values'       	=> 	($_SETTINGS['default_avia']['value']).','.
				($_SETTINGS['default_dhl']['value']).','.
				'0',
	'default'	=> '2',
	'show'		=> 0
	);

//........
// валюта
//........
$calc_cat['ITEM_CALC']['fields']['rate'] = array
	(
	'name'		=> 'rate',
	'title' 	=> CALC_ITEM_RATE,
	'type' 		=> 'select',
	'options'	=> FORM_VALUE_RATES,
	'values'        => 'USD,EUR,UAH,RUR',
	'default'	=> '0',
	'show'		=> 1
	);
?>
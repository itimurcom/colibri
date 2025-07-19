<?
definition($constants = [
		// кнопки
		'DEFAULT_BUTTON_COLOR' 		=> 'blue',
		'DEFAULT_BUTTON_TYPE'		=> 'a',
		'DEFAULT_FILES_NAME'		=> 'MyFiles',
		'DEFAULT_FILES_ACCEPT'		=> 'image/jpeg,image/png,image/gif',

		// формы
		'DEFAULT_FORM_ACTION' 		=> '/ed_field.php',
		'DEFAULT_FORM_METHOD'		=> 'POST',
		'DEFAULT_FORM_CAPTCHA'		=> false,
		'DEFAULT_FORM_TABLE'		=> 'forms',
		'DEFAULT_FORMSTATE'		=> 'view',
		
		// поле ввода
		'DEFAULT_INPUT_COMPACT' 	=> false,
		'DEFAULT_INPUT_TYPE'		=> 'input',	//	input, email, phone
		'DEFAULT_INPUT_NOLABEL'		=> false,
		'DEFAULT_INPUT_GROW'		=> false,

		// поле селектора
		'DEFAULT_BUTTON_SELECTOR' 	=> 'select',
		'DEFAULT_SELECTOR_NAME'		=> 'id',
		'DEFAULT_SELECTOR_TYPE'		=> 'select',
		'DEFAULT_SELECT_NOLABEL'	=> false,
		
		// авто выбор дополняемое поле
		'DEFAULT_AUTOSELECT_CLASS' 	=> 'autoselect',
		'MAX_GLOBAL_TITLE'		=> 24,
		'DEFAULT_AUTOSELECT_NOLABEL'	=> false,
		'DEFAULT_AUTOSELECT_TYPE'	=> 'main',
		'DEFAULT_AUTOSELECT_ACTION'	=> '/more.php',
		'DEFAULT_AUTOSELECT_OP'		=> 'as_main',
		
		// поле выбора даты
		'DEFAULT_DATEPICKER_IMAGE'	=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACoAAAAeCAYAAABaKIzgAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAz9JREFUeNrUWN9LU1Ec/+zu93bndGPmmj9Wli40mQ/iU1gQREREPWQvklAShfoWhOF/0ENPBUHYg4JUDwVC9CKVDwoKQkZiqYUaalNM5+a2u93bOWdq27Lcj+umH/hu9+6ee/e5n/M9n+85R3Hr/oCE/QlF7AmH/QtpM/Y90VjCmRG9cKYUHXfc0OtU/2zTUG9HZ1stzCZNRmxVKSUNyRreoAbHKeAPhFFxJA+lh3kU5GmgUirY77HwbYThOmqG45ARhVY9IhEJStIuGIogEIykpGrSRIuLjGi6fBw2qw4cYdzbN4V1f5hdo3/edr2KkYlF96uvjCxThLRpb462oSQHRxfR1z/DyMumqErF4cbVStgLDdu/KYl6ik0BhbAIg14FnVYZdx9VWBSjxxFRgkEXbUPjfEMJVr0hvBual48ob1ARJePVchab8PbDHL5MryKP1yB/hxw8WWnBm/ezWFzyIySIMPHquOtlDhP5lJEoVUaSpDhrO1VXxL4XPH7cbHQx1RNRV2Mjikr4NudFS2MltBpldgZTIrbI/g/17kIWOw8RSX6iKiWH6Rkv+gd/kNxUQEosHSmYosWsxZVzTvYc2YnSZy6tBDAytpSxg1sLdJtEk78nKcOXyMillhKORIdwdUUBrl0s/8s3k4VWze1Njq6sBdHxYBiCIG576mlScaz5Wjx9PpGqeaeFXV+N+mORzQAjsSgLIUa91MRrtu2ntakKJqN6z4nuqmh7czVK7caEfP3T5ceceazNk95xeJYDuVPUbtOzXIyNxEFQQl7kbksNqf3m3BGlhp0MaHVKrPVZ7fpIEkTDZGLx7OWELNaVNtFH3Z9J1/JxylYRe3KfsLLjtXUBXS8mMD71K7eDiVYjGrGgsx9KdJkUgMc945hb8OXenrby795tNy6dLWPnXp+A2XkfHnZ9ygrJpA1fTSqJ08EzBSmGRn+ySBehsLg3RIOk8tActRPjd5CqFCJLCZqxnCK9ElrjskQJC6K8RH0bAoY/eth0rbO1VpaupCRHxjzyEqXTxp7Xk2SSvAFXeT5b/2Sya0FTaGBkAZPf1+Sf5lEF6LKCRi52TQ7CBsSB2SlRZLxmyuYm2W8BBgBlOwhZF+yhGwAAAABJRU5ErkJggg==",
		'DEFAULT_DATE_NOLABEL'		=> false,
		'DEFAULT_DATE_COMPACT' 		=> true,
		'DEFAULT_DATE_CLEAR' 		=> false,
		'DEFAULT_DATE_TIME' 		=> false,		
		'DEFAULT_DATE_TYPE'		=> 'text',
		'DEFAULT_DATE_CLASS'		=> 'date2',
		'DEFAULT_DATE_GROW'		=> true,
		
		// поле выбора времени
		'DEFAULT_TIME_TYPE'		=> 'input',
		'DEFAULT_TIME_NOLABEL'		=> true,
		'DEFAULT_TIME_COMPACT'		=> true,
		'DEFAULT_TIME_CLASS'		=> 'time2',
		
		// поле набора установок
		'DEFAULT_SET_BUTTON'		=> 'set',
		'DEFAULT_SET_NAME'		=> 'id',
		'DEFAULT_SET_NOLABEL'		=> false,
		'DEFAULT_SET_COMPACT' 		=> false,
		'DEFAULT_SET_TYPE'		=> 'set',
		'DEFAULT_SET_CLASS'		=> 'set2',
		
		// поле динамической галлереи
		'DEFAULT_UPGAL_NOLABEL'	=> false,
		'DEFAULT_UPGAL_COMPACT' => false,
		'DEFAULT_UPGAL_CLASS'	=> 'upgal_div',
		'DEFAULT_UPGAL_FIELD'	=> 'upgal',
		'DEFAULT_UPGAL_IMG'	=> 'btn',
		'DEFAULT_UPGAL_BTN_IMG'	=> 'add_img_button.png',
		'DEFAULT_UPGAL_X_IMG'	=> 'delete_img_button.png',
		'DEFAULT_UPGAL_FILES'	=> 'MyFiles',
		'DEFAULT_UPGAL_NAME'	=> 'images',
		'DEFAULT_UPGAL_DELIMITER' => '|',
		
		// стили
		'F2_DEBUGSTYLE'	=> 'color:rgba(120,255,120,.7);background:rgba(120,120,255,.1);margin-top:-2em;',
		
		'DEFAULT_F2_EDITOR_COLUMN'	=> 'fields_xml',
		'DEFAULT_F2_EDITOR_FIELD'	=> 'editor',
		
		// itDesc2
		'DEFAULT_DESC_COMPACT' 	=> false,
		'DEFAULT_DESC_NOLABEL'	=> true,
		
		//itArea2
		'DEFAULT_AREA_COMPACT' 	=> false,
		'DEFAULT_AREA_NOLABEL'	=> false,
	]);
?>
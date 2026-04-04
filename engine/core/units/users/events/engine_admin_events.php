<?php
//..............................................................................
// функция возвращает html формы входа
//..............................................................................
function get_login_event($options=NULL)
	{
	global $_USER;
	
	if($_USER->is_logged('ANY')) return;
	if (isset($_REQUEST['url']))
		{
		$url = $_REQUEST['url'];
		} else $url =  (isset($_SERVER["HTTP_REFERER"])) ? $_SERVER["HTTP_REFERER"] : '/';


    $o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndUp');
	$o_modal->add_title('Введите данные для входа');

	$o_form = new itForm2([
		'name' => 'login', 
		'reCaptcha'=> get_const('USE_CAPTCHA', true),
		]);
	$o_form->action('/login/');
	$o_form->add_input([
		'name'		=> 'user_login',
		'value'		=> isset($_REQUEST['user_login']) ? $_REQUEST['user_login'] : '',
		'placeholder'	=> get_const('USER_LOGIN'),
		]);
		
	$o_form->add_password([
		'name'		=> 'user_password',
		'value'		=> (isset($_REQUEST['user_password']) ? $_REQUEST['user_password'] : NULL),
		'placeholder'	=> get_const('USER_PASSWORD'),
		]);
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );
	
	if (ready_val($options['reg']))
		{
		$b_reg = new itButton(get_const('NODE_REGISTER'), 'text', ['href' => "/".CMS_LANG."/register/"], 'blue' );
		$o_form->add_field("<span class='gray'>".get_const('LOGIN_REGISTER_DESC').$b_reg->code().BR."</span>");
		unset($b_reg);
		}
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton([
		'title'	=> 'login',
		'type'	=> 'textmodal', 
		'form'	=> $o_modal->form_id(),
		'color' => 'white']);
	$result = $o_modal->code().$o_button->code();
	
	unset($o_button, $o_form, $o_modal);

	return $result;
	}
	
//..............................................................................
// возвращает кнопку смены фоноыого изображения
//..............................................................................
function get_background_event()
	{
	global $_USER;
		
	$options = array (
		'class' 	=> 'admin bg_brown', 
		'name' 		=> get_const('DEFAULT_FILES_NAME'), 
//		'table_name' 	=> $row['table_name'],
//		'rec_id' 	=> $row['rec_id'],
		'controller'	=> $_REQUEST['controller'],
		'op' 		=> 'background',
//		'src'		=> '/themes/'.CMS_THEME.'/images/smartad-ava-add.png'
		);

	$o_btn = new itButton(get_const('BUTTON_BACKGROUND'), 'file', $options);
	$result = $o_btn->code();
	unset ($o_btn);
	return $result;
	}

//..............................................................................
// возвращает кнопку перехода на страницу настроек
//..............................................................................
function get_settings_event()
	{
	$o_btn = new itButton([
		'title'	=> 'Настройки',
		'type'	=> 'a',
		'href'	=> "/".CMS_LANG."/settings/",
		'class'	=> 'admin',
		'color'	=> 'yellow',
		]);
	$result = $o_btn->code();
	unset ($o_btn);
	return $result;
	}

//..............................................................................
// возвращает кнопку перехода на страницу просмотренных писем
//..............................................................................
function get_mailing_event()
	{
	$o_btn = new itButton([
		'title'	=> 'Письма',
		'type'	=> 'a',
		'href'	=> "/".CMS_LANG."/mailing/",
		'class'	=> 'admin',
		'color'	=> 'blue',
		]);
	$result = $o_btn->code();
	unset ($o_btn);
	return $result;
	}

//..............................................................................
// возвращает кнопку перехода на страницу мерок
//..............................................................................
function get_measurement_event()
	{
	$o_btn = new itButton([
		'title'	=> 'Мерки',
		'type'	=> 'a',
		'href'	=> "/".CMS_LANG."/measurement/",
		'class'	=> 'admin',
		'color'	=> 'green',
		]);
	$result = $o_btn->code();
	unset ($o_btn);
	return $result;
	}

//..............................................................................
// возвращает панель перехода на страницу мерок
//..............................................................................
function get_measurement_panel()
	{	
	global $lang_cat;
				// создадим форму для шифровки	
				$o_form = new itForm2([
					'class'		=> 'yellow',
					'action'	=> "/".CMS_LANG.'/measurement/',
					]);
					
				$form_id = $o_form->form_id();

				$o_form->add_email([
					'label'		=> 'Введите email',
					'name'		=> 'email',
					'compact'	=> true,
					]);

				$o_form->add_input([
					'label'		=> '№ заказа на предприятии',
					'name'		=> 'order',
					'compact'	=> true,
					]);

				$o_form->add_field("<input type='hidden' id='{$form_id}-form_id' val='".FORM2_MEASUREMENT."' name='form_id' />");

				$o_form->add_data([
					'op'	=> 'encode',
					]);

// 				$o_form->add_button(get_const('MEAS_1'), 'submit', ['form' => $o_form->form_id()], 'blue' );
				$o_form->add_button(get_const('MEAS_1'), 'a', ['ajax' => "$('#{$form_id}-form_id').val('".FORM2_MEASUREMENT."');$('#{$form_id}').submit();"], 'blue' );
				$o_form->add_button(get_const('MEAS_2'), 'a', ['ajax' => "$('#{$form_id}-form_id').val('".FORM2_MEASUREMENT2."');$('#{$form_id}').submit();"], 'green' );
				$o_form->add_button(get_const('MEAS_3'), 'a', ['ajax' => "$('#{$form_id}-form_id').val('".FORM2_MEASUREMENT3."');$('#{$form_id}').submit();"], 'brown' );
				$o_form->add_button(get_const('MEAS_4'), 'a', ['ajax' => "$('#{$form_id}-form_id').val('".FORM2_MEASUREMENT4."');$('#{$form_id}').submit();"], 'gold' );
				$o_form->add_button(get_const('MEAS_5'), 'a', ['ajax' => "$('#{$form_id}-form_id').val('".FORM2_MEASUREMENT5."');$('#{$form_id}').submit();"], 'fiolet' );				
												
//				$o_form->add_button(get_const('BUTTON_CLEAR'), 'a', ['ajax'=>"f2_reset('".$o_form->form_id()."');"], 'green big' );	

				$result =
					TAB."<div class='tit'>Создать ссылку на мерки <small class='blue'>{$lang_cat[CMS_LANG]['name_orig']}</small></div>".
					$o_form->_view();
				unset($o_form);
	return $result;
	}
?>
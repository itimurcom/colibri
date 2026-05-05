<?php
function admin_event_array_value($arr, $key, $default=NULL)
	{
	return (is_array($arr) && array_key_exists($key, $arr)) ? $arr[$key] : $default;
	}

function admin_event_request_value($key, $default='')
	{
	return isset($_REQUEST) && is_array($_REQUEST) && array_key_exists($key, $_REQUEST) ? $_REQUEST[$key] : $default;
	}

function admin_event_user_is_logged($groups='ANY')
	{
	global $_USER;
	return (is_object($_USER) && method_exists($_USER, 'is_logged')) ? $_USER->is_logged($groups) : false;
	}

function admin_event_language_name()
	{
	global $lang_cat;
	return (is_array($lang_cat) && defined('CMS_LANG') && isset($lang_cat[CMS_LANG]) && is_array($lang_cat[CMS_LANG]) && isset($lang_cat[CMS_LANG]['name_orig']))
		? $lang_cat[CMS_LANG]['name_orig']
		: (defined('CMS_LANG') ? CMS_LANG : '');
	}

function admin_event_link_button($title, $href, $color)
	{
	$o_btn = new itButton([
		'title'	=> $title,
		'type'	=> 'a',
		'href'	=> $href,
		'class'	=> 'admin',
		'color'	=> $color,
		]);
	$result = $o_btn->code();
	unset($o_btn);
	return $result;
	}

function admin_event_login_modal()
	{
	$o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndUp');
	$o_modal->add_title('Введите данные для входа');
	return $o_modal;
	}

function admin_event_add_ok_cancel($o_form, $o_modal)
	{
	$o_form->add_button(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue');
	$o_form->add_button(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green');
	}

function admin_event_measurement_button($o_form, $form_id, $title_const, $target_form_id, $color)
	{
	$o_form->add_button(
		get_const($title_const),
		'a',
		['ajax' => "$('#{$form_id}-form_id').val('{$target_form_id}');$('#{$form_id}').submit();"],
		$color
		);
	}

function get_login_event($options=NULL)
	{
	if(admin_event_user_is_logged('ANY')) return;

	$o_modal = admin_event_login_modal();
	$o_form = new itForm2([
		'name' => 'login',
		'reCaptcha'=> get_const('USE_CAPTCHA', true),
		]);
	$o_form->action('/login/');
	$o_form->add_input([
		'name'		=> 'user_login',
		'value'		=> admin_event_request_value('user_login', ''),
		'placeholder'	=> get_const('USER_LOGIN'),
		]);
	$o_form->add_password([
		'name'		=> 'user_password',
		'value'		=> admin_event_request_value('user_password', NULL),
		'placeholder'	=> get_const('USER_PASSWORD'),
		]);
	admin_event_add_ok_cancel($o_form, $o_modal);

	if (ready_value(admin_event_array_value($options, 'reg')))
		{
		$b_reg = new itButton(get_const('NODE_REGISTER'), 'text', ['href' => "/".CMS_LANG."/register/"], 'blue');
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

function get_background_event()
	{
	$o_btn = new itButton(get_const('BUTTON_BACKGROUND'), 'file', [
		'class'		=> 'admin bg_brown',
		'name'		=> get_const('DEFAULT_FILES_NAME'),
		'controller'	=> admin_event_request_value('controller', ''),
		'op'		=> 'background',
		]);
	$result = $o_btn->code();
	unset($o_btn);
	return $result;
	}

function get_settings_event()
	{
	return admin_event_link_button('Настройки', "/".CMS_LANG."/settings/", 'yellow');
	}

function get_mailing_event()
	{
	return admin_event_link_button('Письма', "/".CMS_LANG."/mailing/", 'blue');
	}

function get_measurement_event()
	{
	return admin_event_link_button('Мерки', "/".CMS_LANG."/measurement/", 'green');
	}

function get_measurement_panel()
	{
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
	$o_form->add_data(['op' => 'encode']);

	admin_event_measurement_button($o_form, $form_id, 'MEAS_1', FORM2_MEASUREMENT, 'blue');
	admin_event_measurement_button($o_form, $form_id, 'MEAS_2', FORM2_MEASUREMENT2, 'green');
	admin_event_measurement_button($o_form, $form_id, 'MEAS_3', FORM2_MEASUREMENT3, 'brown');
	admin_event_measurement_button($o_form, $form_id, 'MEAS_4', FORM2_MEASUREMENT4, 'gold');
	admin_event_measurement_button($o_form, $form_id, 'MEAS_5', FORM2_MEASUREMENT5, 'fiolet');

	$result =
		TAB."<div class='tit'>Создать ссылку на мерки <small class='blue'>".admin_event_language_name()."</small></div>".
		$o_form->_view();
	unset($o_form);
	return $result;
	}
?>

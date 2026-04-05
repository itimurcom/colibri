<?php
// ================ CRC ================
// version: 1.15.04
// hash: 9a8d965b7747068f2e187384341c7e48b847152b3950446152b40d01980511d0
// date: 10 March 2021  9:27
// ================ CRC ================
//..............................................................................
// функция возвращает html формы входа
//..............................................................................
function get_login_event($options=NULL)
	{
	if (isset($_REQUEST['url']))
		{
		$url = $_REQUEST['url'];
		} else $url =  (isset($_SERVER["HTTP_REFERER"])) ? $_SERVER["HTTP_REFERER"] : '/';


        $o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndUp');
	$o_modal->add_title(get_const('LOGIN_QUERY'));

	$o_form = new itForm2(
		[
		'name' 		=> 'login', 
		'reCaptcha'	=> ready_val($options['captcha']),
		]);
	$o_form->action('/login/');
	$o_form->add_input([
		'name'		=> 'user_login',
		'value'		=> isset($_REQUEST['user_login']) ? $_REQUEST['user_login'] : '',
		'label'		=> get_const('USER_LOGIN'),
		'placeholder'	=> true,
		]);
		
	$o_form->add_password('user_password', (isset($_REQUEST['user_password'])) ? $_REQUEST['user_password'] : '', get_const('USER_PASSWORD'), true);

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

	$o_button = new itButton(get_const('NODE_LOGIN'), 'textmodal', ['form' => $o_modal->form_id(), 'class' => 'admin'], 'blue' );
	$result = $o_modal->code().$o_button->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
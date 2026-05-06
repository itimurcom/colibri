<?php
// функция возвращает html формы входа
function get_login_event($options=NULL)
	{
	$options = is_array($options) ? $options : [];
	$url = (isset($_REQUEST) AND is_array($_REQUEST) AND isset($_REQUEST['url']))
		? $_REQUEST['url']
		: ((isset($_SERVER["HTTP_REFERER"])) ? $_SERVER["HTTP_REFERER"] : '/');


        $o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndUp');
	$o_modal->add_title(get_const('LOGIN_QUERY'));

	$o_form = new itForm2(
		[
		'name' 		=> 'login', 
		'reCaptcha'	=> ready_value(isset($options['captcha']) ? $options['captcha'] : NULL),
		]);
	$o_form->action('/login/');
	$o_form->add_input([
		'name'		=> 'user_login',
		'value'		=> (isset($_REQUEST) AND is_array($_REQUEST) AND isset($_REQUEST['user_login'])) ? $_REQUEST['user_login'] : '',
		'label'		=> get_const('USER_LOGIN'),
		'placeholder'	=> true,
		]);
		
	$o_form->add_password('user_password', (isset($_REQUEST) AND is_array($_REQUEST) AND isset($_REQUEST['user_password'])) ? $_REQUEST['user_password'] : '', get_const('USER_PASSWORD'), true);

	$o_form->add_button(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );
	$o_form->add_button(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );
	
	if (ready_value(isset($options['reg']) ? $options['reg'] : NULL))
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
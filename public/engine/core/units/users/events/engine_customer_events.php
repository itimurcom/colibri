<?php 
function customer_event_panel($id, $title, $body, $class='cus-form boxed bordered rounded', $body_class='body boxed', $link=NULL)
	{
	return
		TAB."<div".(!empty($id) ? " id='{$id}'" : NULL)." class='{$class}'>".
		TAB."<span class='title boxed'>".$title."</span>".
			TAB."<div class='{$body_class}'>".
			$body.
			$link.
			TAB."</div>".
		TAB."</div>";
	}

function customer_enter_form($form_id, $action, $op, $button_type='submit', $button_options=[])
	{
	$o_form = new itForm2([
		'action'	=> $action,
		'form_id'	=> $form_id,
		]);

	$o_form->add_email([
		'name'		=> 'logemail',
		'label'		=> get_const('ENTER_EMAIL'),
		'required'	=> true,
		'compact'	=> false,
		'more'		=> false,
		]);
	$o_form->add_data([
		'user_ip'	=> get_user_ip(),
		'op'		=> $op,
		'form_id'	=> $form_id,
		]);
	$o_form->add_button(get_const('BUTTON_ENTER'), $button_type, array_merge(['form'=>$o_form->form_id()], $button_options), 'green');
	return $o_form;
	}

function customer_modal_form($title, $size='medium', $animation='fadeAndPop')
	{
	$o_modal = new itModal();
	$o_modal->set_size($size);
	$o_modal->set_animation($animation);
	$o_form = new itForm2();
	$o_form->add_title($title);
	return [$o_modal, $o_form];
	}

function customer_modal_add_ok_cancel($o_form, $o_modal, $ok_color='blue', $cancel_color='green')
	{
	$o_form->add_button(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], $ok_color );
	$o_form->add_button(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], $cancel_color );
	}

function customer_modal_code($o_modal, $o_form, $button_title, $button_color='green')
	{
	$o_form->compile();
	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton($button_title, 'modal', ['form' => $o_modal->form_id()], $button_color );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}

function customer_profile_row($label, $value)
	{
	return
		TAB."<div class='row'>".
			TAB."<div class='field p1 gray center'>".$label."</div>".
			TAB."<div class='field p1'>".$value."</div>".
		TAB."</div>";
	}

// вход для пользователя
function customer_login_event(&$login)
	{
	$form_id = 'cus_enter';
	$o_form = customer_enter_form($form_id, '/'.CMS_LANG.'/register/', 'enter', 'submit', ['show' => true]);
	$result = $o_form->_view();

	$login = ($o_form->accepted AND (ready_val($_REQUEST['op'])=='enter'));
	unset($o_form);
 	return customer_event_panel(NULL, get_const('ENTER_PAGE_TITLE'), $result);
	}

// функция входа в систему
function customer_register_event(&$register)
	{
	global $_USER;
	$form_id = 'cus_register';

	$o_form = new itForm2([
		'rec_id'	=> FORM2_REGISTER,
		'reCaptcha'	=> true,
		'action'	=> "/".CMS_LANG.'/register/',
		'name'		=> 'register',
 		'form_id'	=> $form_id,
		]);

	$o_form->hiddens_xml = NULL;
	$o_form->add_data([
		'form_id'	=> $form_id,
		'op'		=> 'register',
		]);

	$o_form->buttons_xml = NULL;
	$o_form->add_button(get_const('BUTTON_REGISTER'), 'submit', ['form' => $o_form->form_id(), 'show' => true], 'blue' );
	$o_form->add_button(get_const('BUTTON_CLEAR'), 'a', ['ajax'=>"f2_reset('".$o_form->form_id()."');"], 'green' );

	if ($_USER->is_logged())
		{
		$o_form->store();
		}

	$result = $o_form->container();
	$register = ($o_form->accepted AND (ready_val($_REQUEST['op'])=='register'));
	unset($o_form);

	return
 		TAB."<div class='cus-form bordered rounded'>".
		TAB."<span class='title'>".get_const('REGISTER_PAGE_TITLE')."</span>".
		$result.
		TAB."</div>";
	}

// функция входа в систему
function customer_edit_event()
	{
	global $_USER;

	if (!$_USER->is_logged('ANY')) return;

	list($o_modal, $o_form) = customer_modal_form(str_replace ('[VALUE]', $_USER->data['email'], get_const('QUERY_EDIT_USER')));

	$o_form->add_input([
		'name'		=> 'name',
		'value'		=> $_USER->data['name'],
		'label'		=> get_const('USER_NAME'),
		'compact'	=> true,
		]);
	$o_form->add_input([
		'name'		=> 'address',
		'value'		=> $_USER->data['social'],
		'label'		=> get_const('USER_ADDRESS'),
		]);
	$o_form->add_phone([
		'name'		=> 'phone',
		'value'		=> $_USER->data['phone'],
		'label'		=> get_const('USER_PHONE'),
		'compact'	=> true,
		]);
	$o_form->add_data([
		'table_name'	=> DEFAULT_USER_TABLE,
		'rec_id'	=> $_USER->data['id'],
		'op'		=> 'user_edit',
		]);
	customer_modal_add_ok_cancel($o_form, $o_modal);

	$result =
		TAB."<div class='buttons_div'>".
		customer_modal_code($o_modal, $o_form, get_const('BUTTON_EDIT')).
		TAB."</div>";

	return
		TAB."<div class='list admin'>".
		customer_profile_row(get_const('USER_NAME'), $_USER->data['name']).
		customer_profile_row(get_const('USER_ADDRESS'), $_USER->data['social']).
		customer_profile_row(get_const('USER_PHONE'), $_USER->data['phone']).
		TAB."</div>".
		$result;
	}

// вход для пользователя
function customer_ajaxlogin_event(&$login)
	{
	global $_USER;

	if ($_USER->is_logged('ANY')) return;

	$o_form = customer_enter_form('cus_enter', '/ed_field.php', 'ajaxenter', 'ajaxsubmit', ['ajax' => 'refine_events();']);
	$result = $o_form->_view();
	$login = ($o_form->accepted AND (ready_val($_REQUEST['op'])=='ajaxenter'));
	unset($o_form);

	return customer_event_panel('ajaxlogin', get_const('ENTER_PAGE_TITLE'), $result, 'cus-form bordered rounded', 'body boxed', TAB."<a target='_blank' class='change blue' href='/".CMS_LANG."/register/'>".get_const('BUTTON_REGISTER')."</a>");
	}

// событие PIN пользователя
function customer_ajaxpin_event(&$pined)
	{
	global $_USER;
	$form_id = 'pin_enter';

	$o_form = new itForm2([
		'action'	=> '/'.CMS_LANG.'/register/pin/',
		'form_id'	=> $form_id,
		'class'		=> 'pin',
		]);

	$o_form->add_input([
		'name'		=> 'ajaxpin',
		'label'		=> get_const('ENTER_PIN'),
		'required'	=> true,
		'compact'	=> false,
		'more'		=> false,
		]);
	$o_form->add_description([
		'value'	=> get_const('NODE_PIN_DESC'),
		]);
	$o_form->add_data([
		'user_ip'	=> get_user_ip(),
		'op'		=> 'ajaxpin',
		'form_id'	=> $form_id,
		'path'		=> "{$_REQUEST['controller']}/{$_REQUEST['view']}",
		]);
	$o_form->add_button(get_const('BUTTON_ENTER'), 'ajaxsubmit', ['form'=>$o_form->form_id()], 'green');
	$result = $o_form->_view();

	$id_of_user = NULL;
	$pined = ($o_form->accepted
		AND (ready_val($_REQUEST['op'])=='ajaxpin')
		AND ($id_of_user = user_by_pin($_REQUEST['ajaxpin'])));

	if ($pined)
		{
		itMySQL::_update_value_db('users', $id_of_user, 'ACTIVE', 'status');
		$_USER->login($id_of_user);

		$do_replace = ($_REQUEST['path'] =='register/pin')
			? "window.location.href = '".CMS_CURRENT_BASE_URL."/".CMS_LANG."/cabinet/';"
			: "$('#ajaxpin').remove();".js_replace_userdata();
		$result = "<script>{$do_replace}</script>";
		}

	return ($id_of_user===false)
		? TAB.customer_ajaxlogin_event($login).ajax_error_focus('cus_enter-logemail', 'EXPIRED_PIN')
		: customer_event_panel('ajaxpin', get_const('ENTER_PIN_TITLE'), $result).
			((!$pined AND ready_val($_REQUEST['ajaxpin']))
				? ajax_error_focus('pin_enter-ajaxpin', 'ERROR_PIN')
				: ($pined ? "<script>go_cabinet('".CMS_CURRENT_BASE_URL."/".CMS_LANG."/cabinet/');</script>" : NULL));
	}

// возвращает аякс огибку в поле
function ajax_error_focus($element, $const)
	{
	return minify_js("<script>
			$(function (){
				var element = '{$element}';
				$(\"<div id ='error-\" + element + \"' class='modal_row error_msg f2_row focus'>".get_const($const)."</div>\").insertBefore('#container-' + element);
				$('#container-' + element).addClass('focus');
				$('#error-' + element).ScrollTo({duration:800, offsetTop:64, callback:function(){}});
				});
			</script>");
	}
?>

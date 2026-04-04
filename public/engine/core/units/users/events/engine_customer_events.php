<?
//..............................................................................
// вход для пользователя
//.............................................................................
function customer_login_event(&$login)
	{
	global $_USER;
	$form_id = 'cus_enter';

	$o_form = new itForm2([
		'action'	=> '/'.CMS_LANG.'/register/',
// 		'reCaptcha'	=> get_const('USE_CAPTCHA', true),
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
		'op'		=> 'enter',
		'form_id'	=> $form_id,
		]);
	$o_form->add_button(get_const('BUTTON_ENTER'), 'submit', ['form'=>$o_form->form_id(), 'show' => true], 'green');		
	$result = $o_form->_view();
	
	$login = ($o_form->accepted
		AND (ready_val($_REQUEST['op'])=='enter'));
 	return
 		TAB."<div class='cus-form boxed bordered rounded'>".
	 	TAB."<span class='title boxed'>".get_const('ENTER_PAGE_TITLE')."</span>".
	 		TAB."<div class='body boxed'>".
	 		$result.
	 		TAB."</div>".
 		TAB."</div>";
	}

//..............................................................................
// функция входа в систему
//.............................................................................
function customer_register_event(&$register)
	{
	global $_USER;
	$form_id = 'cus_register';	

	$result = NULL;
	$o_form = new itForm2([
		'rec_id'	=> FORM2_REGISTER,
		'reCaptcha'	=> true,
		'action'	=> "/".CMS_LANG.'/register/',
		'name'		=> 'register',	
 		'form_id'	=> $form_id,
		]);

/*
	if ($err_email = (!empty($email) AND customer_has_email($email)))
		{
		$_SESSION['focus']['element'] = ($o_form->form_id()).'-email';
		};
*/
			
	$o_form->hiddens_xml = NULL;
	$o_form->add_data([
// 		'error'		=> ($err_email OR $err_phone),
		'form_id'	=> $form_id,
		'op'		=> 'register',
		]);

	$o_form->buttons_xml = NULL;
	$o_form->add_button(get_const('BUTTON_REGISTER'), 'submit', ['form' => $o_form->form_id(), 'show' => true], 'blue' );	
	$o_form->add_button(get_const('BUTTON_CLEAR'), 'a', ['ajax'=>"f2_reset('".$o_form->form_id()."');"], 'green' );	

	if ($_USER->is_logged()) {
		$o_form->store();
		}

	$result = $o_form->container();

	$register = ($o_form->accepted
// 		AND !($err_email OR $err_phone)
		AND (ready_val($_REQUEST['op'])=='register'));
	unset($o_form);
	
	return 
 		TAB."<div class='cus-form bordered rounded'>".
		TAB."<span class='title'>".get_const('REGISTER_PAGE_TITLE')."</span>".
		$result.
		TAB."</div>";
	}


//..............................................................................
// функция входа в систему
//.............................................................................
function customer_edit_event()
	{
	global $_USER;
	
	if (!$_USER->is_logged('ANY')) return;

	$o_modal = new itModal();
	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndPop');
	
	$o_form = new itForm2();
	$o_form->add_title(str_replace ('[VALUE]', $_USER->data['email'], get_const('QUERY_EDIT_USER')));
	
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

/*
	$o_form->add_email([
		'name'		=> 'email',
		'value'		=> $_USER->data['email'],
		'label'		=> get_const('ITEM_EMAIL'),
		'compact'	=> true,
		]);
*/

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
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(get_const('BUTTON_EDIT'), 'modal', ['form' => $o_modal->form_id()], 'green' );
	$result =
		TAB."<div class='buttons_div'>".
		$o_button->code().$o_modal->code().
		TAB."</div>";
	unset($o_button, $o_form, $o_modal);
	return 
		TAB."<div class='list admin'>".
			TAB."<div class='row'>".
				TAB."<div class='field p1 gray center'>".get_const('USER_NAME')."</div>".
				TAB."<div class='field p1'>".$_USER->data['name']."</div>".
			TAB."</div>".
			TAB."<div class='row'>".
				TAB."<div class='field p1 gray center'>".get_const('USER_ADDRESS')."</div>".
				TAB."<div class='field p1'>".$_USER->data['social']."</div>".
			TAB."</div>".
			TAB."<div class='row'>".
				TAB."<div class='field p1 gray center'>".get_const('USER_PHONE')."</div>".
				TAB."<div class='field p1'>".$_USER->data['phone']."</div>".
			TAB."</div>".

		TAB."</div>".
		$result;	
	}


//..............................................................................
// вход для пользователя
//.............................................................................
function customer_ajaxlogin_event(&$login)
	{
// 	$data = itEditor::_redata();
	global $_USER;
	
	if ($_USER->is_logged('ANY')) return;
	$form_id = 'cus_enter';
	
	$o_form = new itForm2([
		'action'	=> '/ed_field.php',
// 		'reCaptcha'	=> true,
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
		'op'		=> 'ajaxenter',
		'form_id'	=> $form_id,
		]);
	$o_form->add_button(get_const('BUTTON_ENTER'), 'ajaxsubmit', ['ajax' => 'refine_events();', 'form'=>$o_form->form_id()], 'green');		
	$result = $o_form->_view();

	$login = ($o_form->accepted
		AND (ready_val($_REQUEST['op'])=='ajaxenter'));
 	return
 		TAB."<div  id='ajaxlogin' class='cus-form bordered rounded'>".
	 	TAB."<span class='title boxed'>".get_const('ENTER_PAGE_TITLE')."</span>".
	 		TAB."<div class='body boxed'>".
	 		$result.
	 		TAB."<a target='_blank' class='change blue' href='/".CMS_LANG."/register/'>".get_const('BUTTON_REGISTER')."</a>".
	 		TAB."</div>".
 		TAB."</div>";
	}


//..............................................................................
// событие PIN пользователя
//.............................................................................
function customer_ajaxpin_event(&$pined)
	{
	global $_USER;
	$form_id = 'pin_enter';
	
	$o_form = new itForm2([
		'action'	=> '/'.CMS_LANG.'/register/pin/',
// 		'reCaptcha'	=> get_const('USE_CAPTCHA', true),
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

	$o_form->add_desc([
		'value'	=> get_const('NODE_PIN_DESC'),
		]);
		
		
	$o_form->add_data([
		'user_ip'		=> get_user_ip(),
		'op'			=> 'ajaxpin',
		'form_id'		=> $form_id,
		'path'			=> "{$_REQUEST['controller']}/{$_REQUEST['view']}",
		]);
	$o_form->add_button(get_const('BUTTON_ENTER'), 'ajaxsubmit', ['form'=>$o_form->form_id()], 'green');	
// 	$o_form->add_button(get_const('BUTTON_ENTER'), 'submit', ['form'=>$o_form->form_id()], 'green');
	$result = $o_form->_view();

	$id_of_user = NULL;
	$pinned = ($o_form->accepted
		AND (ready_val($_REQUEST['op'])=='ajaxpin')
		AND ($id_of_user = user_by_pin($_REQUEST['ajaxpin'])));

	if ($pinned)
		{
		itMySQL::_update_value_db('users', $id_of_user, 'ACTIVE', 'status');
		$_USER->login($id_of_user);

		$do_replace = ($_REQUEST['path'] =='register/pin')
			? "window.location.href = 'https://{$_SERVER['HTTP_HOST']}/".CMS_LANG."/cabinet/';"
			: "$('#ajaxpin').remove();".js_replace_userdata();
		$result = "<script>{$do_replace}</script>";
		}

	return ($id_of_user===false)
		?	
// 			TAB."<b class='green'>".get_const('EXPIRED_PIN')."</b>".
			TAB.customer_ajaxlogin_event($login).
			ajax_error_focus('cus_enter-logemail', 'EXPIRED_PIN')
			
		:	TAB."<div id='ajaxpin' class='cus-form boxed bordered rounded'>".
		 	TAB."<span class='title boxed'>".get_const('ENTER_PIN_TITLE')."</span>".
		 		TAB."<div class='body boxed'>".
		 		$result.
		 		TAB."</div>".
	 		TAB."</div>".
	 		( (!$pinned AND ready_val($_REQUEST['ajaxpin']))
	 			? ajax_error_focus('pin_enter-ajaxpin', 'ERROR_PIN')
	 			: ( $pinned ? "<script>go_cabinet('https://".$_SERVER['HTTP_HOST']."/".CMS_LANG."/cabinet/');</script>" : NULL));
	}


//..............................................................................
// возвращает аякс огибку в поле
//.............................................................................
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
<?php 
function register_controller_request_value($key)
	{
	return isset($_REQUEST[$key]) ? ready_val($_REQUEST[$key]) : NULL;
	}

$_CONTENT['admin'] = get_admin_button_set();

global $_USER;

if ($_USER->is_logged('ANY'))
	{
	cms_redirect_page("/".CMS_LANG.'/cabinet/');
	}


$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();

$register_focus_error = function($element, $message_const)
	{
	return minify_js("<script>
		$(function (){
			var element = '{$element}';
			$(\"<div id ='error-\" + element + \"' class='modal_row error_msg f2_row focus'>".get_const($message_const)."</div>\").insertBefore('#container-' + element);
			$('#container-' + element).addClass('focus');
			$('#error-' + element).ScrollTo({duration:800, offsetTop:64, callback:function(){}});
			});
		</script>");
	};

$_CONTENT['content'] =
		TAB."<div class='site_row boxed'>".
			TAB."<div class='widgets row iphonecolumn boxed'>".
				TAB."<div class='fl25 boxed'>".
					customer_login_event($do_login).
				TAB."</div>".
				TAB."<div class='fl1 boxed noipad'></div>".
				TAB."<div class='fl75 boxed'>".
					customer_register_event($do_register).
				TAB."</div>".
			TAB."</div>".
		TAB."</div>";

if ($do_login)
	{
	if (is_array($customer = customer_by_email(register_controller_request_value('logemail'))))
		{
		create_pin($customer);		
		cms_redirect_page('/'.CMS_LANG.'/register/pin/');
		} else 	{
			$_CONTENT['content'] .= $register_focus_error('cus_enter-logemail', 'NOT_REGISTERED');
			}
	}


$already = false;
if (register_controller_request_value('email') AND ($customer = customer_by_email(register_controller_request_value('email'))))
	{
	$_CONTENT['content'] .= $register_focus_error('cus_register-email', 'ALREADY_HAVE');
	$already = true;
	}

if (register_controller_request_value('phone') AND ($customer = customer_by_phone(register_controller_request_value('phone'))))
	{
	$_CONTENT['content'] .= $register_focus_error('cus_register-phone', 'ALREADY_HAVE');
	$already = true;
	}

if ($do_register AND !$already)
	{
	$customer = register_customer();
	$pincode = create_pin($customer);
	cms_redirect_page('/'.CMS_LANG.'/register/pin/');
	}	
		

$plug_og['subtitle'] 	= get_const('CMS_NAME');
$plug_og['title'] 	= get_const('NODE_REGISTER');
?>
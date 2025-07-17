<?
$_CONTENT['admin'] = get_admin_button_set();
$data = itEditor::_redata();

global $_USER, $_SETTINGS;

if ($_USER->is_logged('ANY'))
	{
	cms_redirect_page("/".CMS_LANG.'/cabinet/');
	}


$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();



$_CONTENT['content'] =
// 	print_rr($_REQUEST).
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
	if (is_array($customer = customer_by_email($_REQUEST['logemail'])))
		{
		create_pin($customer);		
		cms_redirect_page('/'.CMS_LANG.'/register/pin/');
		} else 	{
			$_CONTENT['content'] .=  minify_js("<script>
			$(function (){
				var element = 'cus_enter-logemail'; 
				$(\"<div id ='error-\" + element + \"' class='modal_row error_msg f2_row focus'>".get_const('NOT_REGISTERED')."</div>\").insertBefore('#container-' + element);
				$('#container-' + element).addClass('focus');
				$('#error-' + element).ScrollTo({duration:800, offsetTop:64, callback:function(){}});
				});
			</script>");

			}
	}


$already = false;
if (!empty($_REQUEST['email']) AND ($customer = customer_by_email($_REQUEST['email'])))
	{
	$_CONTENT['content'] .= minify_js("<script>
		$(function (){
			var element = 'cus_register-email';
			$(\"<div id ='error-\" + element + \"' class='modal_row error_msg f2_row focus'>".get_const('ALREADY_HAVE')."</div>\").insertBefore('#container-' + element);
			$('#container-' + element).addClass('focus');
			$('#error-' + element).ScrollTo({duration:800, offsetTop:64, callback:function(){}});;
			});
		</script>");
	$already = true;
	}

if (!empty($_REQUEST['phone']) AND ($customer = customer_by_phone($_REQUEST['phone'])))
	{
	$_CONTENT['content'] .= minify_js("<script>
		$(function (){
			var element = 'cus_register-phone';
			$(\"<div id ='error-\" + element + \"' class='modal_row error_msg f2_row focus'>".get_const('ALREADY_HAVE')."</div>\").insertBefore('#container-' + element);
			$('#container-' + element).addClass('focus');
			$('#error-' + element).ScrollTo({duration:800, offsetTop:64, callback:function(){}});;
			});
		</script>");
	$already = true;
	}

if ($do_register AND !$already)
	{
	$customer = register_customer();
	$pincode = create_pin($customer);
	cms_redirect_page('/'.CMS_LANG.'/register/pin/');
	}	
		

// opengraph
$plug_og['subtitle'] 	= get_const('CMS_NAME');
$plug_og['title'] 	= get_const('NODE_REGISTER');
?>
<?php 
function order_controller_request_value($key, $default=NULL)
	{
	return (isset($_REQUEST) AND is_array($_REQUEST) AND array_key_exists($key, $_REQUEST)) ? ready_value($_REQUEST[$key], $default) : $default;
	}

function order_controller_user_logged($scope=NULL)
	{
	global $_USER;
	return is_object($_USER) AND method_exists($_USER, 'is_logged') AND (!is_null($scope) ? $_USER->is_logged($scope) : $_USER->is_logged());
	}

function order_controller_form_title($editor)
	{
	return (is_object($editor) AND isset($editor->data) AND is_array($editor->data) AND isset($editor->data['title_xml']))
		? get_field_by_lang($editor->data['title_xml'], CMS_LANG, '')
		: NULL;
	}

function order_controller_focus_script($form_id, $element='articul')
	{
	$focus_element = "{$form_id}-{$element}";
	return "<script>$('#{$focus_element}').closest('.modal_row.f2_row').addClass('focusblue');</script>";
	}

$_CONTENT['admin'] = get_admin_button_set();
$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();

$order_view = order_controller_request_value('view');
if ($order_view != 'thankyou')
	{
$o_form = new itForm2([
	'rec_id'	=> FORM2_ORDER,
	'reCaptcha'	=> get_const('USE_CAPTCHA', true),
	'action'	=> "/".CMS_LANG.'/order/',
	]);


$o_form->hiddens_xml = NULL;
$o_form->add_data([
	'op'	=> 'order',
	'form_id'	=> $o_form->form_id(),
	]);

$o_form->buttons_xml = NULL;	
$o_form->add_button(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id(), 'show'=>true], 'blue big' );	
$o_form->add_button(get_const('BUTTON_CLEAR'), 'a', ['ajax'=>"f2_reset('".$o_form->form_id()."');"], 'green big' );	

if (order_controller_user_logged()) $o_form->store();
	

$focus_str = NULL;
$order_item_id = order_controller_request_value('rec_id');
if(intval($order_item_id) AND is_array($row=itMySQL::_get_rec_from_db('items', $order_item_id)))
	{
	$articul = get_item_articul($row);
	$_REQUEST['articul'] = $articul;
	$_SESSION['focus']['element'] = $o_form->form_id()."-articul";
	$_SESSION['focus']['color'] = 'blue';
	$_SESSION['focus']['data'] = $articul;
	$focus_str = order_controller_focus_script($o_form->form_id());
	}
	
$form_container =
	customer_ajaxlogin_event($login).
	$o_form->container().
	update_userdata_script();

$o_editor = new itEditor([
	'table_name'	=> 'forms',
	'rec_id'	=> FORM2_ORDER,
	]);

$title = order_controller_form_title($o_editor);
$editor_data = (is_object($o_editor) AND isset($o_editor->data) AND is_array($o_editor->data)) ? $o_editor->data : NULL;


$_CONTENT['content'] = 
	TAB."<div class='block'>".
	TAB."<h1 class='tit'>{$title}</h1>".
		TAB."<div class='siterow boxed'>".
			$o_editor->container().
		TAB."</div>".
	((order_controller_user_logged() AND !is_null($editor_data)) ?
			TAB."<div class='admin_panel_div'>".
			(function_exists('get_content_title_event') ? get_content_title_event($editor_data) : "").
			TAB."</div>"
			: NULL);


if ($o_form->accepted AND (order_controller_request_value('op')=='order'))
	{
	_check_v3reCaptcha();
	$_SESSION['thankyouorder'] = send_colibri_mails(FORM2_ORDER);
	cms_redirect_page("/".CMS_LANG."/order/thankyou/");
	
	} else	{
		$_CONTENT['content'] .= $form_container.$focus_str;
		}

$_CONTENT['content'] .= TAB."</div>";
unset($o_form);

$plug_og['subtitle'] 	= get_const('CMS_NAME');
$plug_og['title'] 	= get_const('NODE_ORDER');
} else	{
	if (isset($_SESSION['thankyouorder']))
		{
		$mail_str = $_SESSION['thankyouorder'];
		unset($_SESSION['thankyouorder']);
		} else 	{
			$mail_str = NULL;
			}
	$_CONTENT['content'] = 
		TAB."<div class='block'>".
		get_colibri_block(BLOCK_THANKORDER, true).
		$mail_str.
		TAB."</div>";

	$plug_og['subtitle'] 	= get_const('CMS_NAME');
	$plug_og['title'] 	= get_const('THANKYOU');
	}
?>

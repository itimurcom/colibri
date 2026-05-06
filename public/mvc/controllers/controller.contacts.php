<?php 
function contacts_controller_request_value($key, $default=NULL)
	{
	return (isset($_REQUEST) AND is_array($_REQUEST) AND array_key_exists($key, $_REQUEST)) ? ready_value($_REQUEST[$key], $default) : $default;
	}

function contacts_controller_user_logged($scope=NULL)
	{
	global $_USER;
	return is_object($_USER) AND method_exists($_USER, 'is_logged') AND (!is_null($scope) ? $_USER->is_logged($scope) : $_USER->is_logged());
	}

function contacts_controller_form_title($editor)
	{
	return (is_object($editor) AND isset($editor->data) AND is_array($editor->data) AND isset($editor->data['title_xml']))
		? get_field_by_lang($editor->data['title_xml'], CMS_LANG, '')
		: NULL;
	}

function contacts_controller_focus_script($form_id, $element='articul')
	{
	$focus_element = "{$form_id}-{$element}";
	return "<script>$('#{$focus_element}').closest('.modal_row.f2_row').addClass('focusblue');</script>";
	}

$_CONTENT['admin'] = get_admin_button_set();

$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();

$contacts_view = contacts_controller_request_value('view');
if ($contacts_view != 'thankyou')
	{
$o_form = new itForm2([
	'rec_id'	=> FORM2_CONTACTS,
	'reCaptcha'	=> get_const('USE_CAPTCHA', true),
	'action'	=> "/".CMS_LANG.'/contacts/',
	]);

$focus_str = NULL;

$o_form->hiddens_xml = NULL;
$o_form->add_data([
	'op'		=> 'contacts',
	'form_id'	=> $o_form->form_id(),
	]);
	
$o_form->buttons_xml = NULL;
$o_form->add_button(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id(), 'show'=>true], 'blue big' );	
$o_form->add_button(get_const('BUTTON_CLEAR'), 'a', ['ajax'=>"f2_reset('".$o_form->form_id()."');"], 'green big' );	

if (contacts_controller_user_logged()) $o_form->store();

$contact_item_id = contacts_controller_request_value('rec_id');
if(intval($contact_item_id) AND is_array($row=itMySQL::_get_rec_from_db('items', $contact_item_id)))
	{
	$articul = get_item_articul($row);
	$category_title = isset($row['category_id']) ? get_category_by_id($row['category_id']) : '';
	$_REQUEST['articul'] = $articul;
	$_REQUEST['message'] = mstr_replace([
		'[VALUE]'	=> $articul,
		'[CAT]'		=> $category_title,
		], get_const('ORDER_MESSAGE_TITLE'));
	$_SESSION['focus']['element'] = $o_form->form_id()."-articul";
	$_SESSION['focus']['color'] = 'blue';
	$_SESSION['focus']['data'] = $articul;
	$focus_str = contacts_controller_focus_script($o_form->form_id());
	}

$form_container = 
	customer_ajaxlogin_event($login).
	$o_form->container().
	update_userdata_script();

$o_editor = new itEditor([
	'table_name'	=> 'forms',
	'rec_id'	=> FORM2_CONTACTS,
	]);

$title = contacts_controller_form_title($o_editor);
$editor_data = (is_object($o_editor) AND isset($o_editor->data) AND is_array($o_editor->data)) ? $o_editor->data : NULL;

$_CONTENT['content'] = 
	TAB."<div class='block'>".
	TAB."<h1 class='tit'>{$title}</h1>".
		TAB."<div class='siterow boxed'>".
			$o_editor->container().
		TAB."</div>".
	((contacts_controller_user_logged() AND !is_null($editor_data)) ?
			TAB."<div class='admin_panel_div'>".
			(function_exists('get_content_title_event') ? get_content_title_event($editor_data) : "").
			TAB."</div>"
			: NULL);


if ($o_form->accepted AND (contacts_controller_request_value('op')=='contacts'))
	{
	_check_v3reCaptcha();
	$_SESSION['thankyoucountacts'] = send_colibri_mails(FORM2_CONTACTS);		
	cms_redirect_page("/".CMS_LANG."/contacts/thankyou/");		

	} else	{
		$_CONTENT['content'] .= $form_container.$focus_str;
		}

$_CONTENT['content'] .= TAB."</div>";
unset($o_form, $o_editor);

$plug_og['subtitle'] 	= get_const('CMS_NAME');
$plug_og['title'] 	= get_const('NODE_CONTACTS');
} else	{
	if (isset($_SESSION['thankyoucountacts']))
		{
		$mail_str = $_SESSION['thankyoucountacts'];
		unset($_SESSION['thankyoucountacts']);
		} else 	{
			$mail_str = NULL;
			}	
	$_CONTENT['content'] = 
		TAB."<div class='block'>".
		get_colibri_block(BLOCK_THANKCONTACTS, true).
		$mail_str.
		TAB."</div>";

	$plug_og['subtitle'] 	= get_const('CMS_NAME');
	$plug_og['title'] 	= get_const('THANKYOU');
	}
?>

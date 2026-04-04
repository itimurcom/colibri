<?
$_CONTENT['admin'] = get_admin_button_set();
$data = itEditor::_redata();

$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();

if ($_REQUEST['view'] != 'thankyou')
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

if ($_USER->is_logged()) $o_form->store();

if(intval($_REQUEST['rec_id']) AND $row=itMySQL::_get_rec_from_db('items', $_REQUEST['rec_id']))
	{
	$_REQUEST['articul'] = get_item_articul($row);
	$_REQUEST['message'] = mstr_replace([
		'[VALUE]'	=> $_REQUEST['articul'],
		'[CAT]'		=> get_category_by_id($row['category_id']), 
		], get_const('ORDER_MESSAGE_TITLE'));
	$_SESSION['focus']['element'] = $o_form->form_id()."-articul";
	$_SESSION['focus']['color'] = 'blue';
	$_SESSION['focus']['data'] = $_REQUEST['articul'];
	$focus_str = "<script>$('#{$_SESSION['focus']['element']}').closest('.modal_row.f2_row').addClass('focusblue');</script>";
	}

// контейнер после данных!!!
$form_container = 
	customer_ajaxlogin_event($login).
	$o_form->container().
	update_userdata_script();

$o_editor = new itEditor([
	'table_name'	=> 'forms',
	'rec_id'	=> FORM2_CONTACTS,
	]);

$title = !is_null($o_editor->data) ? get_field_by_lang($o_editor->data['title_xml'], CMS_LANG, '') : NULL;

$_CONTENT['content'] = 
	TAB."<div class='block'>".
	TAB."<h1 class='tit'>{$title}</h1>".
		TAB."<div class='siterow boxed'>".
			$o_editor->container().
		TAB."</div>".
	(($_USER->is_logged() AND !is_null($o_editor->data)) ?
			TAB."<div class='admin_panel_div'>".
//			(function_exists('get_content_remove_event') ? get_content_remove_event($o_block->editor->data) : "").
			(function_exists('get_content_title_event') ? get_content_title_event($o_editor->data) : "").			
			TAB."</div>"
			: NULL); 					


if ($o_form->accepted AND (ready_val($_REQUEST['op'])=='contacts'))
	{
	_check_v3reCaptcha();
	$_SESSION['thankyoucountacts'] = send_colibri_mails(FORM2_CONTACTS);		
	cms_redirect_page("/".CMS_LANG."/contacts/thankyou/");		

	$_CONTENT['content'] .=
		TAB."<div class='siterow boxed'>".
			send_colibri_mails(FORM2_CONTACTS).
		TAB."</div>";
	} else	{
		$_CONTENT['content'] .= $form_container.$focus_str;
		}

$_CONTENT['content'] .= TAB."</div>";
unset($o_form, $o_block);

// opengraph
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
//		TAB."<div class='tit'>".get_const('CONTACTS_THANKYOU')."</div>".
		$mail_str.
		TAB."</div>";

	$plug_og['subtitle'] 	= get_const('CMS_NAME');
	$plug_og['title'] 	= get_const('THANKYOU');
	}
?>
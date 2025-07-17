<?
$_CONTENT['admin'] = get_admin_button_set();
$data = itEditor::_redata();

$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();

//$_CONTENT['content'] = 	get_colibri_block(BLOCK_BUY, true);

$_ITEM_ID = (isset($_REQUEST['selected_id'])) ?	 $_REQUEST['selected_id'] : $_REQUEST['rec_id'];

if ($articul = ($item_row = itMySQL::_get_rec_from_db('items', $_ITEM_ID)) ? get_item_articul($item_row) : NULL)
	{
	$o_images = new itImages([
		'table_name'	=> 'items',
		'rec_id'	=> $_ITEM_ID,
		'column'	=> 'images',
		'img_type'	=> 'GALLINE_SMALL',
		'class'		=> 'order',
		]);
	
	$_CONTENT['content'] = 
		TAB."<div class='tit'>".str_replace('[VALUE]', $articul, get_const('BUY_PAGE_TITLE'))."</div>".
		TAB."<div class='siterow boxed padded'>".
		$o_images->_view_gallery().
		TAB."</div>";
	
	unset($o_images);
	} else	{
		$_CONTENT['content'] = 
			TAB."<h1 class='tit'>".str_replace('[VALUE]', get_const('NO_DATA'), get_const('BUY_PAGE_TITLE'))."</h1>";	
		}

if ($_REQUEST['view'] != 'thankyou')
	{
$o_form = new itForm2([
	'rec_id'	=> FORM2_BUY,
	'reCaptcha'	=> get_const('USE_CAPTCHA', true),
	'action'	=> "/".CMS_LANG.'/buy/',
//	'debug'		=> 1,
	]);
	
$o_form->hiddens_xml = NULL;
$o_form->add_data([
	'op'		=> 'buy',
	'form_id'	=> $o_form->form_id(),
	]);
	

$o_form->buttons_xml = NULL;
$o_form->add_button(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id(), 'show'=>true], 'blue' );	
$o_form->add_button(get_const('BUTTON_CLEAR'), 'a', ['ajax'=>"f2_reset('".$o_form->form_id()."');"], 'green' );	

if ($_USER->is_logged()) $o_form->store();

$o_form->add_hidden([
	'name'	=> 'selected_id',
	'value'	=> $_ITEM_ID,
	]);


$focus_str = NULL;
if($item_row)
	{
	$_REQUEST['message'] = mstr_replace([
		'[VALUE]'	=> get_item_articul($item_row),
		'[CAT]'		=> get_category_by_id($item_row['category_id']), 
		], get_const('BUY_MESSAGE_TITLE'));
	}

// контейнер после данных!!!
$form_container = 
	customer_ajaxlogin_event($login).
	$o_form->container().
	update_userdata_script();
	
if ($o_form->accepted AND (ready_val($_REQUEST['op'])=='buy'))
	{
	_check_v3reCaptcha();
	$_SESSION['thankyoubuy'] = send_colibri_mails(FORM2_BUY);		
	cms_redirect_page("/".CMS_LANG."/buy/thankyou/");		
		
//	$_CONTENT['content'] .= send_colibri_mails(FORM2_BUY);
	} else	{
		$_CONTENT['content'] .= 
			$form_container.
			$focus_str;
		}
		
unset($o_form);

// opengraph
$plug_og['subtitle'] 	= get_const('CMS_NAME');
$plug_og['title'] 	= get_const('NODE_BUY');
} else	{
	if (isset($_SESSION['thankyoubuy']))
		{
		$mail_str = $_SESSION['thankyoubuy'];
		unset($_SESSION['thankyoubuy']);
		} else 	{
			$mail_str = NULL;
			}	
	$_CONTENT['content'] = 
		TAB."<div class='block'>".
		get_colibri_block(BLOCK_THANKBUY, true).
//		TAB."<div class='tit'>".get_const('CONTACTS_THANKYOU')."</div>".
		$mail_str.
		TAB."</div>";

	$plug_og['subtitle'] 	= get_const('CMS_NAME');
	$plug_og['title'] 	= get_const('THANKYOU');
	
	}
?>
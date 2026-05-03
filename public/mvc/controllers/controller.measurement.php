<?php 
global $_MEASURMENT;
$_CONTENT['admin'] = get_admin_button_set();
$data = itEditor::_redata();
if ($_REQUEST['op'] == 'encode')
	{
	$keycode = urlencode(itEditor::event_data([
		'start'	=> 1,
		'order'		=> $_REQUEST['order'],
		'email'		=> $_REQUEST['email'],
		'form_id'	=> $_REQUEST['form_id'],	
		]));
	cms_redirect_page("/".CMS_LANG."/measurement/?key={$keycode}");
	}

$order_data = 
	isset($_REQUEST['order'])
		? [
		'order'		=> $_REQUEST['order'],
		'email'		=> $_REQUEST['email'],	
		'form_id'	=> $_REQUEST['form_id'],
		]	
		: (isset($_REQUEST['key'])
			? @unserialize(simple_decrypt($_REQUEST['key']))
			: NULL);

if (isset($_REQUEST['key']) AND empty($order_data))
	{
	$_CONTENT['content'] = 
			TAB."<div class='tit'>".get_const('MEASUREMENT_ERROR')."</div>";
			$plug_og['subtitle'] 	= get_const('CMS_NAME');
			$plug_og['title'] 	= get_const('NODE_MEASUREMENT');
			return;
	}
	

$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();


if ($_REQUEST['view'] != 'thankyou')
	{
	if (is_null($order_data) AND !isset($_REQUEST['email']))
		{
		if (!$_USER->is_logged())
			{
			cms_redirect_page("/");
			} else	{
				$_CONTENT['content'] =
					TAB."<div class='siterow boxed'>".
					TAB."<div class='right50 boxed'>".
						get_measurement_panel().
					TAB."</div>".
					TAB."</div>";
				}
		} else {

$order_data['form_id'] = isset($order_data['form_id'])
	? $order_data['form_id']
	: 'error';

$o_form = new itForm2([
	'rec_id'	=> $order_data['form_id'],
	'reCaptcha'	=> get_const('USE_CAPTCHA', true),
	'class'		=> '',
	'action'	=> "/".CMS_LANG.'/measurement/',
	]);


$o_form->hiddens_xml = NULL;
$o_form->add_data([
	'op'	=> 'measurement',
	]);

$o_form->buttons_xml = NULL;	
$o_form->add_button(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue big' );	
$o_form->add_button(get_const('BUTTON_CLEAR'), 'a', ['ajax'=>"f2_reset('".$o_form->form_id()."');"], 'green big' );	

if ($_USER->is_logged()) $o_form->store();
$o_form->add_hidden('order', $order_data['order']);
$o_form->add_hidden('email', $order_data['email']);
$o_form->add_hidden('form_id', $order_data['form_id']);

// контейнер после данных!!!
$form_container = $o_form->container();

$o_editor = new itEditor([
	'table_name'	=> 'forms',
	'rec_id'	=> $order_data['form_id'],
	]);

$title = !is_null($o_editor->data) ? get_field_by_lang($o_editor->data['title_xml'], CMS_LANG, '') : NULL;


$order_str = 
	mstr_replace([
	'[EMAIL]' => $order_data['email'],
	'[ORDER]' => $order_data['order'],
	], get_const('MEASUREMENT_TITLE_DATA'));


$title_color = isset($_MEASURMENT[$order_data['form_id']])
	? "  bg_".$_MEASURMENT[$order_data['form_id']]['color']
	: NULL;

$_CONTENT['content'] = 
	TAB."<div class='block'>".
	TAB."<h1 class='tit white{$title_color}'>{$order_str}</h1>".
//	TAB."<div class='siterow boxed glass' style='font-size:1.4em;text-align:center; padding:1.2em;'>{$order_str}</div>".
	$o_editor->container();
	
if ($o_form->accepted AND ($_REQUEST['op']=='measurement'))
	{
	$_REQUEST['email'] = $order_data['email'];
	$_SESSION['thankyoumeasuremet'] = send_colibri_mails($order_data['form_id']);
	cms_redirect_page("/".CMS_LANG."/measurement/thankyou/");
	} else	{
		$_CONTENT['content'] .= $form_container;
		}

$_CONTENT['content'] .= TAB."</div>";
unset($o_form);

// opengraph
$plug_og['subtitle'] 	= get_const('CMS_NAME');
$plug_og['title'] 	= get_const('NODE_MEASUREMENT');
}
} else	{
	if (isset($_SESSION['thankyoumeasuremet']))
		{
		$mail_str = $_SESSION['thankyoumeasuremet'];
		unset($_SESSION['thankyoumeasuremet']);
		} else 	{
			$mail_str = NULL;
			}
	$_CONTENT['content'] = 
		TAB."<div class='block'>".
		get_colibri_block(BLOCK_THANKMEASUREMENT, true).
		$mail_str.
		TAB."</div>";
		
	$plug_og['subtitle'] 	= get_const('CMS_NAME');
	$plug_og['title'] 	= get_const('THANKYOU');
	}
?>
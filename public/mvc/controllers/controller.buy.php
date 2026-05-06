<?php 
function buy_controller_request_value($key, $default=NULL)
	{
	return (isset($_REQUEST) AND is_array($_REQUEST) AND array_key_exists($key, $_REQUEST)) ? ready_value($_REQUEST[$key], $default) : $default;
	}

function buy_controller_user_logged($scope=NULL)
	{
	global $_USER;
	return is_object($_USER) AND method_exists($_USER, 'is_logged') AND (!is_null($scope) ? $_USER->is_logged($scope) : $_USER->is_logged());
	}

function buy_controller_item_id()
	{
	$selected_id = buy_controller_request_value('selected_id');
	return !is_null($selected_id) ? $selected_id : buy_controller_request_value('rec_id');
	}

function buy_controller_item_preview($item_id, &$item_row)
	{
	$item_id = intval($item_id);
	$item_row = $item_id ? itMySQL::_get_rec_from_db('items', $item_id) : NULL;
	$item_row = is_array($item_row) ? $item_row : NULL;
	$articul = $item_row ? get_item_articul($item_row) : NULL;
	if (!$articul)
		{
		return TAB."<h1 class='tit'>".str_replace('[VALUE]', get_const('NO_DATA'), get_const('BUY_PAGE_TITLE'))."</h1>";
		}

	$o_images = new itImages([
		'table_name'	=> 'items',
		'rec_id'	=> $item_id,
		'column'	=> 'images',
		'img_type'	=> 'GALLINE_SMALL',
		'class'		=> 'order',
		]);
	$result = 
		TAB."<div class='tit'>".str_replace('[VALUE]', $articul, get_const('BUY_PAGE_TITLE'))."</div>".
		TAB."<div class='siterow boxed padded'>".
		$o_images->_view_gallery().
		TAB."</div>";
	unset($o_images);
	return $result;
	}

function buy_controller_form($item_id)
	{
	$o_form = new itForm2([
		'rec_id'	=> FORM2_BUY,
		'reCaptcha'	=> get_const('USE_CAPTCHA', true),
		'action'	=> "/".CMS_LANG.'/buy/',
		]);
	$o_form->hiddens_xml = NULL;
	$o_form->add_data([
		'op'		=> 'buy',
		'form_id'	=> $o_form->form_id(),
		]);
	$o_form->buttons_xml = NULL;
	$o_form->add_button(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id(), 'show'=>true], 'blue' );
	$o_form->add_button(get_const('BUTTON_CLEAR'), 'a', ['ajax'=>"f2_reset('".$o_form->form_id()."');"], 'green' );
	$o_form->add_hidden([
		'name'	=> 'selected_id',
		'value'	=> intval($item_id),
		]);
	return $o_form;
	}

function buy_controller_form_content($o_form)
	{
	global $login;
	return customer_ajaxlogin_event($login).$o_form->container().update_userdata_script();
	}

function buy_controller_thankyou_content()
	{
	$mail_str = isset($_SESSION['thankyoubuy']) ? $_SESSION['thankyoubuy'] : NULL;
	unset($_SESSION['thankyoubuy']);
	return
		TAB."<div class='block'>".
		get_colibri_block(BLOCK_THANKBUY, true).
		$mail_str.
		TAB."</div>";
	}

function buy_controller_item_message($item_row)
	{
	if (!is_array($item_row)) return;
	$_REQUEST['message'] = mstr_replace([
		'[VALUE]'	=> get_item_articul($item_row),
		'[CAT]'		=> isset($item_row['category_id']) ? get_category_by_id($item_row['category_id']) : '',
		], get_const('BUY_MESSAGE_TITLE'));
	}

$_CONTENT['admin'] = get_admin_button_set();
$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();

if (buy_controller_request_value('view') == 'thankyou')
	{
	$_CONTENT['content'] = buy_controller_thankyou_content();
	$plug_og['subtitle'] 	= get_const('CMS_NAME');
	$plug_og['title'] 	= get_const('THANKYOU');
	return;
	}

$_ITEM_ID = buy_controller_item_id();
$_CONTENT['content'] = buy_controller_item_preview($_ITEM_ID, $item_row);
$o_form = buy_controller_form($_ITEM_ID);
if (buy_controller_user_logged()) $o_form->store();

buy_controller_item_message($item_row);

if ($o_form->accepted AND (buy_controller_request_value('op')=='buy'))
	{
	_check_v3reCaptcha();
	$_SESSION['thankyoubuy'] = send_colibri_mails(FORM2_BUY);
	cms_redirect_page("/".CMS_LANG."/buy/thankyou/");
	}
else
	{
	$_CONTENT['content'] .= buy_controller_form_content($o_form);
	}

unset($o_form);
$plug_og['subtitle'] 	= get_const('CMS_NAME');
$plug_og['title'] 	= get_const('NODE_BUY');
?>

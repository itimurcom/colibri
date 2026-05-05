<?php
include ("engine/kernel.php");
define('DEBUG_ON', 0);
$path = UPLOADS_ROOT;

$orig_REQUEST = $_REQUEST;
$data = itEditor::_redata();
$operation = ed_field_request_value('op');
$url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';

function ed_field_json($payload=[])
	{
	return skel80_json_response($payload);
	}

function ed_field_json_result($payload=[])
	{
	return ed_field_json(array_merge(['result' => 1], $payload));
	}

function ed_field_redirect_result($url)
	{
	cms_redirect_page($url);
	return true;
	}

function ed_field_update_value_and_redirect($table_name, $rec_id, $value, $field, $url)
	{
	itMySQL::_update_value_db($table_name, $rec_id, $value, $field);
	return ed_field_redirect_result($url);
	}

function ed_field_request_value($key, $default=NULL)
	{
	return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
	}

function ed_field_ready_request_value($key, $default=NULL)
	{
	return ready_value(ed_field_request_value($key, $default));
	}

function ed_field_request_int($key, $default=0)
	{
	return intval(ed_field_request_value($key, $default));
	}

function ed_field_request_flag_on($key)
	{
	return ed_field_request_value($key) == 'on';
	}

function ed_field_request_data($key='data')
	{
	return skel80_decode_encrypted_array(ed_field_request_value($key), []);
	}

function ed_field_data_value($data, $key, $default=NULL)
	{
	return (is_array($data) AND isset($data[$key])) ? $data[$key] : $default;
	}

function ed_field_has_uploads()
	{
	return isset($_FILES[DEFAULT_FILES_NAME])
		AND isset($_FILES[DEFAULT_FILES_NAME]['name'])
		AND is_array($_FILES[DEFAULT_FILES_NAME]['name'])
		AND isset($_FILES[DEFAULT_FILES_NAME]['tmp_name'])
		AND is_array($_FILES[DEFAULT_FILES_NAME]['tmp_name']);
	}

function ed_field_calculator_rates()
	{
	return [
		'USD' => '$',
		'EUR' => '€',
		'UAH' => 'грн',
		'RUR' => 'руб',
	];
	}

function ed_field_uploaded_files()
	{
	$files = [];
	if (!ed_field_has_uploads())
		{
		return $files;
		}

	foreach ($_FILES[DEFAULT_FILES_NAME]['name'] as $key => $name)
		{
		if ($name === '' OR !isset($_FILES[DEFAULT_FILES_NAME]['tmp_name'][$key]))
			{
			continue;
			}

		$clear_name = clear_file_name($name);
		$clear_name = check_uploaded_file($clear_name, $_FILES[DEFAULT_FILES_NAME]['tmp_name'][$key]);
		if (move_uploaded_file($_FILES[DEFAULT_FILES_NAME]['tmp_name'][$key], UPLOADS_ROOT.$clear_name))
			{
			$files[] = $clear_name;
			}
		}
	return $files;
	}

function ed_field_slider_url($url)
	{
	$split = explode('?', $url);
	$rec_id = ed_field_request_int('rec_id');
	return "{$split[0]}?slide={$rec_id}";
	}

function ed_field_handle_item_calc($data=[])
	{
	global $_SETTINGS;
	$rate_sym = '$';
	$rate_val = 1;
	$quantity = 1;

	$form = new itForm2([
		'table_name' => $data['form_name'],
		'rec_id' => $data['form_id'],
	]);

	$result = $data['price'];
	$rates = ed_field_calculator_rates();
	foreach ($form->fields_xml as $row)
		{
		if (!isset($row['name']))
			{
			continue;
			}

		switch ($row['name'])
			{
			case 'quantity':
				$quantity = ed_field_request_value($row['name'], 1);
				break;
			case 'rate':
				if (($request_rate = ed_field_request_value($row['name'])) !== NULL && isset($rates[$request_rate]))
					{
					$rate_val = isset($_SETTINGS[$request_rate]['value']) ? $_SETTINGS[$request_rate]['value'] : 1;
					$rate_sym = $rates[$request_rate];
					}
				break;
			default:
				if (($request_value = ed_field_request_value($row['name'])) !== NULL)
					{
					$multi = ed_field_request_value($row['name']."-multi", 1);
					$result += doubleval($request_value)*$multi;
					}
				break;
			}
		}

	$result *= $quantity;
	$result_str = round($result*$rate_val, 2)." ".$rate_sym.
		(($rate_sym!='$') ? "&nbsp;<small class=\"green\">( {$result} $ )</small>" : NULL);

	unset($form);
	return ed_field_json_result([
		'show' => false,
		'type' => 'ajax',
		'value' => "$('#calculator-result-{$data['form_id']}').html('{$result_str}');",
	]);
	}

function ed_field_handle_mail_status_operation($operation, $data, $url)
	{
	$mail_id = skel80_request_mail_id($data);
	if (!$mail_id)
		{
		return ed_field_redirect_result($url);
		}

	$mail = itMySQL::_get_rec_from_db('mails', $mail_id);
	if (!is_array($mail) OR empty($mail['id']))
		{
		return ed_field_redirect_result($url);
		}

	$mail_id = intval($mail['id']);
	$reply = isset($mail['reply']) ? trim((string)$mail['reply']) : '';
	$reply_condition = ($reply !== '') ? "`reply` = '".addslashes($reply)."'" : "`id` = '{$mail_id}'";

	switch ($operation)
		{
		case 'spam':
			itMySQL::_request("UPDATE `".DB_PREFIX."mails` SET `status`='SPAM' WHERE {$reply_condition}");
			break;
		case 'spam_x':
			itMySQL::_request("UPDATE `".DB_PREFIX."mails` SET `status`='NOSPAM' WHERE {$reply_condition}");
			break;
		case 'mail_x':
			itMySQL::_request("UPDATE `".DB_PREFIX."mails` SET `status`='DELETED' WHERE `id` = '{$mail_id}'");
			break;
		case 'mail_not_x':
			itMySQL::_request("UPDATE `".DB_PREFIX."mails` SET `status`='NOSPAM' WHERE `id` = '{$mail_id}'");
			break;
		}

	return ed_field_redirect_result($url);
	}

function ed_field_settings_keys()
	{
	return ['DISCOUNT','EUR','UAH','RUR','TAX','SITE_ADMIN_EMAIL','SITE_SMTP_USER','SITE_SMTP_PASSWORD','FB_PAGE','IG_PAGE','TW_PAGE','VK_PAGE','OK_PAGE'];
	}

if (DEBUG_ON==1)
	{
	write_log("request:\n\n".print_r($_REQUEST,true), '--request.log');
	write_log("FILES:\n\n".print_r($_FILES,true), '--request.log');
	write_log("refered:\n\n".$url, '--request.log');
	}

if ($operation)
	{
	if (itUpGal::events($url, $path)) return;
	if (itEditor::events($url, $path)) return;

	switch ($operation)
		{
		case 'wish':
			wish($data);
			return ed_field_json_result(['value' => print_r($_SESSION['wishlist'], 1)]);
		case 'wishlist':
			return ed_field_json_result(['value' => wishlist(true)]);
		case 'clearwishlist':
			clear_wishlist();
			return ed_field_json_result();
		case 'ajaxpin':
			$form = customer_ajaxpin_event($pinned);
			return ed_field_json_result(['show' => true, 'type' => 'ajax', 'value' => "$('#ajaxpin').replaceWith($(obj['form']));", 'form' => $form]);
		case 'ajaxenter':
			$email = ed_field_ready_request_value('logemail');
			$form = customer_ajaxlogin_event($login);
			$code = "$('#ajaxlogin').replaceWith($(obj['form']));";
			if (ed_field_request_value('reload') === NULL AND $login AND is_array($customer = customer_by_email($email)))
				{
				create_pin($customer);
				$form = customer_ajaxpin_event($pin);
				}
			else
				{
				$form .= minify_js("<script>
				$(function (){
					var element = 'cus_enter-logemail';
					$(\"<div id ='error-\" + element + \"' class='modal_row error_msg f2_row focus'>".get_const('NOT_REGISTERED')."</div>\").insertBefore('#container-' + element);
					$('#container-' + element).addClass('focus');
					$('#error-' + element).ScrollTo({duration:800, offsetTop:64, callback:function(){}});
					});
				</script>");
				}
			return ed_field_json_result(['show' => true, 'type' => 'ajax', 'value' => $code, 'form' => $form]);
		case 'itemsort':
			$_SESSION['filter']['sort'] = ed_field_request_value('sort');
			$_SESSION['filter']['min'] = ed_field_ready_request_value('min') ? ed_field_request_value('min') : NULL;
			$_SESSION['filter']['max'] = ed_field_ready_request_value('max') ? ed_field_request_value('max') : NULL;
			return ed_field_json_result();
		case 'clearlastseen':
			if (isset($_SESSION[SESSION_PREFIX.LASTSEEN_ARR])) unset($_SESSION[SESSION_PREFIX.LASTSEEN_ARR]);
			return ed_field_json_result(['value' => NULL]);
		case 'filter':
			set_color_filter(ed_field_request_value('value'));
			return ed_field_json_result(['url' => '/'.CMS_LANG.'/items/']);
		case 'openclose':
			$data = ed_field_request_data();
			if (!ed_field_data_value($data, 'set'))
				{
				return ed_field_json_result(['value' => NULL]);
				}

			itSettings::set(ed_field_data_value($data, 'set'), ed_field_request_value('value'), ed_field_data_value($data, 'user_id'));
			return ed_field_json_result(['value' => NULL]);
		case 'item_calc':
			return ed_field_handle_item_calc($data);
		case '_lang':
			$rel = ed_field_request_value('rel');
			toggleLanguageAllowed($rel);
			return ed_field_json_result(['value' => $rel]);
		}
	}

if ($_USER->is_logged('ANY') && $operation)
	{
	switch ($operation)
		{
		case 'user_edit':
			update_customer();
			return ed_field_redirect_result($url);
		case 'password':
			if (ed_field_request_value('new_password', '')=='')
				{
				$_SESSION['focus']['element'] = "{$data['form_id']}-new_password";
				$_SESSION['focus']['color'] = 'red';
				add_error_message(get_const('ERROR_PASSWORD_EMPTY'));
				}
			else if (ed_field_request_value('new_password2', '')=='')
				{
				$_SESSION['focus']['element'] = "{$data['form_id']}-new_password2";
				$_SESSION['focus']['color'] = 'red';
				add_error_message(get_const('ERROR_PASSWORD2_EMPTY'));
				}
			else if (ed_field_request_value('new_password')!=ed_field_request_value('new_password2'))
				{
				add_error_message(get_const('ERROR_PASSWORD_EQUAL'));
				}
			else
				{
				itMySQL::_update_value_db(ed_field_request_value('table_name'), ed_field_request_int('rec_id'), sqlPassword(ed_field_request_value('new_password')), 'password');
				add_service_message(get_const('MESSAGE_PASSWORD_DONE'));
				}
			return ed_field_redirect_result($url);
		case 'add_user':
			if (itUser::get_user_id_from_login(ed_field_request_value('value'), ed_field_request_value('table_name'))==NULL)
				{
				$rec_id = itUser::register_user(ed_field_request_value('value'), '', 'new_user_script');
				add_service_message(str_replace('[VALUE]', ed_field_request_value('value'), get_const('USER_ADD_DONE')));
				$url = "/".ed_field_request_value('lang', CMS_LANG)."/user/{$rec_id}/";
				}
			else
				{
				add_error_message(str_replace('[VALUE]', ed_field_request_value('value'), get_const('USER_LOGIN_BUSY')));
				}
			return ed_field_redirect_result($url);
		case 'user_name':
		case 'user_phone':
		case 'user_email':
			$field = str_replace('user_', '', $operation);
			return ed_field_update_value_and_redirect(ed_field_request_value('table_name'), ed_field_request_int('rec_id'), html2txt(ed_field_request_value('value')), $field, $url);
		case 'user_sex':
			return ed_field_update_value_and_redirect(ed_field_request_value('table_name'), ed_field_request_int('rec_id'), ed_field_request_value('sex'), 'sex', $url);
		case 'user_description':
			return ed_field_update_value_and_redirect(ed_field_request_value('table_name'), ed_field_request_int('rec_id'), strip_tags(br2nl(ed_field_request_value('description', ''), true), ALLOWED_TAGS), 'description', $url);
		case 'user_bdate':
			return ed_field_update_value_and_redirect(ed_field_request_value('table_name'), ed_field_request_int('rec_id'), ed_field_request_value('b_date'), 'b_date', $url);
		case 'tab':
			$data = ed_field_request_data();
			if (!ed_field_data_value($data, 'set'))
				{
				return ed_field_json_result(['value' => NULL]);
				}

			itSettings::set(ed_field_data_value($data, 'set'), ed_field_data_value($data, 'value'), ed_field_data_value($data, 'user_id'));
			return ed_field_json_result(['value' => NULL]);
		}
	}

if ($_USER->is_logged() && $operation)
	{
	if (itForm2::events($url, $path)) return;
	if (in_array($operation, ['spam', 'spam_x', 'mail_x', 'mail_not_x'])) return ed_field_handle_mail_status_operation($operation, $data, $url);

	switch ($operation)
		{
		case 'banner':
			foreach (ed_field_uploaded_files() as $clear_name)
				{
				$row = itMySQL::_get_rec_from_db($data['table_name'], $data['rec_id']);
				if (!is_array($row['images_xml'])) $row['images_xml'] = NULL;
				$row['images_xml'][CMS_LANG] = $clear_name;
				itMySQL::_update_value_db($data['table_name'], $data['rec_id'], $row['images_xml'], 'images_xml');
				}
			return ed_field_json_result(['value' => $url]);
		case 'bannerx':
			if ($row = itMySQL::_get_rec_from_db($data['table_name'], $data['rec_id']))
				{
				if (isset($row['images_xml'][CMS_LANG])) unset($row['images_xml'][CMS_LANG]);
				itMySQL::_update_value_db($data['table_name'], $data['rec_id'], $row['images_xml'], 'images_xml');
				}
			return ed_field_redirect_result($url);
		case 'add_item':
			$values_arr = [
				'title_xml' => [ed_field_request_value('lang', CMS_LANG) => ed_field_request_value('value')],
				'category_id' => ed_field_request_value('category_id'),
				'is_replicant' => ed_field_request_flag_on('is_replicant'),
				'is_shop' => ed_field_request_flag_on('is_shop'),
				'serie' => ed_field_request_value('serie'),
				'version' => ed_field_request_value('version'),
				'datetime' => mysql_now(),
				'url_xml' => NULL,
			];
			$rec_id = itMySQL::_insert_rec(ed_field_request_value('table_name'), $values_arr);
			return ed_field_redirect_result('/'.CMS_LANG.'/items/'.$rec_id.'/');
		case 'item_articul':
			itMySQL::_update_db_rec($data['table_name'], $data['rec_id'], [
				'category_id' => ed_field_request_value('category_id'),
				'serie' => ed_field_request_value('serie'),
				'version' => ed_field_request_value('version'),
			]);
			return ed_field_redirect_result('/'.CMS_LANG.'/items/'.$data['rec_id'].'/');
		case 'item_price':
			return ed_field_update_value_and_redirect($data['table_name'], $data['rec_id'], str_replace(',', '.', ed_field_request_value('value', '')), 'price', $url);
		case 'item_color':
			set_item_color($data['id'], $data['value']);
			return ed_field_json_result(['value' => $url]);
		case 'lang':
			return ed_field_update_value_and_redirect(ed_field_request_value('table_name'), ed_field_request_int('rec_id'), ed_field_request_value('lang_short'), 'lang', $url);
		case 'settings':
			foreach (ed_field_settings_keys() as $VAR)
				if (ed_field_request_value($VAR) !== NULL) itSettings::set($VAR, str_replace(',', '.', ed_field_request_value($VAR, '')));
			return ed_field_redirect_result($url);
		}
	}

if ($_USER->is_logged($_RIGHTS['EDIT']) && $operation)
	{
	if (itImages::events($url, $path)) return;
	if (itEditor::events($url, $path)) return;
	if (itCategory::events($url, $path)) return;
	if (itWizard::events($url, $path)) return;
	if (itObject::events($url, $path)) return;
	if (itForm2::events($url, $path)) return;

	if (in_array($operation, ['is_new', 'is_econom', 'is_shop', 'is_replicant']))
		{
		if ($row = itMySQL::_get_rec_from_db($data['table_name'], $data['rec_id']))
			{
			$value = !empty($row[$operation]) ? 0 : 1;
			itMySQL::_update_value_db($data['table_name'], $data['rec_id'], $value, $operation);
			}
		return ed_field_redirect_result($url);
		}

	switch ($operation)
		{
		case 'item_x':
			return ed_field_update_value_and_redirect($data['table_name'], $data['rec_id'], 'DELETED', 'status', '/'.CMS_LANG.'/items/');
		case 'add_slider':
			foreach (ed_field_uploaded_files() as $clear_name)
				{
				itSlider::add($clear_name);
				}
			return ed_field_json_result(['value' => $url]);
		case 'slider_x':
			$db = new itMySQL();
			$db->remove_rec_from_db(ed_field_request_value('table_name'), ed_field_request_int('rec_id'));
			unset($db);
			return ed_field_redirect_result($url);
		case 'slider_title':
			itSlider::set_title(ed_field_request_value('table_name'), ed_field_request_int('rec_id'), ed_field_request_value('value'), ed_field_request_value('lang', CMS_LANG));
			return ed_field_redirect_result(ed_field_slider_url($url));
		case 'slider_href':
			itSlider::set_href(ed_field_request_value('table_name'), ed_field_request_int('rec_id'), ed_field_request_value('value'), ed_field_request_value('lang', CMS_LANG));
			return ed_field_redirect_result(ed_field_slider_url($url));
		case 'add_content':
			$db = new itMySQL();
			$rec_id = $db->insert_rec(ed_field_request_value('table_name'));
			$db->update_value_db(ed_field_request_value('table_name'), $rec_id, [ed_field_request_value('lang', CMS_LANG) => ed_field_request_value('value')], 'title_xml');
			$db->update_value_db(ed_field_request_value('table_name'), $rec_id, ed_field_request_value('category_id'), 'category_id');
			unset($db);
			$url = '/'.CMS_LANG.'/material/'.$rec_id.'/'; die;
			cms_redirect_page($url);
			break;
		case 'datetime':
			$db = new itMySQL();
			$db->update_value_db(ed_field_request_value('table_name'), ed_field_request_int('rec_id'), ed_field_request_value('datetime'), 'datetime');
			unset($db);
			return ed_field_redirect_result($url);
		case 'killall':
			$db = new itMySQL();
			$db->request("DELETE from {$db->db_prefix}".ed_field_request_value('table_name')." where `status` = '".ed_field_request_value('status')."'");
			$db->reset_autoinc(ed_field_request_value('table_name'));
			unset($db);
			return ed_field_redirect_result($url);
		case 'background':
			$dest = "themes/".CMS_THEME."/images/bg_{$data['controller']}.jpg";
			if (!ed_field_has_uploads() OR !isset($_FILES[DEFAULT_FILES_NAME]['tmp_name'][0]) OR !move_uploaded_file($_FILES[DEFAULT_FILES_NAME]['tmp_name'][0], $dest))
				{
				$upload_name = (ed_field_has_uploads() AND isset($_FILES[DEFAULT_FILES_NAME]['name'][0])) ? $_FILES[DEFAULT_FILES_NAME]['name'][0] : '';
				add_error_message("error loading background {$upload_name} ...");
				}
			else
				{
				magic_resizer($dest, $dest,
					$pic_tech['BACKGROUND']['sx'],
					$pic_tech['BACKGROUND']['sy'],
					$pic_tech['BACKGROUND']['crop'],
					$pic_tech['BACKGROUND']['logo'],
					$pic_tech['BACKGROUND']['quality'],
					ready_val($pic_tech['BACKGROUND']['place']));
				}
			return ed_field_json_result(['url' => $url]);
		}
	}

if (DEBUG_ON!=1)
	{
	cms_redirect_page($url);
	}
else
	{
	echo "<font color='lightgray'>_REQUEST:".print_rr($_REQUEST).
		"_FILES:\n\n".print_rr($_FILES).
		"_REFERRER:".$url."</font>";
	}

?>

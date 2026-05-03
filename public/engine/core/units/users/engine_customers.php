<?php
function customer_request_value($key, $default=NULL)
	{
	return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
	}

function customer_request_ready_value($key, $default=NULL)
	{
	$value = customer_request_value($key);
	return ready_val($value, $default);
	}

function customer_array_value($row, $key, $default='')
	{
	return is_array($row) && isset($row[$key]) ? $row[$key] : $default;
	}

function customer_find_first($sql)
	{
	return (is_array($request = itMySQL::_request($sql)) && isset($request[0]) && is_array($request[0]))
		? $request[0]
		: false;
	}

function customer_by_email($email=NULL, $table_name=DEFAULT_USER_TABLE, $db_prefix=DB_PREFIX)
	{
	if (empty($email)) return;
	$email = trim((string)$email);
	return customer_find_first("SELECT * FROM `{$db_prefix}{$table_name}` WHERE `email`='{$email}' LIMIT 1");
	}

function customer_normalize_phone($phone)
	{
	return mstr_replace([
		'(' 	=> '',
		')'	=> '',
		'+'	=> '',
		'-'	=> '',
		], $phone);
	}

function customer_by_phone($phone=NULL, $table_name=DEFAULT_USER_TABLE, $db_prefix=DB_PREFIX)
	{
	if (empty($phone)) return;
	$phone = customer_normalize_phone($phone);
	if ($phone === '') return;
	return customer_find_first("SELECT * FROM `{$db_prefix}{$table_name}` WHERE `phone`='{$phone}' OR `phone`='+{$phone}' LIMIT 1");
	}

function user_by_pin($pincode=NULL, $table_name=DEFAULT_PIN_TABLE, $db_prefix=DB_PREFIX)
	{
	if (empty($pincode)) return NULL;
	$request = customer_find_first("SELECT * FROM `{$db_prefix}{$table_name}` WHERE `pin`='{$pincode}' LIMIT 1");
	return is_array($request)
		? ((strtotime('now') > strtotime(customer_array_value($request, 'expire'))) ? false : customer_array_value($request, 'user_id'))
		: NULL;
	}

function customer_smtp_credentials()
	{
	global $_SETTINGS;
	return [
		'user'		=> isset($_SETTINGS['SITE_SMTP_USER']['value']) ? trim((string)$_SETTINGS['SITE_SMTP_USER']['value']) : '',
		'password'	=> isset($_SETTINGS['SITE_SMTP_PASSWORD']['value']) ? trim((string)$_SETTINGS['SITE_SMTP_PASSWORD']['value']) : '',
		];
	}

function create_pin($customer=NULL)
	{
	if (!is_array($customer) || empty($customer['id']) || empty($customer['email'])) return NULL;
	if (!defined('HTTP_PATH'))
		define('HTTP_PATH', CMS_CURRENT_BASE_URL_SLASH);

	$pincode = rand_id();
	$values_arr = [
		'user_id'	=> customer_array_value($customer, 'id'),
		'expire'	=> get_mysql_datetime(strtotime('+5 min')),
		'pin'		=> $pincode,
		];

	itMySQL::_insert_rec('pins', $values_arr);

	$m_code = [
		'prepared'	=> mstr_replace([
			'[USER]'	=> itUser::get_user_name($customer),
			'[PIN]'		=> $pincode,
			'[PINREG]'	=> CMS_CURRENT_BASE_URL.'/'.CMS_LANG.'/register/pin/',
			], get_const('PIN_DESC')),
		'subject'	=> CMS_NAME." (".CMS_LANG.") : ".skel80_strftime_compat(" %d %b %Y  (%a)", strtotime('now'))." PIN CODE",
		];

	itMailTemplate::_code($m_code, false);
	$smtp = customer_smtp_credentials();

	$mails[] =[
		'from'		=> $smtp['user'],
		'to'		=> trim((string)customer_array_value($customer, 'email')),
		'reply'		=> $smtp['user'],
		'subject'	=> $m_code['subject'],
		'message'	=> $m_code['result'],
		'user'		=> $smtp['user'],
		'password'	=> $smtp['password'],
		];

	itMailings::_send_arr($mails, true);

	$o_mailer = new itMailer();
	unset($o_mailer);

	return $pincode;
	}

function register_customer()
	{
	$values_arr = [
		'email'		=> customer_request_value('email', ''),
		'phone'		=> customer_request_value('phone', ''),
		'name'		=> customer_request_value('name', ''),
		'social'	=> customer_request_value('address', ''),
		'description'	=> customer_request_value('select10', ''),
		'datetime'	=> mysql_now(),
		'status'	=> 'NOACTIVE',
		'data'		=> is_array($_REQUEST) ? $_REQUEST : [],
		];

	$values_arr['id'] = itMySQL::_insert_rec('users', $values_arr);
	return $values_arr;
	}

function update_customer($id_of_user=NULL)
	{
	$id_of_user = !is_null($id_of_user) ? $id_of_user : customer_request_value('rec_id');
	if (empty($id_of_user)) return;
	$values_arr = [
		'phone'		=> customer_request_value('phone', ''),
		'name'		=> customer_request_value('name', ''),
		'social'	=> customer_request_value('address', ''),
		'description'	=> customer_request_ready_value('select10'),
		];

	itMySQL::_update_db_rec(customer_request_value('table_name', DEFAULT_USER_TABLE), $id_of_user, $values_arr);
	}

function js_replace_userdata()
	{
	global $_USER;

	$user_data = isset($_USER->data) && is_array($_USER->data) ? $_USER->data : [];
	$replace = [
		'name'		=> customer_array_value($user_data, 'name'),
		'phone'		=> customer_array_value($user_data, 'phone'),
		'email'		=> customer_array_value($user_data, 'email'),
		'address' 	=> customer_array_value($user_data, 'social'),
		'country' 	=> customer_array_value($user_data, 'social'),
		];

	if (isset($user_data['data']) && is_array($user_data['data']))
		foreach($user_data['data'] as $key=>$row)
			{
			if (!in_array($key, str_getcsv('name,address,email,phone,data,rec_id,table_name,lang,controller,view,form_id,op,v3resp')))
				{
				$replace[$key]	= $row;
				}
			}

	$do_replace = " function upd_user() {";
	foreach ($replace as $key=>$value)
			{
			$do_replace .= "update_f2_input('{$key}', '{$value}');\n";
			}
	$do_replace .= "} upd_user();";

	return $do_replace;
	}
function update_userdata_script()
	{
	global $_USER;
	return 	($_USER->is_logged('ANY') ? "<script>".js_replace_userdata()."</script>" : NULL);
	}
?>

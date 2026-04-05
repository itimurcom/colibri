<?
//..............................................................................
// функция проверки email на вхождение
//..............................................................................
function customer_by_email($email=NULL, $table_name=DEFAULT_USER_TABLE, $db_prefix=DB_PREFIX)
	{
	if (empty($email)) return;
	return is_array($request = itMySQL::_request("SELECT * FROM `{$db_prefix}{$table_name}` WHERE `email`='{$email}' LIMIT 1"))
		? $request[0]
		: false;
	}

//..............................................................................
// функция проверки phone на вхождение
//..............................................................................
function customer_by_phone($phone=NULL, $table_name=DEFAULT_USER_TABLE, $db_prefix=DB_PREFIX)
	{
	if (empty($phone)) return;
	$phone = mstr_replace([
		'(' 	=> '',
		')'	=> '',
		'+'	=> '',
		'-'	=> '',
		], $phone);
	$sql = "SELECT * FROM `{$db_prefix}{$table_name}` WHERE `phone`='{$phone}' OR `phone`='+{$phone}' LIMIT 1";
	return is_array($request = itMySQL::_request($sql))
		? $request[0]
		: false;		
	}

//..............................................................................
// вход через pin код
//..............................................................................
function user_by_pin($pincode=NULL, $table_name=DEFAULT_PIN_TABLE, $db_prefix=DB_PREFIX)
	{
	return  (is_array($request = itMySQL::_request("SELECT * FROM `{$db_prefix}{$table_name}` WHERE `pin`='{$pincode}' LIMIT 1")))
		? ( (strtotime('now') > strtotime($request[0]['expire']) )
			? false
			: $request[0]['user_id'] )
		: NULL;
	}

//..............................................................................
// вход через pin код
//..............................................................................
function create_pin($customer=NULL)
	{
	if (!defined('HTTP_PATH'))
		define('HTTP_PATH', 'https://'.$_SERVER['HTTP_HOST'].'/');
		
	global $_SETTINGS;

	$pincode = rand_id();
	$values_arr = [
		'user_id'	=> $customer['id'],
		'expire'	=> get_mysql_datetime(strtotime('+5 min')),
		'pin'		=> $pincode,
		];
	
	itMySQL::_insert_rec('pins', $values_arr);


	$m_code = [
		'prepared'	=> mstr_replace([
			'[USER]'	=> itUser::get_user_name($customer),
			'[PIN]'		=> $pincode,
			'[PINREG]'	=> "https://{$_SERVER['HTTP_HOST']}/".CMS_LANG."/register/pin/",
			], get_const('PIN_DESC')),
		'subject'	=> CMS_NAME." (".CMS_LANG.") : ".strftime(" %d %b %Y  (%a)",strtotime('now'))." PIN CODE",
		];

	itMailTemplate::_code($m_code, false);

	$mails[] =[
	'from'		=> trim($_SETTINGS['SITE_SMTP_USER']['value']),	
	'to'		=> trim($customer['email']),
	'reply'		=> trim($_SETTINGS['SITE_SMTP_USER']['value']),
	'subject'	=> $m_code['subject'],
	'message'	=> $m_code['result'],
	'user'		=> trim($_SETTINGS['SITE_SMTP_USER']['value']),
	'password'	=> trim($_SETTINGS['SITE_SMTP_PASSWORD']['value']),
	];

	itMailings::_send_arr($mails, true);

	$o_mailer = new itMailer();
	unset($o_mailer);
	
	return $pincode;
	}

//..............................................................................
// добавляем нового пользователя
//..............................................................................
function register_customer()
	{
	$values_arr = [
		'email'		=> $_REQUEST['email'],
		'phone'		=> $_REQUEST['phone'],
		'name'		=> $_REQUEST['name'],
		'social'	=> $_REQUEST['address'],
		'description'	=> $_REQUEST['select10'],
		'datetime'	=> mysql_now(),
		'status'	=> 'NOACTIVE',
		'data'		=> $_REQUEST,
		];
	
	$values_arr['id'] = itMySQL::_insert_rec('users', $values_arr);
	return $values_arr;
	}

//..............................................................................
// добавляем нового пользователя
//..............................................................................
function update_customer($id_of_user=NULL)
	{
	$id_of_user = !is_null($id_of_user) ? $id_of_user : $_REQUEST['rec_id'];
	$values_arr = [
// 		'email'		=> $_REQUEST['email'],
		'phone'		=> $_REQUEST['phone'],
		'name'		=> $_REQUEST['name'],
		'social'	=> $_REQUEST['address'],
		'description'	=> ready_val($_REQUEST['select10']),
		];
	
	itMySQL::_update_db_rec($_REQUEST['table_name'], $id_of_user, $values_arr);
	}
	
//..............................................................................
// javascript данных пользователя в форму
//..............................................................................
function js_replace_userdata()
	{
	global $_USER;

	$replace = [
		'name'		=> $_USER->data['name'],
		'phone'		=> $_USER->data['phone'],
		'email'		=> $_USER->data['email'],
		'address' 	=> $_USER->data['social'],
		'country' 	=> $_USER->data['social'],				
		];
	
	if (is_array($_USER->data['data']))
		foreach($_USER->data['data'] as $key=>$row)
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
//..............................................................................
// javascript данных пользователя в форму - PHP версия
//..............................................................................
function update_userdata_script()
	{
	global $_USER;
	return 	($_USER->is_logged('ANY') ? "<script>".js_replace_userdata()."</script>" : NULL);
	}
?>
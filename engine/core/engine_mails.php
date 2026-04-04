<?
define('SEND_USER_MAILS', 0);
//define('DEFAULT_ORDER_TABLE', 'orders');

define('NOT_PINCODE'," AND `subject` NOT REGEXP ('PIN')");
define('HAS_PINCODE'," AND `subject` REGEXP ('PIN')");

//..............................................................................
// возвращает строку полного адреса
//..............................................................................
function get_full_address_request()
	{
	return "<div style='margin-top:0 16px;'><span style='color: blue;'>".get_const('FULL_ADDRESS_TITLE')."&nbsp;:</span>&nbsp;".
		"{$_REQUEST['name']}".
		(!empty($_REQUEST['address']) ? ", ".$_REQUEST['address'] : NULL).
		(!empty($_REQUEST['address2']) ? ", ".$_REQUEST['address2'] : NULL).
		(!empty($_REQUEST['citi']) ? ", ".$_REQUEST['citi'] : NULL).
		(!empty($_REQUEST['country']) ? ", ".$_REQUEST['country'] : NULL).
		(!empty($_REQUEST['index']) ? ", ".$_REQUEST['index'] : NULL).
		"</div>";
	}
//..............................................................................
// выполняет подстановки кода для замены данных при отправке письма
//..............................................................................
function mailtemplate_script(&$options)
	{
	$name_of_user = isset($options['user_id'])
		? itUser::get_name($options['user_id'])
		: ( (isset($options['name']) AND !empty($options['name']))
			? "<b>{$options['name']}</b>"
			: "<b>".get_const('TRADER')."</b>" );
		
	$str_of_user = isset($options['user_id'])
		? "<a target='blank' style='color:blue;' href='http://{$_SERVER['HTTP_HOST']}/".CMS_LANG."/user/{$options['user_id']}/'>{$name_of_user}</a>"
		: $name_of_user;
		
	$options['prepared'] = mstr_replace([
//		'[USER]'	=> $str_of_user,
//		'[ADMIN]'	=> "<a target='blank' style='color:blue;' href='http://{$_SERVER['HTTP_HOST']}/".CMS_LANG."/user/".DEFAULT_ADMIN_ID."/'>".itUser::get_name(DEFAULT_ADMIN_ID)."</a>",
//		'[CHAT]'	=> "<a target='blank' style='color:blue;' href='http://{$_SERVER['HTTP_HOST']}/".CMS_LANG."/chat/'>".get_const('NODE_CHAT')."</a>",
//		'[CHATPRO]'	=> "<a target='blank' style='color:blue;' href='http://{$_SERVER['HTTP_HOST']}/".CMS_LANG."/chat/pro/'>".get_const('NODE_PRO_CHAT')."</a>",
//		'[REG]'		=> "<a target='blank' style='color:blue;' href='http://{$_SERVER['HTTP_HOST']}/".CMS_LANG."/register/'>".get_const('BUTTON_REGISTER')."</a>",
		'[B]'		=> "<b>",
		'[/B]'		=> "</b>",
		'[RED]'		=> "<font color='red'>",
		'[BLUE]'	=> "<font color='blue'>",
		'[GREEN]'	=> "<font color='green'>",
		'[/RED]'	=> "</font>",
		'[/BLUE]'	=> "</font>",
		'[/GREEN]'	=> "</font>",
		'[/COLOR]'	=> "</font>",
		'[BIG]'		=> "<h1>",
		'[/BIG]'	=> "</h1>",		
		'[SMALL]'	=> "<small>",
		'[/SMALL]'	=> "</small>",		
		'[GAL]'		=> "<style>.gal .galcell {flex: 1 1 auto;}</style><div class='gal' style='display: flex;'>",
		'[/GAL]'	=> "</div>",		
		'> '		=> '>',
		
		], $options['prepared']);
		
	return $options['prepared'];
	}
//..............................................................................
// отправляет результат формы на почту
//..............................................................................
function send_colibri_mails($form_id=FORM2_CONTACTS, $table_name=DEFAULT_FORM_TABLE)
	{
	global $_SETTINGS, $_MEASURMENT;
	$subject_user = NULL;
	$prepared_title = NULL;
	
	switch ($form_id)
		{
		case FORM2_CONTACTS : {
			$subject_user 	= USER_CONTACT_ACCEPT_TITLE;
			$subject_admin 	= ADMIN_CONTACT_ACCEPT_TITLE;
			$address	= false;
			$agryment 	= false;
			
			$prepared_title	=
					"<div style='font-size:1.2em; margin-top:16px; font-weight:bold;color:brown;'>СООБЩЕНИЕ от {$_REQUEST['name']}</div>".
					"<div class='info'><span class='label'>email</span>&nbsp;:&nbsp;{$_REQUEST['email']}</div>".
					"";
			break;		
			}
			
		case FORM2_ORDER : {
//			$subject_user 	= USER_ORDER_ACCEPT_TITLE;
			$subject_admin 	= ADMIN_ORDER_ACCEPT_TITLE;
			$address	= true;
			$agryment 	= true;

			$prepared_title	=
					"<div style='font-size:1.2em; margin-top:16px; font-weight:bold;color:blue;'>ЗАКАЗ от {$_REQUEST['name']}</div>".
					"<div class='info'><span class='label'>email</span>&nbsp;:&nbsp;{$_REQUEST['email']}</div>".
					"";
			break;
			}			

		case FORM2_BUY : {
			$subject_user 	= USER_BUY_ACCEPT_TITLE;
			$subject_admin 	= ADMIN_BUY_ACCEPT_TITLE;
			$address	= true;
			$agryment 	= false;

			$prepared_title	=
					"<div style='font-size:1.2em; margin-top:16px; font-weight:bold;color:red;'>ИЗ МАГАЗИНА от {$_REQUEST['name']}</div>".
					"<div class='info'><span class='label'>email</span>&nbsp;:&nbsp;{$_REQUEST['email']}</div>".
					"";
			break;
			}			

		case FORM2_MEASUREMENT :
		case FORM2_MEASUREMENT2 :
		case FORM2_MEASUREMENT3 :
		case FORM2_MEASUREMENT4 :
		case FORM2_MEASUREMENT5 :				
			{
			$meas = $form_id - FORM2_MEASUREMENT + 1;
//			echo print_rr($_REQUEST); die;
//			$subject_user 	= USER_MEASUREMENT_ACCEPT_TITLE;
			$subject_admin 	= str_replace('[VALUE]',
				$meas,
				ADMIN_MEASUREMENT_ACCEPT_TITLE).(isset($_REQUEST['order']) ? " № {$_REQUEST['order']} от {$_REQUEST['email']}" : NULL);
			$address	= false;
			$agryment 	= false;
			
			$title_color 	= isset($_MEASURMENT[$form_id]['mailcolor'])
				? $_MEASURMENT[$form_id]['mailcolor']
				: 'blue';
			
			$prepared_title	=
					"<div style='font-size:1.2em; margin-top:16px; font-weight:bold;color:{$title_color};'>МЕРКИ тип {$meas} для {$_REQUEST['order']}</div>".
					"<div class='info'><span class='label'>email</span>&nbsp;:&nbsp;{$_REQUEST['email']}</div>";
			break;
			}
		}
	$inline_style = "font-size:14px; font-family:Helvetica,Arial;";

	$request = itForm2::_result_info([
			'table_name' 	=> $table_name,
			'rec_id'	=> $form_id,
			'empty'		=> false,
			]);

//------------------
	// подготовим письмо пользователю
	$mail_of_user = [
		'prepared'	=>
			"<div style='{$inline_style}'>".	
			implode('', $request).
			($agryment ? "<div style='color:green;'><b>".str_replace("\n", "<br/><br/>","\n".USER_AGREEMENT)."</b></div>" : NULL).
			"</div>",
		'subject'	=> $subject_user,
		];
	
//	echo $mail_of_user['prepared']; die;
	
	$now = mysql_now();
	

	itMailTemplate::_code($mail_of_user);
				
//------------------
	if (SEND_USER_MAILS)
		{
	$mails[] =[
//		'from'		=> CMS_NAME."<stylecolibri@gmail.com>",
		'from'		=> $_SETTINGS['SITE_ADMIN_EMAIL']['value'],
		//"<stylecolibri@gmail.com>",
//		'reply'		=> "ateliecolibri@gmail.com",
		'to'		=> $_REQUEST['email'],
		'subject'	=> $subject_of_user,
//		'subject'	=> CMS_NAME.' | '.$subject_user,//." #{$i}",
		'message'	=> $mail_of_user['result'],
		];
		}
//------------------	

//------------------
	// подготовим письмо администратору
	$admin_mail = [
		'prepared'	=>
			"<div style='{$inline_style}'>".	
			"<div>{$prepared_title}</div><br/>".
			implode('', $request).
			($address ? get_full_address_request() : NULL).
			"</div>",
		'subject'	=> $subject_admin,
		];
	
	itMailTemplate::_code($admin_mail);	

	if (isset($_REQUEST['articul']) AND ($item_rec = get_item_from_articul($_REQUEST['articul'])))
		{
		$img_str = (isset($item_rec['images']) AND is_array($item_rec['images'])) ? "<img style='display:table' src='".get_thumbnail($item_rec['images'][0],'ADV_AVATAR')."'/>" : NULL;
		$link = "<div style='text-aling:center'><a href='".SERVER_HTTP_DEBUG."/".CMS_LANG."/items/{$item_rec['id']}/' target='_blank'>{$img_str}<span>{$_REQUEST['articul']}</span></a></div>";
		$admin_mail['result'] = str_replace($_REQUEST['articul'], $link, $admin_mail['result']);
		}

	$mails[] =[
		'from'		=> trim($_SETTINGS['SITE_SMTP_USER']['value']),	
		'to'		=> trim($_SETTINGS['SITE_ADMIN_EMAIL']['value']),
		'reply'		=> trim($_REQUEST['email']),
		'subject'	=> CMS_NAME." (".CMS_LANG.") : ".strftime("[ %d %b %Y ] (%a)",strtotime($now))." {$subject_admin}",
		'message'	=> $admin_mail['result'],
		'user'		=> trim($_SETTINGS['SITE_SMTP_USER']['value']),
		'password'	=> trim($_SETTINGS['SITE_SMTP_PASSWORD']['value']),
		];

//	echo print_rr($mails); die;
	itMailings::_send_arr($mails, true);
	
	$o_mailer = new itMailer();
	unset($o_mailer);
//------------------
	
	return
		(!is_null($subject_of_user)
			? 	TAB."<div class='tit'>".
				CMS_NAME." (".CMS_LANG.") : ".strftime("[ %d %b %Y ] (%a)",strtotime($now)).
				$subject_of_user.
				"</div>" 
			: NULL).
		TAB."<div class='mailsend boxed'>{$mail_of_user['result']}</div>".
		"";
	}

//..............................................................................
// строка истории сообщений на выбранный email
//..............................................................................
function mailing_history_panel($email=NULL)
	{
	$o_feed = new itFeed([
		'table'			=> DEFAULT_MAIL_TABLE,
		'condition'		=> (!is_null($email) ? "`to`='$email'" : '1')." AND `status` NOT IN('DELETED','SPAM')".NOT_PINCODE,
		'name'			=> 'mailing_history',
		'order'			=> "`id` DESC",
		'async'			=> true,
		'appear'		=> false,
		]);
	$o_feed->compile();
	$mailpanel = $o_feed->count_all() ? $o_feed->code() : TAB."<div class='field p1 center gray'>".get_const('NO_DATA')."</div>";
	unset ($o_feed);

	$o_feed = new itFeed([
		'table'			=> DEFAULT_MAIL_TABLE,
		'condition'		=> (!is_null($email) ? "`to`='$email'" : '1')." AND `status` NOT IN('DELETED','SPAM')".HAS_PINCODE,
		'name'			=> 'mailing_history',
		'order'			=> "`id` DESC",
		'async'			=> true,
		'appear'		=> false,
		]);
	$o_feed->compile();
	$pinpanel = $o_feed->count_all() ? $o_feed->code() : TAB."<div class='field p1 center gray'>".get_const('NO_DATA')."</div>";
	unset ($o_feed);

	$o_spam = new itFeed([
		'table'			=> DEFAULT_MAIL_TABLE,
		'condition'		=> (!is_null($email) ? "`to`='$email'" : '1')." AND `status` = 'SPAM'",
		'name'			=> 'mailing_history',
		'order'			=> "`id` DESC",
		'async'			=> true,
		'appear'		=> false,
		]);
	$o_spam->compile();
	$spampanel = $o_spam->count_all() ? $o_spam->code() : TAB."<div class='field p1 center gray'>".get_const('NO_DATA')."</div>";
	unset ($o_spam);

	$o_deleted = new itFeed([
		'table'			=> DEFAULT_MAIL_TABLE,
		'condition'		=> (!is_null($email) ? "`to`='$email'" : '1')." AND `status` = 'DELETED'",
		'name'			=> 'mailing_history',
		'order'			=> "`id` DESC",
		'async'			=> true,
		'appear'		=> false,
		]);
	$o_deleted->compile();
	$deletedpanel = $o_deleted->count_all() ? $o_deleted->code() : TAB."<div class='field p1 center gray'>".get_const('NO_DATA')."</div>";
	unset ($o_deleted);
	
	
	$o_tabs = new itTabs([
		'tab_id'	=> 1,		
		]);
		
	$o_tabs->add([
		'title'	=> 'Входящие',
		'name'	=> 'inbox',
		'code'	=> $mailpanel,
		]);

	$o_tabs->add([
		'title'	=> 'Pin',
		'name'	=> 'pin',
		'code'	=> $pinpanel,
		]);

	$o_tabs->add([
		'title'	=> 'Спам',
		'name'	=> 'spam',
		'code'	=> $spampanel,
		]);

	$o_tabs->add([
		'title'	=> 'Удаленные',
		'name'	=> 'deleted',
		'code'	=> $deletedpanel,
		]);

		
	$o_tabs->compile();
 	$result = $o_tabs->code();
	unset($o_tabs);
	
	return 
		TAB."<div class='siterow boxed'>".
		TAB."<div class='block boxed'>".
		TAB."<div class='tit'>Заполненные формы и отправленные письма</div>".
		TAB."<div class='list admin'>".
		$result.
		TAB."</div>".
		TAB."</div>".
		TAB."</div>";	
	}
	
//..............................................................................
// одна строка панели истории сообщений 
//..............................................................................
function get_mailing_history_feed_row($row)
	{
	global $mailers, $_MEASURMENT;
	if (empty($row['status'])) $row['status'] = 'ERROR';
	
	if (empty($client_emails = $row['reply']))
		{
		preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $row['message'], $matches);
		$client_emails = (is_array($matches)) ? implode(', ',array_column($matches,'0')) : '----';
		}
	
	$subject = 
		mstr_replace([
		'Мерки'		=> "<b class='green'><br/>Мерки</b>",
		'тип 1'		=> "<big class='".$_MEASURMENT[FORM2_MEASUREMENT]['color']."'>тип 1</big>",
		'тип 2'		=> "<big class='".$_MEASURMENT[FORM2_MEASUREMENT2]['color']."'>тип 2</big>",
		'тип 3'		=> "<big class='".$_MEASURMENT[FORM2_MEASUREMENT3]['color']."'>тип 3</big>",
		'тип 4'		=> "<big class='".$_MEASURMENT[FORM2_MEASUREMENT4]['color']."'>тип 4</big>",		
		'тип 5'		=> "<big class='".$_MEASURMENT[FORM2_MEASUREMENT5]['color']."'>тип 5</big>",		
		'новый заказ'	=> "новый <b class='blue'>заказ</b>",
		'сообщение'	=> "<b class='blue'>сообщение</b>",
		'Заказ из магазина' => "<br/><b class='red'>Заказ</b> из магазина",
		],$row['subject']);
			
	$status = $row['code'];
	return	
		TAB."<div class='row paylist'>".

			TAB."<div class='field p1 left'>".
			TAB."#{$row['id']}".
			TAB."</div>".

			TAB."<div class='field p2 center'>".
			"<small>".get_local_date_str($row['datetime'])."<br/>".get_time_str($row['datetime'])."</small>".
			TAB."</div>".

			TAB."<div class='field p5 left'>".
			TAB."<small>от:</small>&nbsp;<span class='blue'>{$client_emails}</span>".
			TAB."</div>".


			TAB."<div class='field p7 left'>".
			TAB."<small>{$subject}</small>".
			TAB."</div>".

			TAB."<div class='field p5 left'>".
			TAB."<small>на:&nbsp;{$row['to']}</small>".
			TAB."</div>".

			TAB."<div class='field p1 img'>".
			get_mail_preview_event($row).
			TAB."</div>".

			TAB."<div class='field p2 left'>".
			TAB."<small title='{$status}' class='{$mailers[$row['status']]['color']}'>".get_const($mailers[$row['status']]['title'])."</small>".
			TAB."</div>".
		TAB."</div>";		
	}
	
//..............................................................................
// предосмотр сообщения
//..............................................................................	
function get_mail_preview_event($row=NULL)
	{
// 	$message = ($row = itMySQL::_get_rec_from_db('mails', $mail_id)) ? itMailings::_strip_logo($row['message']) : NULL;


	global $_USER;
	$o_modal = new itModal();
	$o_modal->set_size('large');
	$o_modal->set_animation('fadeAndPop');
	
	$o_modal->add_field(
/*
		TAB."<div class='mailpattern boxed'>".
		$message.
		TAB."</div>"
*/
		TAB."<iframe src='/mail/{$row['id']}' class='mailpattern boxed'></iframe>"
		);
 	
 	if ($row['status']!='SPAM')
 		{
		$o_spam = new itForm2();
		$o_spam->add_data([
			'mail_id'	=> $row['id'],
			'op'		=> 'spam',
			]);
		$o_spam->compile();
		$spam_frm = $o_spam->code();
	
		$o_spam_btn = new itButton(get_const('BUTTON_SPAM'), 'submit', ['form'=>$o_spam->form_id()], 'brown');
		$spam_btn = $o_spam_btn->code();
		} else	{
			$o_spam = new itForm2();
			$o_spam->add_data([
				'mail_id'	=> $row['id'],
				'op'		=> 'spam_x',
				]);
			$o_spam->compile();
			$spam_frm = $o_spam->code();
		
			$o_spam_btn = new itButton(get_const('BUTTON_SPAM_X'), 'submit', ['form'=>$o_spam->form_id()], 'green');
			$spam_btn = $o_spam_btn->code();
			}

 	if ($row['status']!='DELETED')
 		{
		$o_remove = new itForm2();
		$o_remove->add_data([
			'mail_id'	=> $row['id'],
			'op'		=> 'mail_x',
			]);
		$o_remove->compile();
		$remove_frm = $o_remove->code();
	
		$o_remove_btn = new itButton(get_const('BUTTON_REMOVE'), 'submit', ['form'=>$o_remove->form_id()], 'red');
		$remove_btn = $o_remove_btn->code();
		} else 	{
			$o_remove = new itForm2();
			$o_remove->add_data([
				'mail_id'	=> $row['id'],
				'op'		=> 'mail_not_x',
				]);
			$o_remove->compile();
			$remove_frm = $o_remove->code();
		
			$o_remove_btn = new itButton(get_const('BUTTON_SPAM_X'), 'submit', ['form'=>$o_remove->form_id()], 'green');
			$remove_btn = $o_remove_btn->code();
			}


	$ok_button = new itButton(get_const('BUTTON_OK'), 'close', ['form'=>$o_modal->form_id()], 'blue ok');
	$ok_btn = $ok_button->code();
	
	$o_modal->add_field(
		$spam_frm.
		$remove_frm.
		TAB."<div class='buttons_div'>".
		$ok_btn.
		$spam_btn.
		$remove_btn.
		TAB."</div>");

 	$o_modal->compile();		
	$o_button = new itButton("👁", 'textmodal', ['form' => $o_modal->form_id()], 'green' );
	$result = $o_button->code().$o_modal->code();
	return $result;
	}

?>
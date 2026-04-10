<?php
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
		'subject'	=> CMS_NAME." (".CMS_LANG.") : ".skel80_strftime_compat("[ %d %b %Y ] (%a)",strtotime($now), CMS_LANG)." {$subject_admin}",
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
				CMS_NAME." (".CMS_LANG.") : ".skel80_strftime_compat("[ %d %b %Y ] (%a)",strtotime($now), CMS_LANG).
				$subject_of_user.
				"</div>" 
			: NULL).
		TAB."<div class='mailsend boxed'>{$mail_of_user['result']}</div>".
		"";
	}

//..............................................................................
// строка истории сообщений на выбранный email
//..............................................................................
function get_mailing_history_feed_options($email=NULL)
	{
	$base = !is_null($email) ? "`to`='$email'" : '1';
	return [
		'inbox' => [
			'title' => 'Входящие',
			'condition' => $base." AND `status` NOT IN('DELETED','SPAM')".NOT_PINCODE,
		],
		'pin' => [
			'title' => 'Pin',
			'condition' => $base." AND `status` NOT IN('DELETED','SPAM')".HAS_PINCODE,
		],
		'spam' => [
			'title' => 'Спам',
			'condition' => $base." AND `status` = 'SPAM'",
		],
		'deleted' => [
			'title' => 'Удаленные',
			'condition' => $base." AND `status` = 'DELETED'",
		],
	];
	}

function render_mailing_history_feed_panel($name, $title, $condition)
	{
	$o_feed = new itFeed([
		'table'			=> DEFAULT_MAIL_TABLE,
		'condition'		=> $condition,
		'name'			=> 'mailing_history',
		'order'			=> "`id` DESC",
		'async'			=> true,
		'appear'		=> false,
		]);
	$o_feed->compile();
	$result = $o_feed->count_all() ? $o_feed->code() : TAB."<div class='field p1 center gray'>".get_const('NO_DATA')."</div>";
	unset($o_feed);
	return [
		'name' => $name,
		'title' => $title,
		'code' => $result,
	];
	}

//..............................................................................
// panel for sent forms and mails history
//..............................................................................
function mailing_history_panel($email=NULL)
	{
	$panels = [];
	foreach (get_mailing_history_feed_options($email) as $name => $options)
		{
		$panels[] = render_mailing_history_feed_panel($name, $options['title'], $options['condition']);
		}

	$o_tabs = new itTabs([
		'tab_id'	=> 1,
		]);

	foreach ($panels as $panel)
		{
		$o_tabs->add([
			'title'	=> $panel['title'],
			'name'	=> $panel['name'],
			'code'	=> $panel['code'],
			]);
		}

	$o_tabs->compile();
 	$result = $o_tabs->code();
	unset($o_tabs);

	return
		TAB."<div class='siterow boxed'>".
		TAB."<div class='block boxed'>".
		TAB."<div class='tit'>Заполненные формы и отправленные письма</div>".
		TAB."<div class='list admin'>".
		$result.
		get_mailing_history_shared_modal().
		TAB."</div>".
		TAB."</div>".
		TAB."</div>";
	}

//..............................................................................
// one row of mails history panel
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
			TAB."<small title='{$status}' class='".$mailers[$row['status']]['color']."'>".get_const($mailers[$row['status']]['title'])."</small>".
			TAB."</div>".
		TAB."</div>";
	}

//..............................................................................
// lightweight launcher for shared preview modal
//..............................................................................
function get_mail_preview_event($row=NULL)
	{
	$mail_id = intval($row['id']);
	$status = htmlspecialchars($row['status'], ENT_QUOTES);
	return TAB."<a href='#/' data-reveal-id='mail-history-modal' class='green' onclick='return skel80OpenMailHistoryModal(this);' data-mail-id='{$mail_id}' data-mail-status='{$status}'>👁</a>";
	}

//..............................................................................
// one shared modal for all mail history rows
//..............................................................................
function get_mailing_history_shared_modal()
	{
	$ok_label = get_const('BUTTON_OK');
	$spam_label = get_const('BUTTON_SPAM');
	$spam_x_label = get_const('BUTTON_SPAM_X');
	$remove_label = get_const('BUTTON_REMOVE');

	return
		TAB."<div class='reveal-modal large' id='mail-history-modal' data-animation='fade'>".
		TAB."<iframe id='mail-history-frame' src='about:blank' class='mailpattern boxed'></iframe>".
		TAB."<form id='mail-history-spam-form' action='/ed_field.php' method='POST' accept-charset='utf-8'>".
		TAB."<input type='hidden' name='mail_id' id='mail-history-spam-mail-id' value=''>".
		TAB."<input type='hidden' name='op' id='mail-history-spam-op' value='spam'>".
		TAB."</form>".
		TAB."<form id='mail-history-remove-form' action='/ed_field.php' method='POST' accept-charset='utf-8'>".
		TAB."<input type='hidden' name='mail_id' id='mail-history-remove-mail-id' value=''>".
		TAB."<input type='hidden' name='op' id='mail-history-remove-op' value='mail_x'>".
		TAB."</form>".
		TAB."<div class='buttons_div'>".
		TAB."<span class='itButton bg_blue ok close-reveal-modal' id='mail-history-ok'>{$ok_label}</span>".
		TAB."<span class='itButton submit bg_brown' id='mail-history-spam-button' onclick='document.getElementById(&quot;mail-history-spam-form&quot;).submit();'>{$spam_label}</span>".
		TAB."<span class='itButton submit bg_red' id='mail-history-remove-button' onclick='document.getElementById(&quot;mail-history-remove-form&quot;).submit();'>{$remove_label}</span>".
		TAB."</div>".
		TAB."<div class='close-reveal-modal corner'></div>".
		TAB."</div>".
		TAB."<script>
".
		"function skel80OpenMailHistoryModal(node){
".
		"  var mailId = node.getAttribute('data-mail-id');
".
		"  var status = node.getAttribute('data-mail-status');
".
		"  var spamOp = (status === 'SPAM') ? 'spam_x' : 'spam';
".
		"  var removeOp = (status === 'DELETED') ? 'mail_not_x' : 'mail_x';
".
		"  var spamLabel = (status === 'SPAM') ? ".json_encode($spam_x_label)." : ".json_encode($spam_label).";
".
		"  var removeLabel = (status === 'DELETED') ? ".json_encode($spam_x_label)." : ".json_encode($remove_label).";
".
		"  var removeClass = (status === 'DELETED') ? 'itButton submit bg_green' : 'itButton submit bg_red';
".
		"  document.getElementById('mail-history-frame').src = '/mail/' + mailId;
".
		"  document.getElementById('mail-history-spam-mail-id').value = mailId;
".
		"  document.getElementById('mail-history-spam-op').value = spamOp;
".
		"  document.getElementById('mail-history-remove-mail-id').value = mailId;
".
		"  document.getElementById('mail-history-remove-op').value = removeOp;
".
		"  document.getElementById('mail-history-spam-button').innerHTML = spamLabel;
".
		"  document.getElementById('mail-history-remove-button').innerHTML = removeLabel;
".
		"  document.getElementById('mail-history-remove-button').className = removeClass;
".
		"  return true;
".
		"}
".
		"</script>";
	}

?>
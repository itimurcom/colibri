<?php
define('SEND_USER_MAILS', 0);
define('NOT_PINCODE'," AND `subject` NOT REGEXP ('PIN')");
define('HAS_PINCODE'," AND `subject` REGEXP ('PIN')");

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

function mailtemplate_script(&$options)
	{
	$options['prepared'] = mstr_replace([
		'[B]' => "<b>", '[/B]' => "</b>", '[RED]' => "<font color='red'>", '[BLUE]' => "<font color='blue'>", '[GREEN]' => "<font color='green'>",
		'[/RED]' => "</font>", '[/BLUE]' => "</font>", '[/GREEN]' => "</font>", '[/COLOR]' => "</font>", '[BIG]' => "<h1>", '[/BIG]' => "</h1>",
		'[SMALL]' => "<small>", '[/SMALL]' => "</small>", '[GAL]' => "<style>.gal .galcell {flex: 1 1 auto;}</style><div class='gal' style='display: flex;'>", '[/GAL]' => "</div>", '> ' => '>',
	], $options['prepared']);
	return $options['prepared'];
	}

function get_colibri_mail_heading($color, $title)
	{
	return "<div style='font-size:1.2em; margin-top:16px; font-weight:bold;color:{$color};'>{$title}</div>".
		"<div class='info'><span class='label'>email</span>&nbsp;:&nbsp;{$_REQUEST['email']}</div>";
	}

function get_colibri_mail_profile($form_id)
	{
	global $_MEASURMENT;
	switch ($form_id)
		{
		case FORM2_CONTACTS : return ['subject_user' => USER_CONTACT_ACCEPT_TITLE, 'subject_admin' => ADMIN_CONTACT_ACCEPT_TITLE, 'address' => false, 'agreement' => false, 'prepared_title' => get_colibri_mail_heading('brown', "СООБЩЕНИЕ от {$_REQUEST['name']}")];
		case FORM2_ORDER : return ['subject_user' => NULL, 'subject_admin' => ADMIN_ORDER_ACCEPT_TITLE, 'address' => true, 'agreement' => true, 'prepared_title' => get_colibri_mail_heading('blue', "ЗАКАЗ от {$_REQUEST['name']}")];
		case FORM2_BUY : return ['subject_user' => USER_BUY_ACCEPT_TITLE, 'subject_admin' => ADMIN_BUY_ACCEPT_TITLE, 'address' => true, 'agreement' => false, 'prepared_title' => get_colibri_mail_heading('red', "ИЗ МАГАЗИНА от {$_REQUEST['name']}")];
		}
	$meas = $form_id - FORM2_MEASUREMENT + 1;
	$title_color = isset($_MEASURMENT[$form_id]['mailcolor']) ? $_MEASURMENT[$form_id]['mailcolor'] : 'blue';
	return ['subject_user' => NULL, 'subject_admin' => str_replace('[VALUE]', $meas, ADMIN_MEASUREMENT_ACCEPT_TITLE).(isset($_REQUEST['order']) ? " № {$_REQUEST['order']} от {$_REQUEST['email']}" : NULL), 'address' => false, 'agreement' => false, 'prepared_title' => get_colibri_mail_heading($title_color, "МЕРКИ тип {$meas} для {$_REQUEST['order']}")];
	}

function get_colibri_mail_request_rows($table_name, $form_id)
	{
	return itForm2::_result_info(['table_name' => $table_name, 'rec_id' => $form_id, 'empty' => false]);
	}

function get_colibri_mail_body($inline_style, $request_rows, $options=[])
	{
	return "<div style='{$inline_style}'>".
		(!empty($options['prepared_title']) ? "<div>{$options['prepared_title']}</div><br/>" : NULL).
		implode('', $request_rows).
		(!empty($options['address']) ? get_full_address_request() : NULL).
		(!empty($options['agreement']) ? "<div style='color:green;'><b>".str_replace("\n", "<br/><br/>", "\n".USER_AGREEMENT)."</b></div>" : NULL).
		"</div>";
	}

function get_colibri_mail_template_result($prepared, $subject)
	{
	$mail = ['prepared' => $prepared, 'subject' => $subject]; itMailTemplate::_code($mail); return $mail['result'];
	}

function patch_colibri_admin_mail_articul(&$message)
	{
	if (!isset($_REQUEST['articul']) || !($item_rec = get_item_from_articul($_REQUEST['articul']))) return;
	$img_str = (isset($item_rec['images']) AND is_array($item_rec['images'])) ? "<img style='display:table' src='".get_thumbnail($item_rec['images'][0], 'ADV_AVATAR')."'/>" : NULL;
	$link = "<div style='text-align:center'><a href='".CMS_CURRENT_BASE_URL."/".CMS_LANG."/items/{$item_rec['id']}/' target='_blank'>{$img_str}<span>{$_REQUEST['articul']}</span></a></div>";
	$message = str_replace($_REQUEST['articul'], $link, $message);
	}

function get_colibri_admin_mail_subject($now, $subject_admin)
	{
	return CMS_NAME." (".CMS_LANG.") : ".skel80_strftime_compat("[ %d %b %Y ] (%a)", strtotime($now), CMS_LANG)." {$subject_admin}";
	}


function send_colibri_mails($form_id=FORM2_CONTACTS, $table_name=DEFAULT_FORM_TABLE)
	{
	global $_SETTINGS;
	$profile = get_colibri_mail_profile($form_id);
	$inline_style = "font-size:14px; font-family:Helvetica,Arial;";
	$request_rows = get_colibri_mail_request_rows($table_name, $form_id);
	$now = mysql_now();

	$mail_of_user = get_colibri_mail_template_result(get_colibri_mail_body($inline_style, $request_rows, ['agreement' => $profile['agreement']]), $profile['subject_user']);
	$admin_mail = get_colibri_mail_template_result(get_colibri_mail_body($inline_style, $request_rows, ['prepared_title' => $profile['prepared_title'], 'address' => $profile['address']]), $profile['subject_admin']);
	patch_colibri_admin_mail_articul($admin_mail);

	$mails = [];
	if (SEND_USER_MAILS)
		{
		$mails[] = ['from' => $_SETTINGS['SITE_ADMIN_EMAIL']['value'], 'to' => $_REQUEST['email'], 'subject' => $profile['subject_user'], 'message' => $mail_of_user];
		}

	$mails[] = [
		'from' => trim($_SETTINGS['SITE_SMTP_USER']['value']),
		'to' => trim($_SETTINGS['SITE_ADMIN_EMAIL']['value']),
		'reply' => trim($_REQUEST['email']),
		'subject' => get_colibri_admin_mail_subject($now, $profile['subject_admin']),
		'message' => $admin_mail,
		'user' => trim($_SETTINGS['SITE_SMTP_USER']['value']),
		'password' => trim($_SETTINGS['SITE_SMTP_PASSWORD']['value']),
	];
	itMailings::_send_arr($mails, true);
	$o_mailer = new itMailer();
	unset($o_mailer);
	return (!is_null($profile['subject_user']) ? TAB."<div class='tit'>".CMS_NAME." (".CMS_LANG.") : ".skel80_strftime_compat("[ %d %b %Y ] (%a)", strtotime($now), CMS_LANG).$profile['subject_user']."</div>" : NULL).TAB."<div class='mailsend boxed'>{$mail_of_user}</div>";
	}

function get_mailing_history_feed_options($email=NULL)
	{
	$base = !is_null($email) ? "`to`='$email'" : '1';
	return [
		'inbox' => ['title' => 'Входящие', 'condition' => $base." AND `status` NOT IN('DELETED','SPAM')".NOT_PINCODE],
		'pin' => ['title' => 'Pin', 'condition' => $base." AND `status` NOT IN('DELETED','SPAM')".HAS_PINCODE],
		'spam' => ['title' => 'Спам', 'condition' => $base." AND `status` = 'SPAM'"],
		'deleted' => ['title' => 'Удаленные', 'condition' => $base." AND `status` = 'DELETED'"],
	];
	}

function render_mailing_history_feed_panel($name, $title, $condition)
	{
	$o_feed = new itFeed(['table' => DEFAULT_MAIL_TABLE, 'condition' => $condition, 'name' => 'mailing_history', 'order' => "`id` DESC", 'async' => true, 'appear' => false]);
	$o_feed->compile();
	$code = $o_feed->count_all() ? $o_feed->code() : TAB."<div class='field p1 center gray'>".get_const('NO_DATA')."</div>";
	unset($o_feed);
	return ['name' => $name, 'title' => $title, 'code' => $code];
	}

function mailing_history_panel($email=NULL)
	{
	$o_tabs = new itTabs(['tab_id' => 1]);
	foreach (get_mailing_history_feed_options($email) as $name => $options)
		{
		$panel = render_mailing_history_feed_panel($name, $options['title'], $options['condition']);
		$o_tabs->add(['title' => $panel['title'], 'name' => $panel['name'], 'code' => $panel['code']]);
		}
	$o_tabs->compile();
	$result = $o_tabs->code();
	unset($o_tabs);
	return TAB."<div class='siterow boxed'>".TAB."<div class='block boxed'>".TAB."<div class='tit'>Заполненные формы и отправленные письма</div>".TAB."<div class='list admin'>".$result.get_mailing_history_shared_modal().TAB."</div>".TAB."</div>".TAB."</div>";
	}

function get_mailing_history_client_emails($row)
	{
	if (!empty($row['reply'])) return $row['reply'];
	preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $row['message'], $matches);
	return is_array($matches) ? implode(', ', array_column($matches, '0')) : '----';
	}

function get_mailing_history_subject($subject)
	{
	global $_MEASURMENT;
	return mstr_replace([
		'Мерки' => "<b class='green'><br/>Мерки</b>",
		'тип 1' => "<big class='".$_MEASURMENT[FORM2_MEASUREMENT]['color']."'>тип 1</big>",
		'тип 2' => "<big class='".$_MEASURMENT[FORM2_MEASUREMENT2]['color']."'>тип 2</big>",
		'тип 3' => "<big class='".$_MEASURMENT[FORM2_MEASUREMENT3]['color']."'>тип 3</big>",
		'тип 4' => "<big class='".$_MEASURMENT[FORM2_MEASUREMENT4]['color']."'>тип 4</big>",
		'тип 5' => "<big class='".$_MEASURMENT[FORM2_MEASUREMENT5]['color']."'>тип 5</big>",
		'новый заказ' => "новый <b class='blue'>заказ</b>",
		'сообщение' => "<b class='blue'>сообщение</b>",
		'Заказ из магазина' => "<br/><b class='red'>Заказ</b> из магазина",
	], $subject);
	}

function get_mailing_history_feed_row($row)
	{
	global $mailers;
	if (empty($row['status'])) $row['status'] = 'ERROR';
	$client_emails = get_mailing_history_client_emails($row);
	$subject = get_mailing_history_subject($row['subject']);
	$status = $row['code'];
	return TAB."<div class='row paylist'>".
		TAB."<div class='field p1 left'>".TAB."#{$row['id']}".TAB."</div>".
		TAB."<div class='field p2 center'>"."<small>".get_local_date_str($row['datetime'])."<br/>".get_time_str($row['datetime'])."</small>".TAB."</div>".
		TAB."<div class='field p5 left'>".TAB."<small>от:</small>&nbsp;<span class='blue'>{$client_emails}</span>".TAB."</div>".
		TAB."<div class='field p7 left'>".TAB."<small>{$subject}</small>".TAB."</div>".
		TAB."<div class='field p5 left'>".TAB."<small>на:&nbsp;{$row['to']}</small>".TAB."</div>".
		TAB."<div class='field p1 img'>".get_mail_preview_event($row).TAB."</div>".
		TAB."<div class='field p2 left'>".TAB."<small title='{$status}' class='".$mailers[$row['status']]['color']."'>".get_const($mailers[$row['status']]['title'])."</small>".TAB."</div>".
	TAB."</div>";
	}

function get_mail_preview_event($row=NULL)
	{
	$mail_id = intval($row['id']);
	$status = htmlspecialchars($row['status'], ENT_QUOTES);
	return TAB."<a href='#/' data-reveal-id='mail-history-modal' class='green' onclick='return skel80OpenMailHistoryModal(this);' data-mail-id='{$mail_id}' data-mail-status='{$status}'>👁</a>";
	}

function get_mailing_history_action_form($form_id, $mail_id_id, $op_id, $op)
	{
	return TAB."<form id='{$form_id}' action='/ed_field.php' method='POST' accept-charset='utf-8'>".TAB."<input type='hidden' name='mail_id' id='{$mail_id_id}' value=''>".TAB."<input type='hidden' name='op' id='{$op_id}' value='{$op}'>".TAB."</form>";
	}

function get_mailing_history_shared_modal()
	{
	$ok_label = get_const('BUTTON_OK');
	$spam_label = get_const('BUTTON_SPAM');
	$spam_x_label = get_const('BUTTON_SPAM_X');
	$remove_label = get_const('BUTTON_REMOVE');
	return TAB."<div class='reveal-modal large' id='mail-history-modal' data-animation='fade'>".
		TAB."<iframe id='mail-history-frame' src='about:blank' class='mailpattern boxed'></iframe>".
		get_mailing_history_action_form('mail-history-spam-form', 'mail-history-spam-mail-id', 'mail-history-spam-op', 'spam').
		get_mailing_history_action_form('mail-history-remove-form', 'mail-history-remove-mail-id', 'mail-history-remove-op', 'mail_x').
		TAB."<div class='buttons_div'>".
		TAB."<span class='itButton bg_blue ok close-reveal-modal' id='mail-history-ok'>{$ok_label}</span>".
		TAB."<span class='itButton submit bg_brown' id='mail-history-spam-button' onclick='document.getElementById(&quot;mail-history-spam-form&quot;).submit();'>{$spam_label}</span>".
		TAB."<span class='itButton submit bg_red' id='mail-history-remove-button' onclick='document.getElementById(&quot;mail-history-remove-form&quot;).submit();'>{$remove_label}</span>".
		TAB."</div>".
		TAB."<div class='close-reveal-modal corner'></div>".
		TAB."</div>".
		TAB."<script>\n".
		"function skel80OpenMailHistoryModal(node){\n".
		"  var mailId = node.getAttribute('data-mail-id');\n".
		"  var status = node.getAttribute('data-mail-status');\n".
		"  var spamOp = (status === 'SPAM') ? 'spam_x' : 'spam';\n".
		"  var removeOp = (status === 'DELETED') ? 'mail_not_x' : 'mail_x';\n".
		"  var spamLabel = (status === 'SPAM') ? ".json_encode($spam_x_label)." : ".json_encode($spam_label).";\n".
		"  var removeLabel = (status === 'DELETED') ? ".json_encode($spam_x_label)." : ".json_encode($remove_label).";\n".
		"  var removeClass = (status === 'DELETED') ? 'itButton submit bg_green' : 'itButton submit bg_red';\n".
		"  document.getElementById('mail-history-frame').src = '/mail/' + mailId;\n".
		"  document.getElementById('mail-history-spam-mail-id').value = mailId;\n".
		"  document.getElementById('mail-history-spam-op').value = spamOp;\n".
		"  document.getElementById('mail-history-remove-mail-id').value = mailId;\n".
		"  document.getElementById('mail-history-remove-op').value = removeOp;\n".
		"  document.getElementById('mail-history-spam-button').innerHTML = spamLabel;\n".
		"  document.getElementById('mail-history-remove-button').innerHTML = removeLabel;\n".
		"  document.getElementById('mail-history-remove-button').className = removeClass;\n".
		"  return true;\n".
		"}\n".
		"</script>";
	}
?>

<?php
// ================ CRC ================
// version: 1.35.03
// hash: c659c36d27b373115d29f08b895b2284923f5e17b1b595aa431c27c5f6eeb8de
// date: 30 April 2021 16:04
// ================ CRC ================
//..............................................................................
// itMailings : класс для работы рассылками писем
//..............................................................................
class itMailings
	{
	static function option_value($row, $key, $default=NULL)
		{
		return (is_array($row) AND array_key_exists($key, $row)) ? $row[$key] : $default;
		}

	static function option_text($row, $key, $default='')
		{
		return trim((string)self::option_value($row, $key, $default));
		}

	static function email_list($row)
		{
		if (!is_array($row) OR !isset($row['emails_xml']) OR !is_array($row['emails_xml'])) return [];
		return $row['emails_xml'];
		}

	//..............................................................................
	// конструктор класса
	//..............................................................................
	public function __construct($options=NULL)
		{
		}
		
	//..............................................................................
	// создает список email для рассылки по переданным данным
	//..............................................................................
	static function _create_list($options=NULL, $table_name=DEFAULT_MAILINGLIST_TABLE)
		{
		$options = is_array($options) ? $options : [];
		$emails = self::option_value($options, 'emails', []);
		$values_arr = [
			'title_xml'	=> [
				CMS_LANG	=> self::option_text($options, 'title')
				],
			'emails_xml'	=> is_array($emails) ? $emails : [],
			'status'	=> 'PUBLISHED',
			];
		return itMySQL::_insert_rec($table_name, $values_arr);
		}

	//..............................................................................
	// добавляет email в список
	//..............................................................................
	static function _add_to_list($mailing_list_id=NULL, $options=NULL, $table_name=DEFAULT_MAILINGLIST_TABLE)
		{
		$options = is_array($options) ? $options : [];
		$email = self::option_text($options, 'email');
		if ($email === '') return;
		if ( ($row = itMySQL::_get_rec_from_db($table_name, $mailing_list_id)) )
			{
			$emails_xml = self::email_list($row);
			if (!isset($emails_xml[$email]) OR !is_array($emails_xml[$email]))
				$emails_xml[$email] = [];
			if (isset($options['user_id']))
				{
				$emails_xml[$email]['user_id'] = $options['user_id'];
				}
			if (isset($options['name']))
				{
				$emails_xml[$email]['name'] = $options['name'];
				}

			itMySQL::_update_value_db($table_name, $mailing_list_id, $emails_xml, 'emails_xml');
			}
		}


	//..............................................................................
	// импорт emails в список
	//..............................................................................
	static function _import_list($mailing_list_id=NULL,$import=NULL, $table_name=DEFAULT_MAILINGLIST_TABLE)
		{
		if ( ($row = itMySQL::_get_rec_from_db($table_name, $mailing_list_id)) AND !is_null($import))
			{
			$row['emails_xml'] = self::email_list($row);
			$import = (string)$import;
			$lines = explode("\n",$import);
			$data = explode(",", $import);
			
			if (count($lines)>1)
				{
				// обработка многострочная
				foreach($lines as $email_row)
					{
					$data = explode(",",$email_row);
					$email = isset($data[0]) ? trim($data[0]) : '';
					$name = isset($data[1]) ? trim($data[1]) : '';
					if (isEmail($email))
						{
						$row['emails_xml'][$email]['name'] = $name;
						}
					}
				} else

			if (isset($data[1]) AND isEmail(trim($data[1])))
				{
				// это список emails	
				foreach($data as $email)
					{
					$email = trim($email);
					if ($email !== '' AND isEmail($email)) $row['emails_xml'][$email]=NULL;
					}
				} else if (isset($data[0]) AND isEmail(trim($data[0])))
					{
					// передали один email и его имя
					$email = trim($data[0]);
					$row['emails_xml'][$email]['name'] = isset($data[1]) ? trim($data[1]) : '';
					} else	{
						// ошибка
//						add_error_message('NO_EMAILS_IMPORTED');
						}

			itMySQL::_update_value_db($table_name, $mailing_list_id, $row['emails_xml'], 'emails_xml');
			}
		}


	//..............................................................................
	// удаляет emails из списока
	//..............................................................................
	static function _x_from_list($mailing_list_id=NULL, $list=NULL, $table_name=DEFAULT_MAILINGLIST_TABLE)
		{
		if ( ($row = itMySQL::_get_rec_from_db($table_name, $mailing_list_id)) AND !is_null($list))
			{
			$row['emails_xml'] = self::email_list($row);
			// это список emails удаляем
			foreach(explode(",", (string)$list) as $email)
				{
				$email_name = trim($email);
				if ($email_name === '') continue;
				if (array_key_exists($email_name, $row['emails_xml']))
					{
					unset($row['emails_xml'][$email_name]);
					}
				}
			itMySQL::_update_value_db($table_name, $mailing_list_id, count($row['emails_xml']) ? $row['emails_xml'] : NULL , 'emails_xml');
			}
		}

	//..............................................................................
	// удаляет список рассылки из таблицы
	//..............................................................................
	static function _x_list($mailing_list_id=NULL, $table_name=DEFAULT_MAILINGLIST_TABLE)
		{
		itMySQL::_update_value_db($table_name, $mailing_list_id, 'DELETED', 'status');
		}

	//..............................................................................
	// возвращает название списка
	//..............................................................................
	static function _list_name($mailing_list_id=NULL, $table_name=DEFAULT_MAILINGLIST_TABLE)
		{
		if ($row=itMySQL::_get_rec_from_db($table_name, $mailing_list_id))
			{
			return self::option_text($row, 'name');
			}
		}
	//..............................................................................
	// отправляет сообщение по подготовленным данным
	//..............................................................................
	static function _send($options=NULL, $table_name = DEFAULT_MAIL_TABLE)
		{
		$options = is_array($options) ? $options : [];
		$to = self::option_text($options, 'to');
		if ($to === '') return NULL;

		$rec_id  = itMySQL::_last_id($table_name);
		$now = self::option_text($options, 'datetime', mysql_now());
		
		$data = itEditor::event_data([
			'time'		=> $now,
			'rec_id'	=> $rec_id,
			]);

		$message = (string)self::option_value($options, 'message', '');
		$values_arr = [
			'from' 		=> self::option_text($options, 'from', DEFAULT_ADMIN_NAME),
			'to' 		=> $to,
			'subject' 	=> self::option_text($options, 'subject'),
			'reply'		=> self::option_text($options, 'reply'),
			'message'	=> str_replace('[MAILID]', $data, $message),
			'status' 	=> 'WAIT',
			'files_xml'	=> self::option_value($options, 'files', NULL),
			'datetime'	=> $now,
			];
			
		$real_id = itMySQL::_insert_rec($table_name, $values_arr);
		if ($rec_id != $real_id)
			{
			itMySQL::_update_value_db($table_name, $real_id, str_replace('[MAILID]', $real_id, $message), 'message');
			}
			
		return $real_id;
		}
		
	//..............................................................................
	// отправляет сообщение по подготовленным данным
	//..............................................................................
	static function _send_arr($emails_arr=NULL, $forse=false, $table_name = DEFAULT_MAIL_TABLE)
		{
		if (is_array($emails_arr))
			{
			$now = mysql_now();
			$values_arr['keys'] = ['from','to','reply','subject','message','files_xml', 'datetime', 'status'];
			

			$rec_id	= itMySQL::_last_id($table_name);
	
			foreach ($emails_arr as $row)
				{
				$row = is_array($row) ? $row : [];
				$to = self::option_text($row, 'to');
				if ($to === '') continue;

				$now = self::option_text($row, 'datetime', mysql_now());
				
				$data = itEditor::event_data([
					'time'		=> $now,
					'rec_id'	=> $rec_id,
					]);

				$message = (string)self::option_value($row, 'message', '');
				$values_arr[] = [
					self::option_text($row, 'from', DEFAULT_ADMIN_NAME),
					$to,
					self::option_text($row, 'reply'),
					self::option_text($row, 'subject'),
					str_replace('[MAILID]', $data, $message),
					self::option_value($row, 'files', NULL),
					$now,
					'WAIT',
					];
				$rec_id++;
				}
			if (count($values_arr)>1)
				{
				itMySQL::_insert_rec($table_name, $values_arr);
				}
			return $now;
			}
		if ($forse)
			{
			$o_mailer = new itMailer();
			unset($o_mailer);
			}
		}

	//..............................................................................
	// производит отправку почтовой рассылки на основании выбранных данных
	//..............................................................................
	static function _run($mailing_id=NULL, $table_name=DEFAULT_MAILING_TABLE, $list_name=DEFAULT_MAILINGLIST_TABLE)
		{
		if ($row=itMySQL::_get_rec_from_db($table_name,$mailing_id))
			{
			$row = is_array($row) ? $row : [];
			$object = self::option_text($row, 'object');
			switch ($object)
				{
				case 'LIST' : {
					// подготовим рассылку по списку
					if ($maillist = itMySQL::_get_rec_from_db($list_name, self::option_value($row, 'object_id')) )
						{
						$mails = [];
						foreach(self::email_list($maillist) as $email_key=>$email_row)
							{
							$email_row = is_array($email_row) ? $email_row : [];
							if (!isEmail($email_key)) continue;

							$options = [
							'name'		=> self::option_text($email_row, 'name'),
							'pattern_id'	=> self::option_value($row, 'pattern_id'),
							];

							itMailTemplate::_prepare($options);							
							$mails[] = [
								'to'		=> $email_key,
								'subject'	=> CMS_NAME.' | '.itMailTemplate::_subj(self::option_value($row, 'pattern_id')),
								'message'	=> self::option_value($options, 'result', ''),
								];	
							}
						if (count($mails))
							{
							itMailings::_send_arr($mails);
							return true;
							}
						return false;
						}
					break;
					}
				case 'PRO' : {
					if (class_exists('trPro') AND is_array($array_of_users = trPro::_pro_users()))
						{
						$mails = [];
						foreach($array_of_users as $row_of_user)
							{
							$row_of_user = is_array($row_of_user) ? $row_of_user : [];
							$user_id = self::option_value($row_of_user, 'id');
							if (isEmail($email_name = itUser::get_email($user_id)))
								{
								$options = [
									'user_id'	=> $user_id,
									'pattern_id'	=> self::option_value($row, 'pattern_id'),
									];
								itMailTemplate::_prepare($options);
								$mails[] = [
									'to'		=> $email_name,
									'subject'	=> CMS_NAME.' | '.self::option_text($options, 'subject'),
									'message'	=> self::option_value($options, 'result', ''),	
									];
								}
							}
						if (count($mails))
							{
							itMailings::_send_arr($mails);
							return true;
							}
						return false;
						} else return false;
					break;	
					}

				case 'CHAT' : {
					if (class_exists('trCHatRoom') AND is_array($array_of_users = trCHatRoom::_has_access(self::option_value($row, 'object_id'))))
						{
						$mails = [];
						foreach($array_of_users as $row_of_user)
							{
							$row_of_user = is_array($row_of_user) ? $row_of_user : [];
							$user_id = self::option_value($row_of_user, 'user_id');
							if (isEmail($email_name = itUser::get_email($user_id)))
								{
								$options = [
									'user_id'	=> $user_id,
									'pattern_id'	=> self::option_value($row, 'pattern_id'),
									];
								itMailTemplate::_prepare($options);
								$mails[] = [
									'to'		=> $email_name,
									'subject'	=> CMS_NAME.' | '.self::option_text($options, 'subject'),
									'message'	=> self::option_value($options, 'result', ''),	
									];
								}
							}
						if (count($mails))
							{
							itMailings::_send_arr($mails);
							return true;
							}
						return false;
						} else return false;
					break;	
					}				}	
			}
		return false;
		}
		
	//..............................................................................
	// возвращает количество писем на отправку
	//..............................................................................		
	static function _count_wait($table_name = DEFAULT_MAIL_TABLE, $db_prefix=DB_PREFIX)
		{
		$request = itMySQL::_request("SELECT COUNT(`id`) AS count FROM {$db_prefix}{$table_name} WHERE `status`='WAIT'");
		return (is_array($request) AND isset($request[0]) AND is_array($request[0]) AND isset($request[0]['count'])) ? intval($request[0]['count']) : 0;
		}
		

	//..............................................................................
	// возвращает данные статистики рассылки
	//..............................................................................		
	static function _stats($mailing_id=NULL, $table_name = DEFAULT_MAILINGLIST_TABLE, $mail_name = DEFAULT_MAIL_TABLE, $db_prefix=DB_PREFIX)
		{
		$result = NULL;
		if ($mailing = itMySQL::_get_rec_from_db($table_name, $mailing_id))
			{
			if (is_array($request = itMySQL::_request("SELECT * FROM {$db_prefix}{$mail_name} WHERE `mailing_id`='$mailing_id'")))
				{
				$result['mails'] = $request;
				$result['count'] = count($request);
				foreach($request as $mail)
					{
					$status = self::option_text($mail, 'status', 'ERROR');
					$result[$status] = isset($result[$status]) ? ($result[$status]+1) : 1;
					}
				} else {
					$result = [
						$result['mails'] = NULL,
						$result['count'] = 0,
						];
					foreach(['PREPARED','WAIT','SEND','RECIEVED','ERROR'] as $status)
						$result[$status] = 0;	
					}
			}
		return $result;
		}
		
	static function _strip_logo($text)
		{
		return preg_replace("/top_left_logo(.*).png/iUs", "top_left_logo.png", (string)$text);
		}
	
	}
?>
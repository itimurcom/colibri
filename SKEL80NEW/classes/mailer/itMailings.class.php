<?
//..............................................................................
// itMailings : класс для работы рассылками писем
//..............................................................................
class itMailings
	{
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
		$values_arr = [
			'title_xml'	=> [
				CMS_LANG	=> ready_val($options['title'])
				],
			'emails_xml'	=> ready_val($options['emails']),
			'status'	=> 'PUBLISHED',
			];
		return itMySQL::_insert_rec($table_name, $values_arr);
		}

	//..............................................................................
	// добавляет email в список
	//..............................................................................
	static function _add_to_list($mailing_list_id=NULL, $options=NULL, $table_name=DEFAULT_MAILINGLIST_TABLE)
		{
		if ( ($row = itMySQL::_get_rec_from_db($table_name, $mailing_list_id)) AND !is_null($options))
			{
			if (!isset($row['emails_xml'][$options['email']]))
				$row['emails_xml'][$options['email']] = NULL;
			if (isset($options['user_id']))
				{
				$row['emails_xml'][$options['email']]['user_id'] = $options['user_id'];
				}
			if (isset($options['name']))
				{
				$row['emails_xml'][$options['email']]['name'] = $options['name'];
				}

			itMySQL::_update_value_db($table_name, $mailing_list_id, $row['emails_xml'], 'emails_xml');
			}
		}


	//..............................................................................
	// импорт emails в список
	//..............................................................................
	static function _import_list($mailing_list_id=NULL,$import=NULL, $table_name=DEFAULT_MAILINGLIST_TABLE)
		{
		if ( ($row = itMySQL::_get_rec_from_db($table_name, $mailing_list_id)) AND !is_null($import))
			{
			$lines = explode("\n",$import);
			$data = explode(",", $import);
			
			if (count($lines)>1)
				{
				// обработка многострочная
				foreach($lines as $email_row)
					{
					$data = explode(",",$email_row);
					if (!isset($data[1]))
						$data[1] = NULL;
					if (isEmail(trim($data[0])))
						{
						$row['emails_xml'][trim($data[0])]['name'] = trim($data[1]);
						}
					}
				} else

			if (isEmail(trim($data[1])))
				{
				// это список emails	
				foreach($data as $email)
					{
					$row['emails_xml'][trim($email)]=NULL;
					}
				} else if (isEmail(trim($data[0])))
					{
					// передали один email и его имя
					$row['emails_xml'][trim($data[0])]['name'] = trim($data[1]);
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
			// это список emails удаляем
			foreach(explode(",", $list) as $email)
				{
				$email_name = trim($email);
				if (isset($row['emails_xml'][$email_name]) OR @is_null($row['emails_xml'][$email_name]))
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
			return $row['name'];
			}
		}
	//..............................................................................
	// отправляет сообщение по подготовленным данным
	//..............................................................................
	static function _send($options=NULL, $table_name = DEFAULT_MAIL_TABLE)
		{
		$rec_id  = itMySQL::_last_id('mails');
		$now = ready_val($row['datetime'], mysql_now());
		
		$data = itEditor::event_data([
			'time'		=> $now,
			'rec_id'	=> $rec_id,
			]);

		$values_arr = [
			'from' 		=> ready_val($row['from'], DEFAULT_ADMIN_NAME),
			'to' 		=> $options['to'],
			'subject' 	=> $options['subject'],
			'reply'		=> ready_val($row['reply']),
			'message'	=> str_replace('[MAILID]', $data, $row['message']),
			'status' 	=> 'WAIT',
			'files_xml'	=> ready_val($options['files']),
			'datetime'	=> $now,
			];
			
		if ( $rec_id != ($real_id = itMySQL::_insert_rec($table_name, $values_arr)) )
			{
			itMySQL::_update_value_db('mails', $real_id, str_replace('[MAILID]', $real_id, $options['message']), 'message');
			}
			
		return itMySQL::_insert_rec($table_name, $values_arr);
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
			
			$rec_id	= itMySQL::_last_id('mails');
	
			$index = 0;
			foreach ($emails_arr as $row)
				{
				$now = ready_val($row['datetime'], mysql_now());
				
				$data = itEditor::event_data([
					'time'		=> $now,
					'rec_id'	=> $rec_id,
					]);

				$values_arr[] = [
					ready_val($row['from'], DEFAULT_ADMIN_NAME),
					$row['to'],
					ready_val($row['reply']),
					$row['subject'],
					str_replace('[MAILID]', $data, $row['message']),
					ready_val($row['files']),
					$now,
					'WAIT',
					];
				$rec_id++;
				}
			itMySQL::_insert_rec($table_name, $values_arr);
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
			switch ($row['object'])
				{
				case 'LIST' : {
					// подготовим рассылку по списку
					if ($maillist =itMySQL::_get_rec_from_db($list_name, $row['object_id']) )
						{
						foreach($maillist['emails_xml'] as $email_key=>$email_row)
							{
							$options = [
							'name'		=> $email_row['name'],
							'pattern_id'	=> $row['pattern_id'],
							];
	
							itMailTemplate::_prepare($options);								
							$mails[] = [
								'to'		=> $email_key,
								'subject'	=> CMS_NAME.' | '.itMailTemplate::_subj($row['pattern_id']),
								'message'	=> $options['result'],
								];	
							}
						itMailings::_send_arr($mails);
						return true;
						}
					break;
					}
				case 'PRO' : {
					if (is_array($array_of_users = trPro::_pro_users()))
						{
						foreach($array_of_users as $row_of_user)
							{
							if (isEmail($email_name = itUser::get_email($row_of_user['id'])))
								{
								$options = [
									'user_id'	=> $row_of_user['id'],
									'pattern_id'	=> $row['pattern_id'],
									];
								itMailTemplate::_prepare($options);
								$mails[] = [
									'to'		=> $email_name,
									'subject'	=> CMS_NAME.' | '.$options['subject'],
									'message'	=> $options['result'],	
									];
								}
							}
						itMailings::_send_arr($mails);
						return true;
						} else return false;
					break;	
					}

				case 'CHAT' : {
					if (is_array($array_of_users = trCHatRoom::_has_access($row['object_id'])))
						{
						foreach($array_of_users as $row_of_user)
							{
							if (isEmail($email_name = itUser::get_email($row_of_user['user_id'])))
								{
								$options = [
									'user_id'	=> $row_of_user['user_id'],
									'pattern_id'	=> $row['pattern_id'],
									];
								itMailTemplate::_prepare($options);
								$mails[] = [
									'to'		=> $email_name,
									'subject'	=> CMS_NAME.' | '.$options['subject'],
									'message'	=> $options['result'],	
									];
								}
							}
						itMailings::_send_arr($mails);
						return true;
						} else return false;
					break;	
					}				}	
			}
		}
		
	//..............................................................................
	// возвращает количество писем на отправку
	//..............................................................................		
	static function _count_wait($table_name = DEFAULT_MAIL_TABLE, $db_prefix=DB_PREFIX)
		{
		$request = itMySQL::_request("SELECT COUNT(`id`) AS count FROM {$db_prefix}{$table_name} WHERE `status`='WAIT'");
		return (is_array($request) ? $request[0]['count'] : 0);
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
					$result[$mail['status']] = isset($result[$mail['status']]) ? ($result[$mail['status']]+1) : 1;
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
		return preg_replace("/top_left_logo(.*).png/iUs", "top_left_logo.png", $text);
		}
	
	}
?>
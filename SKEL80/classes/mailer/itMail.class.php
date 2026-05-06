<?php
// ================ CRC ================
// version: 1.15.04
// hash: 2a85d6cf42f353fc517befa7cbe00e21ff502d7deeaefc20daaa8e65ced154a4
// date: 30 April 2021 16:04
// ================ CRC ================

//..............................................................................
// itMail :  класс email сообщений 
//..............................................................................
class itMail
	{
	public $from, $to, $subject, $message, $code, $status;
	public $table_name, $rec_id, $ed_rec;
	public $fields, $files;

	static function option_value($options, $key, $default=NULL)
		{
		return (is_array($options) AND array_key_exists($key, $options)) ? $options[$key] : $default;
		}

	static function text_value($options, $key, $default='')
		{
		return trim((string)self::option_value($options, $key, $default));
		}

	//..............................................................................
	// конструктор - создает объект письма
	//..............................................................................
	//
	// указываем в качестве rec_id первый параметр вместо options для загрузки
	// сообщения из базы или указываем массив 
	//
	//	options => 'from', 'to', 'subject', 'files` => array : вложения
	//
	//..............................................................................
	public function __construct($options=NULL, $table_name=DEFAULT_MAIL_TABLE)
		{
		$this->table_name = $table_name;			
		$this->from = DEFAULT_ADMIN_NAME;
		$this->to = '';
		$this->subject = '';
		$this->message = '';
		$this->code = '';
		$this->status = '';
		$this->fields = [];
		$this->files = [];

		if (is_array($options))
			{
			$this->from = self::text_value($options, 'from', $this->from);
			$this->to = self::text_value($options, 'to');
			$this->subject = self::text_value($options, 'subject');
			$this->message = (string)self::option_value($options, 'message', '');
			$this->code = self::text_value($options, 'code');
			$this->status = self::text_value($options, 'status');
			$this->files = is_array(self::option_value($options, 'files')) ? self::option_value($options, 'files') : [];
			return;
			}

		if (intval($options)>0)
			{
			// передано значение ID письма - получаем его из базы, а options = rec_id
			if (is_array($this->ed_rec = itMySQL::_get_rec_from_db($table_name, $options)))
				{
				$this->rec_id 	= $options;
				$this->from 	= self::text_value($this->ed_rec, 'from', $this->from);
				$this->to 	= self::text_value($this->ed_rec, 'to');
				$this->subject 	= self::text_value($this->ed_rec, 'subject');
				$this->message	= (string)self::option_value($this->ed_rec, 'message', '');
				$this->code 	= self::text_value($this->ed_rec, 'code');
				$this->status 	= self::text_value($this->ed_rec, 'status');
				}
			}
		}

	//..............................................................................
	// устанавливает отправителя
	//..............................................................................	
	public function from($from)
		{
		$this->from = $from;
		}

	//..............................................................................
	// устанавливает отправителя
	//..............................................................................	
	public function to($to)
		{
		$this->to = $to;
		}


	//..............................................................................
	// устанавливает отправителя
	//..............................................................................	
	public function subject($subject)
		{
		$this->subject = $subject;
		}


	//..............................................................................
	// создает письмо для отправки
	//..............................................................................	
	public function compile()
		{
		if (is_array($this->fields))
			foreach ($this->fields as $key=>$row)
				{
				if (!is_array($row)) continue;
				$type = self::text_value($row, 'type');
				$value = (string)self::option_value($row, 'value', '');
				switch ($type)
					{                    
					case 'tpl' :
						{
						$this->message .= $value;
						break;
						}
					case 'text' : {
						$this->message .= TAB.$value.TAB;
						break;
						}
					case 'html' :
						{
						$this->message .= TAB."<p>{$value}".TAB."</p>";
						break;
						}
					}
				}
		}

	//..............................................................................
	// добавляет строку текста в поля письма
	//..............................................................................	
	public function add_text($txt)
		{
		$this->fields[] = array	(
			'type'	=> 'text',
			'value' => $txt
			);
		}

	//..............................................................................
	// добавляет текст из шаблона, заменяя переменные из массива repl_array
	//..............................................................................	
	public function add_tpl($tpl=NULL, $repl_arr=NULL)
		{
		$repl_arr = is_array($repl_arr) ? $repl_arr : [];
		$search = [];
		$replace = [];
		if (count($repl_arr))
			foreach($repl_arr as $key=>$row)
				{
				$search[] = "[$key]";
				$replace[] = $row;
				}

		ob_start();
		include "themes/".get_const('CMS_THEME')."/mail.$tpl.php";
		$out = ob_get_clean();

		$this->fields[] = array	(
			'type'	=> 'tpl',
			'value' => str_replace($search, $replace, $out)
			);
		}


	//..............................................................................
	// добавляет строку текста в поля письма
	//..............................................................................	
	public function add_html($txt)
		{
//		$txt = get_mail_html($txt);
		$this->fields[] = array	(
			'type'	=> 'html',
			'value' => $txt
			);
		}


	//..............................................................................
	// добавляет имя файла для прикрепления
	//..............................................................................	
	public function attach($file)
		{
		if (!is_array($this->files)) $this->files = [];
		if ($file !== NULL AND $file !== '') $this->files[] = $file;
		}


	//..............................................................................
	// добавляет email в базу данных
	//..............................................................................	
	public function store()
		{
		$this->push($status='PREPARED');
		}


	//..............................................................................
	// добавляет email в базу данных в очередь для отправки
	//..............................................................................	
	public function push($status='WAIT')
		{
		$this->compile();

		if ($this->to == 'all')
			{
			$this->push_all($status); return;
			}

		if (trim((string)$this->to) === '') return;

		$db = new itMySQL();

		$values = array (
			'from' 		=> trim((string)$this->from),
			'to' 		=> trim((string)$this->to),
			'subject' 	=> (string)$this->subject,
			'message'	=> (string)$this->message,
			'status' 	=> trim((string)$status),
			);

		if (is_array($this->files) AND count($this->files))
			{
			$values['xml_files'] = json_encode($this->files);
			}


		if (intval($this->rec_id)>0)
			{
			// повторная отправка и обновление данных
			$set = [];
			foreach ($values as $key=>$row)
				{
				$set[] = "`$key` = '".addslashes((string)$row)."'";
				}
			$keys_str = implode(',', $set);
			$query = "update {$db->db_prefix}{$this->table_name} set $keys_str where `id`='{$this->rec_id}'";
			$db->request($query);
			} else	{
				// просто добавляем письмо со статусом
				$this->rec_id = $db->insert_rec($this->table_name, $values);
				}
		unset($db);
		}


	//..............................................................................
	// добавляет email в базу данных в очередь для отправки ВСЕМ пользователям
	//..............................................................................	
	public function push_all($status='WAIT', $table_of_user = DEFAULT_USER_TABLE) 
		{
		if (is_array($this->files) AND count($this->files))
			{
			$values['xml_files'] = json_encode($this->files);
			} else $values['xml_files'] = '';

		$this->from = trim((string)$this->from);
		$this->subject = (string)$this->subject;
		$this->message = (string)$this->message;

		$db = new itMySQL();
		$from_sql = addslashes($this->from);
		$subject_sql = addslashes($this->subject);
		$message_sql = addslashes($this->message);
		$files_sql = addslashes((string)$values['xml_files']);
		$status_sql = addslashes((string)$status);
		// отправим всем сообщение
		$db->request("INSERT INTO {$db->db_prefix}{$this->table_name} (`from`, `to`, `subject`, `message`, `files_xml`, `status`) ".
			"SELECT '{$from_sql}', {$db->db_prefix}$table_of_user.email, '{$subject_sql}', '{$message_sql}', '{$files_sql}', '{$status_sql}' ".
			"FROM {$db->db_prefix}$table_of_user WHERE `group_id`<>".get_const('GR_ADMIN')." and `email`<>''");
		unset($db);
		}

	//..............................................................................
	// устанавливает статус отправки сообщения
	//..............................................................................	
	static function status($rec_id, $status='PREPARED', $table_name=DEFAULT_MAIL_TABLE)
		{
		$db = new itMySQL();
		$db->update_value_db($table_name, $rec_id, $status, 'status');
		unset($db);
		}

	//..............................................................................
	// устанавливает код выполнения для сообщения
	//..............................................................................	
	static function code($rec_id, $code='Ok', $table_name=DEFAULT_MAIL_TABLE)
		{
		$db = new itMySQL();
		$db->update_value_db($table_name, $rec_id, $code, 'code');
		unset($db);
		}
	} // class
?>
<?

//..............................................................................
// itMail :  класс email сообщений 
//..............................................................................
class itMail
	{
	public $from, $to, $message, $code, $status;
	public $table_name, $rec_id, $ed_rec;
	public $fields, $files;

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

		if (intval($options)>0)
			{
			// передано значение ID письма - получаем его из базы, а options = rec_id
			if (is_array($this->ed_rec = itMySQL::_get_rec_from_db($table_name, $options)))
				{
				$this->rec_id 	= $options;
				$this->from 	= $this->ed_rec['from'];
				$this->to 	= $this->ed_rec['to'];
				$this->subject 	= $this->ed_rec['subject'];
				$this->message	= $this->ed_rec['message'];
				$this->code 	= $this->ed_rec['code'];
				$this->status 	= $this->ed_rec['status'];
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
				switch ($row['type'])
					{                    
					case 'tpl' :
						{
						$this->message .= $row['value'];
						break;
						}
					case 'text' : {
						$this->message .= TAB.$row['value'].TAB;
						break;
						}
					case 'html' :
						{
						$this->message .= TAB."<p>{$row['value']}".TAB."</p>";
						break;
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
		$search = [];
		$replace = [];
		if (is_array($repl_arr))
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
		$this->files[] = $file;
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
			$this->push_all(); return;
			}

		$db = new itMySQL();

		$values = array (
			'from' 		=> $this->from,
			'to' 		=> $this->to,
			'subject' 	=> $this->subject,
			'message'	=> $this->message,
			'status' 	=> $status,
			);

		if (is_array($this->files))
			{
			$values['xml_files'] = json_encode($this->files);
			}


		if (intval($this->rec_id)>0)
			{
			// повторная отправка и обновление данных
			foreach ($values as $key=>$row)
				{
				$set[] = "`$key` = '$row'";
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
		if (is_array($this->files))
			{
			$values['xml_files'] = json_encode($this->files);
			} else $values['xml_files'] = '';

		$db = new itMySQL();
		// отправим всем сообщение
		$db->request("INSERT INTO {$db->db_prefix}{$this->table_name} (`from`, `to`, `subject`, `message`, `files_xml`, `status`) ".
			"SELECT '{$this->from}', {$db->db_prefix}$table_of_user.email, '{$this->subject}', '".$this->message."', '{$values['xml_files']}', '{$status}' ".
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
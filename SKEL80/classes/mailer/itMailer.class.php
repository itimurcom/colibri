<?php
// ================ CRC ================
// version: 1.15.04
// hash: 1c1224aec930c26485f29d95d03ce5e1f0676811c603122af059b41903b37e70
// date: 10 March 2021  9:27
// ================ CRC ================
//..............................................................................
// itMailer :  класс для отправки сообщений на почту пользователей из стека БД
//..............................................................................
class itMailer
	{
	public $table_name, $num, $force;

	static function row_value($row, $key, $default=NULL)
		{
		return (is_array($row) AND array_key_exists($key, $row)) ? $row[$key] : $default;
		}

	static function row_text($row, $key, $default='')
		{
		return trim((string)self::row_value($row, $key, $default));
		}

	//..............................................................................
	// конструктор класса - готовит данные для писем и отправляет их из стека
	//..............................................................................	
	public function __construct($options = NULL, $table_name=DEFAULT_MAIL_TABLE)
		{
		$options = is_array($options) ? $options : [];
		$this->table_name = $table_name;
		
		$this->num = isset($options['num']) ? max(1, intval($options['num'])) : DEFAULT_MAIL_PACKET;
		$this->force = !empty($options['force']);		
		$this->stack();
		}


	//..............................................................................
	// отправляет пакет из указанного количества писем из стека подготовленных
	//..............................................................................	
	public function stack()
		{
		$mess_arr = [];
		try	{
			$mess_arr = itMySQL::_get_arr_from_db($this->table_name, "`status` IN ('WAIT'".($this->force ? ",'ERROR'" : NULL).")", "`datetime` ASC LIMIT {$this->num}");
			} catch	(Exception $e) {
				$mess_arr = [];
				}
			
		if (is_array($mess_arr))
			foreach ($mess_arr as $key => $row)
				{
				itMailer::send($row);
				}
		}

	//..............................................................................
	// отправляет одно сообщение - пакует его из данных таблицы
	//..............................................................................
	//
	// данные входные ($row)	
	//
	// id		: номер сообщения
	// from 	: отправитель
	// to		: адресат
	// subject	: тема сообщения
	// message	: текст сообщения (скомпилированный)
	// files_xml	: список файлов - вложений письма
	// status	: статус отправки сообщения
	//..............................................................................
	static function send($row, $html=true)
		{
		global $_SETTINGS;
		$row = is_array($row) ? $row : [];
		$mail_id = intval(self::row_value($row, 'id', 0));
		$to = self::row_text($row, 'to');
		if ($mail_id <= 0 OR $to === '') return false;
/*		$boundary = strtoupper(uniqid(time()));
		$type = ($html) ? 'text/html' : 'text/plain';

		$replay_to = isset($row['reply']) ? $row['reply'] : $row['from'];

		// headers
		$headers = "MIME-Version: 1.0\r\n".
	        	"From: {$row['from']}\r\n".
	        	"Reply-To: {$replay_to}\r\n".
	        	"Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n"; 
        
	        //text 
	        $message = "--$boundary\r\n".
	        	"Content-Type: $type; charset=utf-8\r\n".
	        	"Content-Transfer-Encoding: 7bit\r\n\r\n".
	        	$row['message']; 

	        //attachments
		if ($row['files_xml']!=NULL)
			{
			foreach ($row['files_xml'] as $chunk)
				{
				$message .= itMailer::make_chunk($chunk, $boundary);
				}
			}
			
		$sendflag = mail($row['to'], $row['subject'], $message, $headers);
*/

		$smptmail = new PHPMailer;
		$smptmail->CharSet = 'utf-8';
		$smptmail->SMTPDebug = defined('DEFAULT_SMTP_DEBUG') ? DEFAULT_SMTP_DEBUG : 0;
		$smptmail->Debugoutput = 'error_log';
		// $smptmail->isSMTP(); 
		$smptmail->Host = DEFAULT_SMTP_HOST;
		$smptmail->SMTPAuth = true; 
		$smptmail->Username = trim(isset($_SETTINGS['SITE_SMTP_USER']['value']) ? $_SETTINGS['SITE_SMTP_USER']['value'] : '');
		$smptmail->Password = trim(isset($_SETTINGS['SITE_SMTP_PASSWORD']['value']) ? $_SETTINGS['SITE_SMTP_PASSWORD']['value'] : '');

//$smptmail->SMTPSecure = 'ssl'; 
		$smptmail->Port = DEFAULT_SMTP_PORT;

/*
	        //attachments
		if (!is_null($row['files_xml']) AND is_array($row['files_xml']) AND count($row['files_xml']))
			{
			foreach ($row['files_xml'] as $chunk)
				{
				$smptmail->addAttachment($row['files_xml']);
				}
			}
*/

		$from_email = self::row_text($row, 'from', (defined('DEFAULT_ADMIN_EMAIL') ? DEFAULT_ADMIN_EMAIL : trim(isset($_SETTINGS['SITE_ADMIN_EMAIL']['value']) ? $_SETTINGS['SITE_ADMIN_EMAIL']['value'] : '')));
		$from_name = defined('CMS_NAME') ? CMS_NAME : (defined('SITE_NAME') ? SITE_NAME : 'SKEL80');
		$smptmail->setFrom($from_email, $from_name); // Ваш Email
		$smptmail->addAddress($to); // Email получателя


		$reply = self::row_text($row, 'reply');
		if ($reply !== '' AND isEmail($reply))
			{
			$smptmail->ClearReplyTos();
			$name = self::row_text($row, 'name');
			if ($name !== '')
				{
				$smptmail->AddReplyTo($reply, $name);	
				} else $smptmail->AddReplyTo($reply); // ответ нужно добавлять ПЕРЕД setFrom
			}


		$smptmail->isHTML(true); 
		$smptmail->Subject = self::row_text($row, 'subject');
		$smptmail->Body = (string)self::row_value($row, 'message', ''); // Текст письма		
		
		if ($smptmail->send())
			{
			itMail::status($mail_id,'SEND');
			itMail::code($mail_id,'Ok');
			} else	{
				itMail::status($mail_id,'ERROR');
				itMail::code($mail_id, $smptmail->ErrorInfo);
				}
		unset($smptmail);
		}


	//..............................................................................
	// пакует в чанк одно вложение
	//..............................................................................
	static function make_chunk($file, $boundary)
		{                 
		$path = './files/'; 

		$filename = file_exists($file) ? $file :
			(file_exists($path.$file) ? $path.$file : NULL);

		if (is_null($filename))
			{
			add_error_message("error <b>attaching</b> file <b>[".htmlspecialchars((string)$file, ENT_QUOTES)."]</b>");
			return;
			}

		$file_size = filesize($filename);
	     	$handle = fopen($filename, "r");
		$code = fread($handle, $file_size);
		fclose($handle);
		$fileContent = chunk_split(base64_encode($code));
		$fileName = basename($file);
		$data = "\n"."--".$boundary."\n".
			"Content-Type: application/octet-stream; name=\"".$fileName."\"\r\n".
			"Content-Transfer-Encoding: base64\r\n".
			"Content-Disposition: attachment; filename=\"".$fileName."\"\r\n\r\n".
			$fileContent."\r\n\r\n";
		return $data;
		}

	} // class
?>
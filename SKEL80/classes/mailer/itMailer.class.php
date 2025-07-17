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
	public $table_name;

	//..............................................................................
	// конструктор класса - готовит данные для писем и отправляет их из стека
	//..............................................................................	
	public function __construct($options = NULL, $table_name=DEFAULT_MAIL_TABLE)
		{
		$this->table_name = $table_name;
		
		$this->num = isset($options['num']) ? $options['num'] : DEFAULT_MAIL_PACKET;
		$this->force = isset($options['force']) ? $options['force'] : false;		
		$this->stack();
		}


	//..............................................................................
	// отправляет пакет из указанного количества писем из стека подготовленных
	//..............................................................................	
	public function stack()
		{
		try	{
			$mess_arr = itMySQL::_get_arr_from_db($this->table_name, "`status` IN ('WAIT'".($this->force ? ",'ERROR'" : NULL).")", "`datetime` ASC LIMIT {$this->num}");
			} catch	(Exception $e) {
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
		$smptmail->SMTPDebug = 1;
		// $smptmail->isSMTP(); 
		$smptmail->Host = DEFAULT_SMTP_HOST;
		$smptmail->SMTPAuth = true; 
		$smptmail->Username =  trim($_SETTINGS['SITE_SMTP_USER']['value']);
		$smptmail->Password = trim($_SETTINGS['SITE_SMTP_PASSWORD']['value']);

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

		$smptmail->setFrom($row['from'], CMS_NAME); // Ваш Email
		$smptmail->addAddress($row['to']); // Email получателя


		if (isset($row['reply']) and isEmail($row['reply']))
			{
			$smptmail->ClearReplyTos();
			if (isset($row['name']))
				{
				$smptmail->AddReplyTo($row['reply'], $row['name']);	
				} else $smptmail->AddReplyTo($row['reply']); // ответ нужно добавлять ПЕРЕД setFrom
			}


		$smptmail->isHTML(true); 
		$smptmail->Subject = $row['subject'];
		$smptmail->Body = $row['message']; // Текст письма		
		
		if ($smptmail->send())
			{
			itMail::status($row['id'],'SEND');
			itMail::code($row['id'],'Ok');
			} else	{
				itMail::status($row['id'],'ERROR');
				itMail::code($row['id'], $smptmail->ErrorInfo);
				}
		unset($smtpmail);
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
			add_error_message("error <b>attaching</b> file <b>[$file]</b>");
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
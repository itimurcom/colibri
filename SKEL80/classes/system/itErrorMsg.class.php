<?php
// ================ CRC ================
// version: 1.15.04
// hash: 4e35522a64786f5d5ed25f6df33c519eeb212c181ce9480905aac358881640e1
// date: 09 September 2019  5:10
// ================ CRC ================
//..............................................................................
// itErrorMsg : класс доступа к одной записи базы данных
//..............................................................................
class itErrorMsg
	{
	public $code;

	//..............................................................................
	// конструктор класса - создает сообщение об ошибке и убирает его с экрана
	//..............................................................................	
	public function __construct()
		{
		$this->code = '';
		$this->compile();
		}

	//..............................................................................
	// возвращает код сообщения об ошибке
	//..............................................................................
	public function compile()
		{
		$this->code = NULL;
		if (isset($_SESSION['error']) and !empty($_SESSION['error']))
			{
//			$this->code = TAB."<div class='content'>";
			foreach ($_SESSION['error'] as $key=>$row)
				{
//				print_r($row);
				if (is_array($row))
					{
					$color = (isset($row['color']) ) ? $row['color'] : DEFAULT_ERROR_COLOR;
					$keep = ready_val($row['keep']) ? " keep" :'';
					$message = get_const($row['msg']);
					$this->code .= TAB."<div class='error_msg {$color}'{$keep}>{$message}</div>";
					} else	{
						unset($_SESSION['error']);
						break;
						}
				}
//			$this->code .= TAB."</div>";
			unset($_SESSION['error']);
			}
		}

	} //class
?>
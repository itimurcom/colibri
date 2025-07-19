<?
//..............................................................................
// itUserReg : класс регистрации пользователя на сайте
//..............................................................................
Class itUserReg
	{
	public $access, $data, $provider, $token_request, $func;

	//..............................................................................
	// Конструктор класса, создаем объект проверяя условия
	//..............................................................................
	public function __construct($options=NULL)
		{
		$this->data	= NULL;
		$this->access 	= NULL;
		$this->provider = isset($_REQUEST[SOCIAL_SELECTOR]) ? $_REQUEST[SOCIAL_SELECTOR] : NULL;
		$this->func	= isset($options['func']) ? $options['func'] : DEFAULT_USER_SCRIPT;
		$this->table_name	= isset($options['table_name']) ? $options['table_name'] : DEFAULT_USER_TABLE;

		$this->access();		
		$this->userdata();
		$this->store();
		}

	//..............................................................................
	// получает массив для доступа к данным социальной сети
	//..............................................................................
	public function access()
		{
		global $soc_net;

		if (isset($_REQUEST['error']) or ($this->provider==NULL))
			{
			add_error_message(get_const('SOCIAL_LOGIN_ERROR'));
			return;
			}

		// установим параметры получения токена
		if (isset($soc_net[$this->provider]['token']['param']))
			{
			$this->token_request = $soc_net[$this->provider]['token']['param'];
			}

		$this->token_request['redirect_uri'] = REDIRECT_URI_SCRIPT."?".SOCIAL_SELECTOR."=".$this->provider;

		if (isset($_REQUEST['code']))
			{
			$this->token_request['code']  = $_REQUEST['code'];
			}

		$acces_rec_func = "{$this->provider}_access_rec";
		if (!function_exists($acces_rec_func))
			{
			$this->access = NULL;
			add_error_message('ERROR_ACCESS_TOKEN_FUNC');
			return;
			} else	{
				$this->access = $acces_rec_func($this->token_request);
				}
//		print_r($this->access);
		}


	//..............................................................................
	// получает данные пользователя на основании кода доступа
	//..............................................................................
	public function userdata()
		{
		if ($this->access==NULL) return;

		$rec_of_user_func = "{$this->provider}_user_rec";

		if (!function_exists($rec_of_user_func))
			{
			$this->data = NULL;
			add_error_message('ERROR_USER_DATA_FUNC');
			return;
			}

		$this->data = $rec_of_user_func($this->access);
		}


	//..............................................................................
	// заносит данный пользователя из социальной сети в базу системы
	//..............................................................................
	public function store()
		{
		global $_USER;
		if (!is_array($this->data))
			{
			add_error_message('ERROR_USER_DATA');
			add_error_message($this->data);
			return;
			}

		$id_of_user = $_USER->get_user_id_from_login($this->data['login']);
		if ($id_of_user==false)
			{
			// обновляем данные пользователя и логинимся
			$_USER->register_user($this->data['login'], generate_new_password(), $this->func, $this->table_name);
			$id_of_user = $_USER->get_user_id_from_login($this->data['login']);
			}


		// обновляем данные
		$db = new itMySQL();
		$db->update_value_db('users', $id_of_user, $this->data, 'social');

		// логинимся
		$_USER->login($id_of_user);

		// обновляем пустые поля
		if (isset($this->data['email']))
			{
			$db->update_value_db('users', $id_of_user, $this->data['email'], 'email');
			$_USER->data['email'] = $this->data['email'];
			}

		if (isset($this->data['sex']))
			{
			$db->update_value_db('users', $id_of_user, $this->data['sex'], 'sex');
			$_USER->data['sex'] = $this->data['sex'];
			}

		// если нужно правим имя
		if ((trim($_USER->data['name'])=='') or ($_USER->data['name']==$this->data['login']) )
			{
			$db->update_value_db('users', $id_of_user, trim($this->data['name']), 'name');
			}
		unset($db);
		}

	//..............................................................................
	// возвращает ссылку на авторизацию для сети [ twitter ] - нужен для замены
	//..............................................................................
	static function oauth()
		{
		global $soc_net;

		$type = $_REQUEST['engine'];
		$url = $soc_net[$type]['button']['link'];

		switch ($type)
			{
			case 'TW' : {
				$tw_token = get_tw_token();
				$request = 'oauth_token='.$tw_token['oauth_token'];
				break;
				}
			default : {
				foreach ($soc_net[$type]['token']['param'] as $key=>$row)
					{
					$res[] = "$key=".urlencode($row);
					}
				$request = implode ('&', $res);
				break;
				}
			}
		$_SESSION['http_referer'] = $_SERVER['HTTP_REFERER'];

		cms_redirect_page("$url?$request");
		}

	} // class

?>
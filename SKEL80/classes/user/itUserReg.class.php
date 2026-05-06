<?php
// itUserReg : класс регистрации пользователя на сайте
Class itUserReg
	{
	public $access, $data, $provider, $token_request, $func, $table_name;

	static function request_value($key, $default=NULL)
		{
		return (isset($_REQUEST) AND is_array($_REQUEST) AND array_key_exists($key, $_REQUEST)) ? $_REQUEST[$key] : $default;
		}

	static function array_value($row, $key, $default=NULL)
		{
		return (is_array($row) AND array_key_exists($key, $row)) ? $row[$key] : $default;
		}

	static function user_runtime()
		{
		global $_USER;
		return (isset($_USER) AND is_object($_USER)) ? $_USER : NULL;
		}

	// Конструктор класса, создаем объект проверяя условия
	public function __construct($options=NULL)
		{
		$options = is_array($options) ? $options : [];
		$this->data	= NULL;
		$this->access 	= NULL;
		$this->token_request = [];
		$this->provider = defined('SOCIAL_SELECTOR') ? self::request_value(SOCIAL_SELECTOR) : NULL;
		$this->func	= self::array_value($options, 'func', DEFAULT_USER_SCRIPT);
		$this->table_name	= self::array_value($options, 'table_name', DEFAULT_USER_TABLE);

		$this->access();		
		$this->userdata();
		$this->store();
		}

	// получает массив для доступа к данным социальной сети
	public function access()
		{
		global $soc_net;

		if (self::request_value('error') OR ($this->provider==NULL))
			{
			add_error_message(get_const('SOCIAL_LOGIN_ERROR'));
			return;
			}

		$network = (isset($soc_net) AND is_array($soc_net) AND isset($soc_net[$this->provider]) AND is_array($soc_net[$this->provider])) ? $soc_net[$this->provider] : [];
		if (empty($network))
			{
			$this->access = NULL;
			add_error_message('ERROR_SOCIAL_PROVIDER');
			return;
			}

		// установим параметры получения токена
		$this->token_request = (isset($network['token']['param']) AND is_array($network['token']['param'])) ? $network['token']['param'] : [];
		$this->token_request['redirect_uri'] = REDIRECT_URI_SCRIPT."?".SOCIAL_SELECTOR."=".$this->provider;

		if (($code = self::request_value('code')) !== NULL)
			{
			$this->token_request['code']  = $code;
			}

		$acces_rec_func = "{$this->provider}_access_rec";
		if (!function_exists($acces_rec_func))
			{
			$this->access = NULL;
			add_error_message('ERROR_ACCESS_TOKEN_FUNC');
			return;
			} else	{
				$this->access = $acces_rec_func($this->token_request);
				if (!is_array($this->access)) $this->access = NULL;
				}
//		print_r($this->access);
		}


	// получает данные пользователя на основании кода доступа
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


	// заносит данный пользователя из социальной сети в базу системы
	public function store()
		{
		$_USER = self::user_runtime();
		if (!is_array($this->data))
			{
			add_error_message('ERROR_USER_DATA');
			if (!is_null($this->data)) add_error_message((string)$this->data);
			return;
			}

		if (!is_object($_USER) OR empty($this->data['login']))
			{
			add_error_message('ERROR_USER_RUNTIME');
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
		$user_name = (isset($_USER->data) AND is_array($_USER->data) AND isset($_USER->data['name'])) ? $_USER->data['name'] : '';
		$data_name = isset($this->data['name']) ? trim((string)$this->data['name']) : '';
		if ((trim($user_name)=='') OR ($user_name==$this->data['login']) )
			{
			$db->update_value_db('users', $id_of_user, $data_name, 'name');
			}
		unset($db);
		}

	// возвращает ссылку на авторизацию для сети [ twitter ] - нужен для замены
	static function oauth()
		{
		global $soc_net;

		$type = self::request_value('engine');
		if (empty($type) OR !isset($soc_net[$type]) OR !is_array($soc_net[$type]) OR empty($soc_net[$type]['button']['link']))
			{
			add_error_message('ERROR_SOCIAL_PROVIDER');
			return;
			}

		$url = $soc_net[$type]['button']['link'];
		$res = [];

		switch ($type)
			{
			case 'TW' : {
				$tw_token = get_tw_token();
				$request = (is_array($tw_token) AND isset($tw_token['oauth_token'])) ? 'oauth_token='.$tw_token['oauth_token'] : '';
				break;
				}
			default : {
				$params = (isset($soc_net[$type]['token']['param']) AND is_array($soc_net[$type]['token']['param'])) ? $soc_net[$type]['token']['param'] : [];
				foreach ($params as $key=>$row)
					{
					$res[] = "$key=".urlencode((string)$row);
					}
				$request = implode ('&', $res);
				break;
				}
			}
		$_SESSION['http_referer'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';

		cms_redirect_page($url.($request !== '' ? "?$request" : ''));
		}

	} // class

?>
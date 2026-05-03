<?php
class itRouter
	{
	public $data, $path, $lang, $controller, $view, $rec_id, $table_name;
	private $o_lang;

	private function request_value($key, $default=NULL)
		{
		return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
		}

	private function set_request_value($key, $value)
		{
		$_REQUEST[$key] = $value;
		return $value;
		}

	private function path_segments($path)
		{
		$path = trim((string)$path, '/');
		return ($path === '') ? [] : explode('/', $path);
		}

	private function set_rec_id($value)
		{
		$this->rec_id = $value;
		$this->set_request_value('rec_id', $value);
		}

	public function __construct($url=NULL)
		{
		$this->lang		= NULL;
		$this->controller	= DEFAULT_ROUTER_CONTROLLER;
		$this->view		= DEFAULT_ROUTER_VIEW;
		$this->rec_id		= $this->set_request_value('rec_id', $this->request_value('rec_id'));
		$this->table_name	= $this->set_request_value('table_name', $this->request_value('table_name'));
		$this->parse($url);

		if ($url==NULL)
			{
			$this->o_lang = new itLang($this->lang);
			$this->lang = get_const('CMS_LANG');
			if (($this->lang == 'CMS_LANG') or ($this->lang==NULL))
				{
				$_SESSION['error'][]['msg'] = "Error setting language in class <b>itRouter</b>.";
				}
			}
		$this->set_request_value('lang', $this->lang);
		$this->set_request_value('controller', $this->controller);
		$this->set_request_value('view', $this->view);
		}

	public function parse($url=NULL)
		{
		if ($url==NULL)
			{
			$url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
			}

		$parsed_url = parse_url($url);
		$this->path = (is_array($parsed_url) AND isset($parsed_url['path'])) ? $parsed_url['path'] : '/';
		$segments = $this->path_segments($this->path);
		$this->data = $segments;
		$num = count($segments);

		if ($num)
			{
			if (itLang::is_lang($segments[0]))
				{
				$this->lang = $segments[0];
				if ($num>1)
					{
					$this->controller = $segments[1];
					}

				if ($num>2)
					{
					$this->view = $segments[2];
					} elseif ($num>1) $this->view = $this->controller;

				if ($num>3)
					{
					$this->set_rec_id($segments[3]);
					}

				} else 	{
					$this->lang = itLang::get_lang();
					$this->controller = $segments[0];
					if ($num>1)
						{
						$this->view = $segments[1];
						} else $this->view = $this->controller;
					if ($num>2)
						{
						$this->set_rec_id($segments[2]);
						}
					}
			if (intval($this->view) or isMAC($this->view))
				{
				$this->set_rec_id($this->view);
				$this->view = $this->controller;
				}
			}
		}

	} // class

?>

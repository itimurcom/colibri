<?
//..............................................................................
// itRouter : класс разыменования командной строки при загрузке страницы
//..............................................................................
class itRouter
	{
	public $data, $path, $lang, $controller, $view, $rec_id;
	private $o_lang;


	//..............................................................................
	// конструктор класса - парсит путь страницы и параметры вызова адресной строки
	//..............................................................................	
	public function __construct($url=NULL)
		{
		$this->lang		= NULL;
		$this->controller	= DEFAULT_ROUTER_CONTROLLER;
		$this->view		= DEFAULT_ROUTER_VIEW;
		$this->rec_id		= (isset($_REQUEST['rec_id'])) ? $_REQUEST['rec_id'] : ($_REQUEST['rec_id'] = NULL);
		$this->table_name	= (isset($_REQUEST['table_name'])) ? $_REQUEST['table_name'] : ($_REQUEST['table_name'] = NULL);
		$this->parse();


		if ($url==NULL)
			{
			$this->o_lang = new itLang($this->lang);
			$this->lang = get_const('CMS_LANG');
			if (($this->lang == 'CMS_LANG') or ($this->lang==NULL))
				{
				$_SESSION['error'][]['msg'] = "Error setting language in class <b>itRouter</b>.";
				}
			}
		$_REQUEST['controller'] 	= $this->controller;
		$_REQUEST['view'] 		= $this->view;
		}		

	//..............................................................................
	// крутой парсер пути
	//..............................................................................
	public function parse($url=NULL)
		{
		if ($url==NULL) 
			{
			$url = $_SERVER['REQUEST_URI'];
			}

		// Распаковываем путь
		$this->path = parse_url($url)['path'];
		$this->data = explode("/", $this->path);
		unset ($this->data[0]);
		unset ($this->data[count($this->data)]);

		$num = count($this->data);

		if ($num)
			{
			if (itLang::is_lang($this->data[1]))
				{
				// первый элемент это язык
				$this->lang = $this->data[1];
				if ($num>1)
					{
					$this->controller = $this->data[2];
					}

				if ($num>2)
					{
					$this->view = $this->data[3];
					} else $this->view = $this->controller; 

				if ($num>3)
					{
					$_REQUEST['rec_id'] = $this->rec_id = $this->data[4];
					} 

				} else 	{
					// установим язык, так как он не был указан в пути
					$this->lang = itLang::get_lang();
					$this->controller = $this->data[1];
					if ($num>1)
						{
						$this->view = $this->data[2];
						} else $this->view = $this->controller;
					if ($num>2)
						{
						$_REQUEST['rec_id'] = $this->rec_id = $this->data[3];	
						}
					}
			if (intval($this->view) or isMAC($this->view))
				{
				// установлена переменная номера записи - вид установим равный контроллеру
				$_REQUEST['rec_id'] = $this->rec_id 	= $this->view;
				$this->view = $this->controller;
				}
			}
		}
	
	} // class


?>
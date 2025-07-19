<?
//..............................................................................
// itFocus : класс обрабоки фокуса на выделенный элемент
//..............................................................................
class itFocus
	{
	public $code;
	private $color, $element;

	//..............................................................................
	// конструктор класса - создает установку для отработки фокуса
	//..............................................................................	
	public function __construct()
		{      
		$this->element = '';
		$this->color = DEFAULT_FOCUS_COLOR;
		$this->data = '';
		$this->code = NULL;
		$this->compile();
		}

	//..............................................................................
	// возвращает код сообщения об ошибке
	//..............................................................................
	public function compile()
		{
		if (isset($_SESSION['focus']))
			{
			if (is_array($_SESSION['focus']))
				{
				foreach ($_SESSION['focus'] as $key=>$row)
					{
					$this->$key = $row;
					}
				}
			$this->code = TAB."<div id='focus' rel='{$this->element}' rel-color='{$this->color}' rel-data='{$this->data}'></div>";
			unset($_SESSION['focus']);
			}
		}

	} //class
?>
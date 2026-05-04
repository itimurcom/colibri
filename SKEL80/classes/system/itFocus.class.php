<?php
// itFocus : класс обрабоки фокуса на выделенный элемент
class itFocus
	{
	public $code, $data;
	private $color, $element;

	// конструктор класса - создает установку для отработки фокуса
	public function __construct()
		{      
		$this->element = '';
		$this->color = DEFAULT_FOCUS_COLOR;
		$this->data = '';
		$this->code = NULL;
		$this->compile();
		}

	// возвращает код сообщения об ошибке
	public function compile()
		{
		if (isset($_SESSION['focus']))
			{
			if (is_array($_SESSION['focus']))
				{
				$allowed = ['element', 'color', 'data'];
				foreach ($_SESSION['focus'] as $key=>$row)
					{
					if (in_array($key, $allowed))
						$this->$key = (string)$row;
					}
				}
			$this->code = TAB."<div id='focus' rel='{$this->element}' rel-color='{$this->color}' rel-data='{$this->data}'></div>";
			unset($_SESSION['focus']);
			}
		}

	} //class
?>

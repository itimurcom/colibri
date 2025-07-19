<?
/* класс для управления выбором языка сайта */
//..............................................................................
// itLang : класс доступа к базе данных, таблица и полям в виде массива
//..............................................................................
class itLang
	{
	public $lang, $code;

	//..............................................................................
	// конструктор класса - проверяет установку языка при создании обекта класса
	//..............................................................................	
	public function __construct($lang=NULL)
		{
		if ($lang==NULL)
			{
			$lang = itLang::get_lang();
			} 

		itLang::set_lang($lang);
		$_REQUEST['lang'] = $lang;
		$this->lang = $lang;


		if (file_exists(USER_ENGINE_PATH.'kernel.customs.php'))
			{
			// настраиваем данные пользователя
			include USER_ENGINE_PATH.'kernel.customs.php';
			}

		if (!file_exists(LANG_DIR."common.php"))
			{
//			add_error_message('required file <b>common.php</b>');
			} else include(LANG_DIR."common.php");

		if (!file_exists(LANG_DIR."{$lang}.php"))
			{
			add_error_message("required file <b>{$lang}.php</b>");
			} else include(LANG_DIR."{$lang}.php");

		if (!defined('CMS_LANG'))
			define('CMS_LANG', $lang);
		}

	//..............................................................................
	// проверяет или указанное значение является разрешенным языком	
	//..............................................................................
	static function is_lang($url=NULL)
		{
		global $lang_cat;
		if ($url==NULL)
			{
			$url = get_const('DEFAULT_LANG');
			}

		if ($url==NULL) return;

		if (is_array($lang_cat))
		foreach ($lang_cat as $key=>$row)
			{
			if ($url == $key) return true;
			}
		}

	//..............................................................................
	// возвращает установки языка по сесии
	//..............................................................................
	static function get_lang()
		{
		global $lang_cat;
	
		if (defined('CMS_LANG'))
			self::set_lang(CMS_LANG);

		if (isset($_SESSION[SESSION_PREFIX."_LANG"]) and ($lang_cat[$_SESSION[SESSION_PREFIX."_LANG"]]['allowed']) and (get_const('USE_LANG_SESSION')==1))
			{
			$lang = $_SESSION[SESSION_PREFIX."_LANG"];
			} else 	{
				$lang = DEFAULT_LANG;
				}
		return $lang;
		}

	//..............................................................................
	// устанавливает установки языка сессии
	//..............................................................................
		static function set_lang($lang)
		{
		global $lang_cat;
		if (get_const('USE_LANG_SESSION')==1 AND is_array($lang_cat))
			{
			$exp_time =  time()+SESSION_TIME;
			$_SESSION[SESSION_PREFIX."_LANG"] = $lang_cat[$lang]['short'];
			}
		}

	//..............................................................................
	// возвращает список разрешенных языков
	//..............................................................................
	static function compile($show_current=false)
		{
		global $lang_cat;

		$result = TAB."\t<div class='lang_select_div'>";
		foreach ($lang_cat as $key=>$row)
			{
			// пропускаем текущий язык, если нет принуждения
			if ((($show_current) or ($row['short']!=$_REQUEST['lang'])) and ($row['allowed']==1))
				{
				$result .= get_lang_row($row);
				}
			}
		$result .= TAB."\t</div>";

		return $result;
		}

	//..............................................................................
	// возвращает ссылку на текущий документ с заменой языка
	//..............................................................................
	static function change_link($short, $link=NULL)
		{
		if ($link==NULL)
			{
			$link = $_SERVER['REQUEST_URI'];
			}

		if ($link=="/")
			{
			$link = "/{$_REQUEST['lang']}/";
			}

		return str_replace("/{$_REQUEST['lang']}/", "/$short/", $link);
		}
	} // class


//..............................................................................
// возвращает альтернативное значение языка
//..............................................................................
function get_alt_language($lang=NULL)
	{
	global $lang_cat;
	if ($lang==NULL) $lang = CMS_LANG;

	foreach ($lang_cat as $index=>$row)
		{
	        if (($lang_cat[$index]['allowed']) and ($index!=$lang) and ($row!=''))
			{
			$alt_lang = $row['short'];
			}
		}
	return $alt_lang;
	}


?>
<?php
class itLang
	{
	public $lang, $code;

	private static function language_row($lang)
		{
		global $lang_cat;
		return (is_array($lang_cat) AND isset($lang_cat[$lang]) AND is_array($lang_cat[$lang])) ? $lang_cat[$lang] : NULL;
		}

	private static function default_lang()
		{
		global $lang_cat;
		$default = get_const('DEFAULT_LANG');
		if (self::language_row($default)) return $default;

		if (is_array($lang_cat))
		foreach ($lang_cat as $key=>$row)
			{
			if (is_array($row) AND ready_val($row['allowed'])) return $key;
			}

		return $default;
		}

	private static function current_lang()
		{
		if (isset($_REQUEST['lang']) AND self::language_row($_REQUEST['lang'])) return $_REQUEST['lang'];
		if (defined('CMS_LANG') AND self::language_row(CMS_LANG)) return CMS_LANG;
		return self::default_lang();
		}

	public function __construct($lang=NULL)
		{
		if ($lang==NULL OR !self::language_row($lang))
			{
			$lang = itLang::get_lang();
			}

		itLang::set_lang($lang);
		$_REQUEST['lang'] = $lang;
		$this->lang = $lang;

		if (file_exists(USER_ENGINE_PATH.'kernel.customs.php'))
			{
			include USER_ENGINE_PATH.'kernel.customs.php';
			}

		if (!file_exists(LANG_DIR."common.php"))
			{
			} else include(LANG_DIR."common.php");

		if (!file_exists(LANG_DIR."{$lang}.php"))
			{
			add_error_message("required file <b>{$lang}.php</b>");
			} else include(LANG_DIR."{$lang}.php");

		if (!defined('CMS_LANG'))
			define('CMS_LANG', $lang);
		}

	static function is_lang($url=NULL)
		{
		if ($url==NULL)
			{
			$url = get_const('DEFAULT_LANG');
			}

		if ($url==NULL) return;
		return self::language_row($url) ? true : NULL;
		}

	static function get_lang()
		{
		$session_key = SESSION_PREFIX."_LANG";
		if (defined('CMS_LANG') AND self::language_row(CMS_LANG))
			self::set_lang(CMS_LANG);

		if ((get_const('USE_LANG_SESSION')==1)
			AND isset($_SESSION[$session_key])
			AND ($row = self::language_row($_SESSION[$session_key]))
			AND ready_val($row['allowed']))
			{
			$lang = $_SESSION[$session_key];
			} else 	{
				$lang = self::default_lang();
				}
		return $lang;
		}

	static function set_lang($lang)
		{
		if (get_const('USE_LANG_SESSION')==1 AND ($row = self::language_row($lang)))
			{
			$_SESSION[SESSION_PREFIX."_LANG"] = ready_val($row['short'], $lang);
			}
		}

	static function compile($show_current=false)
		{
		global $lang_cat;

		$result = TAB."\t<div class='lang_select_div'>";
		if (is_array($lang_cat))
		foreach ($lang_cat as $key=>$row)
			{
			if (!is_array($row) OR !isset($row['short'])) continue;
			$current_lang = self::current_lang();
			if ((($show_current) or ($row['short']!=$current_lang)) and ready_val($row['allowed'])==1)
				{
				$result .= get_lang_row($row);
				}
			}
		$result .= TAB."\t</div>";

		return $result;
		}

	static function change_link($short, $link=NULL)
		{
		if ($link==NULL)
			{
			$link = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
			}

		$parts = parse_url($link);
		$path = (is_array($parts) AND isset($parts['path'])) ? $parts['path'] : '/';
		$query = (is_array($parts) AND isset($parts['query'])) ? '?'.$parts['query'] : '';
		$fragment = (is_array($parts) AND isset($parts['fragment'])) ? '#'.$parts['fragment'] : '';
		$segments = trim($path, '/') === '' ? [] : explode('/', trim($path, '/'));

		if (isset($segments[0]) AND self::is_lang($segments[0]))
			{
			array_shift($segments);
			}

		$tail = count($segments) ? implode('/', $segments).'/' : '';
		return "/{$short}/{$tail}{$query}{$fragment}";
		}
	} // class

function get_alt_language($lang=NULL)
	{
	global $lang_cat;
	if ($lang==NULL) $lang = defined('CMS_LANG') ? CMS_LANG : itLang::get_lang();

	$alt_lang = NULL;
	if (is_array($lang_cat))
	foreach ($lang_cat as $index=>$row)
		{
		if (is_array($row) AND ready_val($row['allowed']) AND ($index!=$lang) AND ($row!=''))
			{
			$alt_lang = ready_val($row['short'], $index);
			}
		}
	return $alt_lang;
	}

?>

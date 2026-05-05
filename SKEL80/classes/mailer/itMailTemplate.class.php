<?php
// ================ CRC ================
// version: 1.35.03
// hash: c6536d276b06a6ae3c90aef928a457b2448f1328ac4e8873cf50395549d37e08
// date: 10 March 2021  9:27
// ================ CRC ================
definition([
	'DEFAULT_MAIL_TEMPLATE'		=> 'default',		
	]);

//..............................................................................
// itMailTemplate : класс для работы c шаблонами писем
//..............................................................................
class itMailTemplate
	{
	//..............................................................................
	// конструктор класса
	//..............................................................................
	public function __construct($options=NULL)
		{
		}

	//..............................................................................
	// чистит код и заменяет нужные [CODE] команды
	//..............................................................................
	static function _strip_tags(&$options)
		{
		$options = is_array($options) ? $options : [];
		$options['prepared'] = htmlspecialchars_decode(isset($options['prepared']) ? (string)$options['prepared'] : '');
		if (function_exists('mailtemplate_script'))
			{
			mailtemplate_script($options);	
			}
			
		$options['result'] = mstr_replace([
			'[CODE]' => $options['prepared'],
			], isset($options['result']) ? (string)$options['result'] : '');
		
		return 	$options['result'];	
		}

	//..............................................................................
	// возвращает название шаблона
	//..............................................................................
	static function _subj($pattern_id=NULL, $table_name=DEFAULT_MAILINGPATTERN_TABLE)
		{
		if ($row=itMySQL::_get_rec_from_db($table_name, $pattern_id))
			{
			return isset($row['subject']) ? $row['subject'] : NULL;
			}
		}	

	//..............................................................................
	// готовит код письма к отправке по данным
	//..............................................................................
	static function _code(&$options, $links=true)
		{
//		$table_name = ready_val($options['table_name'], DEFAULT_MAILINGPATTERN_TABLE);
		$options = is_array($options) ? $options : [];
		$tpl = ready_value(isset($options['tpl']) ? $options['tpl'] : DEFAULT_MAIL_TEMPLATE, DEFAULT_MAIL_TEMPLATE);
		$options['prepared'] = isset($options['prepared']) ? (string)$options['prepared'] : '';

		if ($links) { $options['prepared'] = self::_links($options['prepared']); }

		ob_start();
		include "themes/".CMS_THEME."/mail.{$tpl}.".CMS_LANG.".php";
		$options['result'] = ob_get_clean();
		
		self::_strip_tags($options);
		return $options['result'];
		}
	
	//..............................................................................
	// компилирует письмо для user_id по указанному template_id из базы
	//..............................................................................
	static function _prepare(&$options)
		{
		$options = is_array($options) ? $options : [];
		$table_name = ready_value(isset($options['table_name']) ? $options['table_name'] : DEFAULT_MAILINGPATTERN_TABLE, DEFAULT_MAILINGPATTERN_TABLE);
		if (isset($options['pattern_id']) AND ($row=itMySQL::_get_rec_from_db($table_name, $options['pattern_id'])))
			{
			$options['prepared'] = isset($row['code']) ? $row['code'] : '';
			$options['subject'] = isset($row['subject']) ? $row['subject'] : '';
			self::_code($options);
			}	
		}
		
	//..............................................................................
	// распаковывает ссылки в тексте
	//..............................................................................
	static function _links($text)
		{
		$text = (string)$text;
		// компилипуем ссылки и изображения
		$text = preg_replace_callback('/"[^"]*"(*SKIP)(*FAIL)|(http[s]?:[^\s|<|\'|\"]*)/i', function ($m)
			{
			if (!preg_match('(jpg|png|jpeg|bmp|gif)', $m[0])) 
				{
				$link = (strlen($m[0])>get_const('MAXIMUM_LINK_LEN')) ? get_const('BUTTON_LINK') : striphttp($m[0]);
//&#128279;&nbsp;
				return "<a style='color:blue;' href='{$m[0]}' target='_blank' title='{$m[0]}'>{$link}</a>";
				} else return "<div class='galcell'><img style='max-width:100%;width:100%;height:auto;' src='".trim($m[0])."'></div>";
			}, $text);

//		$text = (!is_null($rules)) ? mstr_replace($rules, $text) : $text;
//		file_put_contents('test', $text);
		return $text;
		}
	}
?>

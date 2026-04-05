<?php
// ================ CRC ================
// version: 1.15.04
// hash: 016f9ab69812cdfbc75c6b849cfb75031408aa2bd0d74bbd6e1e0c116394ec84
// date: 10 March 2021  9:27
// ================ CRC ================
global $openclose_counter;
$openclose_counter = (function_exists('rand_id')) ? rand_id() : 0;
definition([
	// itOpenclose
	'DEFAULT_OPENCLOSE_STATE' 	=> 'close',
	'DEFAULT_OPENCLOSE_EFFECT'	=> 'swing',
	'DEFAULT_OPEN_CLASS'		=> 'blue',
	'DEFAULT_CLOSE_CLASS'		=> 'blue',
	'DEFAULT_OPENCLOSE_LENGTH'	=> NULL,
	'DEFAULT_OPENCLOSE_EFFECT'	=> 'swing',
	'DEFAULT_OPENCLOSE_DURATION'	=> 300,
	'DEFAULT_OPENCLOSE_STATE'	=> 'open',
	'OPEN_ALL_OPENCLOSE'		=> 'open',
	'CLOSE_ALL_OPENCLOSE'		=> 'close',
	'DEFAULT_OPENCLOSE_ALERT'	=> true,	
	]);
//..............................................................................
// itOpenClose : класс автоматизации акордеонов любого контента
//..............................................................................
class itOpenClose
	{
	public $name, $state, $open_text, $open_class, $close_text, $close_class, $length, $replace, $code, $set, $class, $alert;
	//..............................................................................
	// конструктор класса - создает элемент автоматического выбора данных
	//..............................................................................
	// options  
	//
	//	'content' 	=> отображаемый код, который прячется в контейтер
	//
	//	'state'		=> состояние при загрузке open/close (по умолчанию close
	//
	//	'length'	=> количество символов, которое ограничивает тект 
	//				или NULL для блока
	//
	//	'open'		=> элемент кнопки 'открыть' ['text', 'class']
	//
	//	'close'		=> элемент кнопки 'закрыть' ['text', 'class']
	//
	//..............................................................................
	public function __construct($code=NULL, $options=NULL)
		{
		global $openclose_counter, $_USER;
		$openclose_counter++;


		$this->name = "openclose-$openclose_counter";
		$this->content 		= !is_null($code) 			? $code	 		: "";
		$this->length 		= is_null(ready_val($options['length']))
										? $options['length'] 		: get_const('DEFAULT_OPENCLOSE_LENGTH');


		$this->set 		= isset($options['set']) 		? $options['set'] 		: NULL;
		$this->state 		= isset($options['state']) 		? $options['state'] 		: 
				(!is_null($this->set) 	? itSettings::get($this->set, $_USER->id(), get_const('DEFAULT_OPENCLOSE_STATE'))
							: get_const('DEFAULT_OPENCLOSE_STATE'));

		$this->class 		= isset($options['class']) 		? " {$options['class']}" 	: '';

		$this->effect 		= isset($options['effect']) 		? $options['effect'] 		: get_const('DEFAULT_OPENCLOSE_EFFECT');
		$this->duration		= isset($options['duration']) 		? $options['duration'] 		: get_const('DEFAULT_OPENCLOSE_DURATION');
		$this->alert		= isset($options['alert']) 		? $options['alert'] 		: get_const('DEFAULT_OPENCLOSE_ALERT');

		$this->open_text	= isset($options['open']['text'])	? $options['open']['text'] 	: (($this->length==NULL) ? get_const('OPEN_TEXT_TITLE') : get_const('MORE_TEXT_TITLE'));
		$this->open_class	= isset($options['open']['class'])	? $options['open']['class'] 	: get_const('DEFAULT_OPEN_CLASS');

		$this->close_text	= isset($options['close']['text'])	? $options['close']['text'] 	: (($this->length==NULL) ? get_const('CLOSE_TEXT_TITLE') : get_const('LESS_TEXT_TITLE'));
		$this->close_class	= isset($options['close']['class'])	? $options['close']['class'] 	: get_const('DEFAULT_CLOSE_CLASS');

		
		$this->compile();
		}
		
	//..............................................................................
	// конструктор готового кода
	//..............................................................................
	static function _create($code=NULL, $options=NULL)
		{
		$o_close = new itOpenClose($code, $options);
		$code = $o_close->code();
		unset($o_close);
		return $code;
		}

	//..............................................................................
	// генерирует код календаря на основе установленных параметров и заносит в code
	//..............................................................................
	public function compile()
		{
		global $_USER;
		$this->code = '';
		$sw_text = ($this->state=='close') ? $this->open_text : $this->close_text;

		// проверим установлен ли флаг обрезки длинного сообщения (если да - это кнопка больше/меньше
		if (!is_null($this->length))
			{
			// определяем есть ли что сокращать
			$size = iconv_strlen(html2txt($this->content, false, true));

			if ($size<=$this->length)
				{
				// нечего сокращать просто выдаем контент
				$this->code = $this->content;
				} else	{
					$this->state = 'close';
					$text = get_str_cut(html2txt($this->content,false), $this->length);
					// создаем кнопку больше/меньше
					$this->code = 
						TAB."<div class='openclose{$this->class}' id='{$this->name}'>".
						TAB."<span class='less'>".
						$text.
						TAB."</span>".
						TAB."<div class='block' rel-eff='{$this->effect}' rel-dur='{$this->duration}'>".
						$this->content.
						TAB."</div>".
						TAB."<div class='switch' rel-state='{$this->state}' rel='{$this->open_text},{$this->open_class},{$this->close_text},{$this->close_class}'>{$sw_text}</div>".
						TAB."</div>";
					}
			} else	{
				if (!is_null($this->set))
					{
					$data = simple_encrypt(serialize(array(
						'set' 		=> $this->set,
						'value'		=> (($this->state=='open') ? 'close' : 'open'),
						'user_id'       => $_USER->id(),
						)));		

					$dataset = "data-set='{$data}' ";
					} else $dataset = '';

				// создаем кнопку после сокращенного текст
				$this->code = 
					TAB."<div class='openclose{$this->class}' id='{$this->name}'>".
					TAB."<div class='switch'{$dataset} rel-state='{$this->state}' rel='{$this->open_text},{$this->open_class},{$this->close_text},{$this->close_class}' rel-alert='{$this->alert}'>{$sw_text}</div>".
					TAB."<div class='block' rel-eff='{$this->effect}' rel-dur='{$this->duration}'>".
					$this->content.
					TAB."</div>".
					TAB."</div>";
				}
		}
		
	//..............................................................................
	// возвращает код кнопки открыть все
	//..............................................................................
	static function openAllbtn($options=NULL)
		{
		$color = isset($options['color']) ? $options['color'] : '';
		$class = isset($options['class']) ? " {$options['class']}" : '';
		$rel = isset($options['element']) ? " rel='{$options['element']}'" : '';		
		return 
			TAB."<span class='opencloseall {$color}{$class}' onclick='openclose_openAll(this);'{$rel}>".get_const('OPEN_ALL_OPENCLOSE')."</span>";
		}

	//..............................................................................
	// возвращает код кнопки открыть все
	//..............................................................................
	static function closeAllbtn($options=NULL)
		{
		$color = isset($options['color']) ? $options['color'] : '';
		$class = isset($options['class']) ? " {$options['class']}" : '';
		$rel = isset($options['element']) ? " rel='{$options['element']}'" : '';
		return 
			TAB."<span class='opencloseall {$color}{$class}' onclick='openclose_closeAll(this);'{$rel}>".get_const('CLOSE_ALL_OPENCLOSE')."</span>";
		}

	//..............................................................................
	// возвращает код аккордеона
	//..............................................................................
	public function code()
		{
		return $this->code;
		}

	}
?>
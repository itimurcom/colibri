<?
//..............................................................................
// itBlock : класс настраиваемого блока на странице, привязка к контенту
//..............................................................................
class itBlock
	{
	public $table_name, $rec_id, $options, $data, $code, $no_data, $no_lang, $no_moderate, $no_avatar;
	public $subtitle, $description, $og_image, $url, $editor;

	//..............................................................................
	// конструктор класса создает представление контента на странице
	//..............................................................................
	//
	//   options:	'no_title'	=> убирает заголовок, если установлен
	//		'no_data'	=> убирает дату в заголовке
	//		'lang_xml'	=> отображается в указанном языке (все)
	//		'table_name'	=> DEFAULT_BLOCK_TABLE // таблица блоков
	//		'editor_name'	=> DEFAULT_CONTENT_TABLE // таблица контента
	//			
	//
	//..............................................................................
	public function __construct($rec_id=NULL, $options=NULL )
		{
		$this->options	= $options;
		$this->rec_id	= $rec_id;

		if (!isset($options['table_name']))
			{
			$this->table_name = DEFAULT_BLOCK_TABLE;
			}

		if (!isset($options['content_name']))
			{
			$this->editor_name = DEFAULT_CONTENT_TABLE;
			}

		$this->no_data		= ready_val($options['no_data'], DEFAULT_NODATE);
		$this->no_title		= ready_val($options['no_title'], DEFAULT_NOTITLE);		
		$this->no_lang		= ready_val($options['no_lang'], DEFAULT_NOLANG);
		$this->no_avatar	= ready_val($options['no_avatar'], DEFAULT_NOAVATAR);
		$this->no_moderate	= ready_val($options['no_moderate'], DEFAULT_NOMODERATE);
		$this->no_related	= ready_val($options['no_related'], DEFAULT_NORELATED);				
		$this->edclass		= ready_val($options['edclass'], DEFAULT_EDCLASS);			

		$this->data = itMySQL::_get_rec_from_db($this->table_name, $this->rec_id);

		if (!is_array($this->data))
			{
			// вставим новую запись
			itMySQL::_insert_rec($this->table_name, ['id' => $this->rec_id]);
               		$this->data = itMySQL::_get_rec_from_db($this->table_name, $this->rec_id);
			}
		$this->compile();
		}

	//..............................................................................
	// генерирует html код блока и заносит его в качестве результата
	//..............................................................................	
	public function compile()
		{
		global $_USER;

		$this->code = NULL;
                $this->description = NULL;
                $this->og_image = DEFAULT_OG_IMAGE;
		$this->subtitle = NULL;

		if ($this->data['content_id']!==NULL)
			{
			$this->editor = new itEditor([
				'table_name'	=> $this->editor_name,
				'rec_id'	=> $this->data['content_id'],
				'edclass'	=> $this->edclass,
				]);
				
			if ($_USER->is_logged() OR (!isset($this->options['no_title']) AND ($this->data['content_id']!==NULL)))
				{
				$this->code .= $this->editor->get_title($this->no_data, $this->no_lang, $this->no_moderate, $this->no_avatar);
				$this->subtitle = html2txt($this->editor->title);
				}
			}

		$this->code .=
			TAB."<div class='ed_div'>";

		if ($this->data['content_id']!==NULL)
			{
			$this->description = $this->editor->description();
			$this->og_image = $this->editor->og_image();
			$this->editor->compile();
			$this->code.= $this->editor->code();
			}

		$this->code .= 
			get_block_content_event($this->data).
			TAB."</div>";
	      	}


	//..............................................................................
	// возвращает код скомпилированного блока
	//..............................................................................	
	public function code()
		{
		return $this->code;
		}

	//..............................................................................
	// id блока ищз глобального массива
	//..............................................................................	
	static function _id($key=NULL)
		{
		global $_BLOCK;
		return isset($_BLOCK[$key])
			? $_BLOCK[$key]['id']
			: NULL;
		}


	}// class
?>

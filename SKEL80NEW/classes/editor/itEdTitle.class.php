<?
global $ed_title_counter;
$ed_title_counter = (function_exists('rand_id')) ? rand_id() : time();
//-------------------------------------------------------------------------------------
// itEdTitle : класс обработки заколовка новости
//-------------------------------------------------------------------------------------
class itEdTitle
	{
	public $data, $code, $table_name, $rec_id, $name, $class;
	public $no_cache, $no_title, $no_date, $no_lang, $no_moderate, $no_avatar, $no_related;	
	//..............................................................................
	// конструктор класса - создает привязку редактора к записи в базе данных
	//..............................................................................
	public function __construct($data=NULL)
		{
		global $_USER, $ed_title_counter;
		$ed_title_counter++;

		$this->data = $data;
		$this->data['table_name'] 	= $this->table_name 	= $this->data['table_name'];
		$this->data['rec_id'] 		= $this->rec_id 	= $this->data['rec_id'];
		$this->name 		= isset($data['name']) ? "{$data['name']}-title" : "ed-title-{$ed_title_counter}";
		$this->no_date 		= isset($data['no_date']) 	? $data['no_date'] 	: DEFAULT_NODATE;
		$this->no_lang 		= isset($data['no_lang']) 	? $data['no_lang'] 	: DEFAULT_NOLANG;
		$this->no_moderate	= isset($data['no_moderate'])	? $data['no_moderate'] 	: DEFAULT_NOMODERATE;
		$this->no_avatar	= isset($data['no_avatar']) 	? $data['no_avatar'] 	: DEFAULT_NOAVATAR;
		$this->no_title		= isset($data['no_title']) 	? $data['no_title'] 	: DEFAULT_TITLE;
		$this->no_related	= isset($data['no_related']) 	? $data['no_related'] 	: DEFAULT_NORELATED;
		$this->class		= isset($data['class']) 	? " {$data['class']}" 	: NULL;
		}


	//..............................................................................
	// просмотр аватарки для титула
	//..............................................................................	
	public function _view_avatar()
		{
		global $editor_blocks, $pic_tech;
		$class = isset($pic_tech['TITAVA']) ? 'TITAVA' : 'ED_NEWS';		
		return (!$this->no_avatar AND isset($editor_blocks['avatar']) AND !empty($this->data['avatar']))
			? mstr_replace([
			'[ID]'		=> "{$this->name}-avatar",
			'[VALUE]'	=> (!empty($this->data['avatar'])
						?	get_thumbnail($this->data['avatar'], $class)
						:	''),

			'[EDIT]'	=> '',
			], TAB.$editor_blocks['avatar']['code'])
			: "";
		}
		
	//..............................................................................
	// редактирование аватарки для титула
	//..............................................................................	
	public function _edit_avatar()
		{
		global $editor_blocks, $pic_tech;
		$class = isset($pic_tech['TITAVA']) ? 'TITAVA' : 'ED_NEWS';
		return (isset($editor_blocks['avatar']) AND ($this->no_avatar==false) AND !empty($this->data['avatar']))
			? mstr_replace([
			'[ID]'		=> "{$this->name}-avatar",
			'[VALUE]'	=> (!empty($this->data['avatar'])
						?	get_thumbnail($this->data['avatar'], $class)
						:	''),
			'[EDIT]'	=> ED_DEVIDER,
			'[BUTTONS]'	=> (!empty($this->data['avatar'])
						? 	get_content_avatar_x_event($this->data).
							get_content_avatar_button($this->data)
						: 	''),
			], TAB.$editor_blocks['avatar']['code'])
			: NULL;
		}

	//..............................................................................
	// просмотр титула
	//..............................................................................	
	public function _view()
		{
		global $editor_blocks;
		return mstr_replace([
			'[VALUE]'	=> (!$this->no_title
						? itEditor::title($this->data)
						: ''),
			'[ID]'		=> $this->name,
			'[CLASS]'	=> $this->class,
			'[BUTTONS]'	=> '',
			'[EDIT]'	=> '',
			'[AVATAR]'	=> $this->_view_avatar(),
			], TAB.$editor_blocks['title']['code']);	
		}

	//..............................................................................
	// редактирование титула
	//..............................................................................	
	public function _edit()
		{
		global $editor_blocks;
		return mstr_replace([
			'[VALUE]'	=> ((!$this->no_title)
						? itEditor::title($this->data)
						: ''),
			'[ID]'		=> $this->name,
			'[CLASS]'	=> $this->class,
			'[BUTTONS]'	=> 
				((!$this->no_moderate AND function_exists('get_content_remove_event'))
					? 	get_content_remove_event($this->data)
					: 	NULL).
				((!$this->no_lang AND function_exists('get_content_lang_event'))
					? 	get_content_lang_event($this->data)
					: 	NULL).
				get_content_title_event($this->data).
				
				((!$this->no_avatar AND function_exists('get_content_avatar_button') AND empty($this->data['avatar']))
					? 	get_content_avatar_button($this->data)
					: 	NULL).
				((!$this->no_related AND function_exists('get_related_conents_event'))
					? 	get_related_conents_event($this->data)
					: 	NULL).
				((!$this->no_date AND function_exists('get_content_date_event'))
					? 	get_content_date_event($this->data)
					: 	NULL),
			'[EDIT]'	=> ' edit',
			'[AVATAR]'	=> $this->_edit_avatar(),
			], TAB.$editor_blocks['title']['code']);	
		}

	//..............................................................................
	// разыменовывает поле титула
	//..............................................................................	
	public function compile()
		{
		global $_USER;
		$this->code = '';

		if (!array_key_exists('title_xml', $this->data)) return;
		
		$this->code = $_USER->is_logged(itEditor::moderators())
			? $this->_edit()
			: $this->_view();		
		}

	//..............................................................................
	// возвращает код текстового блока
	//..............................................................................
	public function code()
		{
		return $this->code;
		}

	}
?>
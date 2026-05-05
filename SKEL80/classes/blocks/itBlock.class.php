<?php
class itBlock
	{
	public $table_name, $rec_id, $options, $data, $code, $no_data, $no_lang, $no_moderate, $no_avatar;
	public $subtitle, $description, $og_image, $url, $editor;
	public $editor_name, $no_title, $no_related, $edclass;

	protected static function row_value($row, $key, $default=NULL)
		{
		return (is_array($row) AND array_key_exists($key, $row)) ? $row[$key] : $default;
		}

	public function __construct($rec_id=NULL, $options=NULL )
		{
		$options = is_array($options) ? $options : [];
		$this->options	= $options;
		$this->rec_id	= $rec_id;

		$this->table_name = ready_value(self::row_value($options, 'table_name'), DEFAULT_BLOCK_TABLE);
		$this->editor_name = ready_value(self::row_value($options, 'content_name'), DEFAULT_CONTENT_TABLE);

		$this->no_data		= ready_value(self::row_value($options, 'no_data'), DEFAULT_NODATE);
		$this->no_title		= ready_value(self::row_value($options, 'no_title'), DEFAULT_NOTITLE);
		$this->no_lang		= ready_value(self::row_value($options, 'no_lang'), DEFAULT_NOLANG);
		$this->no_avatar	= ready_value(self::row_value($options, 'no_avatar'), DEFAULT_NOAVATAR);
		$this->no_moderate	= ready_value(self::row_value($options, 'no_moderate'), DEFAULT_NOMODERATE);
		$this->no_related	= ready_value(self::row_value($options, 'no_related'), DEFAULT_NORELATED);
		$this->edclass		= ready_value(self::row_value($options, 'edclass'), DEFAULT_EDCLASS);

		$this->data = itMySQL::_get_rec_from_db($this->table_name, $this->rec_id);

		if (!is_array($this->data))
			{
			itMySQL::_insert_rec($this->table_name, ['id' => $this->rec_id]);
               		$this->data = itMySQL::_get_rec_from_db($this->table_name, $this->rec_id);
			}
		$this->data = is_array($this->data) ? $this->data : [];
		$this->compile();
		}

	public function compile()
		{
		global $_USER;

		$this->code = NULL;
                $this->description = NULL;
                $this->og_image = DEFAULT_OG_IMAGE;
		$this->subtitle = NULL;
		$this->editor = NULL;
		$content_id = self::row_value($this->data, 'content_id');
		$user_logged = (is_object($_USER) AND method_exists($_USER, 'is_logged')) ? $_USER->is_logged() : false;

		if (!is_null($content_id))
			{
			$this->editor = new itEditor([
				'table_name'	=> $this->editor_name,
				'rec_id'	=> $content_id,
				'edclass'	=> $this->edclass,
				]);

			if ($user_logged OR (!isset($this->options['no_title']) AND !is_null($content_id)))
				{
				$this->code .= $this->editor->get_title($this->no_data, $this->no_lang, $this->no_moderate, $this->no_avatar);
				$this->subtitle = html2txt($this->editor->title);
				}
			}

		$this->code .=
			TAB."<div class='ed_div'>";

		if (!is_null($content_id) AND is_object($this->editor))
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

	public function code()
		{
		return $this->code;
		}

	}// class
?>

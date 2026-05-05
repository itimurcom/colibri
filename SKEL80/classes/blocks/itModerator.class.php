<?php
global $moder_count;
$moder_count = (function_exists('rand_id')) ? rand_id() : time();

global $plug_css;
if (!isset($plug_css) || !is_array($plug_css)) $plug_css = [];
$plug_css[] = 'class.itModerator.css';

// itModerator : класс admin панели модерируемых материалов выбранной таблицы
class itModerator
	{
	public $table_name, $data, $code, $element_id, $statuses, $allowed, $order, $set;

	public static function option_value($options, $key, $default=NULL)
		{
		return (is_array($options) && array_key_exists($key, $options)) ? $options[$key] : $default;
		}

	public static function row_value($row, $key, $default=NULL)
		{
		return (is_array($row) && array_key_exists($key, $row)) ? $row[$key] : $default;
		}

	public static function positive_id($value)
		{
		$value = (int)$value;
		return $value > 0 ? $value : NULL;
		}

	public static function user_id()
		{
		global $_USER;
		return (is_object($_USER) && method_exists($_USER, 'id')) ? $_USER->id() : NULL;
		}

	public static function normalized_statuses($statuses)
		{
		return is_array($statuses) ? $statuses : [];
		}

	public static function status_meta($statuses, $group_key)
		{
		return (is_array($statuses) && isset($statuses[$group_key]) && is_array($statuses[$group_key]))
			? $statuses[$group_key]
			: [];
		}

	// конструктор класса - создает объект, который получает данные из базы
	public function __construct($table_name=DEFAULT_MODERATOR_TABLE, $options=NULL)
		{
		global $moder_count;
		global $statuses;
		$moder_count++;
		$this->element_id 	= "moderator-{$moder_count}";

		if (is_array($table_name))
			{
			$options = $table_name;
			$table_name = self::option_value($options, 'table', DEFAULT_MODERATOR_TABLE);
			}

		$options = is_array($options) ? $options : [];
		$this->order		= self::option_value($options, 'order', DEFAULT_MODERATOR_ORDER);
		$this->table_name = !empty($table_name) ? $table_name : DEFAULT_MODERATOR_TABLE;
		$this->data = [];
		$tmp_arr = itMySQL::_get_arr_from_db($this->table_name, "`".DEFAULT_STATUS_FIELD."` NOT IN (".NOT_PUBLISHED_STATUSES.")", $this->order);
		if (is_array($tmp_arr))
			foreach ($tmp_arr as $key=>$row)
				{
				if (!is_array($row)) continue;
				$status = self::row_value($row, 'status');
				$rec_id = self::positive_id(self::row_value($row, 'id'));
				if ($status===NULL || $rec_id===NULL) continue;
				$this->data[$status][$rec_id] = $row;
				$this->data[$status][$rec_id]['table_name'] = $this->table_name;
				$this->data[$status][$rec_id]['rec_id'] = $rec_id;
				}
		$this->set = self::option_value($options, 'set', NULL);
		
		$this->statuses	= isset($options['statuses']) && is_array($options['statuses']) ? $options['statuses'] : self::normalized_statuses($statuses);
		$this->allowed	= isset($options['allowed']) && is_array($options['allowed']) ? $options['allowed'] : (defined('MODERATED_STATUSES') ? (is_array(@unserialize(MODERATED_STATUSES)) ? @unserialize(MODERATED_STATUSES) : []) : []);
		$this->compile();
		}

	// генерирует html код административной панели и заносит его в $code
	public function compile()
		{
		$result = '';

		$func = 'get_'.$this->table_name.'_moderate_code';
		if (function_exists($func))
			{
			if (is_array($this->data) && count($this->data) && is_array($this->allowed) && count($this->allowed))
				{
				$result = html_comment('Начало блока модерации').
					TAB."\t<div class='moderate_panel list'>";
				foreach ($this->allowed as $group_key)
					if (isset($this->data[$group_key]) and is_array($this->data[$group_key]) and count($this->data[$group_key]))
					{
					$status_meta = self::status_meta($this->statuses, $group_key);
					$status_title = self::row_value($status_meta, 'title', 'STATUS_'.$group_key);
					$status_name = self::row_value($status_meta, 'name', $group_key);
					$status_killall = (int)self::row_value($status_meta, 'killall', 0);
					$status_close = self::row_value($status_meta, 'close');
					$status_open = self::row_value($status_meta, 'open');
					$status_state = self::row_value($status_meta, 'state');
					$num = count($this->data[$group_key]);

					$moderate_title = get_const($status_title)." ( $num )";

					$moderate_code = 						
						TAB."\t<div class='moderate_group' id='moder-{$this->element_id}-{$status_name}'>".
						$func($this->data[$group_key]).
						TAB."\t</div>".
						((($status_killall==1) AND function_exists('get_moderate_killall_event')) ? TAB.get_moderate_killall_event($this->data[$group_key]) : '');

					$options = array(
						'set'		=> "{$this->set}-{$group_key}",
						'length'	=> NULL,
						'open' 		=> ['text' => "{$moderate_title} &#9658;", 'class' => ready_val($status_close)],
						'close' 	=> ['text' => "{$moderate_title} &#9660;", 'class' => ready_val($status_open)],
						'state'		=> (empty($this->set) ? $status_state : itSettings::get("{$this->set}-{$group_key}", self::user_id(), $status_state)),
						'alert'		=> false,
						);

					$o_close = new itOpenClose($moderate_code, $options);

					$result .=  $o_close->code();
					unset($o_close);
					}
			$result .= TAB."\t</div>".
				html_comment('Конец блока модерации');
			}
			$this->code = $result;			
			} else	{
				add_error_message("<b>{$func}()</b> ".get_const('FUNCTION_NOT_EXISTS'));
				$this->code = '';
				}

		}

	// обновляет статус записи по полученным данным
	static function set_status($table_name, $rec_id, $value, $field='status')
		{
		$rec_id = self::positive_id($rec_id);
		if (empty($table_name) || $rec_id===NULL || empty($field)) return false;
		$db = new itMySQL();
		$db->update_value_db($table_name, $rec_id, $value, $field);
		unset($db);
		return true;
		}

	// обновляет тип контента и сбрасывает категорию материала
	static function content_type($table_name, $rec_id, $value, $field='content_type')
		{
		$rec_id = self::positive_id($rec_id);
		if (empty($table_name) || $rec_id===NULL || empty($field)) return false;
		$db = new itMySQL();
		$db->update_value_db($table_name, $rec_id, $value, $field);
		$db->update_value_db($table_name, $rec_id, 0, 'category_id');
		unset($db);
		return true;
		}


	// генерирует код из переменной
	public function code()
		{
		return $this->code;
		}

	} // class
?>

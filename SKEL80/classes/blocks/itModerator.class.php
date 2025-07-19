<?php
// ================ CRC ================
// version: 1.15.03
// hash: c8e94758ccf6b60cdc9af4c8347571192a3de8c7d7c515abd4cccf8a329f97b5
// date: 09 September 2020 16:28
// ================ CRC ================
global $moder_count;
$moder_count = (function_exists('rand_id')) ? rand_id() : time();

global $plug_css;
$plug_css[] = 'class.itModerator.css';

//..............................................................................
// itModerator : класс admin панели модерируемых материалов выбранной таблицы
//..............................................................................
class itModerator
	{
	public $table_name, $data, $code, $element_id, $statuses, $allowed, $order;

	//..............................................................................
	// конструктор класса - создает объект, который получает данные из базы
	//..............................................................................	
	public function __construct($table_name=DEFAULT_MODERATOR_TABLE, $options=NULL)
		{
		global $moder_count;
		global $statuses;
		$moder_count++;
		$this->element_id 	= "moderator-{$moder_count}";
		$this->order		= isset($options['order']) ? $options['order'] : DEFAULT_MODERATOR_ORDER;

		if (is_array($table_name))
			{
			$options = $table_name;
			$table_name = isset($options['table']) ? $options['table'] : DEFAULT_MODERATOR_TABLE;
			}
		$this->table_name = $table_name;
		$tmp_arr = itMySQL::_get_arr_from_db($table_name, "`".DEFAULT_STATUS_FIELD."` NOT IN (".NOT_PUBLISHED_STATUSES.")", $this->order);
		if (is_array($tmp_arr))
			foreach ($tmp_arr as $key=>$row)
				{
				$this->data[$row['status']][$row['id']] = $row;
				$this->data[$row['status']][$row['id']]['table_name'] = $table_name;
				$this->data[$row['status']][$row['id']]['rec_id'] = $row['id'];
				}
		$this->set = isset($options['set']) ? $options['set'] : NULL;
		
		$this->statuses	= isset($options['statuses']) ? $options['statuses'] : $statuses;
		$this->allowed	= isset($options['allowed']) ? $options['allowed'] : (defined('MODERATED_STATUSES') ? unserialize(MODERATED_STATUSES) : NULL);
		$this->compile();
		}

	//..............................................................................
	// генерирует html код административной панели и заносит его в $code
	//..............................................................................	
	public function compile()
		{
		global $_USER;
		$result = '';

		
		$func = 'get_'.$this->table_name.'_moderate_code';
		if (function_exists($func))
			{
		if (count($this->data))
			{
			$result = html_comment('Начало блока модерации').
				TAB."\t<div class='moderate_panel list'>";
			foreach ($this->allowed as $group_key)
				if (isset($this->data[$group_key]) and count($this->data[$group_key]))
					{
					if ($num = count($this->data[$group_key]))

					$moderate_title = get_const($this->statuses[$group_key]['title'])." ( $num )";

					$moderate_code = 						
						TAB."\t<div class='moderate_group' id='moder-{$this->element_id}-{$this->statuses[$group_key]['name']}'>".
						$func($this->data[$group_key]).
						TAB."\t</div>".
						((($this->statuses[$group_key]['killall']==1) AND function_exists('get_moderate_killall_event')) ? TAB.get_moderate_killall_event($this->data[$group_key]) : '');

					$options = array(
						'set'		=> "{$this->set}-{$group_key}",
						'length'	=> NULL,
						'open' 		=> ['text' => "{$moderate_title} &#9658;", 'class' => ready_val($this->statuses[$group_key]['close'])],
						'close' 	=> ['text' => "{$moderate_title} &#9660;", 'class' => ready_val($this->statuses[$group_key]['open'])],
						'state'		=> (empty($this->set) ? $this->statuses[$group_key]['state'] : itSettings::get("{$this->set}-{$group_key}", $_USER->id(), $this->statuses[$group_key]['state'])),
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
				}

		}

	//..............................................................................
	// обновляет статус записи по полученным данным
	//..............................................................................	
	static function set_status($table_name, $rec_id, $value, $field='status')
		{
		$db = new itMySQL();
		$db->update_value_db($table_name, $rec_id, $value, $field);
		unset($db);
		}

	//..............................................................................
	// обновляет тип контента и сбрасывает категорию материала
	//..............................................................................	
	static function content_type($table_name, $rec_id, $value, $field='content_type')
		{
		$db = new itMySQL();
		$db->update_value_db($table_name, $rec_id, $value, $field);
		$db->update_value_db($table_name, $rec_id, 0, 'category_id');
		unset($db);
		}


	//..............................................................................
	// генерирует код из переменной
	//..............................................................................	
	public function code()
		{
		return $this->code;
		}

	} // class
?>
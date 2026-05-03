<?php
global $feed_counter;
$feed_counter = (function_exists('rand_id')) ? rand_id() : time();

// itFeed : класс построения бесконечной ленты данных из любых таблиц базы
class itFeed
	{
	public $table_name, $prefix, $fields, $condition, $group, $order;
	public $sql, $request, $code, $rows, $weight, $query, $async;
	public $start, $fewer, $loop, $appear, $rotate, $nodiv, $func;
	public $COUNTALL, $limit, $limit_explicit, $need_total, $total_count_resolved;
	public $element_id, $name, $position, $onefield, $field, $params;
	public $MAXINBLOCK, $WASRESET, $COUNTAL, $field_rec;

	// конструктор класса - создает блок и кнопку с параметрами из запроса
	public function __construct($options=NULL)
		{
		global $feed_counter;
		$feed_counter++;
		$options = is_array($options) ? $options : [];

		$this->sql		= ready_val($options['sql']);
		$this->order 		= ready_val($options['order']);
		$this->limit_explicit	= array_key_exists('limit', $options) && $options['limit'] !== NULL && $options['limit'] !== '';
		$this->limit		= $this->limit_explicit ? intval($options['limit']) : intval(get_const('FEED_LIMIT'));
		$this->need_total	= array_key_exists('need_total', $options) ? !!$options['need_total'] : true;
		$this->total_count_resolved = false;

		if (is_null($this->sql))
			{
			$this->table_name 	= ready_val($options['table'], get_const('DEFAULT_CONTENT_TABLE'));
			$this->prefix 		= ready_val($options['prefix'], get_const('DB_PREFIX'));
			$this->fields 		= ready_val($options['fields'],'*');
			$this->condition 	= ready_val($options['condition'],'1');
			$this->order 		= ready_val($options['order']);
			$this->group 		= ready_val($options['group']);
			}

		$this->element_id	= ready_val($options['element_id'], "feed-{$feed_counter}");
		$this->name 		= ready_val($options['name'], ready_val($options['table'], NULL));
		$this->position		= intval(ready_val($options['position'], 0));
		$this->weight 		= ready_val($options['weight'], false);
		$this->async 		= ready_val($options['async'],false);
		$this->start 		= ready_val($options['start'], false);
		$this->appear 		= ready_val($options['appear'], DEFAULT_FEED_APPEAR);
		$this->fewer 		= ready_val($options['fewer'], NULL);
		$this->loop		= ready_val($options['loop'], ready_val(unserialize(FEED_LOOP)[$this->name]));
		$this->onefield		= ready_val($options['onefield'], false);
		$this->field		= ready_val($options['field'], 'ed_xml');
		$this->rotate		= ready_val($options['rotate'], true);
		$this->nodiv		= ready_val($options['nodiv'], false);
		$this->func		= ready_val($options['func']);
		$this->params		= ready_val($options['params']);

		$fnum_arr = ($this->start) ? unserialize(FEED_START) : unserialize(FEED_NUMBER);
		$this->MAXINBLOCK	= intval(ready_val($fnum_arr[$this->name], DEFAULT_FEED_NUM));
		$this->WASRESET		= false;
		$this->COUNTAL		= NULL;
		$this->COUNTALL		= NULL;
		$this->rows		= NULL;
		$this->field_rec	= NULL;
		$this->start_feed();
		}

	// determines actual query limit for feed query; explicit limit has priority
	public function resolve_query_limit()
		{
		$limit = intval($this->limit);
		return ($limit > 0) ? $limit : 0;
		}

	// returns SQL LIMIT clause for current feed position
	private function build_limit_clause($position=NULL, $limit=NULL)
		{
		if ($this->onefield)
			{
			return '';
			}

		$position = is_null($position) ? $this->position : intval($position);
		if (is_null($position))
			{
			return '';
			}

		$limit = is_null($limit) ? $this->resolve_query_limit() : intval($limit);
		return ($limit > 0) ? " LIMIT {$position},{$limit}" : " LIMIT {$position}";
		}

	// builds base query without limit for current feed
	public function build_base_query()
		{
		if (is_null($this->sql))
			{
			return "SELECT {$this->fields} FROM {$this->prefix}{$this->table_name} ".
				"WHERE ( {$this->condition} )".
				(($this->group!='') ? " GROUP BY {$this->group}" : '').
				(($this->order!='') ? " ORDER BY {$this->order}" : '');
			}

		return $this->sql.
			(($this->group!='') ? " GROUP BY {$this->group}" : '').
			(($this->order!='') ? " ORDER BY {$this->order}" : '');
		}

	// builds current feed query with limit clause
	public function build_feed_query()
		{
		return $this->build_base_query().$this->build_limit_clause();
		}

	// calculates actual total count for SQL or function feeds
	private function resolve_total_count()
		{
		if (!is_null($this->func))
			{
			if ($this->onefield)
				{
				return is_array($this->field_rec) ? count($this->field_rec) : 0;
				}
			return is_array($this->request) ? count($this->request) : 0;
			}

		if ($this->onefield)
			{
			return is_array($this->field_rec) ? count($this->field_rec) : 0;
			}

		if (is_null($this->sql) && $this->group=='')
			{
			$count_query = "SELECT COUNT(*) AS `count` FROM {$this->prefix}{$this->table_name} WHERE ( {$this->condition} )";
			}
		else
			{
			$count_query = "SELECT COUNT(*) AS `count` FROM (".$this->build_base_query().") skel80_feed_total";
			}

		$request = itMySQL::_request($count_query);
		return isset($request[0]['count']) ? intval($request[0]['count']) : 0;
		}

	// launches feed query/function source
	private function start_feed()
		{
		if (!is_null($this->func) AND function_exists($this->func))
			{
			$this->request = call_user_func($this->func, $this->params);
			if ($this->onefield)
				{
				$record = is_array($this->request) ? ready_val($this->request[0]) : NULL;
				if (is_array($record) && isset($record[$this->field]))
					{
					$this->field_rec = is_array($record[$this->field]) ? $record[$this->field] : json_decode($record[$this->field], JSON_ALLOWED);
					}
				}
			}
		else
			{
			$this->query = $this->build_feed_query();
			if ($this->fewer)
				{
				$this->reverse_order();
				}
			$this->request = itMySQL::_request($this->query, false);
			}

		if ($this->need_total)
			{
			$this->COUNTALL = $this->resolve_total_count();
			$this->total_count_resolved = true;
			}
		else
			{
			$this->COUNTALL = NULL;
			$this->total_count_resolved = false;
			}
		}

	// switches direction for fewer-feed query
	public function reverse_order()
		{
		$this->query = (strpos($this->query, "`id`<='")!==false)
			? str_replace(["`id`<='"], ["`id`>'"], $this->query)
			: $this->query;
		}


	private function reset_run_state()
		{
		$this->rows = NULL;
		$this->WASRESET = false;
		}

	private function restart_loop_if_needed()
		{
		if (!$this->loop)
			{
			return false;
			}

		$this->position = 0;
		$this->start_feed();
		$this->WASRESET = true;
		return true;
		}

	private function with_row_context($row)
		{
		$row['table_name'] = $this->table_name;
		$row['rec_id'] = isset($row['id']) ? $row['id'] : NULL;
		return $row;
		}

	// gets rows for weighted feed display
	public function weight_run()
		{
		global $show_as;
		$i=1;
		$last = NULL;
		$func = $this->callback_func();
		if (!$func) return $i;
		$this->reset_run_state();

		while ($i<=$this->MAXINBLOCK)
			{
			$sum = 0;
			if ($last!=NULL)
				{
				$this->rows[$i][] = call_user_func($func, $last);
				$sum += $show_as[$last['show_as']]['size'];
				$last = NULL;
				}

			while(($row =$this->step()) AND ($sum<101))
				{
				$this->position++;
				decode_json_values($row);

				$sum += $show_as[$row['show_as']]['size'];
				$row = $this->with_row_context($row);
				if ($sum<101)
					{
					$this->rows[$i][] = call_user_func($func, $row);
					}
				else
					{
					$last = $row;
					break;
					}
				}
			$i++;
			}
		return $i;
		}

	// gets blocks for onefield processing
	public function onefield_run()
		{
		$i=1;
		$func = $this->callback_func();
		if (!$func) return $i;
		$this->reset_run_state();

		if (is_null($this->field_rec) && is_object($this->request) && ($record = mysqli_fetch_assoc($this->request)))
			{
			$this->field_rec = is_array($record[$this->field]) ? $record[$this->field] : json_decode($record[$this->field], JSON_ALLOWED);
			}

		while ($i<=$this->MAXINBLOCK)
			{
			if ($field_row = ready_val($this->field_rec[$this->position]))
				{
				$this->position++;
				$field_row['key'] = $this->position;
				$field_row = (is_array($this->params)) ? array_merge($field_row, $this->params) : $field_row;
				$this->rows[$i] = call_user_func($func, $field_row);
				}
			elseif ($this->restart_loop_if_needed())
				{
				continue;
				}
			else break;
			$i++;
			}
		return $i;
		}

	// gets feed rows by settings
	public function run()
		{
		$i=1;
		$func = $this->callback_func();
		if (!$func) return $i;
		$this->reset_run_state();

		while ($i<=$this->MAXINBLOCK)
			{
			if ($row = $this->step())
				{
				$this->position++;
				decode_json_values($row);
				if (!isset($this->sql))
					{
					$row = $this->with_row_context($row);
					}
				$this->rows[$i] = call_user_func($func, $row);
				}
			elseif ($this->restart_loop_if_needed())
				{
				continue;
				}
			else break;
			$i++;
			}
		return $i++;
		}

	// callback resolver for one feed row
	public function callback_func()
		{
		if (!function_exists($func = "get_{$this->name}_feed_row"))
			{
			add_error_message("Function does not exists <b>{$func}()</b>");
			return NULL;
			}
		return $func;
		}

	// prepares encrypted payload for more/fewer feed buttons
	private function build_feed_payload($fewer=false)
		{
		$payload = [
			'table'		=> $this->table_name,
			'prefix'	=> $this->prefix,
			'fields'	=> $this->fields,
			'condition'	=> $this->condition,
			'group'		=> $this->group,
			'order'		=> $this->order,
			'element_id'	=> $this->element_id,
			'name'		=> $this->name,
			'position'	=> $this->position,
			'weight'	=> $this->weight,
			'loop'		=> $this->loop,
			'onefield'	=> $this->onefield,
			'field'		=> $this->field,
			'fewer'		=> $fewer,
			'start'		=> false,
			'rotate'	=> $this->rotate,
			'nodiv'		=> $this->nodiv,
			'appear'	=> (is_null($this->appear) ? true : $this->appear),
			'func'		=> $this->func,
			'params'	=> $this->params,
			'need_total'	=> $this->need_total,
		];

		if ($this->limit_explicit)
			{
			$payload['limit'] = $this->limit;
			}

		if (!is_null($this->sql))
			{
			$payload['sql'] = $this->sql;
			}

		return simple_encrypt(serialize($payload));
		}

	// resolves localized feed-button text by constant prefix and suffix
	private function feed_button_text($prefix, $suffix='TEXT')
		{
		$const_name = "{$prefix}_".strtoupper($this->name)."_{$suffix}";
		return get_const($const_name);
		}

	// returns common HTML attributes for more/fewer controls
	private function feed_button_attrs($feed_code)
		{
		return " class='more_feed' id='{$this->element_id}' feed-rel='{$feed_code}'".
			($this->async ? ' async' : ' scroll').
			($this->appear ? ' appear' : '');
		}

	// builds more/fewer control HTML
	private function build_feed_button($fewer=false)
		{
		$feed_code = $this->build_feed_payload($fewer);
		$button_msg = $this->feed_button_text($fewer ? 'FEWER' : 'MORE');
		$logo_class = $fewer ? "fewer_{$this->name}" : $this->name;
		$rotate_class = (!$fewer && !$this->rotate) ? ' norotate' : '';
		return
			TAB."<div".$this->feed_button_attrs($feed_code).">".
			TAB."	<div class='more_logo {$logo_class}' tabindex=-1{$rotate_class}></div>".
			TAB."	<div class='more_text'>{$button_msg}</div>".
			TAB."</div>";
		}

	// returns more-button HTML
	public function get_more_button()
		{
		return $this->build_feed_button(false);
		}

	// returns begin-of-feed marker for fewer mode
	public function fewer_begin_text()
		{
		return TAB."	<div class='fewer_begin'>".$this->feed_button_text('FEWER', 'BEGIN')."</div>";
		}

	// returns fewer-button HTML
	public function get_fewer_button()
		{
		return $this->build_feed_button(true);
		}

	// builds current feed block with optional more/fewer controls
	public function get_feed_arr()
		{
		if (!($this->start AND $this->fewer))
			{
			($this->onefield)
				? $this->onefield_run()
				: ($this->weight ? $this->weight_run() : $this->run());
			}

		if (!is_null($this->step()) OR isset($this->field_rec[$this->position]))
			{
			$this->rows[$this->fewer ? 'fewer' : 'more'] = $this->fewer ? $this->get_fewer_button() : $this->get_more_button();
			return;
			}

		if ($this->fewer)
			{
			$this->rows['fewer'] = ($this->position >= $this->count_all()) ? $this->fewer_begin_text() : NULL;
			}
		}

	// compiles weighted feed columns into HTML
	private function compile_weight_rows()
		{
		$code = '';
		$func = "get_{$this->name}_feed_col";
		foreach ($this->rows_without_controls($this->rows) as $row)
			{
			$code .= $func(self::compile_rows($row, $this->fewer));
			}
		return $code;
		}

	// wraps compiled feed code with controls and optional feed div
	private function wrap_feed_code($code)
		{
		return
			ready_val($this->rows['fewer']).
			(($code)
				? ((!$this->nodiv) ? TAB."<div class='feed_div'>" : '').
				$code.
				((!$this->nodiv) ? TAB."</div>" : '')
				: '').
			ready_val($this->rows['more']);
		}

	// compiles standard feed block to HTML
	public function compile()
		{
		if ($this->async)
			{
			$this->code = $this->get_more_button();
			return;
			}

		$this->get_feed_arr();
		if (!is_array($this->rows))
			{
			$this->code = $this->wrap_feed_code('');
			return;
			}

		$this->code = $this->weight
			? $this->compile_weight_rows()
			: self::compile_rows($this->rows, $this->fewer);
		$this->code = $this->wrap_feed_code($this->code);
		}

	// returns feed rows without more/fewer control keys
	private static function rows_without_controls($data, $reverse=false)
		{
		$rows = [];
		foreach (($reverse ? array_reverse((array)$data, true) : (array)$data) as $key=>$row)
			{
			if (!in_array($key, ['fewer','more']))
				{
				$rows[] = $row;
				}
			}
		return $rows;
		}

	// собираем блоки в общий код
	static function compile_rows($data, $reverse=false)
		{
		return implode('', self::rows_without_controls($data, $reverse));
		}

	// returns one row from request or function data source
	public function step()
		{
		if (is_null($this->func))
			{
			return is_object($this->request) ? mysqli_fetch_assoc($this->request) : NULL;
			}

		return isset($this->request[$this->position]) ? $this->request[$this->position] : NULL;
		}

	// returns total count; calculates lazily when disabled at startup
	public function count_all()
		{
		if ($this->COUNTALL === NULL && !$this->total_count_resolved)
			{
			$this->collect_all();
			}
		return intval($this->COUNTALL);
		}

	// explicitly calculates total count for current feed
	public function collect_all()
		{
		$this->COUNTALL = $this->resolve_total_count();
		$this->total_count_resolved = true;
		return intval($this->COUNTALL);
		}

	// returns compiled HTML code for feed
	public function code()
		{
		return $this->code;
		}
	}
?>

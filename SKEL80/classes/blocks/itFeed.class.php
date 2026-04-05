<?php
// ================ CRC ================
// version: 1.15.09
// hash: 66b124c147958f086d9fead5ecbf677c34a2966b9b2f311c458e606273b5a345
// date: 28 May 2021  4:42
// ================ CRC ================
global $feed_counter;
$feed_counter = (function_exists('rand_id')) ? rand_id() : time();
//..............................................................................
// itFeed : класс построения бесконечной ленты данных из любых таблиц базы
//..............................................................................

class itFeed
	{
	public 	$table_name, $prefix, $fields, $condition, $group, $order, 
		$sql, $request, $code, $rows, $weight, $query, $async,
		$start, $fewer, $loop, $appear, $rotate, $nodiv, $func,
		$COUNTALL;

	//..............................................................................
	// конструктор класса - создает блок и кнопку с параметрами из запроса
	//..............................................................................
	public function __construct($options=NULL)
		{
		global $feed_counter;
		$feed_counter++;

		// побробуем кстановить текс запроса
		$this->sql	= ready_val($options['sql']);
		$this->order 	= ready_val($options['order']);
		$this->limit	= ready_val($options['limit'], get_const('FEED_LIMIT'));
		
		// если не установлен sql код
		if (is_null($options['sql']))		
			{
			$this->table_name 	= ready_val($options['table'], get_const('DEFAULT_CONTENT_TABLE'));
			$this->prefix 		= ready_val($options['prefix'], get_const('DB_PREFIX'));			
			$this->fields 		= ready_val($options['fields'],'*');
			$this->condition 	= ready_val($options['condition'],'1');
			$this->order 		= ready_val($options['order']);
			$this->group 		= ready_val($options['group']);
			}

		$this->element_id	= ready_val($options['element_id'], "feed-{$feed_counter}");		// автоматическое имя, или указанный id в html
		$this->name 		= ready_val($options['name'], ready_val($options['table'], NULL));	// выдаст ошибку
		$this->position		= ready_val($options['position'], 0);					// позиция начала блока (фактически ID верхнего левого объекта)
		$this->weight 		= ready_val($options['weight'], false);					// заливка новостей по весу
		$this->async 		= ready_val($options['async'],false);					// пустое поле с асинхронной загрузкой после document.ready
		$this->start 		= ready_val($options['start'], false);					// флаг того, что это начало - установка дает кнопку fewer_feed при position>0
	
		$this->appear 		= ready_val($options['appear'], DEFAULT_FEED_APPEAR);			// флаг автоматической подгрузки новых элементов

		$this->fewer 		= ready_val($options['fewer'], NULL);					// разворачивает ленту вверх
		$this->loop		= ready_val($options['loop'], ready_val(unserialize(FEED_LOOP)[$this->name]));	// принудительное дополнение ленты
		$this->onefield		= ready_val($options['onefield'], false);				// обработка массива из одного поля базы данных
		$this->field		= ready_val($options['field'], 'ed_xml');				// название поля для обработки
		
		$this->rotate		= ready_val($options['rotate'], true);					// флаг вращения кнопки
		$this->nodiv		= ready_val($options['nodiv'], false);					// без feed_div?
		
		$this->func		= ready_val($options['func']);						// функция замены массива вместо обращения к MySQL
		$this->params		= ready_val($options['params']);					// параметры для такой функции
		
		$fnum_arr = ($this->start) ? unserialize(FEED_START) : unserialize(FEED_NUMBER);
		$this->MAXINBLOCK	= ready_val($fnum_arr[$this->name], DEFAULT_FEED_NUM);			// количество рядов в блоке обновления
		$this->WASRESET		= false;
		$this->COUNTAL		= NULL; 								// начало
		$this->start_feed();
//		$this->collect_all();
		}

	//..............................................................................
	// запускает поток новостей из базы
	//..............................................................................
	private function start_feed()
		{
		// проверим есть устанволена ли функция доступа к данным
		if (!is_null($this->func) AND function_exists($this->func))
			{
			// получим массив данных от внешней функции
			$this->request = call_user_func($this->func, $this->params);
			$this->COUNTALL = $this->onefield ? 100 : (is_array($this->request) ? count($this->request) : 0);
			} else {
				// иначе - создадим запрос к базе данных
				$this->query = (is_null($this->sql))
				
					?	"SELECT {$this->fields} FROM {$this->prefix}{$this->table_name} ".
						"WHERE ( {$this->condition} )".
						(($this->group!='') ? " GROUP BY " : '').$this->group.
						(($this->order!='') ? " ORDER BY " : '').$this->order.
						((!is_null($this->position) AND !$this->onefield) ? " LIMIT {$this->position}".((intval(FEED_LIMIT)>0) ? ",".FEED_LIMIT : "") : "")
						
					:	$this->sql.
						(($this->group!='') ? " GROUP BY " : '').$this->group.
						(($this->order!='') ? " ORDER BY " : '').$this->order.
						((!is_null($this->position) AND !$this->onefield) ? " LIMIT {$this->position}".((intval(FEED_LIMIT)>0) ? ",".FEED_LIMIT : "") : "");

				if ($this->fewer) $this->reverse_order();
				$this->request = itMySQL::_request($this->query, false); 	// важно - указать чтобы возврат был не массив!
				$this->COUNTALL = $this->onefield ? 100 : mysqli_num_rows($this->request);
				}
		}

	//..............................................................................
	//  меняет направление в обратном порядке для mysql запроса
	//..............................................................................
	public function reverse_order()
		{
		$this->query = (strpos($this->query, "`id`<='")!==false)
			? str_replace(["`id`<='"], ["`id`>'"], $this->query) 
			: $this->query;
		}

	//..............................................................................
	// получает блоки для установки по весу
	//..............................................................................
	public function weight_run()
		{
		global $show_as;			
		$i=1;	
		$last = NULL;
		$this->rows = NULL;
		$this->WASRESET = false;

		// количественное значение соответствует количеству столбцов
		while ($i<=$this->MAXINBLOCK)
			{
			$sum = 0;
			if ($last!=NULL)
				{
				$this->rows[$i][] = call_user_func($this->callback_func(), $last);
				$sum += $show_as[$last['show_as']]['size'];
				$last = NULL;
				}
	
			while($row =$this->step() AND ($sum<101))
				{
				$this->position++;
				decode_json_values($row);

				$sum += $show_as[$row['show_as']]['size'];

				$row['table_name'] = $this->table_name;
				$row['rec_id'] = $row['id'];
				if ($sum<101)
					{
					$this->rows[$i][] = call_user_func($this->callback_func(), $row);
					} else	{
						$last = $row;
						break;
						}
				}
				$i++;
			}
		return $i;
		}
		
		
	//..............................................................................
	// получает блоки для установки по обработке данных одного поля
	//..............................................................................
	public function onefield_run()
		{
		global $show_as;			
		$i=1;	
		$last = NULL;
		$this->rows = NULL;
		$this->WASRESET = false;

		if ($record = mysqli_fetch_assoc($this->request))
			{
//			$this->field_rec = json_decode(ready_val($record[$this->field]), JSON_ALLOWED);				
			$this->field_rec = is_array($record[$this->field]) ? $record[$this->field] : json_decode($record[$this->field] ,JSON_ALLOWED);
			while ($i<=$this->MAXINBLOCK)
				{
				if ($field_row = ready_val($this->field_rec[$this->position]))
					{
					// все нормально есть элемент в базе
					$this->position++;
					$field_row['key'] = $this->position;
					$field_row = (is_array($this->params)) ? array_merge($field_row, $this->params) : $field_row;
					$this->rows[$i] = call_user_func($this->callback_func(), $field_row);
					} elseif ($this->loop OR (isset($loop_arr[$this->name]) AND ($loop_arr[$this->name]==1)) )
						{
						// сброс счетчика
						$this->position = 0;
						$this->start_feed();
						$this->WASRESET = true;
						continue;
						} else break;
				$i++;
				}		
			}
		return $i;
		}		

	//..............................................................................
	// получает блоки по установкам
	//..............................................................................
	public function run()
		{
		global $show_as;			
		$i=1;	
		$last = NULL;
		$this->rows = NULL;
		$this->WASRESET = false;			
					
		while ($i<=$this->MAXINBLOCK)
			{
			if ($row = $this->step())
				{
				// все нормально
				$this->position++;
				decode_json_values($row);
				
				// пополоним данные полями таблицы и записи
				if (!isset($this->sql))
					{
					$row['table_name'] = $this->table_name;
					$row['rec_id'] = $row['id'];
					}
				$this->rows[$i] = call_user_func($this->callback_func(), $row);
				} elseif ($this->loop)
					{
					// сброс счетчика
					$this->position = 0;
					$this->start_feed();
					$this->WASRESET = true;
					continue;
					} else break;
			$i++;
			}
		return $i++;
		}

	//..............................................................................
	// возвращает имя функции отображения одной записи, если она существует
	//..............................................................................
	public function callback_func()
		{
		if (!function_exists($func = "get_{$this->name}_feed_row"))
			{
			add_error_message("Function does not exists <b>{$func}()</b>");
			return;
			}
		return $func;
		}

	//..............................................................................
	// возвращает блок данных для базы, лимитированный с кнопкой 'more'
	//..............................................................................
	public function get_feed_arr()
		{
		$counter = 0;
		// подготовим кнопку обратной загрузки данных
		if (!($this->start AND $this->fewer))
			{
			// проверим, установлен ли флаг "веса" блоков
			$counter = ($this->onefield)
				? $this->onefield_run()
				: ($this->weight ? $this->weight_run() : $this->run());
			}

		// дополним пустыми объектами
//		if ($this->fewer AND $counter AND ($this->MAXINBLOCK>$counter))
//			$this->append_fewer($this->MAXINBLOCK-$counter);
			
			
		// обраотаем проверки на необходимость кнопок
		if (!is_null($this->step()) OR isset($this->field_rec[$this->position]))
			{
			if ($this->fewer)
				{
				$this->rows['fewer'] = $this->get_fewer_button();
				} else $this->rows['more'] = $this->get_more_button();
			} else	{
				if ($this->fewer)
					{
					$this->rows['fewer'] = ($this->position>=$this->COUNTALL) ? $this->fewer_begin_text() : NULL;
					}
				}
		}                                        
                          
	//..............................................................................
	// добавляет пустыми полями ленту товаров
	//..............................................................................
	public function append_fewer($size=0)
		{
		for($key=0;$key<=$size;$key++)
			{
			$this->rows[] = call_user_func($this->callback_func(), NULL);
			}
		}

	//..............................................................................
	// возвращает код кнопки загрузки следующего контента в ленте
	//..............................................................................
	public function get_more_button()
		{
		if ($this->sql==NULL)
			{
			$feed_code	= simple_encrypt(serialize(array(
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
					'fewer'		=> false,
					'start'		=> false, // не может быть начальным блоком!										
					'rotate'	=> $this->rotate,
					'nodiv'		=> $this->nodiv,
					'appear'	=> (is_null($this->appear) ? true : $this->appear),
					'func'		=> $this->func,						
					'params'	=> $this->params,					
					)));
			} else	{
				$feed_code	= simple_encrypt(serialize(array(
					'table'		=> $this->table_name,
					'prefix'	=> $this->prefix,
					'sql'		=> $this->sql,
					'order'		=> $this->order,
					'position'	=> $this->position,
					'element_id'	=> $this->element_id,
					'name'		=> $this->name,
					'weight'	=> $this->weight,
					'loop'		=> $this->loop,
					'onefield'	=> $this->onefield,
					'field'		=> $this->field,
					'fewer'		=> false,
					'start'		=> false, // не может быть начальным блоком!														
					'rotate'	=> $this->rotate,
					'nodiv'		=> $this->nodiv,
					'appear'	=> (is_null($this->appear) ? true : $this->appear),
					'func'		=> $this->func,						
					'params'	=> $this->params,					
					)));
				}


		eval('$button_msg = get_const(\'MORE_'.strtoupper($this->name).'_TEXT\');');
	
		$result = 
			TAB."<div class='more_feed' id='{$this->element_id}' feed-rel='{$feed_code}'".($this->async ? " async" : " scroll").($this->appear ? " appear" : '').">".
			TAB."\t<div class='more_logo {$this->name}' tabindex=-1".($this->rotate ? "" : " norotate")."></div>".
			TAB."\t<div class='more_text'>{$button_msg}</div>".
//			($this->async ? TAB."<script> $(document).ready( function(){ $('#{$this->element_id}').click();}); </script>" : "").
			TAB."</div>";
			
		return $result;
		}


	//..............................................................................
	// возвращает код того, что мы достигли начала
	//..............................................................................
	public function fewer_begin_text()
		{
		eval('$button_msg = get_const(\'FEWER_'.strtoupper($this->name).'_BEGIN\');');
		$result = TAB."\t<div class='fewer_begin'>{$button_msg}</div>";
		return $result;
		}			
		
	//..............................................................................
	// возвращает код кнопки загрузки предыдущего контента в ленте
	//..............................................................................
	public function get_fewer_button()
		{
		if ($this->sql==NULL)
			{
			$feed_code	= simple_encrypt(serialize(array(
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
					'rotate'	=> $this->rotate,
					'nodiv'		=> $this->nodiv,					
					'fewer'		=> true,
					'start'		=> false, // не может быть начальным блоком!
					'appear'	=> (is_null($this->appear) ? true : $this->appear),
					'func'		=> $this->func,						
					'params'	=> $this->params,											
					)));
			} else	{
				$feed_code	= simple_encrypt(serialize(array(
					'table'		=> $this->table_name,
					'prefix'	=> $this->prefix,
					'sql'		=> $this->sql,
					'position'	=> $this->position,
					'element_id'	=> $this->element_id,
					'name'		=> $this->name,
					'weight'	=> $this->weight,
					'loop'		=> $this->loop,
					'onefield'	=> $this->onefield,					
					'rotate'	=> $this->rotate,
					'nodiv'		=> $this->nodiv,										
					'fewer'		=> true,
					'start'		=> false, // не может быть начальным блоком!					
					'appear'	=> (is_null($this->appear) ? true : $this->appear),
					'func'		=> $this->func,						
					'params'	=> $this->params,					
					)));
				}


		eval('$button_msg = get_const(\'FEWER_'.strtoupper($this->name).'_TEXT\');');
	
		$result = 
			TAB."<div class='more_feed' id='{$this->element_id}' feed-rel='{$feed_code}'".($this->async ? " async" : " scroll").($this->appear ? " appear" : '').">".
			TAB."\t<div class='more_logo fewer_{$this->name}' tabindex=-1></div>".
			TAB."\t<div class='more_text'>{$button_msg}</div>".
//			($this->async ? TAB."<script> $(document).ready( function(){ $('#{$this->element_id}').click();}); </script>" : "").
			TAB."</div>";
			
		return $result;
		}		

	//..............................................................................
	// компилирует стандартный блок данных в код
	//..............................................................................
	public function compile()
		{
		if (!$this->async)
			{
			$this->get_feed_arr($this->start);

			if (is_array($this->rows))
				{
				if ($this->weight)
					{
					$this->code = '';
					foreach ($this->rows as $key=>$row)
						{
						if (!in_array($key,['fewer','more']))
							{
							$func = "get_{$this->name}_feed_col";
							$this->code .= $func(itFeed::compile_rows($row, $this->fewer));
							}
						}
	
					} else	{
						$this->code = itFeed::compile_rows($this->rows, $this->fewer);
						}
				}
			} else	{				
				$this->code = $this->get_more_button();
				return;
				}
			
		$this->code = 
			ready_val($this->rows['fewer']).
			(($this->code) ? 
			((!$this->nodiv) ? TAB."<div class='feed_div'>" : "").
			$this->code.
			((!$this->nodiv) ? TAB."</div>" : "").			
				""
				: "").
			ready_val($this->rows['more']);
		}

	//..............................................................................
	// собираем блоки в общий код
	//..............................................................................
	static function compile_rows($data, $reverse=false)
		{
                $code = '';
		if ($reverse)
			{
			// подадим в обратном порядке
			foreach (array_reverse($data,true) as $key=>$row)
				{
				if (!in_array($key,['fewer','more']))
					{
					$code .= $row;
					}
				}
			} else	{
				// подадим в прямом порядке
				foreach ($data as $key=>$row)
					{
					if (!in_array($key,['fewer','more']))
						{
						$code .= $row;
						}
					}
				}
		return $code;
		}

	//..............................................................................
	// возвращает оин ряд выборки в зависимости от установки
	//..............................................................................	
	public function step()
		{
		return is_null($this->func)
			? mysqli_fetch_assoc($this->request)
			: (isset($this->request[$this->position])
				? $this->request[$this->position]
				: NULL);
		}


	//..............................................................................
	// возвращает количество удовлетворящих условию елемнтов
	//..............................................................................	
	public function count_all()
		{
		return $this->COUNTALL;
		}

	//..............................................................................
	// считает количество удовлетворящих условию елемнтов
	//..............................................................................	
	public function collect_all()
		{
		if (!is_null($this->func))
			{
			$result = count($this->request);
			}
		else 	{
/*			$this->query = (is_null($this->sql))
				?	"SELECT COUNT(`id`) AS count FROM {$this->prefix}{$this->table_name} ".
					"WHERE ( {$this->condition} )".
					(($this->group!='') ? " GROUP BY " : '').$this->group.
//					(!is_null($this->position) ? " LIMIT {$this->position}, ".FEED_LIMIT : "").
					""
				:	str_replace("SELECT ", "SELECT COUNT(`id`) AS count,", $this->sql).
//					"SELECT COUNT(`id`) AS count FROM ({$this->sql} ".
					(!empty($this->group) ? " GROUP BY {$this->group}" : '').
//					(!is_null($this->position) ? " LIMIT {$this->position}, ".FEED_LIMIT : "").
//					") res".
					"";
				
			if ($this->fewer) $this->reverse_order();
			$request = itMySQL::_request($this->query);
			$result = isset($request[0]) ? $request[0]['count'] : 0;
*/			}

		return $this->COUNTALL;
		}

	//..............................................................................
	// возвращает код скомпилированного блока
	//..............................................................................	
	public function code()
		{
		return $this->code;
		}
	
	} // class

?>
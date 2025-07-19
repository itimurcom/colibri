<?

// счетчик древовидных стуктур категорий
global $cats_counter;
//$cats_counter = time();
$cats_counter = rand_id();

//..............................................................................
// itCats : класс который позволяет оперировать с сохраняемми категориями
//..............................................................................
class itCats
	{
	public $ed_rec, $parent_id, $group_id, $table_name;
	//..............................................................................
	// конструктор класса - создает объект структуры начиная с родительского id
	//..............................................................................
	// '$parent_id' : номер категории с которой начнется отсчтет дерева категорий
	//..............................................................................

	public function __construct($parent_id=NULL, $gr= GR_MATERIAL, $table_name = DEFAULT_CATS_TABLE)
		{
		$this->parent_id 	= $parent_id;
		$this->group_id 	= $gr;
		$this->table_name	= $table_name;
		$this->get_from_db();
		}

	//..............................................................................
	// загружает из базы данных все категории, начиная с родительской текущего языка
	//..............................................................................
	public function get_from_db()
		{		
		$this->ed_rec = itCats::get_cats_parent($this->parent_id, $this->group_id, $this->table_name);
		}

	//..............................................................................
	// загружает из базы данных элементы с родительским полем
	//..............................................................................
	static function get_cats_parent($parent_id=NULL, $gr = GR_MATERIAL, $table_name=DEFAULT_CATS_TABLE)
		{
		global $cats_gr;

		$res_arr = [];
		$base = itMySQL::_get_rec_from_db($table_name, $parent_id);
			if (is_array($base))
				{
				$base['prev'] = is_array($prev = itMySQL::_request("SELECT * FROM `".DB_PREFIX."{$table_name}` WHERE `group_id`='{$base['group_id']}' and `parent_id`='{$base['parent_id']}' and `id`<{$base['id']} order by `id` DESC limit 1"))
					? $prev[0]['id']
					: NULL;
				$base['next'] = is_array($next = itMySQL::_request("SELECT * FROM `".DB_PREFIX."{$table_name}` WHERE `group_id`='{$base['group_id']}' and `parent_id`='{$base['parent_id']}' and `id`>{$base['id']} order by `id` ASC limit 1"))
					? $next[0]['id']
					: NULL;
				$res_arr[$parent_id][$base['id']] = $base;
				}

		if (isset($cats_gr[$gr]['id']))
			{
			$request = itMySQL::_get_arr_from_db($table_name, "`group_id`='{$cats_gr[$gr]['id']}' and `parent_id`='{$parent_id}'", '`id`');

			if (is_array($request))
				foreach ($request as $row)
					{
					if( ($child = itCats::get_cats_parent($row['id'], $gr, $table_name))!=NULL)
						{
						$res_arr[$parent_id][] = $child;
						}
					}
			}       

		if (is_array($res_arr)) {
			return $res_arr;
			} else return NULL;
		}

	//..............................................................................
	// генерирует код дерева категорий и заносит в code
	//..............................................................................
	public function compile()
		{
		global $cats_gr;
		$this->code = 
			get_cats_admin_set($this->group_id).
			TAB."\t<div class='cats_header {$cats_gr[$this->group_id]['name']}'>".get_const($cats_gr[$this->group_id]['header'])."</div>".
			TAB."\t<div class='cats_roller {$cats_gr[$this->group_id]['name']}'>".
			$this->get_node($this->ed_rec).
			TAB."\t</div>";
		}

	//..............................................................................
	// рекурсивено строит ноду дерева
	//..............................................................................
	private function get_node($ed_rec)
		{
		global $_USER;

		$result='';
		if (is_array($ed_rec)) {
			foreach ($ed_rec as $key=>$row)
				{
				if (!isset($row['id']) and count($row))
					{
					$result .= TAB."\t<div class='cats_node' cat_id='{$key}'>".
						$this->get_node($row).
                                                TAB."\t</div>";
					} else 	{
						$titles_str = $this->get_titles($row['id']);
						$has_child = $this->has_child($key);

						if ($titles_str or $_USER->is_logged() or $has_child)
							{
						$result .= TAB."\t<div class='cats' cat_id='{$key}' parent_id='{$row['parent_id']}' ".((count($ed_rec)==1)? ' last' : '').">".
							TAB."\t<span class='cats_label".((($titles_str!='') or $has_child) ? ' not_empty' : '')."' cat_id='{$key}' parent_id='{$row['parent_id']}'>".get_field_by_lang($row['title_xml'])."</span>".
							(($_USER->is_logged()) ? TAB."\t\t<span class='cats_admin_div'>".
								get_cats_events_set($row).
								"</span>" : '').
							$titles_str.
							TAB."\t</div>";
							}

						}
				}

			}
		return $result;
		}


	//..............................................................................
	// возвращает список заголовков
	//..............................................................................
	public function get_titles($category_id, $table_name=DEFAULT_CONTENT_TABLE)
		{
		global $_USER;
		$result = '';
		$db = new itMySQL();
		$request = $db->get_arr_from_db('contents', "`category_id`='{$category_id}' ".((!$_USER->is_logged()) ? "AND `status`='PUBLISHED' AND (`lang`='".CMS_LANG."' OR `lang` IS NULL OR `lang`='')" : "")."order by `id`");
		if (is_array($request)) {
			$result = TAB."\t<div class='cats_list' parent_id='$category_id'>";
			foreach ($request as $key=>$row)
				{
				$base['prev'] = is_array($prev = itMySQL::_request("SELECT * FROM `".DB_PREFIX."{$table_name}` WHERE `category_id`='{$row['category_id']}' and `id`<{$row['id']} order by `id` DESC limit 1"))
					? $prev[0]['id']
					: NULL;
				$base['next'] = is_array($next = itMySQL::_request("SELECT * FROM `".DB_PREFIX."{$table_name}` WHERE `category_id`='{$row['category_id']}' and `id`>{$row['id']} order by `id` ASC limit 1"))
					? $next[0]['id']
					: NULL;
				$result .= TAB."\t<div class='cats_title_div'>".
					TAB."\t<a class='cats_title".(($_REQUEST['rec_id']==$row['id']) ? " selected' id='cats_selected'" : "'")." href='/".CMS_LANG."/material/{$row['rec_id']}/'>".get_field_by_lang($row['title_xml'])."</a>".
					(($_USER->is_logged()) ? TAB."\t\t<span class='cats_admin_div'>".
						get_content_cats_events_set($row).
						"</span>" : '').
					TAB."\t</div>";
				}
			$result .= TAB."\t</div>";
			}
		unset($db);
		return $result;
		}

	//..............................................................................
	// удаляет категорию из базы и также сбрасывает статус материалов, связанных
	//..............................................................................
	static function remove_cats($table_name, $id)
		{	
		$rem_rec = itMySQL::_get_rec_from_db($table_name, $id);
		$title = get_field_by_lang($rem_rec['title_xml']);
		itMySQL::_remove_rec_from_db($table_name, $id);
		itMySQL::_request("update ".DB_PREFIX.DEFAULT_CONTENT_TABLE." set `category_id`='0', `status`='MODERATE' WHERE `category_id`='{$id}'");

		$_SESSION['error'][] = array
			(
			'msg' 	=> "Категория <b>$title</b> удалена успешно, материалы перенесены в раздел <font color=green>Модерация</font>...",
			'color'	=> 'blue'
			);
		}

	//..............................................................................
	// возвращает название каттегории для текущего языка
	//..............................................................................
	static function get_category_name($category_id, $table_name=DEFAULT_CATS_TABLE)
		{
		$db = new itMySQL();
		$rem_rec = $db->get_rec_from_db($table_name, $category_id);
		$title = get_field_by_lang($rem_rec['title_xml']);
		unset($db);
		return $title;		
		}

	//..............................................................................
	// обновляет категорию для записи
	//..............................................................................	
	static function set_category($table_name, $rec_id, $category_id)
		{
		$db = new itMySQL();
		$db->update_value_db($table_name, $rec_id, $category_id, 'category_id');
		unset($db);
		}

	//..............................................................................
	// меняет местами две категории, сохраняя связанные новости
	//..............................................................................	
	static function exchange($table_name, $rec_id, $value)
		{
		$db = new itMySQL();

		// поменяем местами занчения категории в новостях
		$db->request("UPDATE ".DB_PREFIX.DEFAULT_CONTENT_TABLE." SET `category_id`= '-999' WHERE `category_id`='$rec_id'");
		$db->request("UPDATE ".DB_PREFIX.DEFAULT_CONTENT_TABLE." SET `category_id`= '-888' WHERE `category_id`='$value'");
		$db->request("UPDATE ".DB_PREFIX.DEFAULT_CONTENT_TABLE." SET `category_id`= '$value' WHERE `category_id`='-999'");
		$db->request("UPDATE ".DB_PREFIX.DEFAULT_CONTENT_TABLE." SET `category_id`= '$rec_id' WHERE `category_id`='-888'");

		// поменяем дочерние категории
		$db->request("UPDATE ".DB_PREFIX."$table_name SET `parent_id`= '-999' WHERE `parent_id`='$rec_id'");
		$db->request("UPDATE ".DB_PREFIX."$table_name SET `parent_id`= '-888' WHERE `parent_id`='$value'");
		$db->request("UPDATE ".DB_PREFIX."$table_name SET `parent_id`= '$value' WHERE `parent_id`='-999'");
		$db->request("UPDATE ".DB_PREFIX."$table_name SET `parent_id`= '$rec_id' WHERE `parent_id`='-888'");

		// поменяем местами категории
		$db->update_value_db($table_name, $rec_id, -999, 'id');
		$db->update_value_db($table_name, $value, -888, 'id');
		$db->update_value_db($table_name, -999, $value, 'id');
		$db->update_value_db($table_name, -888, $rec_id, 'id');
		unset($db);
		}

	//..............................................................................
	// возвращает местами две категории, сохраняя связанные новости
	//..............................................................................	
	private function has_child($rec_id)
		{
		$request = itMySQL::_request("SELECT * FROM `".DB_PREFIX."cats` WHERE `parent_id`='{$rec_id}'");
		return is_array($request);
		}


	//..............................................................................
	// возвращает код группы
	//..............................................................................	
	static function group($rec_id=NULL, $table_name = DEFAULT_CATS_TABLE)
		{
		$db = new itMySQL();
		$row = $db->get_rec_from_db($table_name, $rec_id);
		unset($db);
		if(is_array($row))
			{
			return $row['group_id'];
			}
		}

	//..............................................................................
	// возвращает код дерева каталогов с аккордеоном
	//..............................................................................
	public function code()
		{
		return $this->code;
		}


	} // class
?>
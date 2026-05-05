<?php
global $item_counter;
$item_counter = (function_exists('rand_id')) ? rand_id() : time();

definition([
	'DEFAULT_ITEM_TABLE'	=> 'items',		
	'DEFAULT_OBJECT_TABLE'	=> 'objects',
	'DEFAULT_CATEGORY_TABLE'=> 'categories',
	'DEFAULT_WIZARD_TABLE'	=> 'categories',	
	'DEFAULT_WIZARD_VALUES'	=> 'values_xml',
	]);
// itItem : класс управления товаром
class itItem
	{
	public $table_name, $rec_id, $data, $code, $object;
	public $name, $object_name, $wizard, $wiz_values;

	protected static function row_value($row, $key, $default=NULL)
		{
		return (is_array($row) AND array_key_exists($key, $row)) ? $row[$key] : $default;
		}

	protected function data_value($key, $default=NULL)
		{
		return self::row_value($this->data, $key, $default);
		}

	protected function object_data_value($key, $default=NULL)
		{
		return (is_object($this->object) AND is_array($this->object->data)) ? self::row_value($this->object->data, $key, $default) : $default;
		}

	// конструктор класса
	public function __construct($options=NULL)
		{
		global $item_counter;
		$options = is_array($options) ? $options : [];
		$item_counter ++;
		
		$this->name 		= "item-{$item_counter}";
		$this->table_name	= ready_value(self::row_value($options, 'table_name'), get_const('DEFAULT_ITEM_TABLE'));
		$this->object_name	= ready_value(self::row_value($options, 'objectname'), get_const('DEFAULT_OBJECT_TABLE'));	
		$this->rec_id		= ready_value(self::row_value($options, 'rec_id'));
		$this->wiz_values	= ready_value(self::row_value($options, 'wiz_values'), get_const('DEFAULT_WIZARD_VALUES'));
		$this->data 		= itMySQL::_get_rec_from_db($this->table_name, $this->rec_id);
		$this->data		= is_array($this->data) ? $this->data : [];
		$this->object		= NULL;
		$this->wizard		= [];
		
		$object_id = self::row_value($this->data, 'object_id');
		if (!empty($object_id))
			{
			$this->object = new itObject(['table_name' => $this->object_name, 'rec_id' => $object_id]);
			$this->wizard = (is_object($this->object) AND is_array($this->object->wizard)) ? $this->object->wizard : [];
			}
		}
	// создает код отображения карты товара
	public function compile()
		{
		$rows = [];
		if (!is_array($this->data) OR empty($this->data) OR !is_object($this->object) OR !is_array($this->object->data))
			{
			$this->code = NULL;
			return;
			}

		$object_id = $this->object_data_value('id');
		$object_title = get_field_by_lang($this->object_data_value('title_xml'));
		$subtitle_xml = $this->data_value('subtitle_xml');
		$datetime = $this->data_value('datetime');
		
/*		$title_field = 
			($subtitle = get_field_by_lang($this->data['subtitle_xml'], CMS_LANG, '')).
			( !empty($subtitle) ? " - " : "").
			($object_title);
*/
		$rows[] =
			TAB."<div class='row'>".
			TAB."<div class='field p5 right item_title'>".get_const('ITEM_TITLE')."</div>".
			TAB."<div class='field p1'></div>".
			TAB."<div class='field p5'>".get_field_by_lang($subtitle_xml)."</div>".
			TAB."</div>";

		$rows[] =
			TAB."<div class='row'>".
			TAB."<div class='field p5 right'>".get_const('ITEM_GROUP')."</div>".
			TAB."<div class='field p1'></div>".
			TAB."<div class='field p5'><a class='blue' href='/".CMS_LANG."/object/{$object_id}/' target='_blank'>{$object_title}</a></div>".
			TAB."</div>";

		
		if (is_array($this->wizard))
			{
			foreach ($this->wizard as $wiz_key=>$wiz_row)
				{
				$rows[] =
					TAB."<div class='row'>".
					TAB."<div class='field p5 right'>".get_field_by_lang(self::row_value($wiz_row, 'label'))."</div>".
					TAB."<div class='field p1'></div>".
					TAB."<div class='field p5'>".self::row_value($wiz_row, 'text')."</div>".
					TAB."</div>";
				}
			}

		$rows[] =
			TAB."<div class='row'>".
			TAB."<div class='field p5 right'>".get_const('ITEM_DATETIME')."</div>".
			TAB."<div class='field p1'></div>".
			TAB."<div class='field p5'>".get_local_date_str($datetime)."&nbsp;".get_time_str($datetime)."</div>".
			TAB."</div>"; 


		if (ready_val($this->data_value('stock_id')))
			{
			$rows[] =
				TAB."<div class='row'>".
				TAB."<div class='field p5 right'>".get_const('ITEM_PLACE')."</div>".
				TAB."<div class='field p1'></div>".
				TAB."<div class='field p5'>".itItem::_place($this->data)."</div>".
				TAB."</div>";

			$rows[] =
				TAB."<div class='row'>".
				TAB."<div class='field p5 right'>".get_const('ITEM_OLD_PLACE')."</div>".
				TAB."<div class='field p1'></div>".
				TAB."<div class='field p5'>".itItem::_old_place($this->data)."</div>".
				TAB."</div>";
			}


		$this->code = 
			TAB."<div class='list'>".
			implode('', $rows).
			TAB."</div>";

		}
	// возвращает код таблицы значений
	public function table()
		{
		global $wiz_types;
		if (is_array($this->wizard))
			{
			$rows = [];
			foreach ($this->wizard as $wiz_key=>$wiz_row)
				{
				$values = self::row_value($this->data, $this->wiz_values, []);
				$value  = ready_value(self::row_value($values, $wiz_key), 'NO_DATA');
			
			// проверим наличие заголовков и отдадим редультат	
				$type = self::row_value($wiz_row, 'type');
				if (!in_array($type, unserialize(WIZARD_NOTITLES)))
					{
					$sel_arr = [];
					$titles = isset($wiz_row['titles'][CMS_LANG]) AND is_array($wiz_row['titles'][CMS_LANG]) ? $wiz_row['titles'][CMS_LANG] : [];
					foreach($titles as $sel_key=>$sel_row)
						{
						if (!isset($wiz_row['values'][$sel_key])) continue;
						$sel_arr[$wiz_row['values'][$sel_key]] = 
							[
							'title'	=> get_const($sel_row),
							'value'	=> $wiz_row['values'][$sel_key],
							];	
						}
					$value = ready_value(self::row_value(self::row_value($sel_arr, $value, []), 'title'), 'NO_DATA');
					}  
				$rows[] = 
					TAB."<div class='row'>".
					TAB."<div class='field p5'>".get_field_by_lang(self::row_value($wiz_row, 'label'), CMS_LANG, '')."</div>".
					TAB."<div class='field p5'>{$value}</div>".
					TAB."</div>";
				}
			return	TAB."<div class='list'>".
				implode('', $rows).
				TAB."</div>";
			}
		}	
	// добавляет товар
	static function _add($options=NULL)
		{
		global $_USER;
		$options = is_array($options) ? $options : [];
		if (isset($options['object_id']))
			{
			$options['table_name']	= ready_value(self::row_value($options, 'table_name'), DEFAULT_ITEM_TABLE);
			$user_id = (is_object($_USER) AND method_exists($_USER, 'id')) ? $_USER->id() : NULL;
			
			$values_arr = [
				'user_id'	=> ready_value(self::row_value($options, 'user_id'), $user_id),
				'avatar'	=> ready_value(self::row_value($options, 'avatar')),
				'price'		=> ready_value(self::row_value($options, 'price'), 0),
				'object_id'	=> ready_value(self::row_value($options, 'object_id')),
				'status'	=> 'PUBLISHED',
				];
			
			if (isset($options['subtitle']))
				{
				$values_arr['subtitle_xml'][CMS_LANG] = $options['subtitle'];
				}
				
			$rec_id = itMySQL::_insert_rec($options['table_name'], $values_arr);
			return $rec_id;
			} else add_error_message('ERROR_OPTIONS_ITEM');
		}
	// возвращает расшифровку места хранения товара
	static function _place($row=NULL)
		{
		$row = is_array($row) ? $row : [];
		return 
			(ready_value(self::row_value($row, 'stock_id')) ? TAB.get_const('STOCK_TITLE').": ".self::row_value($row, 'stock_id') : '').
			(ready_value(self::row_value($row, 'cabinet')) ? TAB."<br/>".get_const('CABINET_TITLE').": ".self::row_value($row, 'cabinet') : '').
			(ready_value(self::row_value($row, 'rack')) ? TAB."<br/>".get_const('RACK_TITLE').": ".self::row_value($row, 'rack') : '').
			(ready_value(self::row_value($row, 'position')) ? TAB."<br/>".get_const('POSITION_TITLE').": ".self::row_value($row, 'position') : '').
			(ready_value(self::row_value($row, 'half')) ? TAB."<br/>".get_const('HALF_TITLE').": ".get_const('HULF_'.self::row_value($row, 'position')) : '').
			"";

		}
	// возвращает старую расшифровку места хранения товара
	static function _old_place($row=NULL)
		{
		$row = is_array($row) ? $row : [];
		return 
//			(ready_val($row['stock_id']) ? TAB."<br/>".get_const('STOCK_TITLE').": {$row['stock_id']}" : '').
			(ready_value(self::row_value($row, 'cabinet')) ? self::row_value($row, 'cabinet') : '').
			(ready_value(self::row_value($row, 'rack')) ? "-".self::row_value($row, 'rack') : '').
			(ready_value(self::row_value($row, 'position')) ? "-".self::row_value($row, 'position') : '').
//			(ready_val($row['half']) ? TAB."<br/>".get_const('HALF_TITLE').": ".get_const('HULF_'.$row['position']) : '').
			"";

		}
	// возвращает скомпилированный код товара
	public function code() 
		{
		return ($this->code);
		}	
	}	
?>

<?php
// ================ CRC ================
// version: 1.15.03
// hash: ce57fcd980da732a933b14fcbe12254d789b242be781e83c30a49afd26389d74
// date: 09 September 2019  5:10
// ================ CRC ================
global $item_counter;
$item_counter = (function_exists('rand_id')) ? rand_id() : time();

definition([
	'DEFAULT_ITEM_TABLE'	=> 'items',		
	'DEFAULT_OBJECT_TABLE'	=> 'objects',
	'DEFAULT_CATEGORY_TABLE'=> 'categories',
	'DEFAULT_WIZARD_TABLE'	=> 'categories',	
	'DEFAULT_WIZARD_VALUES'	=> 'values_xml',
	]);
//..............................................................................
// itItem : класс управления товаром
//..............................................................................
class itItem
	{
	public $table_name, $rec_id, $data, $code, $object;
	//..............................................................................
	// конструктор класса
	//..............................................................................
	public function __construct($options=NULL)
		{
		global $item_counter;
		$item_counter ++;
		
		$this->name 		= "item-{$item_counter}";
		$this->table_name	= ready_val($options['table_name'], get_const('DEFAULT_ITEM_TABLE'));
		$this->object_name	= ready_val($options['objectname'], get_const('DEFAULT_OBJECT_TABLE'));	
		$this->rec_id		= ready_val($options['rec_id']);
		$this->data 		= itMySQL::_get_rec_from_db($this->table_name, $this->rec_id);
		
		if (is_array($this->data))
			{
			$this->object 		= new itObject(['table_name' => $this->object_name, 'rec_id' => $this->data['object_id']]);
			}
		}

	//..............................................................................
	// создает код отображения карты товара
	//..............................................................................
	public function compile()
		{
		$rows = NULL;
		$object_title = get_field_by_lang($this->object->data['title_xml']);
		
/*		$title_field = 
			($subtitle = get_field_by_lang($this->data['subtitle_xml'], CMS_LANG, '')).
			( !empty($subtitle) ? " - " : "").
			($object_title);
*/
		$rows[] =
			TAB."<div class='row'>".
			TAB."<div class='field p5 right item_title'>".get_const('ITEM_TITLE')."</div>".
			TAB."<div class='field p1'></div>".
			TAB."<div class='field p5'>".get_field_by_lang($this->data['subtitle_xml'])."</div>".
			TAB."</div>";

		$rows[] =
			TAB."<div class='row'>".
			TAB."<div class='field p5 right'>".get_const('ITEM_GROUP')."</div>".
			TAB."<div class='field p1'></div>".
			TAB."<div class='field p5'><a class='blue' href='/".CMS_LANG."/object/{$this->object->data['id']}/' target='_blank'>{$object_title}</a></div>".
			TAB."</div>";

		
		if (is_array($this->object->wizard))
			{
			foreach ($this->object->wizard as $wiz_key=>$wiz_row)
				{
				$rows[] =
					TAB."<div class='row'>".
					TAB."<div class='field p5 right'>".get_field_by_lang($wiz_row['label'])."</div>".
					TAB."<div class='field p1'></div>".
					TAB."<div class='field p5'>{$wiz_row['text']}</div>".
					TAB."</div>";
				}
			}

		$rows[] =
			TAB."<div class='row'>".
			TAB."<div class='field p5 right'>".get_const('ITEM_DATETIME')."</div>".
			TAB."<div class='field p1'></div>".
			TAB."<div class='field p5'>".get_local_date_str($this->data['datetime'])."&nbsp;".get_time_str($this->data['datetime'])."</div>".
			TAB."</div>"; 


		if (ready_val($this->data['stock_id']))
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



	//..............................................................................
	// возвращает код таблицы значений
	//..............................................................................			
	public function table()
		{
		global $wiz_types;
		if (is_array($this->wizard))
			{
			foreach ($this->wizard as $wiz_key=>$wiz_row)
				{
				$value  = ready_val($this->data[$this->wiz_values][$wiz_key], 'NO_DATA');
			
			// проверим наличие заголовков и отдадим редультат	
			if (!in_array($wiz_row['type'], unserialize(WIZARD_NOTITLES)))
				{
				foreach($wiz_row['titles'][CMS_LANG] as $sel_key=>$sel_row)
					{
					$sel_arr[$wiz_row['values'][$sel_key]] = 
						[
						'title'	=> get_const($sel_row),
						'value'	=> $wiz_row['values'][$sel_key],
						];	
					}
				$value = ready_val($sel_arr[$value]['title'], 'NO_DATA');
				}  
				$rows[] = 
					TAB."<div class='row'>".
					TAB."<div class='field p5'>".get_field_by_lang($wiz_row['label'], CMS_LANG, '')."</div>".
					TAB."<div class='field p5'>{$value}</div>".
					TAB."</div>";
				}
			return	TAB."<div class='list'>".
				implode('', $rows).
				TAB."</div>";
			}
		}	
	
		
	//..............................................................................
	// добавляет товар
	//..............................................................................	
	static function _add($options=NULL)
		{
		global $_USER;
		if (is_array($options) AND isset($options['object_id']))
			{
			$options['table_name']	= ready_val($options['table_name'], DEFAULT_ITEM_TABLE);
			
			$values_arr = [
				'user_id'	=> ready_val($options['user_id'], $_USER->id()),
				'avatar'	=> ready_val($options['avatar']),
				'price'		=> ready_val($options['price'], 0),
				'object_id'	=> ready_val($options['object_id']),
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

	//..............................................................................
	// возвращает расшифровку места хранения товара
	//..............................................................................	
	static function _place($row=NULL)
		{
		return 
			(ready_val($row['stock_id']) ? TAB.get_const('STOCK_TITLE').": {$row['stock_id']}" : '').
			(ready_val($row['cabinet']) ? TAB."<br/>".get_const('CABINET_TITLE').": {$row['cabinet']}" : '').
			(ready_val($row['rack']) ? TAB."<br/>".get_const('RACK_TITLE').": {$row['rack']}" : '').
			(ready_val($row['position']) ? TAB."<br/>".get_const('POSITION_TITLE').": {$row['position']}" : '').
			(ready_val($row['half']) ? TAB."<br/>".get_const('HALF_TITLE').": ".get_const('HULF_'.$row['position']) : '').
			"";

		}

	//..............................................................................
	// возвращает старую расшифровку места хранения товара
	//..............................................................................	
	static function _old_place($row=NULL)
		{
		return 
//			(ready_val($row['stock_id']) ? TAB."<br/>".get_const('STOCK_TITLE').": {$row['stock_id']}" : '').
			(ready_val($row['cabinet']) ? $row['cabinet'] : '').
			(ready_val($row['rack']) ? "-{$row['rack']}" : '').
			(ready_val($row['position']) ? "-{$row['position']}" : '').
//			(ready_val($row['half']) ? TAB."<br/>".get_const('HALF_TITLE').": ".get_const('HULF_'.$row['position']) : '').
			"";

		}

	//..............................................................................
	// возвращает скомпилированный код товара
	//..............................................................................	
	public function code() 
		{
		return ($this->code);
		}	
	}	
?>
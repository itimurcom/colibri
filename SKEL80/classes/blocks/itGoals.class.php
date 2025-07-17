<?php
// ================ CRC ================
// version: 1.15.03
// hash: 51152ccbdf51c6af07f62f177f603eebbd94940794eb1498bc31bcb664e5d9ba
// date: 21 March 2019 20:47
// ================ CRC ================
global $plug_css;
$plug_css[] = 'class.itGoals.css';

//..............................................................................
// itGoals : класс, который отображает достижения сайта
//..............................................................................
class itGoals
	{
	public $data, $code;

	//..............................................................................
	// конструктор класса - создает блок статистики достижений сайта
	//..............................................................................	
	// массив options состоит из полей :
	//	'title'		=> надпись над результатами статистики
	//	'table'		=> таблица из базы из котрой ведется подстчет
	//	'condition'	=> условия выборки
	//	'value'		=> запрос MySQL, присваемый переменной 'value'
	//	'order'		=> сортировка данных, чтобы выбрать верхнее значение
	//	'color'		=> цвет, которым будет отображатсья фон достижения
	//..............................................................................	
	public function __construct($options=NULL)
		{
		if (is_array($options))
			{
			foreach ($options as $key=>$row)
				{
				if (!isset($row['color']))
					{
					$row['color'] = 'blue';
					}
				$this->data[] =	itGoals::get_goals_field($row);
				}
			}
		}

	//..............................................................................
	// делает запрос и возвращает код результата одного поля блока достижений сайта
	//..............................................................................
	static function get_goals_field($row)
		{
		$result = '';
		if (isset($row['table']) and ($row['table']!=NULL))
			{
			$db = new itMySQL();
			$ed_rec = $db->request("SELECT {$row['value']} as `value` FROM {$db->db_prefix}{$row['table']} WHERE {$row['condition']} ORDER BY {$row['order']} LIMIT 1");
			unset($db);
			} else $ed_rec[0]['value'] = $row['value'];

		if (isset($ed_rec[0]['value']))
			{
			$value = intval($ed_rec[0]['value']);
			$value = str_replace(',', '.', ($value<1000000) ? (($value>1000) ? round($value/1000,2)."K" : $value) : round($value/1000000,2)."M");
				
			$result = TAB."<div class='goals_wrapper' title='".get_const(ready_val($row['desc']))."'>".
				(isset($row['url']) ? TAB."<a href='{$row['url']}' class='goals_field {$row['color']}'>" : TAB."<div class='goals_field {$row['color']}'>").
				TAB."<div class='goals_value'>{$value}</div>".
				TAB."<div class='goals_title'>".get_const($row['title'])."</div>".
				(isset($row['url']) ? TAB."</a>" : TAB."</div>").
				TAB."</div>";
			}
		return $result;
		}

	//..............................................................................
	// компилирует код блока достижений сайта
	//..............................................................................
	public function compile()
		{
		if (is_array($this->data))
			{
			$this->code = TAB."<div class='goals_div'>";
			foreach ($this->data as $key=>$row)
				{
				$this->code .= $row;
				}
			$this->code .= TAB."</div>";
			}
		}

	//..............................................................................
	// возвращает код
	//..............................................................................
	public function code()
		{
		return $this->code;
		}
	
	} //class
?>
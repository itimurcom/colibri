<?php
global $tab_counter, $tab_menu;
$tab_counter = (function_exists('rand_id')) ? rand_id() : 0;

//..............................................................................
// itTabs : класс построения вкладок кода
//..............................................................................
class itTabs
	{
	public $name, $data, $tab, $tab_id, $options, $code, $set, $default;
	

	//..............................................................................
	// конструктор класса - получает данные из базы и заносит в data
	//..............................................................................	
	// 'tabs' 	=> [
	//			'title',- отображаемое название (константа)
	//			'name',	- имя вкладки для открытия по ссылке
	//			'code',- код вкладки
	//		] - значения кода для вкладок
	//
	//
	// 'tab'	=> имя открытой раскладки если перередается / NULL или
	// 'tab_id'	=> номер открытой вкладки / NULL
	//
	//..............................................................................	
	public function __construct($options = NULL)
		{
		global $tab_counter;
		$tab_counter++;
		
		$this->options 	= $options;
		$this->name	= "tabs-{$tab_counter}";
		$this->set	= (isset($options['set'])) ? $options['set'] : 'tab';
		$this->data	= (isset($options['tabs']) AND is_array($options['tabs'])) ? $options['tabs'] : NULL;
		$this->tab	= (isset($options['tab'])) ? $options['tab'] : NULL;		
		$this->tab_id	= (isset($options['tab_id']))
			? $options['tab_id']
			: ((is_null($this->tab)) ? NULL : 1);
		}


	//..............................................................................
	// компилирует код блока вкладок
	//..............................................................................	
	public function compile()
		{
		global $_USER;
		global $tab_menu;
		
		$this->code = '';
                $buttons = NULL;
                $tabs = NULL;

		if (is_array($this->data))
			{
			foreach ($this->data as $key=>$row)
				{
				$row['tab_id'] = $key + 1;

				$active = (($this->tab==$row['name'] AND !is_null($this->tab)) OR ($this->tab_id==$row['tab_id'] AND !is_null($this->tab_id))) ? ' active' : '';
				$color = isset($row['class']) ? " {$row['class']}" : NULL;
				$nomobile = empty($active) ? ' nomobile' : "";

				$data = simple_encrypt(serialize(array(
					'set' 		=> $this->set,
					'value'		=> $row['name'],
					'user_id'       => $_USER->id(),
					)));		

				$buttons[] =
					TAB."<div class='inset{$active}{$nomobile}{$color} boxed' id='inset-{$this->name}-{$row['name']}' data-rel='{$this->name}' data-set='{$data}' data-tab='{$row['name']}' onclick='active_tab(this);'>".
					((!is_null($row['image'])) ? "<img src='{$row['image']}'/>" : '').
					get_const($row['title']).
					TAB."</div>";

				$tabs[] = 
					TAB."<div class='tab{$active} boxed' id='tab-{$this->name}-{$row['name']}' data-rel='{$this->name}' data-tab='{$row['name']}'>".
					TAB."<div class='container boxed'>".
					$row['code'].
					TAB."</div>".
					TAB."</div>";
					
				if (ready_val($row['mobile']))
				$tab_menu[$key] = 
					[
					'controller'	=> NULL,
					'view'		=> NULL,
					'mobile'	=> true,
					'top'		=> false,
					'bottom'	=> false,
					'code'		=> TAB."<div class='boxed' data-rel='{$this->name}' data-set='{$data}' data-tab='{$row['name']}' onclick=\"active_tab(this);$(this).closest('.itmenu_mobile').slideToggle(400);\">".
						((!is_null($row['image'])) ? "<img src='{$row['image']}'/>" : '').
						get_const($row['title']).
						TAB."</div>",
					'show'		=> 1,
					];
				}

			if (is_array($buttons))
				$this->code .= TAB."<div class='menu boxed' id='menu-{$this->name}'>".implode($buttons).TAB."</div>";

			if (is_array($tabs))
				$this->code .= implode($tabs);
			$this->code = TAB."<div class='tabs_div boxed' id='{$this->name}'>{$this->code}</div>";
			}
		}


	//..............................................................................
	// добавляет вкладку в базу владок
	//..............................................................................	
	public function add($options)
		{
		$this->data[] = [
			'title'		=> (isset($options['title']) 	? $options['title'] 	: ''),
			'name'		=> (isset($options['name']) 	? $options['name'] 	: ''),
			'code'		=> (isset($options['code']) 	? $options['code'] 	: ''),
			'image' 	=> (isset($options['image']) 	? $options['image'] 	: NULL),
			'mobile' 	=> (isset($options['mobile']) 	? $options['mobile'] 	: 1),
			'class' 	=> (isset($options['class']) 	? $options['class'] 	: 'white'),
			];
		}

	//..............................................................................
	// устанавливает или возвращает значение выбранной вкладки
	//..............................................................................	
	public function active($tab=NULL)
		{
		if (is_null($tab))
			{
			return $this->tab;
			} else $this->tab = $tab;
		}

	//..............................................................................
	// устанавливает или возвращает значение номера выбранной вкладки
	//..............................................................................	
	public function active_id($tab_id=NULL)
		{
		if (is_null($tab_id))
			{
			return $this->tab_id;
			} else $this->tab_id = $tab_id;
		}


	//..............................................................................
	// возвращает код вкладок
	//..............................................................................
	public function code()
		{
		return $this->code;
		}

	} // class
?>
<?php
// ================ CRC ================
// version: 1.15.02
// hash: e7baff561b194566e933b9b7f9cd18fcc214e7ac96f1addba275d3b933df9773
// date: 27 June 2019  9:14
// ================ CRC ================

global $soclink_counter;
$soclink_counter = (function_exists('rand_id')) ? rand_id() : time();

global $plug_css;
$plug_css[] = 'class.itSharer.css';

//..............................................................................
// itSharer : класс постинга в соцсети (шаринг)
//..............................................................................
class itSharer
	{
	public $code, $link;

	//..............................................................................
	// конструктор класса - создает объект и обрабатывает данные по шарингу
	//..............................................................................
	public function __construct($options=NULL)
		{
		$this->link = ready_val($options['link'], "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
		$this->compile();
		}

	//..............................................................................
	// возвращает код доступных кнопок шарига текущей страницы
	//..............................................................................
	public function compile()
		{
		global $social_cat;

		$result = '';
		if (is_array($social_cat))
		foreach ($social_cat as $key=>$row)
			{
			$result .= $this->get_social_link($key);
			}

		$this->code = $result;
		}

	//..............................................................................
	// возвращает код кнопки шарига текущей страницы
	//..............................................................................
	private function get_social_link($type=NULL)
		{
		global $social_cat;
		global $soclink_counter;
		
		if ($social_cat[$type]['show']!=1) return;

		$soclink_counter++;

		$l_class 	= $social_cat[$type]['class'];
		$field_id 	= "soclink-$soclink_counter";
		$l_title	= $social_cat[$type]['share'];

		$result = TAB."<span id='$field_id' class='l-ico $l_class' title='$l_title'></span>".
			TAB."
		<script>
		$('#$field_id').click( function()
			{
			social_popup('{$l_class}', '{$this->link}')
			});
		</script>";
		return $result;
		}

	//..............................................................................
	// возвращает код блока кнопок для шаринга
	//..............................................................................
	public function code()
		{
		return $this->code;
		}

	} // class






//..............................................................................
// возвращает код кнопок на группы в сети
//..............................................................................
function get_social_groups()
	{
	global $social_cat;
	$tab = "\n\t";
	foreach ($social_cat as $key=>$row)
		{
		if ($row['group'])
			{
			$result .= get_group_link($key);
			}
		}
	if ($result)
		{
		$result = "$tab<span class='title_groups'>".TITLE_GROUPS."</span>".
			"$tab <div class='groups_div'>".
			$result.
			"$tab</div>";
		}
	return $result;
	}

//..............................................................................
// возвращает код кнопки перехода на группу в сети
//..............................................................................
function get_group_link($type=NULL)
	{
	global $_PATH;
	global $social_cat;
	global $num_sum;

	if ($social_cat[$type]['show']!=1) return;

	$l_class 	= $social_cat[$type]['class'];
	$l_link		= $social_cat[$type]['group'];
	$l_title	= $social_cat[$type]['title'];

	$tab = "\n\t\t";

	$result .= "$tab<a target='_blank' href='$l_link' class='l-ico $l_class' title='$l_title'></a>";
	return $result;
	}
?>
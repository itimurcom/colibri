<?php
// ================ CRC ================
// version: 1.15.03
// hash: 161c54f5e989e1a84c800e98320a170f7f1d6679f463abf53bd56357e733ec6b
// date: 09 September 2019  5:10
// ================ CRC ================
global $slider_count;
$slider_count = 0;

global $plug_css;
$plug_css[] = 'class.itSlider.css';


//..............................................................................
// itSlider : класс фиксированного слайдера для основной страницы
//..............................................................................
class itSlider
	{
	public $table_name, $db_prefix,
		$time,
		$code;
	private $db;

	//..............................................................................
	// конструктор класса - соединяется с базой данных при создании класса
	//..............................................................................
	public function __construct($options=NULL)
		{
		global $_USER;
		global $slider_count;

		$this->table_name 	= ready_val($options['table'], DEFAULT_SLIDER_TABLE);
		$this->db_prefix	= ready_val($options['prefix'], DB_PREFIX);
		$this->time		= ready_val($options['time'], 6000);
		
		if ($_USER->is_logged())
			{
			$query = "SELECT * FROM {$this->db_prefix}{$this->table_name} WHERE 1 ".
				"ORDER by `status`, `id`";
			} else 	{
			$query = "SELECT * FROM {$this->db_prefix}{$this->table_name} WHERE `status`='PUBLISHED' ".
				"ORDER by `id`";
				}

		$this->ed_rec = itMySQL::_request($query);
		$slider_count++;
		}

	//..............................................................................
	// генерирует код слайдера на основе установленных параметров и заносит в code
	//..............................................................................
	public function compile()
		{
		global $slider_count, $_USER;

		if (count($this->ed_rec)==0) return;
		$result = TAB."<div id='slider-$slider_count' class='main_slider'>";

		$selected_slide = 0;

		foreach ($this->ed_rec as $key=>$row)
			{
			if (isset($_REQUEST['slide']) and ($row['id']==$_REQUEST['slide']) )
				{
				$selected_slide = $key;
				}
			$title = get_field_by_lang($row['title_xml']);
			$href = get_field_by_lang($row['href_xml']);
			$src = get_thumbnail($row['avatar'], 'SLIDER_MAIN');

			if ($title!=NO_TITLE) 
				{
				if ($href!=NO_TITLE)
					{
					$title_str = TAB."<div class='slider_text_container'>".
						TAB."<a href='$href' target='_blank'>".
						TAB."<span class='slider_text'>$title</span>".
						TAB."</a>".
						TAB."</div>";
					} else $title_str = TAB."<div class='slider_text_container'>".
							TAB."<span class='slider_text'>$title</span>".
							TAB."</div>";
				} else $title_str = '';

			$admin_str = ($_USER->is_logged()) ? TAB."<div class='slider_admin'>".get_slider_x_event($row, $key).get_slider_href_event($row, $key).get_slider_title_event($row, $key).TAB."</div>" : '';

			if (($title==NO_TITLE) and ($href!=NO_TITLE))
				{
				$slide_str = TAB."<a href='$href'><img class='gallery_avatar' src='$src'/>".TAB."</a>";					
				} else $slide_str = TAB."<img class='gallery_avatar' src='$src'/>";



			$result .= TAB."<div>".
				$slide_str.
				$title_str.
				$admin_str.
				(($_USER->is_logged()) ? TAB."<div class='slider_n'>#".($key+1)."</div>" : '').
				TAB."</div>";

			unset($title);
			}
		$result .= TAB."</div>";
		$result .= TAB."	<script>
			$('#slider-$slider_count').css('height','0');
			$(document).ready(function()
				{
				var slider = $('#slider-$slider_count').bxSlider(
					{
					pause : {$this->time},
					useCSS : false,
					mode : 'horizontal',
					startSlide : ".(($_USER->is_logged()) ? $selected_slide : 0).",
					auto : ".(($_USER->is_logged() or (count($this->ed_rec)<2)) ? 'false' : 'true').",
					moveSlides : 1,
					touchEnabled : (navigator.maxTouchPoints > 0),
					".(($_USER->is_logged()) ? "infiniteLoop : false,\n" : '')."
					".(($_USER->is_logged()) ?  "pause : ".get_const('DEFAULT_SLIDER_PAUSE').",\n": '')."
					});
				$('#slider-$slider_count').animate({opacity:1},600);
				});
			</script>";
		$this->code = $result;
		}

	//..............................................................................
	// устанавливает надпись слайдера для конкретного языка
	//..............................................................................
	static function set_title($rec_id, $value, $lang=CMS_LANG, $table_name=DEFAULT_SLIDER_TABLE)
		{
		$ed_rec = itMySQL::_get_rec_from_db($table_name, $rec_id);
		$ed_rec['title_xml'][$lang] = $value;
		itMySQL::_update_value_db($table_name, $rec_id, $ed_rec['title_xml'], 'title_xml');
		}

	//..............................................................................
	// устанавливает надпись слайдера для конкретного языка
	//..............................................................................
	static function add($value, $lang=CMS_LANG, $table_name=DEFAULT_SLIDER_TABLE)
		{
		return itMySQL::_insert_rec($table_name, ['avatar'=>$value]);
		}


	//..............................................................................
	// устанавливает ссылку слайдера для конкретного языка
	//..............................................................................
	static function set_href($rec_id, $value, $lang=CMS_LANG, $table_name=DEFAULT_SLIDER_TABLE)
		{
		$ed_rec = itMySQL::_get_rec_from_db($table_name, $rec_id);
		$ed_rec['href_xml'][$lang] = $value;
		itMySQL::_update_value_db($table_name, $rec_id, $ed_rec['href_xml'], 'href_xml');
		}


	//..............................................................................
	// возвращает код селектора с привязкой обраточика события ($options)
	//..............................................................................
	public function code()
		{
		return $this->code;
		}

	} // class
?>
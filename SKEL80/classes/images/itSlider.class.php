<?php
global $slider_count;
$slider_count = 0;

global $plug_css;
$plug_css[] = 'class.itSlider.css';


// itSlider : класс фиксированного слайдера для основной страницы
class itSlider
	{
	public $table_name, $db_prefix,
		$time,
		$code,
		$ed_rec;
	private $db;

	// конструктор класса - соединяется с базой данных при создании класса
	public function __construct($options=NULL)
		{
		global $_USER;
		global $slider_count;
		$options = is_array($options) ? $options : [];

		$this->table_name 	= ready_value($options['table'] ?? NULL, DEFAULT_SLIDER_TABLE);
		$this->db_prefix	= ready_value($options['prefix'] ?? NULL, DB_PREFIX);
		$this->time		= ready_value($options['time'] ?? NULL, 6000);
		$is_logged = (isset($_USER) AND is_object($_USER) AND method_exists($_USER, 'is_logged') AND $_USER->is_logged());
		
		if ($is_logged)
			{
			$query = "SELECT * FROM {$this->db_prefix}{$this->table_name} WHERE 1 ".
				"ORDER by `status`, `id`";
			} else 	{
			$query = "SELECT * FROM {$this->db_prefix}{$this->table_name} WHERE `status`='PUBLISHED' ".
				"ORDER by `id`";
				}

		$this->ed_rec = itMySQL::_request($query);
		$this->ed_rec = is_array($this->ed_rec) ? $this->ed_rec : [];
		$slider_count++;
		}

	// генерирует код слайдера на основе установленных параметров и заносит в code
	public function compile()
		{
		global $slider_count, $_USER;
		$is_logged = (isset($_USER) AND is_object($_USER) AND method_exists($_USER, 'is_logged') AND $_USER->is_logged());

		if (!is_array($this->ed_rec) OR count($this->ed_rec)==0) return;
		$result = TAB."<div id='slider-$slider_count' class='main_slider'>";

		$selected_slide = 0;
		$request_slide = ready_value($_REQUEST['slide'] ?? NULL, NULL);

		foreach ($this->ed_rec as $key=>$row)
			{
			if (!is_array($row)) continue;
			if (!is_null($request_slide) and ready_value($row['id'] ?? NULL, NULL)==$request_slide )
				{
				$selected_slide = $key;
				}
			$title = get_field_by_lang(ready_value($row['title_xml'] ?? NULL, []));
			$href = get_field_by_lang(ready_value($row['href_xml'] ?? NULL, []));
			$src = get_thumbnail(ready_value($row['avatar'] ?? NULL, NULL), 'SLIDER_MAIN');

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

			$admin_str = ($is_logged) ? TAB."<div class='slider_admin'>".get_slider_x_event($row, $key).get_slider_href_event($row, $key).get_slider_title_event($row, $key).TAB."</div>" : '';

			if (($title==NO_TITLE) and ($href!=NO_TITLE))
				{
				$slide_str = TAB."<a href='$href'><img class='gallery_avatar' src='$src'/>".TAB."</a>";					
				} else $slide_str = TAB."<img class='gallery_avatar' src='$src'/>";



			$result .= TAB."<div>".
				$slide_str.
				$title_str.
				$admin_str.
				(($is_logged) ? TAB."<div class='slider_n'>#".($key+1)."</div>" : '').
				TAB."</div>";

			unset($title);
			}
		$result .= TAB."</div>";
		$result .= TAB."\t<script>
			$('#slider-$slider_count').css('height','0');
			$(document).ready(function()
				{
				var slider = $('#slider-$slider_count').bxSlider(
					{
					pause : {$this->time},
					useCSS : false,
					mode : 'horizontal',
					startSlide : ".(($is_logged) ? $selected_slide : 0).",
					auto : ".(($is_logged or (count($this->ed_rec)<2)) ? 'false' : 'true').",
					moveSlides : 1,
					touchEnabled : (navigator.maxTouchPoints > 0),
					".(($is_logged) ? "infiniteLoop : false,\n" : '')."
					".(($is_logged) ?  "pause : ".get_const('DEFAULT_SLIDER_PAUSE').",\n": '')."
					});
				$('#slider-$slider_count').animate({opacity:1},600);
				});
			</script>";
		$this->code = $result;
		}

	// устанавливает надпись слайдера для конкретного языка
	static function set_title($rec_id, $value, $lang=CMS_LANG, $table_name=DEFAULT_SLIDER_TABLE)
		{
		$ed_rec = itMySQL::_get_rec_from_db($table_name, $rec_id);
		if (!is_array($ed_rec)) return false;
		$title_xml = ready_value($ed_rec['title_xml'] ?? NULL, []);
		$title_xml = is_array($title_xml) ? $title_xml : [];
		$title_xml[$lang] = $value;
		itMySQL::_update_value_db($table_name, $rec_id, $title_xml, 'title_xml');
		}

	// устанавливает надпись слайдера для конкретного языка
	static function add($value, $lang=CMS_LANG, $table_name=DEFAULT_SLIDER_TABLE)
		{
		return itMySQL::_insert_rec($table_name, ['avatar'=>$value]);
		}


	// устанавливает ссылку слайдера для конкретного языка
	static function set_href($rec_id, $value, $lang=CMS_LANG, $table_name=DEFAULT_SLIDER_TABLE)
		{
		$ed_rec = itMySQL::_get_rec_from_db($table_name, $rec_id);
		if (!is_array($ed_rec)) return false;
		$href_xml = ready_value($ed_rec['href_xml'] ?? NULL, []);
		$href_xml = is_array($href_xml) ? $href_xml : [];
		$href_xml[$lang] = $value;
		itMySQL::_update_value_db($table_name, $rec_id, $href_xml, 'href_xml');
		}


	// возвращает код селектора с привязкой обраточика события ($options)
	public function code()
		{
		return $this->code;
		}

	} // class
?>

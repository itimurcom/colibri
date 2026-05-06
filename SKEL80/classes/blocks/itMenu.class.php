<?php
global $itmenu_count;
$itmenu_count = (function_exists('rand_id')) ? rand_id() : time();

definition([
	'DEFAULT_MENU_FIXED'	=> false,
	'DEFAULT_MENU_MOBILE'	=> false,

	'SHOW_TOP_BOTTOM'		=> 1,
	'SHOW_TOP_ONLY'		=> 2,
	'SHOW_BOTTOM_ONLY'	=> 3,

	'DEFAULT_MENU_COLORS'	=>
	serialize([
		'menu'	=> [
			'common'=> [
				'color'	=>	'white',
				'back'	=>	'rgba(128, 128, 255, .4)',
				],
			'hover'	=> [
				'color'	=>	'rgba(128, 128, 255, 1)',
				'back'	=>	'rgba(128, 128, 255, 1)',
				],
			'selected' => [
				'color'	=>	'rgba(0, 0, 128, .9)',
				'back'	=>	'rgba(128, 128, 255, 1)',
				],
			'seleover' => [
				'color'	=>	'rgba(0, 0, 128, .7)',
				'back'	=>	'rgba(128, 128, 255, 1)',
				],
			],
		'submenu'	=> [
			'common'=> [
				'color'	=>	'white',
				'back'	=>	'rgba(128, 128, 255, .4)',
				],
			'hover'	=> [
				'color'	=>	'rgba(128, 128, 255, 1)',
				'back'	=>	'rgba(128, 128, 255, 1)',
				],
			'selected' => [
				'color'	=>	'rgba(0, 0, 128, .9)',
				'back'	=>	'rgba(128, 128, 255, 1)',
				],
			'seleover' => [
				'color'	=>	'rgba(0, 0, 128, .7)',
				'back'	=>	'rgba(128, 128, 255, 1)',
				],
			],
		'mobile' => [
			'common'=> [
				'color'	=>	'white',
				'back'	=>	'rgba(128, 128, 255, .4)',
				],
			'hover'	=> [
				'color'	=>	'rgba(128, 128, 255, 1)',
				'back'	=>	'rgba(128, 128, 255, 1)',
				],
			'selected' => [
				'color'	=>	'rgba(0, 0, 128, .9)',
				'back'	=>	'rgba(128, 128, 255, 1)',
				],
			'seleover' => [
				'color'	=>	'rgba(0, 0, 128, .7)',
				'back'	=>	'rgba(128, 128, 255, 1)',
				],
			],
		]),
	]);
class itMenu
	{
	public
		$data, $code,
		$colors,
		$fixed, $mobile,
		$element_id, $subdata;

	public function __construct($options=NULL)
		{
		global $itmenu_count;
		$itmenu_count++;
		$options = is_array($options) ? $options : [];
		$this->element_id 		= "itmenu-{$itmenu_count}";

		$this->data 		= ready_value($options['data'] ?? NULL, []);
		$this->data 		= is_array($this->data) ? $this->data : [];
		$this->subdata		= ready_value($options['subdata'] ?? NULL, []);
		$this->subdata		= is_array($this->subdata) ? $this->subdata : [];
		$this->code 		= [];

		$this->colors 	= unserialize(DEFAULT_MENU_COLORS);
		$this->colors 	= is_array($this->colors) ? $this->colors : [];
		$custom_colors = ready_value($options['colors'] ?? NULL, []);
		$this->colors	= is_array($custom_colors) ? array_replace_recursive($this->colors, $custom_colors) : $this->colors;

		$this->fixed 	= ready_value($options['fixed'] ?? NULL, DEFAULT_MENU_FIXED);		// фиксированное меню плавает при прокрутке
		$this->mobile	= ready_value($options['mobile'] ?? NULL, DEFAULT_MENU_MOBILE);		// мобильная версия меню (прячет основное/ заменяет боковым)
		}

	private function current_view()
		{
		return ready_value($_REQUEST['view'] ?? NULL, '');
		}

	private function row_link($row)
		{
		$link = ready_value($row['link'] ?? NULL, NULL);
		if (!is_null($link) AND $link!=='') return $link;

		$controller = ready_value($row['controller'] ?? NULL, '');
		return ($controller!=='') ? "/".CMS_LANG."/{$controller}/" : "/".CMS_LANG."/";
		}

	private function color_value($group, $state, $field, $default='')
		{
		return ready_value($this->colors[$group][$state][$field] ?? NULL, $default);
		}

	public function prepare_top()
		{
		$rows = [];
		$current_view = $this->current_view();

		foreach ($this->data as $key=>$row)
			{
			if (!is_array($row)) continue;
			$row_view = ready_value($row['view'] ?? NULL, '');
			$selected  = ($row_view!=='' AND $row_view==$current_view) ? " selected" : '';
			if (ready_value($row['show'] ?? NULL, false) AND ready_value($row['top'] ?? NULL, false))
				{
				if (isset($row['code']) AND !is_null($row['code']))
					{
					$rows[] =
						TAB."<div class='itmenu_top_button menu-{$key} transparent boxed'>".
						$row['code'].
						TAB."</div>";
					} else	{
						$link = $this->row_link($row);
						$rows[] =
							TAB."<div class='itmenu_top_button{$selected} menu-{$key} boxed' onclick=\"window.location.href='$link'\">".
							TAB.get_const(ready_value($row['title'] ?? NULL, '')) .
							TAB."</div>";
						}
				}
			}

		$size = count($rows) ? str_replace(',', '.', round(100/count($rows),1)) : 100;

		$style =
			TAB."<style>
				{
				color: ".$this->color_value('menu', 'common', 'color', 'white').";
				background: ".$this->color_value('menu', 'common', 'back', 'transparent').";
				width: {$size}%;
				}

				{
				color: ".$this->color_value('menu', 'selected', 'color', 'white').";
				background: ".$this->color_value('menu', 'selected', 'back', 'transparent').";
				}

				{
				color: ".$this->color_value('menu', 'hover', 'color', 'white').";
				background: ".$this->color_value('menu', 'hover', 'back', 'transparent').";
				}

				{
				color: ".$this->color_value('menu', 'seleover', 'color', 'white').";
				background: ".$this->color_value('menu', 'seleover', 'back', 'transparent').";
				}".
			TAB."</style>";

		$this->code['top'] = count($rows) ? TAB."{$style}<div id='{$this->element_id}-top' class='itmenu_top boxed nomobile'>".implode('', $rows).TAB."</div>" : "";

		if ($this->mobile)
			{
			$this->prepare_mobile();
			}
		}

	public function prepare_mobile()
		{
		$rows = [];
		$selected_str = '';
		$current_view = $this->current_view();
		foreach ($this->data as $key=>$row)
			{
			if (!is_array($row)) continue;
			if (ready_value($row['show'] ?? NULL, false) AND ready_value($row['mobile'] ?? NULL, false))
				{
				if (ready_value($row['view'] ?? NULL, '')==$current_view)
					{
					$selected  = " selected";
					$selected_str = get_const(ready_value($row['title'] ?? NULL, ''));
					} else	{
						$selected  = "";
						}
				if (isset($row['code']) AND !is_null($row['code']))
					{
					$rows[] =
						TAB."<div class='itmenu_mobile_button menu-{$key} transparent boxed'>".
						$row['code'].
						TAB."</div>";
					} else	{
						$link = $this->row_link($row);
						$rows[] =
							TAB."<div class='itmenu_mobile_button{$selected} menu-{$key} boxed' onclick=\"window.location.href='$link'\">".
							TAB.get_const(ready_value($row['title'] ?? NULL, '')).

							TAB."</div>";
						}
				}
			}
			$style =
			TAB."<style>
				{
				color: ".$this->color_value('mobile', 'common', 'color', 'white').";
				background: ".$this->color_value('mobile', 'common', 'back', 'transparent').";

				}

				{
				color: ".$this->color_value('mobile', 'selected', 'color', 'white').";
				background: ".$this->color_value('mobile', 'selected', 'back', 'transparent').";
				}

				{
				color: ".$this->color_value('mobile', 'hover', 'color', 'white').";
				background: ".$this->color_value('mobile', 'hover', 'back', 'transparent').";
				}

				{
				color: ".$this->color_value('mobile', 'seleover', 'color', 'white').";
				background: ".$this->color_value('mobile', 'seleover', 'back', 'transparent').";
				}".
			TAB."</style>";
		$this->code['mobile'] = count($rows)
			?	TAB."<div class='itmenu_mobile_title boxed mobile' onclick=\"$('#{$this->element_id}-mobile').slideToggle(400); $('.itmenu_hamburger:after').rotate({count:.5, forceJS:true});\">".$selected_str.TAB."<span class='itmenu_hamburger'></span></div>".
				TAB."{$style}<div id='{$this->element_id}-mobile' class='itmenu_mobile boxed mobile'>".implode('', $rows).TAB."</div>"
			: 	"";
		}

	public function compile()
		{
		}

	} // class
?>

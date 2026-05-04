<?php
global $itmenu_count;
$itmenu_count = (function_exists('rand_id')) ? rand_id() : time();

definition([
	'DEFAULT_MENU_FIXED'	=> false,
	'DEFAULT_MENU_MOBILE'	=> false,

	'SHOW_TOP_BOTTOM'	=> 1,
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
		$this->element_id 		= "itmenu-{$itmenu_count}";

		$this->data 		= ready_val($options['data'], NULL);
		$this->subdata		= ready_val($options['subdata'], NULL);

		$this->colors 	= unserialize(DEFAULT_MENU_COLORS);
		$this->colors	= isset($options['colors']) ? array_merge($this->colors, $options['colors']) : $this->colors;

		$this->fixed 	= ready_val($options['fixed'], DEFAULT_MENU_FIXED);		// фиксированное меню плавает при прокрутке
		$this->mobile	= ready_val($options['mobile'], DEFAULT_MENU_MOBILE);		// мобильная версия меню (прячет основное/ заменяет боковым)
		}

	public function prepare_top()
		{
		$rows = [];

		foreach ($this->data as $key=>$row)
			{
			$selected  = ($row['view']==$_REQUEST['view']) ? " selected" : '';
			if ($row['show'] AND ready_val($row['top']))
				{
				if (isset($row['code']) AND !is_null($row['code']))
					{
					$rows[] =
						TAB."<div class='itmenu_top_button menu-{$key} transparent boxed'>".
						$row['code'].
						TAB."</div>";
					} else	{
						$link = is_null($row['link']) ? "/".CMS_LANG."/{$row['controller']}/" : $link;
						$rows[] =
							TAB."<div class='itmenu_top_button{$selected} menu-{$key} boxed' onclick=\"window.location.href='$link'\">".
							TAB.get_const($row['title']).
							TAB."</div>";
						}
				}
			}

		$size = count($rows) ? str_replace(',', '.', round(100/count($rows),1)) : 100;

		$style =
			TAB."<style>
				{
				color: {$this->colors['menu']['common']['color']};
				background: {$this->colors['menu']['common']['back']};
				width: {$size}%;
				}

				{
				color: {$this->colors['menu']['selected']['color']};
				background: {$this->colors['menu']['selected']['back']};
				}

				{
				color: {$this->colors['menu']['hover']['color']};
				background: {$this->colors['menu']['hover']['back']};
				}

				{
				color: {$this->colors['menu']['seleover']['color']};
				background: {$this->colors['menu']['seleover']['back']};
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
		foreach ($this->data as $key=>$row)
			{
			if ($row['show'] AND ready_val($row['mobile']))
				{
				if ($row['view']==$_REQUEST['view'])
					{
					$selected  = " selected";
					$selected_str = get_const($row['title']);
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
						$link = is_null($row['link']) ? "/".CMS_LANG."/{$row['controller']}/" : $link;
						$rows[] =
							TAB."<div class='itmenu_mobile_button{$selected} menu-{$key} boxed' onclick=\"window.location.href='$link'\">".
							TAB.get_const($row['title']).

							TAB."</div>";
						}
				}
			}
			$style =
			TAB."<style>
				{
				color: {$this->colors['mobile']['common']['color']};
				background: {$this->colors['mobile']['common']['back']};

				}

				{
				color: {$this->colors['mobile']['selected']['color']};
				background: {$this->colors['mobile']['selected']['back']};
				}

				{
				color: {$this->colors['mobile']['hover']['color']};
				background: {$this->colors['mobile']['hover']['back']};
				}

				{
				color: {$this->colors['mobile']['seleover']['color']};
				background: {$this->colors['mobile']['seleover']['back']};
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

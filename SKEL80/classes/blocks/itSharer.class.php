<?php

global $soclink_counter;
$soclink_counter = (function_exists('rand_id')) ? rand_id() : time();

global $plug_css;
$plug_css[] = 'class.itSharer.css';

class itSharer
	{
	public $code, $link;

	public function __construct($options=NULL)
		{
		$options = is_array($options) ? $options : [];
		$host = ready_value($_SERVER['HTTP_HOST'] ?? NULL, 'localhost');
		$uri = ready_value($_SERVER['REQUEST_URI'] ?? NULL, '/');
		$this->link = ready_value($options['link'] ?? NULL, "https://{$host}{$uri}");
		$this->compile();
		}

	public function compile()
		{
		global $social_cat;

		$result = '';
		if (is_array($social_cat))
		foreach ($social_cat as $key=>$row)
			{
			if (!is_array($row)) continue;
			$result .= $this->get_social_link($key);
			}

		$this->code = $result;
		}

	private function get_social_link($type=NULL)
		{
		global $social_cat;
		global $soclink_counter;

		if (!isset($social_cat[$type]) OR !is_array($social_cat[$type]) OR ready_value($social_cat[$type]['show'] ?? NULL, 0)!=1) return '';

		$soclink_counter++;

		$l_class 	= ready_value($social_cat[$type]['class'] ?? NULL, '');
		$field_id 	= "soclink-$soclink_counter";
		$l_title	= ready_value($social_cat[$type]['share'] ?? NULL, '');

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

	public function code()
		{
		return $this->code;
		}

	} // class

function get_social_groups()
	{
	global $social_cat;
	$tab = "\n\t";
	$result = '';
	if (is_array($social_cat))
	foreach ($social_cat as $key=>$row)
		{
		if (!is_array($row)) continue;
		if (ready_value($row['group'] ?? NULL, false))
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

function get_group_link($type=NULL)
	{
	global $_PATH;
	global $social_cat;
	global $num_sum;

	if (!isset($social_cat[$type]) OR !is_array($social_cat[$type]) OR ready_value($social_cat[$type]['show'] ?? NULL, 0)!=1) return '';

	$l_class 	= ready_value($social_cat[$type]['class'] ?? NULL, '');
	$l_link		= ready_value($social_cat[$type]['group'] ?? NULL, '#');
	$l_title	= ready_value($social_cat[$type]['title'] ?? NULL, '');

	$tab = "\n\t\t";

	$result = "$tab<a target='_blank' href='$l_link' class='l-ico $l_class' title='$l_title'></a>";
	return $result;
	}
?>

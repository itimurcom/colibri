<?php
// обрезает код если больше строк в поле визарда
function wiz_cut(&$result, $field_value, $cut=WIZ_CUT_LINES)
	{
	if (($count = substr_count($field_value, "<br/>") +1) > $cut)
		{
		$o_close = new itOpenClose($result, [
			'open'	=> ['text' => "[ {$count} ] &#9658;", 'class' => 'green'],
			'close'	=> ['text' => "[ {$count} ] &#9660;", 'class' => 'black'],
			]);
		$result = $o_close->code();
		unset($o_close);
		}
	}
?>
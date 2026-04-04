<?php
// ================ CRC ================
// version: 1.15.02
// hash: 779b03fc3328bd80ff2a4f105bb2524639a5e9f62f28ec6a258f1e68bd02d086
// date: 16 September 2018 19:58
// ================ CRC ================
//..............................................................................
// обрезает код если больше строк в поле визарда
//..............................................................................
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
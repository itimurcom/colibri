<?php
// возвращает событие визарда для категории
function get_wizard_event($row)
	{
	$o_button = new itButton("&#9783;", 'text', ['target' => '_blank', 'href' => "/".CMS_LANG."/wizard/{$row['id']}/"], 'blue' );
	$result = $o_button->code();
	unset($o_button);

	return $result;		
	}
?>
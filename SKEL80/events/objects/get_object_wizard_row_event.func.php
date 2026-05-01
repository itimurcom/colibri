<?php
// возвращает строку разыменования визарда для объекта
function get_object_wizard_row_event($row)
	{
	$func = "get_object_{$row['type']}_event";
	return 
		TAB."<div class='field p5'>".
		get_field_by_lang($row['label']).
		TAB."</div>".
		TAB."<div class='field p5'>".
		(function_exists($func) ? $func($row) : $row['value']).
		TAB."</div>".
		TAB."<div class='field p2'>".(isset($row['user_id']) ? itUser::get_name($row['user_id']) : "").TAB."</div>";
	return print_r($row, 1);
	}
?>
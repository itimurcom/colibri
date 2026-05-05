<?php
// возвращает строку разыменования визарда для объекта
function get_object_wizard_row_event_value($row, $key, $default=NULL)
	{
	return (is_array($row) AND array_key_exists($key, $row)) ? $row[$key] : $default;
	}

function get_object_wizard_row_event($row)
	{
	if (!is_array($row)) return '';
	$type = get_object_wizard_row_event_value($row, 'type', DEFAULT_WIZARD_TYPE);
	$func = "get_object_{$type}_event";
	$value = get_object_wizard_row_event_value($row, 'value', '');
	return 
		TAB."<div class='field p5'>".
		get_field_by_lang(get_object_wizard_row_event_value($row, 'label')).
		TAB."</div>".
		TAB."<div class='field p5'>".
		(function_exists($func) ? $func($row) : $value).
		TAB."</div>".
		TAB."<div class='field p2'>".(get_object_wizard_row_event_value($row, 'user_id') ? itUser::get_name(get_object_wizard_row_event_value($row, 'user_id')) : "").TAB."</div>";
	}
?>
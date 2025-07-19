<?
//..............................................................................
// возвращает код кнопки смены режима редактора формы (версия 2.1)
//..............................................................................
function get_f2_async_event($row, $table_name=DEFAULT_FORM_TABLE)
	{
	$table_name = isset($row['table_name']) ? $row['table_name'] : $table_name;
	$element_id = isset($row['container_id']) ? $row['container_id'] : itForm2::_container_id($row);
	
	$text = ($row['state']=='view') ? 'edit' : 'finish';	
	
	$o_button = new itButton2([
		'title'	=> get_const($text), 
		'type'	=> 'a',
		'ajax'	=> "f2_edstate('#{$element_id}');",
		'class'	=> "edswitch_button {$text}",
		]);
	$result = $o_button->code();
	unset($o_button);
	
	return $result;
	}
?>
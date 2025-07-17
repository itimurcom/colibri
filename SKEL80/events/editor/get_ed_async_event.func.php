<?php
// ================ CRC ================
// version: 1.15.02
// hash: e52999babd012fb6078f4abc493c540f619987c25b533ab2dfe217974fc236c7
// date: 16 September 2018 19:58
// ================ CRC ================
//..............................................................................
// возвращает кнопку смены состояния редактора
//..............................................................................
function get_ed_async_event($row, $table_name=DEFAULT_BLOCK_TABLE)
	{
	$table_name = isset($row['table_name']) ? $row['table_name'] : $table_name;
	$element_id = isset($row['container_id']) ? $row['container_id'] : itEditor::_container_id($row);
	
	$text = ($row['state']=='view') ? 'edit' : 'close';	
	
	$o_button = new itButton(get_const($text), 'a', ['href'=>'#/', 'ajax'=>"editor_edstate('#{$element_id}');", 'class'=>"edswitch_button {$text}"], '' );
	$result = $o_button->code();
	unset($o_button);
	
	
	return $result;
	}
?>
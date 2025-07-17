<?php
// ================ CRC ================
// version: 1.15.02
// hash: 055c7742199fb6f6119054411e17f8b18cf73762d78aced065fcbf31ff921cad
// date: 16 September 2018 19:58
// ================ CRC ================
//..............................................................................
// возвращает кнопку смены состояния редактора
//..............................................................................
function get_itimages_async_event($row, $table_name=DEFAULT_IMAGES_TABLE)
	{
	$table_name = isset($row['table_name']) ? $row['table_name'] : $table_name;
	$element_id = isset($row['container_id']) ? $row['container_id'] : itEditor::_container_id($row);
	
	$text = ($row['state']=='view') ? 'edit' : 'close';	
	
	$o_button = new itButton(get_const($text), 'a', ['href'=>'#/', 'ajax'=>"itimages_state('#{$element_id}');", 'class'=>"edswitch_button {$text}"], '' );
	$result = $o_button->code();
	unset($o_button);
	
	
	return $result;
	}
?>
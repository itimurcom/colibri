<?php
// ================ CRC ================
// version: 1.15.06
// hash: f4d7726c7c94324e80454b765f4f65272b6f37ef0b320bf6eb0378ce154024b7
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку выбора контента для блока
//..............................................................................
function get_block_content_event($row, $table_name=DEFAULT_BLOCK_TABLE)
	{
	global $prepared_arr, $_USER;

	if (!$_USER->is_logged()
		OR !is_array(ready_val($prepared_arr['contents'])))
		return;

	$options = array (
		'array' 	=> $prepared_arr['contents'],
		'titles'        => 'title',
		'values'	=> 'value',
		'name'		=> 'content_id'
		);

	$o_modal = new itModal();

	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndPop');

	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', $row['id'], get_const('QUERY_BLOCK_CONTENT')));
	
	$o_form->add_data([
		'table_name' 	=> $table_name,
		'rec_id'	=> $row['id'],
		'op'		=> 'block',
		]);
	
	$o_form->add_itSelector('select', $options, $row['content_id'], NULL, get_const('QUERY_BLOCK'));

	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(get_const('BLOCK_BUTTON').$row['id'], 'textmodal', ['form' => $o_modal->form_id(), 'class'=>'block_button'], 'default' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;	
	}
?>
<?php
// ================ CRC ================
// version: 1.15.03
// hash: 3be656e2b2a5a82d275643340bd569276c7bc46c1ad208e464f3f065607d01fd
// date: 09 September 2019  5:10
// ================ CRC ================
//..............................................................................
// возвращает кнопку добавления категории в окне группы
//..............................................................................
function get_wizard_copy_event($row)
	{
	
	global $lang_cat, $prepared_arr, $_USER, $_RIGHTS;
	
	if (!$_USER->is_logged($_RIGHTS['EDIT'])) return;

	$o_modal = new itModal();
	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndPop');
	
	$o_form = new itForm2();
	$o_form->add_title(str_replace ('[VALUE]', $row['name'], get_const('QUERY_COPY_WIZARD')));

	
	if(isset($prepared_arr['categories']))
		{
		$options = array (
			'array' 	=> $prepared_arr['categories'],
			'titles'        => 'title',
			'values'	=> 'value',
			'color'		=> 'color',
			'name'		=> 'category_id',
			);
		$o_form->add_itSelector('select', $options, $row['rec_id'], NULL, get_const('QUERY_COPY_WIZARD_PARENT'));
		} else $o_form->add_hidden('category_id', 0);
	
	$o_form->add_data([
		'table_name' 	=> $row['table_name'],
		'rec_id' 	=> $row['rec_id'],
		'user_id'	=> $_USER->id(),
		'key'		=> $row['key'],
		'op'		=> 'wiz_copy',
		]);
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton("<b>&#10063;</b>", 'textmodal', [ 'class'=>'treebtn', 'form' => $o_modal->form_id()], 'blue' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;	
	}
?>
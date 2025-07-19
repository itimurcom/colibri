<?php
// ================ CRC ================
// version: 1.15.04
// hash: bfb6b5f43edf2e0933265a67ebebd4e3d0926b25706a9240a0678c77fc9f0614
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку изменения родительской категории
//..............................................................................
function get_category_parent_event($row, $table_name=DEFAULT_CATEGORY_TABLE)
	{
	global $lang_cat;
	global $prepared_arr;

	$parents = $prepared_arr['categories'];
	$options = array (
		'array' 	=> $parents,
		'titles'        => 'title',
		'values'	=> 'value',
		'color'		=> 'color',
		'name'		=> 'parent_id'
		);
		
	$o_modal = new itModal();
	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndPop');
	
	$o_form = new itForm2();
	$o_form->add_title(str_replace ('[VALUE]', "#{$row['id']} <b>".get_field_by_lang($row['title_xml'])."</b>", get_const('QUERY_CATEGORY_PARENT')));
	$o_form->add_itSelector('select', $options, $row['parent_id'], NULL, get_const('QUERY_CHANGE_CATEGORY_PARENT'));
	
	
	$o_form->add_data([
		'table_name' 	=> $table_name,
		'rec_id' 	=> $row['id'],
		'op'		=> 'set_parent',
		]);
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton("<b>&#8962;</b>", 'textmodal', [ 'class'=>'treebtn', 'form' => $o_modal->form_id()], 'blue' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;	
	}
?>
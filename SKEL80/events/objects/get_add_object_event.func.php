<?php
// ================ CRC ================
// version: 1.15.05
// hash: 1c85b1dfd74d76cf0194ccd4722271461b255e5fb9d4f2798ece90d7a309f3ca
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку добавления объекта
//..............................................................................
function get_add_object_event($table_name=DEFAULT_OBJECT_TABLE)
	{
	global $lang_cat, $prepared_arr, $_USER, $_RIGHTS;
	
	if (!$_USER->is_logged($_RIGHTS['EDIT'])) return;

	$o_modal = new itModal();
	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndPop');
	
	$o_form = new itForm2();
	$o_form->add_title(get_const('QUERY_ADD_OBJECT'));
	
	$o_form->add_input([
		'name'		=> 'value',
		'value'		=> '',
		'lablel'	=> str_replace('[VALUE]', $lang_cat[CMS_LANG]['name_orig'], get_const('ADD_OBJECT_LABEL')),
		]);
	
	if(isset($prepared_arr['categories']))
		{
		$options = array (
			'array' 	=> $prepared_arr['categories'],
			'titles'        => 'title',
			'values'	=> 'value',
			'color'		=> 'color',
			'name'		=> 'category_id'
			);
		$o_form->add_itSelector('select', $options, '', NULL, get_const('QUERY_ADD_CATEGORY_PARENT'));
		} else $o_form->add_hidden('category_id', 0);
	
	$o_form->add_data([
		'table_name' 	=> $table_name,
		'op'		=> 'add_object',
		]);
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton("<b>".get_const('BUTTON_PLUS_OBJECT')."</b>", 'modal', [ 'class'=>'admin', 'form' => $o_modal->form_id()], 'green' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;	
	}
?>
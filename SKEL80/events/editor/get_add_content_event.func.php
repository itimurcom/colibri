<?php
// ================ CRC ================
// version: 1.15.05
// hash: 6175d0634750c686775772b31f30eddeb2b70a01699c37d47dfcc60172894114
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку добавления категории в общей админ панели
//..............................................................................
function get_add_content_event($table_name=DEFAULT_CONTENT_TABLE)
	{
	global $lang_cat, $prepared_arr, $_USER, $_RIGHTS;
	
	if (!$_USER->is_logged()) return;

	$o_modal = new itModal();
	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndPop');
	
	$o_form = new itForm2();
	$o_form->add_title(str_replace ('[VALUE]', '', get_const('QUERY_ADD_CONTENT')));
	
	$o_form->add_input([
		'name'		=> 'value',
		'value'		=> '',
		'label'		=> str_replace ('[VALUE]', $lang_cat[CMS_LANG]['name_orig'], get_const('ADD_CONTENT_LABEL')),
		]);

	if (isset($prepared_arr['cats']))
		{
		$options = array (
			'array' 	=> $prepared_arr['cats'],
			'titles'        => 'title',
			'values'	=> 'value',
			'name'		=> 'category_id'
			);			
		$o_form->add_itSelector('select', $options, '', NULL, get_const('QUERY_ADD_CONTENT_CATEGORY'));
		}
	
	$o_form->add_data([
		'table_name'	=> $table_name,
		'name'		=> 'material',
		'op'		=> 'add_content',
		]);
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton("<b>".get_const('BUTTON_PLUS_CONTENT')."</b>", 'modal', ['class'=>'admin', 'form' => $o_modal->form_id()], 'blue' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;	
	}
?>
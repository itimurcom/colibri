<?php
// ================ CRC ================
// version: 1.15.03
// hash: d9ab97db4da3addcdd720364ab0216a63591fe5b87114e6df8e6404d25de4ceb
// date: 09 September 2019  5:10
// ================ CRC ================
//..............................................................................
// возвращает событие добавления поля визарда для категории
//..............................................................................
function get_add_wizard_event($row)
	{
	global $lang_cat, $prepared_arr, $_USER;

	$o_modal = new itModal();
	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndPop');
	
	$o_form = new itForm2();
	$o_form->add_title(str_replace ('[VALUE]', '', get_const('QUERY_ADD_WIZARD')));
	
	$o_form->add_input([
		'name'		=> 'name', 
		'label'		=> get_const('ADD_WIZARD_NAME'),
		'compact'	=> true,
		]);
	
	if(isset($prepared_arr['wiz_types']))
		{
		$options = array (
			'array' 	=> $prepared_arr['wiz_types'],
			'titles'        => 'title',
			'values'	=> 'value',
			'name'		=> 'type',
			'compact'	=> true,
			);
		$o_form->add_itSelector('select', $options, '', NULL, get_const('QUERY_ADD_WIZ_TYPE'));
		} else $o_form->add_hidden('type', DEFAULT_WIZARD_TYPE);

	$o_form->add_input([
		'name'		=> 'label',
		'label'		=> str_replace ('[VALUE]', $lang_cat[CMS_LANG]['name_orig'], get_const('ADD_WIZARD_LABEL')),
		'compact'	=> true,
		]);
	$o_form->add_area([
		'name'		=> 'titles', 
		'label'		=> str_replace ('[VALUE]', $lang_cat[CMS_LANG]['name_orig'], get_const('ADD_WIZARD_TITLES')),
		'compact'	=> true,
		]);
	$o_form->add_area([
		'name'		=> 'values', 
		'label'		=> str_replace ('[VALUE]', $lang_cat[CMS_LANG]['name_orig'], get_const('ADD_WIZARD_VALUES')),
		'compact'	=> true,
		]);
	
	$o_form->add_data([
		'table_name' 	=> $row['table_name'],
		'rec_id' 	=> $row['rec_id'],
		'user_id'	=> $_USER->id(),
		'op'		=> 'wiz_add',
		]);
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton("<b>".get_const('BUTTON_PLUS_WIZARD')."</b>", 'modal', ['form' => $o_modal->form_id()], 'blue' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
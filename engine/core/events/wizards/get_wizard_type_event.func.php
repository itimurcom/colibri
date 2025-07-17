<?php
// ================ CRC ================
// version: 1.15.03
// hash: 5d46da33364e7b89029461ccefffea4ec0747492b78c9c2868644f41b4fc6e9e
// date: 09 September 2019  5:10
// ================ CRC ================
//..............................................................................
// возвращает кнопку изменения типа поля визарда
//..............................................................................
function get_wizard_type_event($row)
	{
	global $_USER, $prepared_arr, $lang_cat;

	$o_modal = new itModal();
	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndUp');

	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', "#{$row['key']}<br/>({$lang_cat[CMS_LANG]['name_orig']})", get_const('CHANGE_WIZ_TYPE')));

	if(isset($prepared_arr['wiz_types']))
		{
		$options = array (
			'array' 	=> $prepared_arr['wiz_types'],
			'titles'        => 'title',
			'values'	=> 'value',
			'name'		=> 'value',
			'compact'	=> true,
			);
		$o_form->add_itSelector('select', $options, $row['type'], NULL, get_const('QUERY_CHANGE_WIZ_TYPE'));
		} else $o_form->add_hidden('type', DEFAULT_WIZARD_TYPE);
		
	$o_form->add_data([
		'table_name' 	=> $row['table_name'],
		'rec_id'	=> $row['rec_id'],
		'key'		=> $row['key'],
		'user_id'	=> $_USER->id(),
		'op' 		=> 'wiz_type',
		]);
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

 	$field_value = isset($prepared_arr['wiz_types'][$row['type']]['title']) ? $prepared_arr['wiz_types'][$row['type']]['title'] : $row['type'];

	$o_button = new itButton($field_value, 'textmodal', ['form' => $o_modal->form_id()], '' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
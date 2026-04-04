<?php
// ================ CRC ================
// version: 1.15.05
// hash: ee9df3fe5206ac49af2db2e773aa80bf7c66018d7945fddd889fa102f447dbbc
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает событие смены категории объекта
//..............................................................................
function get_object_category_event($row)
	{
	global $lang_cat, $prepared_arr, $_USER, $_RIGHTS;

	$value_field = isset($prepared_arr['categories'][$row['category_id']]) ? get_const($prepared_arr['categories'][$row['category_id']]['title']) : get_const('NO_DATA');
	
	if ($_USER->is_logged($_RIGHTS['EDIT']))
		{
		$o_modal = new itModal();
		$o_modal->set_size('medium');
		$o_modal->set_animation('fadeAndPop');
	
		$o_form = new itForm2();
		$o_form->add_title(str_replace('[VALUE]', get_field_by_lang($row['title_xml']), get_const('QUERY_OBJECT_CATEGORY_TITLE')));

		if(isset($prepared_arr['categories']))
			{
			$options = array (
				'array' 	=> $prepared_arr['categories'],
				'titles'        => 'title',
				'values'	=> 'value',
				'color'		=> 'color',
				'name'		=> 'value'
				);
			$o_form->add_itSelector('select', $options, $row['category_id'], NULL, get_const('QUERY_OBJECT_CATEGORY'));
			} else $o_form->add_hidden('category_id', 0);
	
		$o_form->add_data([
			'table_name' 	=> $row['table_name'],
			'rec_id' 	=> $row['rec_id'],
			'op'		=> 'obj_category'
			]);
		$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
		$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
		$o_form->compile();

		$o_modal->add_field($o_form->code());
	 	$o_modal->compile();

		$o_button = new itButton($value_field, 'textmodal', ['form' => $o_modal->form_id()], '' );
		$result = $o_button->code().$o_modal->code();
		unset($o_button, $o_form, $o_modal);
		} else $result = $value_field;
	return $result;	
	}
?>
<?php
// возвращает событие смены категории объекта
function get_object_category_event_row_value($row, $key, $default=NULL)
	{
	return (is_array($row) AND array_key_exists($key, $row)) ? $row[$key] : $default;
	}

function get_object_category_event_can_edit()
	{
	global $_USER, $_RIGHTS;
	return is_object($_USER) AND method_exists($_USER, 'is_logged') AND $_USER->is_logged(isset($_RIGHTS['EDIT']) ? $_RIGHTS['EDIT'] : NULL);
	}

function get_object_category_event($row)
	{
	global $prepared_arr;
	if (!is_array($row)) return '';

	$category_id = get_object_category_event_row_value($row, 'category_id', 0);
	$category_row = (isset($prepared_arr['categories']) AND is_array($prepared_arr['categories']) AND isset($prepared_arr['categories'][$category_id]) AND is_array($prepared_arr['categories'][$category_id]))
		? $prepared_arr['categories'][$category_id]
		: [];
	$value_field = isset($category_row['title']) ? get_const($category_row['title']) : get_const('NO_DATA');
	
	if (get_object_category_event_can_edit())
		{
		$table_name = get_object_category_event_row_value($row, 'table_name');
		$rec_id = (int)get_object_category_event_row_value($row, 'rec_id', get_object_category_event_row_value($row, 'id', 0));
		if (empty($table_name) OR $rec_id<=0) return $value_field;

		$o_modal = new itModal();
		$o_modal->set_size('medium');
		$o_modal->set_animation('fadeAndPop');
	
		$o_form = new itForm2();
		$o_form->add_title(str_replace('[VALUE]', get_field_by_lang(get_object_category_event_row_value($row, 'title_xml')), get_const('QUERY_OBJECT_CATEGORY_TITLE')));

		if(isset($prepared_arr['categories']) AND is_array($prepared_arr['categories']))
			{
			$options = array (
				'array' 	=> $prepared_arr['categories'],
				'titles'        => 'title',
				'values'	=> 'value',
				'color'		=> 'color',
				'name'		=> 'value'
				);
			$o_form->add_selector('select', $options, $category_id, NULL, get_const('QUERY_OBJECT_CATEGORY'));
			} else $o_form->add_hidden('category_id', 0);
	
		$o_form->add_data([
			'table_name' 	=> $table_name,
			'rec_id' 	=> $rec_id,
			'op'		=> 'obj_category'
			]);
		$o_form->add_button(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
		$o_form->add_button(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
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
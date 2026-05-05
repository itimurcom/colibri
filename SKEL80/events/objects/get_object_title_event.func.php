<?php
// возвращает кнопку изменения названия
function get_object_title_event_row_value($row, $key, $default=NULL)
	{
	return (is_array($row) AND array_key_exists($key, $row)) ? $row[$key] : $default;
	}

function get_object_title_event_lang_name()
	{
	global $lang_cat;
	return (is_array($lang_cat) AND defined('CMS_LANG') AND isset($lang_cat[CMS_LANG]) AND is_array($lang_cat[CMS_LANG]) AND isset($lang_cat[CMS_LANG]['name_orig']))
		? $lang_cat[CMS_LANG]['name_orig']
		: (defined('CMS_LANG') ? CMS_LANG : '');
	}

function get_object_title_event($row)
	{
	global $_USER;
	if (!is_array($row)) return '';
	$title_xml = get_object_title_event_row_value($row, 'title_xml', []);
	if (!is_object($_USER) OR !method_exists($_USER, 'is_logged') OR !$_USER->is_logged()) return get_field_by_lang($title_xml);
	$table_name = get_object_title_event_row_value($row, 'table_name');
	$rec_id = (int)get_object_title_event_row_value($row, 'rec_id', get_object_title_event_row_value($row, 'id', 0));
	if (empty($table_name) OR $rec_id<=0) return get_field_by_lang($title_xml);

	$o_modal = new itModal();
	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndPop');
	
	$o_form = new itForm2();
	$o_form->add_title( str_replace ('[VALUE]', get_object_title_event_lang_name(), get_const('ADD_OBJECT_LABEL')));
	
	$o_form->add_input([
		'name'	=> 'value',
		'value'	=> get_field_by_lang($title_xml, CMS_LANG, ''),
		'label'	=> get_const('QUERY_OBJECT_TITLE_VALUE'),
		]);
	
	$o_form->add_data([
		'table_name' 	=> $table_name,
		'rec_id' 	=> $rec_id,
		'op'		=> 'obj_title',
		]);
	$o_form->add_button(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_button(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(get_field_by_lang($title_xml), 'textmodal', ['form' => $o_modal->form_id()], '' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;	
	}
?>
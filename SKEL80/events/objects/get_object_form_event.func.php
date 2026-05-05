<?php
// возвращает кнопку добавления объекта
function get_object_form_event_row_value($row, $key, $default=NULL)
	{
	return (is_array($row) AND array_key_exists($key, $row)) ? $row[$key] : $default;
	}

function get_object_form_event_lang_name()
	{
	global $lang_cat;
	return (is_array($lang_cat) AND defined('CMS_LANG') AND isset($lang_cat[CMS_LANG]) AND is_array($lang_cat[CMS_LANG]) AND isset($lang_cat[CMS_LANG]['name_orig']))
		? $lang_cat[CMS_LANG]['name_orig']
		: (defined('CMS_LANG') ? CMS_LANG : '');
	}

function get_object_form_event($row)
	{
	global $_USER, $_RIGHTS;
	if (!is_array($row)) return '';
	if (!is_object($_USER) OR !method_exists($_USER, 'is_logged') OR !$_USER->is_logged(isset($_RIGHTS['EDIT']) ? $_RIGHTS['EDIT'] : NULL)) return;
	$table_name = get_object_form_event_row_value($row, 'table_name');
	$rec_id = (int)get_object_form_event_row_value($row, 'rec_id', get_object_form_event_row_value($row, 'id', 0));
	if (empty($table_name) OR $rec_id<=0) return '';
	
	$o_object = new itObject(['table_name' => $table_name, 'rec_id'	=> $rec_id]);
	
	$o_modal = new itModal();
	$o_modal->set_size('large');
	$o_modal->set_animation('fadeAndPop');
	$o_modal->add_title( str_replace ('[VALUE]', get_object_form_event_lang_name(), get_const('OBJECT_DATA_LABEL')));
	$o_modal->add_field($o_object->form($o_modal));
 	$o_modal->compile();

	$o_button = new itButton('#', 'textmodal', ['form' => $o_modal->form_id()], '' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_modal);
	return $result;	
	}
?>
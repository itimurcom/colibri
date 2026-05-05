<?php
// ================ CRC ================
// version: 1.15.03
// hash: 626156fd3d87d0239eef0dea0f4ed435ade9c633d7b541149e6de8d0ae7d6bc4
// date: 10 March 2021  9:27
// ================ CRC ================
//..............................................................................
// возвращает кнопку изменения заголовка контента для текущего языка
//..............................................................................
function get_category_title_event_row_value($row, $key, $default=NULL)
	{
	return (is_array($row) AND array_key_exists($key, $row)) ? $row[$key] : $default;
	}

function get_category_title_event_lang_name()
	{
	global $lang_cat;
	return (is_array($lang_cat) AND defined('CMS_LANG') AND isset($lang_cat[CMS_LANG]) AND is_array($lang_cat[CMS_LANG]) AND isset($lang_cat[CMS_LANG]['name_orig']))
		? $lang_cat[CMS_LANG]['name_orig']
		: (defined('CMS_LANG') ? CMS_LANG : '');
	}

function get_category_title_event($row, $table_name=DEFAULT_CATEGORY_TABLE)
	{
	if (!is_array($row)) return '';
	$rec_id = (int)get_category_title_event_row_value($row, 'id', get_category_title_event_row_value($row, 'rec_id', 0));
	if ($rec_id<=0) return '';

	$o_modal = new itModal();
	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndUp');

	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', "#{$rec_id}<br/>(".get_category_title_event_lang_name().")", get_const('ED_CATEGORY_QUERY')));

	$o_form->add_input([
		'name'		=> 'value',
		'value'		=> get_field_by_lang(get_category_title_event_row_value($row, 'title_xml', [])),
		]);
	$o_form->add_hidden('data', itEditor::event_data([
		'table_name'	=> $table_name,
		'rec_id'	=> $rec_id,
		'op'		=> 'ed_title',
		]));
	$o_form->add_button(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_button(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton("<b>&#9999;</b>", 'textmodal', [ 'class'=>'treebtn', 'form' => $o_modal->form_id()], 'green' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
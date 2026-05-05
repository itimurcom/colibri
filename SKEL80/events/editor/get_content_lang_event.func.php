<?php
// ================ CRC ================
// version: 1.15.05
// hash: 90febd9afc6d3b2c7d521f926689e0920f564db98065665fae4d700b494748a6
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку изменения заголовка контента для текущего языка
//..............................................................................
function get_content_lang_event_row_value($row, $key, $default=NULL)
	{
	return (is_array($row) && array_key_exists($key, $row)) ? $row[$key] : $default;
	}

function get_content_lang_event($row)
	{
	global $lang_cat;
	global $prepared_arr;
	if (!is_array($row)) return '';
	$table_name = get_content_lang_event_row_value($row, 'table_name');
	$rec_id = (int)get_content_lang_event_row_value($row, 'rec_id', get_content_lang_event_row_value($row, 'id', 0));
	if (empty($table_name) || $rec_id<=0) return '';

	if (!isset($prepared_arr['lang']) AND is_array($lang_cat))
		{
		//..............................................................................
		// массив языков для выбора
		//..............................................................................
		$prepared_arr['lang']['ALL'] = [
				'title' => get_const('ALL_LANG_TITLE'),
				'value'	=> 'ALL',
				]; 

		foreach ($lang_cat as $lang_key=>$lang_row)
			{
			if (is_array($lang_row) && get_content_lang_event_row_value($lang_row, 'allowed', 0)==1)
				{
				$prepared_arr['lang'][$lang_key] = [
					'title' => get_const(get_content_lang_event_row_value($lang_row, 'name_orig', $lang_key)),
					'value'	=> $lang_key,
					]; 
				}
			}
		$prepared_arr['lang'][NULL] = [
			'title' => get_const('SEPARETED_LANG_TITLE'),
			'value'	=> NULL,
			]; 
		}

	if (!isset($prepared_arr['lang']) || !is_array($prepared_arr['lang'])) return '';
	$current_lang = get_content_lang_event_row_value($row, 'lang');
	$current_lang_title = isset($prepared_arr['lang'][$current_lang]) && is_array($prepared_arr['lang'][$current_lang]) && isset($prepared_arr['lang'][$current_lang]['title'])
		? $prepared_arr['lang'][$current_lang]['title']
		: get_const('SEPARETED_LANG_TITLE');

	$options = [
		'array' 	=> $prepared_arr['lang'],
		'titles'        => 'title',
		'values'	=> 'value',
		'name'		=> 'lang_short'
		];

	$o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndUp');

	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', get_field_by_lang(get_content_lang_event_row_value($row, 'title_xml', '')), get_const('ED_LANG_QUERY')));

	$o_form->add_selector('select', $options, $current_lang, NULL, get_const('QUERY_LANG_CONTENT'));
	$o_form->add_data([
		'table_name'	=> $table_name,
		'rec_id'	=> $rec_id,
		'op'		=> 'lang',
		]);
	$o_form->add_button(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_button(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton([
		'title'	=> $current_lang_title,
		'type'	=> 'modal',
		'class' => 'admin',
		'form'	=> $o_modal->form_id(),
		'color'	=> DEFAULT_ALLLANG_COLOR,
		]);
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>

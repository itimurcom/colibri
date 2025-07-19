<?
//..............................................................................
// возвращает кнопку изменения заголовка контента для текущего языка
//..............................................................................
function get_content_lang_event($row)
	{
	global $lang_cat;
	global $prepared_arr;


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
			if ($lang_row['allowed']==1)
				{
				$prepared_arr['lang'][$lang_key] = [
					'title' => get_const($lang_row['name_orig']),
					'value'	=> $lang_key,
					]; 
				}
			}
		$prepared_arr['lang'][NULL] = [
			'title' => get_const('SEPARETED_LANG_TITLE'),
			'value'	=> NULL,
			]; 
		}	

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
	$o_form->add_title(str_replace('[VALUE]', get_field_by_lang($row['title_xml']), get_const('ED_LANG_QUERY')));

	$o_form->add_itSelector('select', $options, $row['lang'], NULL, get_const('QUERY_LANG_CONTENT'));
	$o_form->add_data([
		'table_name'	=> $row['table_name'],
		'rec_id'	=> $row['rec_id'],
		'op'		=> 'lang',
		]);
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton([
		'title'	=> $prepared_arr['lang'][$row['lang']]['title'],
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
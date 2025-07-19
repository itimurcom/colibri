<?
//..............................................................................
// возвращает кнопку изменения заголовка контента для текущего языка
//..............................................................................
function get_category_title_event($row, $table_name=DEFAULT_CATEGORY_TABLE)
	{
	global $lang_cat;

	$o_modal = new itModal();
	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndUp');

	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', "#{$row['id']}<br/>({$lang_cat[CMS_LANG]['name_orig']})", get_const('ED_CATEGORY_QUERY')));

	$o_form->add_input([
		'name'		=> 'value',
		'value'		=> get_field_by_lang($row['title_xml']),
		]);
	$o_form->add_hidden(data, itEditor::event_data([
		'table_name'	=> $table_name,
		'rec_id'	=> $row['id'],
		'op'		=> 'ed_title',
		]));
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton("<b>&#9999;</b>", 'textmodal', [ 'class'=>'treebtn', 'form' => $o_modal->form_id()], 'green' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
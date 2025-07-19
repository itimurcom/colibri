<?
//..............................................................................
// возвращает кнопку выбора связанных материалов для редактора
//..............................................................................
function get_related_conents_event($row, $table_name=DEFAULT_CONTENT_TABLE)
	{
	global $lang_cat;

	$o_modal = new itModal();
	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndUp');

	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', get_field_by_lang($row['title_xml']), get_const('QUERY_RELATED_TITLE')));

	$options = array (
		'type'		=> 'input',
		'name' 		=> 'content_id',
		'op' 		=> 'contents',
		);
	$o_form->add_itAutoSelect($options, get_const('QUERY_RELATED_LABEL'));
	$o_form->add_data([
		'table_name' 	=> $table_name,
		'rec_id' 	=> $row['id'],
		'field' 	=> ready_val($row['field'], DEFAULT_RELATED_FIELD),
		'op'		=> 'related',
		]);	
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(get_const('BUTTON_RELATED'), 'modal', ['class' => 'admin', 'form' => $o_modal->form_id()], 'blue' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
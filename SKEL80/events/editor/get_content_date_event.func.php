<?php
// ================ CRC ================
// version: 1.15.05
// hash: 9ebf9c42e5423526d023887db28f4ea6a151009b3230a6b75e7b8e8570fa33b1
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку изменения даты публикации контента
//..............................................................................
function get_content_date_event_row_value($row, $key, $default=NULL)
	{
	return (is_array($row) && array_key_exists($key, $row)) ? $row[$key] : $default;
	}

function get_content_date_event_user_is_logged($groups)
	{
	global $_USER;
	return (is_object($_USER) && method_exists($_USER, 'is_logged')) ? $_USER->is_logged($groups) : false;
	}

function get_content_date_event($row)
	{
	if (!is_array($row)) return '';
	if (ready_val(get_content_date_event_row_value($row, 'category_id'))==-1) return;
	$datetime = get_content_date_event_row_value($row, 'datetime');
	$table_name = get_content_date_event_row_value($row, 'table_name');
	$rec_id = (int)get_content_date_event_row_value($row, 'rec_id', get_content_date_event_row_value($row, 'id', 0));
	
	if (!get_content_date_event_user_is_logged(itEditor::moderators())) return TAB."<span class='content_date'>".get_local_date_str($datetime).TAB."</span>";
	if (empty($table_name) || $rec_id<=0) return TAB."<span class='content_date'>".get_local_date_str($datetime).TAB."</span>";

	$o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndPop');
	
	$o_form = new itForm2();
	$o_form->add_title("<b>".get_const('QUERY_CHANGE_DATE')."</b>");
	$o_form->add_date($datetime, ['name' => 'datetime', 'type' => 'text']);

	$o_form->add_data([
		'table_name'	=> $table_name,
		'rec_id'	=> $rec_id,
		'op'		=> 'datetime',
		]);
	$o_form->add_button(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_button(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(get_local_date_str($datetime), 'textmodal', ['class' => ($datetime) ? '' : 'change_button', 'form' => $o_modal->form_id()], '' );

	$result = TAB."<span class='content_date'>".$o_button->code().$o_modal->code().TAB."</span>";
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>

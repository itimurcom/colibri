<?php
// ================ CRC ================
// version: 1.15.05
// hash: 9ebf9c42e5423526d023887db28f4ea6a151009b3230a6b75e7b8e8570fa33b1
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку изменения даты публикации контента
//..............................................................................
function get_content_date_event($row)
	{
	global $_USER;
	if (ready_val($row['category_id'])==-1) return;
	
	if (!$_USER->is_logged(itEditor::moderators())) return TAB."<span class='content_date'>".get_local_date_str($row['datetime']).TAB."</span>";

	$o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndPop');
	
	$o_form = new itForm2();
	$o_form->add_title("<b>".get_const('QUERY_CHANGE_DATE')."</b>");
	$o_form->add_itDate($row['datetime'], ['name' => 'datetime', 'type' => 'text']);

	$o_form->add_data([
		'table_name'	=> $row['table_name'],
		'rec_id'	=> $row['rec_id'],
		'op'		=> 'datetime',
		]);
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(get_local_date_str($row['datetime']), 'textmodal', ['class' => ($row['datetime']) ? '' : 'change_button', 'form' => $o_modal->form_id()], '' );

	$result = TAB."<span class='content_date'>".$o_button->code().$o_modal->code().TAB."</span>";
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
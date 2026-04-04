<?php
// ================ CRC ================
// version: 1.15.07
// hash: 83b22ae346b27a8c2b0d20c45dcfcbff9482aa8ebdd47078f5d9b60a1aaeafd4
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку изменения заголовка контента для текущего языка
//..............................................................................
function get_content_title_event($row)
	{
	global $lang_cat;
	if (function_exists('definition'))
		{
		definition($constants = [
			'ED_TITLE_QUERY' 	=> '<b>Введите название для контента <font color=\'blue\'>[VALUE]</font></b>',
		]);
		}
	$o_modal = new itModal();
	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndUp');

	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', "#{$row['rec_id']}<br/>({$lang_cat[CMS_LANG]['name_orig']})", ED_TITLE_QUERY));

	$o_form->add_input([
		'name'		=> 'value',
		'value'		=> get_field_by_lang($row['title_xml'], CMS_LANG, ''),
		]);
	$o_form->add_data([
		'table_name' 	=> $row['table_name'],
		'rec_id'	=> $row['rec_id'],
		'op'		=> 'ed_title'
		]);
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton([
		'title'	=> get_const('BUTTON_ED_TITLE'),
		'type'	=> 'modal',
		'class' => 'admin',
		'form'	=> $o_modal->form_id(), 
		'color'	=> 'fiolet',
		]);
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
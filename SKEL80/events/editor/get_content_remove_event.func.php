<?php
// ================ CRC ================
// version: 1.15.06
// hash: 9f6485d76c6745ba70d5e5119ea2591de851a95a11fc33c868d1c7f9c3a0a23d
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку для удаления контента
//..............................................................................
function get_content_remove_event($row)
	{
	definition([
		'QUERY_CONTENT_MODERATE' => 'Действительно хотите отправить материал<br/><font color=\'blue\'>[VALUE]</font><br/>на модерацию?'
		]);
	if ($row['status']=='MODERATE')
		{
		$button = get_const('BUTTON_OK');
		$color = 'blue';
		$color_ok = 'blue';
		$query =  'QUERY_CONTENT_PUBLISH';
		} else	{
			$button = 'BUTTON_MODERATE';
			$color = 'red';
			$color_ok = 'red';
			$query =  'QUERY_CONTENT_MODERATE';
			}

	$o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndPop');

	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', get_field_by_lang($row['title_xml']), get_const($query)));
	$o_form->add_data([
		'table_name'	=> $row['table_name'],
		'rec_id'	=> $row['rec_id'],
		'op'		=> 'moderate',
		]);
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], $color_ok );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton([
		'title'	=> get_const($button),
		'type'	=> 'modal',
		'class' => 'admin',
		'form'	=> $o_modal->form_id(),
		'color'	=> $color]);
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
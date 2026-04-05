<?php
// ================ CRC ================
// version: 1.35.03
// hash: a034808fb70c11b7b5fcc6f537fc13fdb8957dfddd7aca58c52a4ecc37199227
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку для удаления поля                                         *
//..............................................................................
function f2_x_field_event($row)
	{
	definition([
		'QUERY_ED_REMOVE'	=> "Желаете <b class='red'>удалить</b> поле <b class='blue'>[VALUE]</b>?",
		]);
	if ($row['last_field']=='0') return;

	$o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndPop');

	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', "{$row['kind']} #".($row['ed_key']+1), get_const('QUERY_ED_REMOVE')));

	$row['op'] = 'f2_x';
	$o_form->add_data($row);
	
	$o_form->add_itButton(get_const('BUTTON_REMOVE'), 'ajaxsubmit', ['form' => $o_form->form_id(), 'ajax'=>"f2_edreload('#".itForm2::_container_id($row)."');"], 'red' );	
	$o_form->add_button(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	

	$o_modal->add_field($o_form->_view());
 	$o_modal->compile();

	$o_button = new itButton(get_const('BUTTON_ED_REMOVE'), 'modal', ['class' => 'admin', 'form' => $o_modal->form_id()], 'red' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
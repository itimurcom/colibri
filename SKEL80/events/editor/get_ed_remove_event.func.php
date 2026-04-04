<?php
// ================ CRC ================
// version: 1.15.05
// hash: 87fac2c760a005874ab1e5b32c0e53f8c8d75218e434bacca1aaf7ef9a08b658
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку для удаления поля                                         *
//..............................................................................
function get_ed_remove_event($row)
	{	
	if ($row['last_field']=='0') return;

	if (function_exists('definition'))
		{
		definition($constants = [
			'QUERY_ED_REMOVE' 	=> "Вы действительно хотите удалить поле <font color='blue'>[VALUE]</font>?",
		]);
		}

	$o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndPop');

	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', "{$row['type']} #".($row['ed_key']+1), QUERY_ED_REMOVE));

	$row['op'] = 'ed_remove';
	$o_form->add_data($row);
	
	$o_form->add_itButton(get_const('BUTTON_REMOVE'), 'ajaxsubmit', ['form' => $o_form->form_id(), 'ajax'=>"editor_edreload('#".itEditor::_container_id($row)."');"], 'red' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(get_const('BUTTON_ED_REMOVE'), 'modal', ['class' => 'admin', 'form' => $o_modal->form_id()], 'red' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
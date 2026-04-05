<?php
// ================ CRC ================
// version: 1.35.03
// hash: 29224a3f5a6ef298d761a3c6a103aa0663e79a9be652914f2d028e4ab07ec011
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает код кнопки смены режима редактора формы (версия 2.1)
//..............................................................................
function f2_new_field_event($row)
	{
	definition([
		'QUERY_NEW_FILED'	=> 'Выберите тип поля',
		]);
	global $form2_defaults;
	$table_name = isset($row['table_name']) ? $row['table_name'] : $table_name;
	
//	if ($row['last_field']=='0') return;

	$o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndPop');

	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', "{$row['kind']} #".($row['ed_key']+1), get_const('QUERY_NEW_FILED')));
	$o_form->add_select([
		'array'	=> $form2_defaults,
		'name'	=> 'kind',
		]);

	$row['op'] = 'f2_field';
	$o_form->add_data($row);
	
	$o_form->add_itButton(get_const('BUTTON_OK'), 'ajaxsubmit', ['form' => $o_form->form_id(), 'ajax'=>"f2_edreload('#".itForm2::_container_id($row)."');"], 'blue' );	
	$o_form->add_button(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	

	$o_modal->add_field($o_form->_view());
 	$o_modal->compile();

	$o_button = new itButton(get_const('BUTTON_PLUS_FILED'), 'modal', ['class' => 'admin', 'form' => $o_modal->form_id()], 'green' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
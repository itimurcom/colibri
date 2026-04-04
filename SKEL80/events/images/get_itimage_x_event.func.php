<?php
// ================ CRC ================
// version: 1.15.04
// hash: b96e98ef06e63cad75e3bb1d34885571dda1c739217b02248ffbb7e126e201af
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку для удаления изображения из галлереи поля
//..............................................................................
function get_itimage_x_event($data)
	{
	$o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndPop');

	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', $data['key']+1, get_const('QUERY_GAL_X')));
	$o_form->add_title(TAB."<img src='".get_thumbnail($data['image'], 'IMG_PREV')."'/>");

	$data['op'] = 'itimage_x';
	$o_form->add_data($data);
	$o_form->add_itButton(get_const('BUTTON_REMOVE'), 'ajaxsubmit', ['form' => $o_form->form_id(), 'ajax'=>"itimages_reload('#".itImages::_container_id($data)."');"], 'red' );	
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
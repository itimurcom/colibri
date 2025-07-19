<?php
// ================ CRC ================
// version: 1.15.05
// hash: aebf08ee248c3afd946ee3187e7c2c6586a1186e766039f3dc677577d04a9aab
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку для удаления изображения из галлереи                      *
//..............................................................................
function get_gal_x_event($row, $gallery_id=NULL)
	{
	if (function_exists('definition'))
		{
		definition($constants = [
			'QUERY_GAL_X' 	=> "Вы действительно хотите удалить изображение <font color='blue'>#[VALUE]</font>?",
		]);
		}

	$o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndPop');

	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', $gallery_id+1, QUERY_GAL_X));
	$o_form->add_title(TAB."<img src='".get_thumbnail($row['value'][$gallery_id], 'IMG_PREV')."'/>");

	$row['gallery_id'] = $gallery_id; 
	$row['op'] = 'gal_x';
	$o_form->add_itButton(get_const('BUTTON_REMOVE'), 'ajaxsubmit', ['form' => $o_form->form_id(), 'ajax'=>"editor_edreload('#".itEditor::_container_id($row)."');"], 'red' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->add_data($row);
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(get_const('BUTTON_ED_REMOVE'), 'modal', ['class' => 'admin', 'form' => $o_modal->form_id()], 'red' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
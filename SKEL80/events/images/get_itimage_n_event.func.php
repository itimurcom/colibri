<?php
// ================ CRC ================
// version: 1.15.05
// hash: ea29c00fd509433887cea36ab34f92c7c93ed78e276dac49cd0be4fc85344932
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку перемещения изображения галлереи поля по номеру
//..............................................................................
function get_itimage_n_event($data)
	{
//	if (count($row['value']['media'])<2) return;
	definition([
		'BUTTON_N' => '#',
	]);

	for ($i=0; $i<$data['count']; $i++)
		{
		$sel_rec[] = array (
			'title' => $i+1,
			'value' => $i
			);
		}

	$options = array (
		'array' 	=> $sel_rec,
		'titles'        => 'title',
		'values'	=> 'value',
		'name'		=> 'new_id'
		);

	$o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndPop');
	
	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', $data['key']+1, get_const('QUERY_GAL_N')));
	$o_form->add_title(TAB."<img src='".get_thumbnail($data['image'], 'IMG_PREV')."'/>");

	$o_form->add_itSelector('select', $options, $data['key']);

	$data['op'] = 'itimage_n';
	$o_form->add_data($data);

	$o_form->add_itButton(get_const('BUTTON_OK'), 'ajaxsubmit', ['form' => $o_form->form_id(), 'ajax'=>"itimages_reload('#".itImages::_container_id($data)."');"], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(get_const('BUTTON_N'), 'modal', ['class' => 'admin', 'form' => $o_modal->form_id()], 'green' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
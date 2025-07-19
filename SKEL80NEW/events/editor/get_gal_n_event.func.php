<?
//..............................................................................
// возвращает кнопку перемещения изображения по номеру                         *
//..............................................................................
function get_gal_n_event($row, $gallery_id)
	{
	if (function_exists('definition'))
		{
		definition($constants = [
			'QUERY_GAL_N' 	=> "Выберите позиию изображения <font color='blue'>#[VALUE]</font>",
		]);
		}

	for ($i=0; $i<count ($row['value']); $i++)
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
	$o_form->add_title(str_replace('[VALUE]', $gallery_id+1, QUERY_GAL_N));
	$o_form->add_title(TAB."<img src='".get_thumbnail($row['value'][$gallery_id], 'IMG_PREV')."'/>");

	$o_form->add_itSelector('select', $options, $gallery_id);

	$row['gallery_id'] = $gallery_id;
	$row['op'] = 'gal_n';
	$o_form->add_data($row);

	$o_form->add_itButton(get_const('BUTTON_OK'), 'ajaxsubmit', ['form' => $o_form->form_id(), 'ajax'=>"editor_edreload('#".itEditor::_container_id($row)."');"], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(get_const('BUTTON_N'), 'modal', ['class' => 'admin', 'form' => $o_modal->form_id()], 'gray' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
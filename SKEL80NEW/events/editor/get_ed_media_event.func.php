<?
//..............................................................................
// возвращает кнопку добавления media-материалов                               *
//..............................................................................
function get_ed_media_event($row)
	{
	if (!get_const('ENABLE_ED_MEDIA')) return;

	if (function_exists('definition'))
		{
		definition($constants = [
			'ED_MEDIA_QUERY' 	=> '<b>Введите ссылку на внешние <font color=\'blue\'>VIDEO или AUDIO</font> материалы</b><br/>(разешены ссылки <b>YouTube</b>, <b>VIMEO</b>, <b>SoundCloud</b> или <b>MixCloud</b>)',
		]);
		}

	$o_modal = new itModal();
	$o_modal->set_size('large');
	$o_modal->set_animation('fadeAndUp');

	$o_form = new itForm2();
	$o_form->add_title(ED_MEDIA_QUERY);
	$o_form->add_input([
		'name'		=> 'value',
		'value'		=> '',
		]);

	$row['op'] = 'add_ed_media';
	$o_form->add_data($row);
	$o_form->add_itButton(get_const('BUTTON_ADD'), 'ajaxsubmit', ['form' => $o_form->form_id(), 'ajax'=>"editor_edreload('#".itEditor::_container_id($row)."');"], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(get_const('BUTTON_ED_MEDIA'), 'modal', ['class' => 'admin', 'form' => $o_modal->form_id()], 'fiolet' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
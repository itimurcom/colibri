<?
//..............................................................................
// возвращает кнопку изменения ссылки слайдера для текущего языка
//..............................................................................
function get_itimage_link_event($row, $gallery_id=NULL)
	{
	return;
	global $lang_cat;

	$o_modal = new itModal();
	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndUp');

	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', "#".($gallery_id+1)."<br/>({$lang_cat[CMS_LANG]['name_orig']})", get_const('SLIDER_HTML_QUERY')));
	$o_form->add_title(TAB."<center><img src='".get_thumbnail($row['value'][$gallery_id], 'IMG_PREV')."'/></center>");

	$o_form->add_input([
		'name'	=> 'value',
		'value'	=> ready_val($row['link'][$gallery_id]),
		'label'	=> get_const('QUERY_EMPTY_FIELD'),
		]);

	$row['gallery_id'] = $gallery_id;
	$row['op'] = 'itimage_link';
	$o_form->add_data($row);
	$o_form->add_itButton(get_const('BUTTON_OK'), 'ajaxsubmit', ['form' => $o_form->form_id(), 'ajax'=>"editor_edreload('#".itEditor::_container_id($row)."');"], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(( (ready_val($row['link'])==NULL) ? '+' : '').get_const('BUTTON_SLIDER_HREF'), 'modal', ['class' => 'admin', 'form' => $o_modal->form_id()], 'blue' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
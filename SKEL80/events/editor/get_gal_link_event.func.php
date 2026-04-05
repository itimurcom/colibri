<?php
// ================ CRC ================
// version: 1.15.05
// hash: 49ea9c054145f968fa3063b6609043304eaded0d45ccc90ca97a91007d80806b
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает кнопку изменения ссылки слайдера для текущего языка
//..............................................................................
function get_gal_link_event($row, $gallery_id=NULL)
	{
	global $lang_cat;
	if (function_exists('definition'))
		{
		definition($constants = [
			'SLIDER_HTML_QUERY' 	=> '<b>Введите ссылку для слайда <font color=\'blue\'>[VALUE]</font></b>',
			'QUERY_EMPTY_FIELD'	=> 'чтобы скрыть данные, оставьте поле пустым',			
		]);
		}		

	$o_modal = new itModal();
	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndUp');

	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', "#".($gallery_id+1)."<br/>({$lang_cat[CMS_LANG]['name_orig']})", SLIDER_HTML_QUERY));
	$o_form->add_title(TAB."<center><img src='".get_thumbnail($row['value'][$gallery_id], 'IMG_PREV')."'/></center>");

	$o_form->add_input([
		'name'	=> 'value',
		'value'	=> get_field_by_lang(ready_val($row['link'][$gallery_id]),CMS_LANG,''),
		'label'	=> get_const('QUERY_EMPTY_FIELD'),
		]);

	$row['gallery_id'] = $gallery_id;
	$row['op'] = 'gal_link';
	$o_form->add_data($row);
	$o_form->add_itButton(get_const('BUTTON_OK'), 'ajaxsubmit', ['form' => $o_form->form_id(), 'ajax'=>"editor_edreload('#".itEditor::_container_id($row)."');"], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(get_const('GAL_LINK'), 'modal', ['class' => 'admin', 'form' => $o_modal->form_id()], 'gray' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
?>
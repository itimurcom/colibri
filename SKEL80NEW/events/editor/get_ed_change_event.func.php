<?
//..............................................................................
// возвращает кнопку замены данного value поля редактора
//..............................................................................
function get_ed_change_event($row)
	{
	global $lang_cat;
	if ($row['type']!='media') return;

	$o_modal = new itModal();
	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndPop');
	
	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', "<b>{$row['type']} #".($row['ed_key']+1)."</b> <font color='blue'>{$lang_cat[CMS_LANG]['name_orig']}</font>", get_const('QUERY_ED_CHANGE')));

	$o_form->add_input([
		'name'	=> 'value',
		'value'	=> $row['value']
		]);
	$row['op'] = 'ed_change';
	$o_form->add_data($row);

	$o_form->add_itButton(get_const('BUTTON_OK'), 'ajaxsubmit', ['form' => $o_form->form_id(), 'ajax'=>"editor_edreload('#".itEditor::_container_id($row)."');"], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(get_const('BUTTON_ED_CHANGE'), 'modal', ['class' => 'admin', 'form' => $o_modal->form_id()], 'blue' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return TAB."<div class='ed_change'>".$result.TAB."</div>";
	}
?>
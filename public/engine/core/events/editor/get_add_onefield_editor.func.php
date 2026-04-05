<?
//..............................................................................
// функция события добавления поля редактора в однополном редакторе
//..............................................................................
function get_add_onefield_editor($row)
	{
	$o_form = new itForm2();
	$row['op'] = 'add_root_editor';
	$o_form->add_data($row);
	$o_form->compile();

	$o_button = new itButton(get_const('BUTTON_ED'), 'imsubmit', ['src' => "/themes/".CMS_THEME."/images/add_img_button.png", 'class' => 'admin', 'form' => $o_form->form_id(), 'ajax'=>"editor_edreload('#".itEditor::_container_id($row)."');"], 'blue' );	
	$result = $o_form->code().$o_button->code();
	unset($o_button, $o_form);
	return $result;	
	}
?>
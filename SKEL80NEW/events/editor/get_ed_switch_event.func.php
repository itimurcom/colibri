<?
//..............................................................................
// возвращает кнопку переключения позиции аватарки в тексте                    *
//..............................................................................
function get_ed_switch_event($row)
	{
	if (!isset($row['avatar'])) return;

	if (isset($row['position']) and ($row['position']=='RIGHT') )
		{
		$btn_text = get_const('BUTTON_ED_SWITCH_LEFT');
		} else	{
			$btn_text = get_const('BUTTON_ED_SWITCH_RIGHT');
			}
	$o_form = new itForm2();

	$row['op'] = 'ed_switch';
	$o_form->add_data($row);
	$o_form->compile();

	$o_button = new itButton($btn_text, 'ajaxsubmit', ['class' => 'admin', 'form' => $o_form->form_id(), 'ajax'=>"editor_edreload('#".itEditor::_container_id($row)."');"], 'green' );	
	$result = $o_form->code().$o_button->code();
	unset($o_button, $o_form);
	return $result;	
	}
?>
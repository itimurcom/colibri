<?
//..............................................................................
// возвращает кнопку перемещения изображения галлереи поля вверх по списку
//..............................................................................
function get_itimage_up_event($data)
	{
	if ($data['key']==0) return;
	$o_form = new itForm2();

	$data['op'] = 'itimage_up';
	$o_form->add_data($data);	

	$o_form->compile();

	$o_button = new itButton(get_const('BUTTON_LEFT'), 'ajaxsubmit', ['class' => 'admin', 'form' => $o_form->form_id(), 'ajax'=>"itimages_reload('#".itImages::_container_id($data)."');"], 'gray' );	
	$result = $o_form->code().$o_button->code();
	unset($o_button, $o_form);
	return $result;	
	}
?>
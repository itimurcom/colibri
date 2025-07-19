<?
//..............................................................................
// возвращает кнопку удаления аватарки для КОНТЕНТА
//..............................................................................
function get_content_avatar_x_event($row, $class=NULL) {
	global $_USER;
	if ($_USER->is_logged() AND !is_null($row['avatar'])) {
		$o_modal = new itModal();
		$o_modal->set_size('small');
		$o_modal->set_animation('fadeAndPop');

		$o_form = new itForm2();
		$o_form->add_title(get_const('QUERY_CONTENT_AVATAR_X'));
		$o_form->add_title(TAB."<img src='".get_thumbnail($row['avatar'], 'IMG_PREV')."'/>");

		$row['op'] = 'ava_x';
		$o_form->add_data($row);
		$o_form->add_itButton(get_const('BUTTON_REMOVE'), 'submit', ['form' => $o_form->form_id()], 'red' );	
		$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
		$o_form->compile();

		$o_modal->add_field($o_form->code());
	 	$o_modal->compile();

		$o_button = new itButton(get_const('BUTTON_ED_AVATAR_REMOVE'), 'modal', ['class' => 'admin'.($class ? " {$class}" : NULL), 'form' => $o_modal->form_id()], 'red');

		$result = $o_button->code().$o_modal->code();
		unset($o_button, $o_form, $o_modal);
		return $result;
		}
	}
?>
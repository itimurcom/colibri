<?
//..............................................................................
// возвращает кнопку загрузки аватарки для текста                              *
//..............................................................................
function get_ed_avatar_event($row=NULL)
	{
	if (isset($row['avatar']))
		{
	if (function_exists('definition'))
		{
		definition($constants = [
			'QUERY_REMOVE_AVATAR' 	=> "Вы действительно хотите удалить аватарку из <font color='blue'>#[VALUE]</font>?",
		]);
		}			
		$o_modal = new itModal();
		$o_modal->set_size('small');
		$o_modal->set_animation('fadeAndPop');

		$o_form = new itForm2();
		$o_form->add_title(str_replace('[VALUE]', ($row['ed_key']+1), QUERY_REMOVE_AVATAR));
		$o_form->add_title(TAB."<img src='".get_thumbnail($row['avatar'], 'IMG_PREV')."'/>");

		$row['op'] = 'ed_remove_avatar';
		$o_form->add_data($row);
		$o_form->add_itButton(get_const('BUTTON_REMOVE'), 'ajaxsubmit', ['form' => $o_form->form_id(), 'ajax'=>"editor_edreload('#".itEditor::_container_id($row)."');"], 'red' );	
		$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
		$o_form->compile();

		$o_modal->add_field($o_form->code());
	 	$o_modal->compile();

		$o_button = new itButton(get_const('BUTTON_ED_AVATAR_REMOVE'), 'modal', [ 'class' => 'admin', 'form' => $o_modal->form_id()], 'red');

		$result = $o_button->code().$o_modal->code();
		unset($o_button, $o_form, $o_modal);
		} else	{
			$options = array (
				'class' 	=> 'admin', 
				'name' 		=> get_const('DEFAULT_FILES_NAME'),
				'table_name' 	=> $row['table_name'],
				'rec_id' 	=> $row['rec_id'],
				'ed_key' 	=> $row['ed_key'],
				'field' 	=> $row['field'],
				'column' 	=> $row['column'],
				'root' 		=> $row['root'],
				'selector'	=> $row['selector'],
				'op' 		=> 'ed_add_avatar'
				);
			$b_files = new itButton(get_const('BUTTON_ED_AVATAR'), 'file', $options, 'green');
			$result = $b_files->code();
			unset($b_files, $options);
			} 
	return $result;
	}
?>
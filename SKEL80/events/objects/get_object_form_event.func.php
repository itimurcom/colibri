<?php
// ================ CRC ================
// version: 1.15.03
// hash: aaaa3df20f572fc987c740b2ceb3e6aa79531d3d888726fb892a8cfc5176f704
// date: 09 September 2019  5:10
// ================ CRC ================
//..............................................................................
// возвращает кнопку добавления объекта
//..............................................................................
function get_object_form_event($row)
	{
	global $lang_cat, $prepared_arr, $_USER, $_RIGHTS;
	if (!$_USER->is_logged($_RIGHTS['EDIT'])) return;
	
	
	$o_object = new itObject(['table_name' => $row['table_name'], 'rec_id'	=> $row['rec_id']]);
	
	$o_modal = new itModal();
	$o_modal->set_size('large');
	$o_modal->set_animation('fadeAndPop');
	$o_modal->add_title( str_replace ('[VALUE]', $lang_cat[CMS_LANG]['name_orig'], get_const('OBJECT_DATA_LABEL')));
	$o_modal->add_field($o_object->form($o_modal));
 	$o_modal->compile();

	$o_button = new itButton('#', 'textmodal', ['form' => $o_modal->form_id()], '' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_modal);
	return $result;	
	}
?>
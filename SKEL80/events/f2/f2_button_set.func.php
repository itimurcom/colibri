<?php
// набор элементов управления для одного поля редактора формы
function f2_control_reload_js($row)
	{
	return "f2_edreload('#".itForm2::_container_id($row)."');";
	}

function f2_control_add_data($o_form, $row, $op)
	{
	$row['op'] = $op;
	$o_form->add_data($row);
	}

function f2_control_ajax_button($row, $op, $title_const, $color)
	{
	$o_form = new itForm2();
	f2_control_add_data($o_form, $row, $op);
	$o_button = new itButton(get_const($title_const), 'ajaxsubmit', ['class' => 'admin', 'form' => $o_form->form_id(), 'ajax'=>f2_control_reload_js($row)], $color);
	$result = $o_form->_view().$o_button->code();
	unset($o_button, $o_form);
	return $result;
	}

function f2_control_modal_form($row, $query_const)
	{
	$o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndPop');
	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', "{$row['kind']} #".($row['ed_key']+1), get_const($query_const)));
	return [$o_modal, $o_form];
	}

function f2_control_modal_buttons($o_form, $o_modal, $row, $submit_const, $submit_color)
	{
	$o_form->add_button(get_const($submit_const), 'ajaxsubmit', ['form' => $o_form->form_id(), 'ajax'=>f2_control_reload_js($row)], $submit_color);
	$o_form->add_button(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green');
	}

function f2_control_modal_code($o_modal, $o_form, $trigger_const, $trigger_color)
	{
	$o_modal->add_field($o_form->_view());
 	$o_modal->compile();
	$o_button = new itButton(get_const($trigger_const), 'modal', ['class' => 'admin', 'form' => $o_modal->form_id()], $trigger_color);
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}

function f2_button_set($row)
	{
	$num = (str_replace ('ed_','',$row['ed_key'])+1);
	return
		TAB."<div class=\"f2_admin\">".
		TAB."<span id='key_{$num}' class='ed_number'># {$num} <small>{$row['kind']}</small></span>".
		f2_x_field_event($row).
		f2_down_field_event($row).
		f2_up_field_event($row).
		f2_new_field_event($row).
		f2_change_event($row).
		TAB."</div>";
	}
?>

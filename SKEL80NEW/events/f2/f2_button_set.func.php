<?
//..............................................................................
// набор элементов управления для одного поля редактора формы
//..............................................................................
function f2_button_set($row)
	{
	$num = (str_replace ('ed_','',$row['ed_key'])+1);
	return
		TAB."<div class=\"f2_admin\">".
		TAB."<span id='key_{$num}' class='ed_number'># {$num} <small>{$row['kind']}</small></span>".
// 			TAB."<div class=\"\" style='display:inline-table; width:90%;line-height:1;'>".
			f2_x_field_event($row).
			f2_down_field_event($row).
			f2_up_field_event($row).
			f2_new_field_event($row).
			f2_change_event($row).
// 			TAB."</div>".
		TAB."</div>".
		"";
	}
?>
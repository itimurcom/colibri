<?php
// ================ CRC ================
// version: 1.15.04
// hash: 7a9e5c2b3ed539185c3d2e58c403644928a616aea29d6088320fef8d7f3e17b7
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает селектор смены категории материала
//..............................................................................
function get_category_event_row_value($row, $key, $default=NULL)
	{
	return (is_array($row) && array_key_exists($key, $row)) ? $row[$key] : $default;
	}

function get_category_event($row)
	{
//	if ($row['category_id']==0) return;
	global $cats_gr, $lang_cat;
	global $prepared_arr;
	if (!is_array($row) || !isset($prepared_arr['cats']) || !is_array($prepared_arr['cats'])) return '';
	$table_name = get_category_event_row_value($row, 'table_name');
	$rec_id = (int)get_category_event_row_value($row, 'rec_id', get_category_event_row_value($row, 'id', 0));
	if (empty($table_name) || $rec_id<=0) return '';

	$o_form = new itForm2();
	$options = array (
		'array' 	=> $prepared_arr['cats'],
		'titles'        => 'title',
		'values'	=> 'value',
		'name'		=> 'category_id',
		'form'		=> $o_form->form_id()
		);

	$o_form->add_data([
		'table_name' 	=> $table_name,
		'rec_id'	=> $rec_id,
		'op'		=> 'category',
		]);
	$o_form->add_selector('submit', $options, get_category_event_row_value($row, 'category_id', 0));

	$o_form->compile();

	$result = $o_form->code();
	unset($o_form);
	return $result;	
	}
?>

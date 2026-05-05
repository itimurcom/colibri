<?php
// ================ CRC ================
// version: 1.15.04
// hash: c0fdaaab1c18960e672ca2a194cba2d3777d0cf50da6bd95d1bea30bbb308751
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает селектор статуса записи
//..............................................................................
function get_status_event_row_value($row, $key, $default=NULL)
	{
	return (is_array($row) && array_key_exists($key, $row)) ? $row[$key] : $default;
	}

function get_status_event($row, $selector='statuses')
	{
	global $prepared_arr, $statuses;
	if (!is_array($row)) return '';
	$table_name = get_status_event_row_value($row, 'table_name');
	$rec_id = (int)get_status_event_row_value($row, 'rec_id', get_status_event_row_value($row, 'id', 0));
	if (empty($table_name) || $rec_id<=0) return '';

	$o_form = new itForm2();


	if (!isset($prepared_arr[$selector]) AND $selector=='statuses' AND is_array($statuses))
		{
		//..............................................................................
		// массив статусов
		//..............................................................................
		foreach ($statuses as $gr_key=>$gr_row)
			{
			if (is_array($gr_row) && get_status_event_row_value($gr_row, 'show', 0)==1)
				{
				$prepared_arr['statuses'][$gr_key] = array
					(
					'title' => get_const(get_status_event_row_value($statuses[$gr_key], 'title', 'STATUS_'.$gr_key)),
					'value'	=> $gr_key,
					); 
				}
			}
		}


	if (isset($prepared_arr[$selector]) && is_array($prepared_arr[$selector]))
		{		
		$options = [
			'array' 	=> $prepared_arr[$selector],
			'titles'        => 'title',
			'values'	=> 'value',
			'name'		=> 'status',
			'form'		=> $o_form->form_id()
			];
		$o_form->add_selector('submit', $options, get_status_event_row_value($row, 'status'));
		} else add_error_message("no prepared index <b>{$selector}</b>");
		
	$o_form->add_data([
		'table_name'	=> $table_name,
		'rec_id'	=> $rec_id,
		'op'		=> 'status'
		]);

	$o_form->compile();

	$result = $o_form->code();
	unset($o_form);
	return $result;	
	}
?>

<?php
// ================ CRC ================
// version: 1.15.04
// hash: c0fdaaab1c18960e672ca2a194cba2d3777d0cf50da6bd95d1bea30bbb308751
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// возвращает селектор статуса записи
//..............................................................................
function get_status_event($row, $selector='statuses')
	{
	global $prepared_arr, $statuses;
	$o_form = new itForm2();


	if (!isset($prepared_arr[$selector]) AND $selector=='statuses' AND is_array($statuses))
		{
		//..............................................................................
		// массив статусов
		//..............................................................................
		foreach ($statuses as $gr_key=>$gr_row)
			{
			if ($gr_row['show']==1)
				{
				$prepared_arr['statuses'][$gr_key] = array
					(
					'title' => get_const($statuses[$gr_key]['title']),
					'value'	=> $gr_key,
					); 
				}
			}
		}


	if (isset($prepared_arr[$selector]))
		{		
		$options = [
			'array' 	=> $prepared_arr[$selector],
			'titles'        => 'title',
			'values'	=> 'value',
			'name'		=> 'status',
			'form'		=> $o_form->form_id()
			];
		$o_form->add_itSelector('submit', $options, $row['status']);
		} else add_error_message("no prepared index <b>{$selector}</b>");
		
	$o_form->add_data([
		'table_name'	=> $row['table_name'],
		'rec_id'	=> $row['rec_id'],
		'op'		=> 'status'
		]);

	$o_form->compile();

	$result = $o_form->code();
	unset($o_form);
	return $result;	
	}
?>
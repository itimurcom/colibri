<?php
// ================ CRC ================
// version: 1.35.05
// hash: ec21022504d92efbe67cb3bfb014221a45db44a7419cb4fd6f78f9b4a0058a4e
// date: 21 May 2021 10:57
// ================ CRC ================
//..............................................................................
// редактирование свойств поля в зависимости от типа поля формы (2.1)
//..............................................................................
function f2_change_event($row)
	{
	definition([
		'F2_TITLES'	=> 'Надписи (в каждой строке по одной)',
		'F2_VALUES'	=> 'Значения (в каждой строке по одному)',
		]);
	global $lang_cat, $form2_defaults;

	$o_modal = new itModal();
	$o_modal->set_size('large');
	$o_modal->set_animation('fadeAndPop');
	
	$o_form = new itForm2();
	$o_form->add_title(mstr_replace([
		'[VALUE]' 	=> "{$row['kind']} #".($row['ed_key']+1),
		'[KIND]'	=> get_const($form2_defaults[$row['kind']]['title']),
		], get_const('F2_CHANGE_TITLE')));


	$o_form->add_button(get_const('BUTTON_OK'), 'ajaxsubmit', ['form' => $o_form->form_id(), 'ajax'=>"f2_edreload('#".itForm2::_container_id($row)."');"], 'blue' );	
	$o_form->add_button(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	

	if (!in_array($row['kind'], explode(',', F2_STRIPLABEL)))
	$o_form->add_input([
		'name'		=> 'label',
		'label'		=>  get_const('F2_LABEL')."&nbsp;<small class='blue'>{$lang_cat[CMS_LANG]['name_orig']}</small>",
		'compact'	=> true,
		'value'		=> isset($row['label'][CMS_LANG]) ? $row['label'][CMS_LANG] : get_const(ready_val($row['label'])),
		]);


	if (!in_array($row['kind'], explode(',', F2_STRIPNAME)))
	$o_form->add_input([
		'name'		=> 'name',
		'label'		=>  'F2_NAME',
		'compact'	=> true,
		'value'		=> isset($row['name'][CMS_LANG]) ? $row['name'][CMS_LANG] : get_const(ready_val($row['name'])),
		]);

	if (!in_array($row['kind'], explode(',', F2_STRIPVALUE)))
		{
		switch ($row['kind'])
			{
			case 'SELECT' : {
				$o_form->add_select([
					'type'		=>'select',
					'name'		=> 'value',
					'array' 	=> ready_val($row['array'], []),
					'titles'        => isset($row['titles']) ? $row['titles'] : 'title',
					'values'	=> isset($row['values']) ? $row['values'] : 'values',
					'value'		=> isset($row['value'][CMS_LANG]) ? $row['value'][CMS_LANG] : ready_val($row['value']),
					'label'		=> get_const('F2_VALUE')."&nbsp;<small class='blue'>{$lang_cat[CMS_LANG]['name_orig']}</small>",
					'compact'	=> true,
					]);
				

				$titles_res = NULL;
				if (is_array((@$titles_arr = array_column($row['array'], $row['titles']))))
					{
					foreach ($titles_arr as $key=>$title_row)
						{
						$titles_res[] = is_array($title_row) ? get_field_by_lang($title_row, CMS_LANG, "autotitle{$key}") : $title_row;
						}
					}
				$values_res = NULL;
				if (is_array((@$values_arr = array_column($row['array'], $row['values']))))
					{
					foreach ($values_arr as $key=>$value_row)
						{
						$values_res[] = is_array($value_row) ? get_field_by_lang($value_row, CMS_LANG, "autooption{$key}") : $value_row;
						}
					}

				if ($row['name']=='dms_ab_2')
					{
//					print_r($titles_res); die;
					}
				$o_form->add_area([
					'name'		=> 'f2_titles',
					'value'		=> (is_array($titles_res) ? implode("\n",$titles_res) : $titles_res),
					'label'		=> 'F2_TITLES',
					'class'		=> 'fixed',
					]);					
				$o_form->add_area([
					'name'		=> 'f2_values',
					'value'		=> (is_array($values_res) ? implode("\n",$values_res) : $values_res),
					'label'		=> 'F2_VALUES',
					'class'		=> 'fixed',					
					]);					

				break;
				}

			case "SET" : {
				$o_form->add_set([
					'name'		=> 'value',
					'array' 	=> $row['array'],
					'titles'        => $row['titles'],
					'values'	=> $row['values'],
					'value'		=> ready_val($row['value']),
					'label'		=>  get_const('F2_VALUE')."&nbsp;<small class='blue'>{$lang_cat[CMS_LANG]['name_orig']}</small>",
//					'compact'	=> true,
					]);


				$titles_res = NULL;
				if (is_array(($titles_arr = array_column($row['array'], $row['titles']))))
					{
					foreach ($titles_arr as $key=>$title_row)
						{
						$titles_res[] = is_array($title_row) ? get_field_by_lang($title_row, CMS_LANG, "autotitle{$key}") : $title_row;
						}
					}
				$values_res = NULL;
				if (is_array(($values_arr = array_column($row['array'], $row['values']))))
					{
					foreach ($values_arr as $key=>$value_row)
						{
						$values_res[] = is_array($value_row) ? get_field_by_lang($value_row, CMS_LANG, "autooption{$key}") : $value_row;
						}
					}
										
				$o_form->add_area([
					'name'		=> 'f2_titles',
					'value'		=> implode("\n",$titles_res),
					'label'		=> 'F2_TITLES',
					'class'		=> 'fixed',					
					]);					
				$o_form->add_area([
					'name'		=> 'f2_values',
					'value'		=> implode("\n",$values_res),
					'label'		=> 'F2_VALUES',
					'class'		=> 'fixed',					
					]);					

				break;
				}			
			default : {
				$o_form->add_input([
					'name'		=> 'value',
					'label'		=>  get_const('F2_VALUE')."&nbsp;<small class='blue'>{$lang_cat[CMS_LANG]['name_orig']}</small>",
					'compact'	=> true,
					'value'		=> isset($row['value'][CMS_LANG]) ? $row['value'][CMS_LANG] : ready_val($row['value']),
					]);
				}
			}
		}


	if (in_array($row['kind'], explode(',', "NUMBER")))
		{
		$o_form->add_number([
			'name'		=> 'min',
			'label'		=>  'F2_MIN',
			'compact'	=> true,
			'value'		=> isset($row['min']) ? $row['min'] : NULL,
			]);

		$o_form->add_number([
			'name'		=> 'min',
			'label'		=>  'F2_MAX',
			'compact'	=> true,
			'value'		=> isset($row['max']) ? $row['max'] : NULL,
			]);
			
		$o_form->add_input([
			'name'		=> 'multi',
			'label'		=>  'F2_MULTI',
			'compact'	=> true,
			'value'		=> isset($row['multi']) ? $row['multi'] : 1,
			]);

		}


	$set_settings['more'] = [
		'title'	=>	'F2_ENABLE_EDITOR',
		'value'	=>	'more',
		'color'	=> 	'blue',
 		];
 		
	$set_settings['compact'] = [
		'title'	=>	'F2_ENABLE_COMPACT',
		'value'	=>	'compact',
		'color'	=> 	'green',
		];
		
	if (in_array($row['kind'], explode(',',"INPUT,PASS,PHONE,EMAIL,AREA,SELECT,AUTO,DATE,TIME")))
		{
		$set_settings['required'] = [
			'title'	=>	'F2_ENABLE_REQUIRED',
			'value'	=>	'required',
			'color'	=> 	'red',
			];
		}
		
	$o_form->add_set([
		'label'		=> 'F2_EDITOR_SET',
		'name'		=> 'editor',
		'array' 	=> $set_settings,
		'titles'	=> 'title',
		'values'	=> 'value',
		'compact'	=> true,
		'editor'	=> true,
		'value'		=> [
			'more' => ready_val($row['more']),
			'compact' => ready_val($row['compact']),			
			'required' => ready_val($row['required']),
			],
		]);


	$row['op'] = 'f2_change';
	$o_form->add_data($row);

	$o_modal->add_field($o_form->_view());
 	$o_modal->compile();

	$o_button = new itButton(get_const('BUTTON_ED_CHANGE'), 'modal', ['class' => 'admin', 'form' => $o_modal->form_id()], 'blue' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return TAB."<div class='ed_change'>".$result.TAB."</div>";		
	}
?>
<?php
// редактирование свойств поля в зависимости от типа поля формы (2.1)
function f2_change_localized_label($const='F2_VALUE')
	{
	global $lang_cat;
	return get_const($const)."&nbsp;<small class='blue'>{$lang_cat[CMS_LANG]['name_orig']}</small>";
	}

function f2_change_list_settings($row)
	{
	return [
		'array'		=> isset($row['array']) && is_array($row['array']) ? $row['array'] : [],
		'titles'	=> isset($row['titles']) ? $row['titles'] : 'title',
		'values'	=> isset($row['values']) ? $row['values'] : 'value',
		];
	}

function f2_change_list_lines($list_array, $field, $prefix)
	{
	$result = NULL;
	foreach (array_column($list_array, $field) as $key=>$value)
		{
		$result[] = is_array($value) ? get_field_by_lang($value, CMS_LANG, "{$prefix}{$key}") : $value;
		}
	return $result;
	}

function f2_change_add_list_areas($o_form, $titles_res, $values_res)
	{
	foreach (['f2_titles' => ['F2_TITLES', $titles_res], 'f2_values' => ['F2_VALUES', $values_res]] as $name=>$data)
		{
		$o_form->add_area([
			'name'	=> $name,
			'value'	=> is_array($data[1]) ? implode("\n", $data[1]) : ready_val($data[1]),
			'label'	=> $data[0],
			'class'	=> 'fixed',
			]);
		}
	}

function f2_change_add_list_value($o_form, $row)
	{
	$list = f2_change_list_settings($row);
	$options = [
		'name'		=> 'value',
		'array' 	=> $list['array'],
		'titles'        => $list['titles'],
		'values'	=> $list['values'],
		'value'		=> isset($row['value'][CMS_LANG]) ? $row['value'][CMS_LANG] : ready_val($row['value']),
		'label'		=> f2_change_localized_label(),
		];

	if ($row['kind']=='SELECT')
		{
		$options['type'] = 'select';
		$options['compact'] = true;
		$o_form->add_selector($options);
		}
	else
		{
		$o_form->add_set($options);
		}

	f2_change_add_list_areas(
		$o_form,
		f2_change_list_lines($list['array'], $list['titles'], 'autotitle'),
		f2_change_list_lines($list['array'], $list['values'], 'autooption')
		);
	}

function f2_change_add_numeric_settings($o_form, $row)
	{
	if (!in_array($row['kind'], explode(',', "NUMBER"))) return;

	foreach (['min' => 'F2_MIN', 'max' => 'F2_MAX'] as $name=>$label)
		{
		$o_form->add_number([
			'name'		=> $name,
			'label'		=> $label,
			'compact'	=> true,
			'value'		=> isset($row[$name]) ? $row[$name] : NULL,
			]);
		}

	$o_form->add_input([
		'name'		=> 'multi',
		'label'		=> 'F2_MULTI',
		'compact'	=> true,
		'value'		=> isset($row['multi']) ? $row['multi'] : 1,
		]);
	}

function f2_change_editor_settings($row)
	{
	$set_settings = [
		'more'		=> ['title' => 'F2_ENABLE_EDITOR', 'value' => 'more', 'color' => 'blue'],
		'compact'	=> ['title' => 'F2_ENABLE_COMPACT', 'value' => 'compact', 'color' => 'green'],
		];

	if (in_array($row['kind'], explode(',',"INPUT,PASS,PHONE,EMAIL,AREA,SELECT,AUTO,DATE,TIME")))
		$set_settings['required'] = ['title' => 'F2_ENABLE_REQUIRED', 'value' => 'required', 'color' => 'red'];

	return $set_settings;
	}

function f2_change_event($row)
	{
	definition([
		'F2_TITLES'	=> 'Надписи (в каждой строке по одной)',
		'F2_VALUES'	=> 'Значения (в каждой строке по одному)',
		]);
	global $form2_defaults;

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
			'label'		=> f2_change_localized_label('F2_LABEL'),
			'compact'	=> true,
			'value'		=> isset($row['label'][CMS_LANG]) ? $row['label'][CMS_LANG] : get_const(ready_val($row['label'])),
			]);

	if (!in_array($row['kind'], explode(',', F2_STRIPNAME)))
		$o_form->add_input([
			'name'		=> 'name',
			'label'		=> 'F2_NAME',
			'compact'	=> true,
			'value'		=> isset($row['name'][CMS_LANG]) ? $row['name'][CMS_LANG] : get_const(ready_val($row['name'])),
			]);

	if (!in_array($row['kind'], explode(',', F2_STRIPVALUE)))
		{
		if (in_array($row['kind'], ['SELECT', 'SET']))
			{
			f2_change_add_list_value($o_form, $row);
			}
		else
			{
			$o_form->add_input([
				'name'		=> 'value',
				'label'		=> f2_change_localized_label(),
				'compact'	=> true,
				'value'		=> isset($row['value'][CMS_LANG]) ? $row['value'][CMS_LANG] : ready_val($row['value']),
				]);
			}
		}

	f2_change_add_numeric_settings($o_form, $row);

	$o_form->add_set([
		'label'		=> 'F2_EDITOR_SET',
		'name'		=> 'editor',
		'array' 	=> f2_change_editor_settings($row),
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

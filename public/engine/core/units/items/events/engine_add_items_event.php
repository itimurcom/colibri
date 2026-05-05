<?php
function colibri_item_event_row_value($row, $key, $default=NULL)
	{
	return (is_array($row) && array_key_exists($key, $row)) ? $row[$key] : $default;
	}

function colibri_item_event_positive_id($value)
	{
	$value = (int)$value;
	return $value > 0 ? $value : NULL;
	}

function colibri_item_event_language_name()
	{
	global $lang_cat;
	return (is_array($lang_cat) && defined('CMS_LANG') && isset($lang_cat[CMS_LANG]) && is_array($lang_cat[CMS_LANG]) && isset($lang_cat[CMS_LANG]['name_orig']))
		? $lang_cat[CMS_LANG]['name_orig']
		: (defined('CMS_LANG') ? CMS_LANG : '');
	}

function colibri_item_event_user_is_logged($groups=NULL)
	{
	global $_USER;
	return (is_object($_USER) && method_exists($_USER, 'is_logged')) ? $_USER->is_logged($groups) : false;
	}

function colibri_item_event_user_id()
	{
	global $_USER;
	return (is_object($_USER) && method_exists($_USER, 'id')) ? $_USER->id() : NULL;
	}

function colibri_item_event_toggle_button($row, $op, $button_const, $flag_key)
	{
	if (!is_array($row)) return '';
	$table_name = colibri_item_event_row_value($row, 'table_name');
	$rec_id = colibri_item_event_positive_id(colibri_item_event_row_value($row, 'rec_id', colibri_item_event_row_value($row, 'id', 0)));
	if (empty($table_name) || $rec_id===NULL) return '';

	$o_form = new itForm2();
	$o_form->add_data([
		'user_id'	=> colibri_item_event_user_id(),
		'table_name' 	=> $table_name,
		'rec_id'	=> $rec_id,
		'op'		=> $op,
		]);
	$o_form->compile();
	$o_button = new itButton(get_const($button_const), 'submit', ['form'=>$o_form->form_id(), 'class' => 'admin'], colibri_item_event_row_value($row, $flag_key, 0) ? 'blue' : 'gray');

	$result = $o_form->code().$o_button->code();
	unset($o_button, $o_form);
	return $result;
	}

function colibri_item_event_modal($size='small', $animation='fadeAndPop')
	{
	$o_modal = new itModal();
	$o_modal->set_size($size);
	$o_modal->set_animation($animation);
	return $o_modal;
	}

function colibri_item_event_modal_form($title, $size='small', $animation='fadeAndPop')
	{
	$o_modal = colibri_item_event_modal($size, $animation);
	$o_form = new itForm2();
	$o_form->add_title($title);
	return [$o_modal, $o_form];
	}

function colibri_item_event_add_ok_cancel($o_form, $o_modal, $ok_color='blue', $cancel_color='green')
	{
	$o_form->add_button(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], $ok_color);
	$o_form->add_button(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], $cancel_color);
	}

function colibri_item_event_modal_code($o_modal, $o_form, $button_title, $button_color='green', $button_class='admin')
	{
	$o_form->compile();
	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton($button_title, 'modal', ['class' => $button_class, 'form' => $o_modal->form_id()], $button_color);
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}

function colibri_item_event_link_button($title, $href, $color)
	{
	$o_button = new itButton2([
		'type'	=> 'a',
		'href'	=> $href,
		'title'	=> $title,
		'color' => $color,
		]);

	$result = $o_button->code();
	unset($o_button);
	return $result;
	}

function colibri_item_event_category_selector_options($prepared_arr, $value=NULL)
	{
	$options = [
		'array' 	=> (is_array($prepared_arr) && isset($prepared_arr['items']) && is_array($prepared_arr['items'])) ? $prepared_arr['items'] : [],
		'titles'	=> 'title',
		'values'	=> 'value',
		'name'		=> 'category_id',
		'compact'	=> true,
		];

	if ($value!==NULL)
		{
		$options['type'] = 'select';
		$options['label'] = 'ITEM_CATEGORY';
		$options['value'] = $value;
		}

	return $options;
	}

function colibri_item_event_add_item_identity_fields($o_form, $serie='', $version='')
	{
	$o_form->add_input([
		'name'		=> 'serie',
		'value'		=> $serie,
		'label'		=> get_const('ITEM_SERIE'),
		'compact'	=> true,
		]);
	$o_form->add_input([
		'name'		=> 'version',
		'value'		=> $version,
		'label'		=> get_const('ITEM_VERSION'),
		'compact'	=> true,
		]);
	}

function get_item_shop_event($row)
	{
	return colibri_item_event_toggle_button($row, 'is_shop', 'BUTTON_SHOP', 'is_shop');
	}

function get_item_replicant_event($row)
	{
	return colibri_item_event_toggle_button($row, 'is_replicant', 'BUTTON_REPLICANT', 'is_replicant');
	}

function get_item_econom_event($row)
	{
	return colibri_item_event_toggle_button($row, 'is_econom', 'BUTTON_ECONOM', 'is_econom');
	}

function get_item_new_event($row)
	{
	return colibri_item_event_toggle_button($row, 'is_new', 'BUTTON_NEW', 'is_new');
	}

function get_item_title_event($row)
	{
	global $lang_cat;
	if (!is_array($row)) return '';
	if (colibri_item_event_positive_id(colibri_item_event_row_value($row, 'rec_id', colibri_item_event_row_value($row, 'id', 0)))===NULL || empty(colibri_item_event_row_value($row, 'table_name'))) return '';

	list($o_modal, $o_form) = colibri_item_event_modal_form(mstr_replace([
		'[VALUE]'	=> get_item_articul($row),
		'[LANG]'	=> colibri_item_event_language_name(),
		], get_const('ITEM_TITLE_QUERY')), 'medium', 'fadeAndUp');
	$o_form->add_input([
		'name'		=> 'value',
		'value'		=> get_field_by_lang(colibri_item_event_row_value($row, 'title_xml', ''), CMS_LANG, ''),
		]);
	$o_form->add_data([
		'table_name' 	=> colibri_item_event_row_value($row, 'table_name'),
		'rec_id'	=> colibri_item_event_positive_id(colibri_item_event_row_value($row, 'rec_id', colibri_item_event_row_value($row, 'id', 0))),
		'op'		=> 'ed_title'
		]);
	colibri_item_event_add_ok_cancel($o_form, $o_modal);
	return colibri_item_event_modal_code($o_modal, $o_form, get_const('BUTTON_ED_TITLE'), 'green');
	}

function get_item_x_event($row)
	{
	if (!is_array($row)) return '';
	if (colibri_item_event_positive_id(colibri_item_event_row_value($row, 'rec_id', colibri_item_event_row_value($row, 'id', 0)))===NULL || empty(colibri_item_event_row_value($row, 'table_name'))) return '';
	list($o_modal, $o_form) = colibri_item_event_modal_form(str_replace('[VALUE]', get_item_articul($row), get_const('QUERY_ITEM_REMOVE')));
	$o_form->add_data([
		'table_name'	=> colibri_item_event_row_value($row, 'table_name'),
		'rec_id'	=> colibri_item_event_positive_id(colibri_item_event_row_value($row, 'rec_id', colibri_item_event_row_value($row, 'id', 0))),
		'op'		=> 'item_x',
		]);
	colibri_item_event_add_ok_cancel($o_form, $o_modal, 'red');
	return colibri_item_event_modal_code($o_modal, $o_form, get_const('X'), 'red');
	}

function get_item_add_event()
	{
	global $lang_cat, $prepared_arr, $_USER, $_RIGHTS;

	if (!colibri_item_event_user_is_logged(isset($_RIGHTS['EDIT']) ? $_RIGHTS['EDIT'] : NULL)) return;

	list($o_modal, $o_form) = colibri_item_event_modal_form(str_replace ('[VALUE]', colibri_item_event_language_name(), get_const('QUERY_ADD_ITEM')), 'medium');
	$o_form->add_input([
		'name'		=> 'value',
		'value'		=> '',
		'label'		=> get_const('ITEM_LABEL'),
		]);

	if (isset($prepared_arr['items']))
		{
		$o_form->add_selector('select', colibri_item_event_category_selector_options($prepared_arr), '', NULL, get_const('ITEM_CATEGORY'));
		}

	$o_form->add_set([
		'label'	=> 'SPECIAL_ITEM_LABEL',
		'name'	=> 'is',
		'array'	=> [
			[
			'title'	=> 'ITEM_REPLICANT',
			'value'	=> 'replicant',
			],
			[
			'title'	=> 'ITEM_SHOP',
			'value'	=> 'shop',
			],
		],
		'compact'	=> true,
		]);
	colibri_item_event_add_item_identity_fields($o_form);
	$o_form->add_data([
		'table_name'	=> DEFAULT_ITEM_TABLE,
		'op'		=> 'add_item',
		]);
	colibri_item_event_add_ok_cancel($o_form, $o_modal);
	return colibri_item_event_modal_code($o_modal, $o_form, '<b>'.get_const('BUTTON_PLUS_ITEM').'</b>', 'green');
	}

function get_item_articul_event($row)
	{
	global $prepared_arr, $_USER, $_RIGHTS;

	if (!is_array($row)) return '';
	if (!colibri_item_event_user_is_logged(isset($_RIGHTS['EDIT']) ? $_RIGHTS['EDIT'] : NULL)) return;
	if (colibri_item_event_positive_id(colibri_item_event_row_value($row, 'id', colibri_item_event_row_value($row, 'rec_id', 0)))===NULL) return '';

	list($o_modal, $o_form) = colibri_item_event_modal_form(str_replace ('[VALUE]', get_field_by_lang(colibri_item_event_row_value($row, 'title_xml', '')), get_const('QUERY_ARTICUL_ITEM')));

	if (isset($prepared_arr['items']))
		{
		$o_form->add_selector(colibri_item_event_category_selector_options($prepared_arr, colibri_item_event_row_value($row, 'category_id', 0)));
		}

	colibri_item_event_add_item_identity_fields($o_form, colibri_item_event_row_value($row, 'serie', ''), colibri_item_event_row_value($row, 'version', ''));
	$o_form->add_data([
		'table_name'	=> DEFAULT_ITEM_TABLE,
		'rec_id'	=> colibri_item_event_positive_id(colibri_item_event_row_value($row, 'id', colibri_item_event_row_value($row, 'rec_id', 0))),
		'op'		=> 'item_articul',
		]);
	colibri_item_event_add_ok_cancel($o_form, $o_modal);
	return colibri_item_event_modal_code($o_modal, $o_form, get_const('BUTTON_ARTICUL'), 'blue');
	}

function get_rewind_event()
	{
	return;
	}

function get_buy_item_event($row)
	{
	if (!is_array($row)) return '';
	if (colibri_item_event_positive_id(colibri_item_event_row_value($row, 'id', 0))===NULL) return '';
	$is_orderable = (colibri_item_event_row_value($row, 'is_shop', 0) OR is_for_sale($row));
	return colibri_item_event_link_button(
		$is_orderable ? str_replace('[VALUE]', colibri_item_event_row_value($row, 'price', 0), get_const('BUTTON_BUY')) : get_const('BUTTON_CONTACT'),
		$is_orderable ? '/'.CMS_LANG."/buy/".colibri_item_event_positive_id(colibri_item_event_row_value($row, 'id', 0))."/" : '/'.CMS_LANG."/contacts/".colibri_item_event_positive_id(colibri_item_event_row_value($row, 'id', 0))."/",
		$is_orderable ? 'brown big' : 'green big'
	);
	}

function get_order_item_event($row)
	{
	if (!is_array($row)) return '';
	if (colibri_item_event_positive_id(colibri_item_event_row_value($row, 'id', 0))===NULL) return '';
	if (colibri_item_event_row_value($row, 'is_shop', 0) OR is_for_sale($row)) return;
	return colibri_item_event_link_button(get_const('BUTTON_ORDER'), '/'.CMS_LANG."/order/".colibri_item_event_positive_id(colibri_item_event_row_value($row, 'id', 0))."/", 'yellow big');
	}

function get_item_calc_event($row, $table_name=DEFAULT_ITEM_TABLE, $form_name=DEFAULT_FORM_TABLE)
	{
	if (!is_array($row)) return '';
	if (colibri_item_event_row_value($row, 'is_shop', 0) OR is_for_sale($row)) return;

	$o_modal = new itModal();
	$o_modal->set_size('large');
	$o_modal->set_animation('fadeAndPop');
	$o_modal->add_title(str_replace ('[VALUE]', get_item_articul($row), get_const('QUERY_CALC_ITEM')));

	$o_form = new itForm2([
		'rec_id'	=> FORM2_CALC,
		]);

	$o_form->hiddens_xml = NULL;
	$o_form->add_data([
		'form_name'	=> $form_name,
		'form_id'	=> FORM2_CALC,
		'price'		=> colibri_item_event_row_value($row, 'price', 0),
		'articul'	=> get_item_articul($row),
		'op'		=> 'item_calc',
		]);

	$store = false;
	foreach($o_form->fields_xml as $key=>$form_row)
		{
		if (isset($form_row['name']) AND !isset($form_row['ajaz'] ))
			{
			$o_form->fields_xml[$key]['ajax'] = "ajax_submit('#form-forms-".FORM2_CALC."');";
			$store = true;
			}
		}
	if ($store) $o_form->store();

	$o_form->compile();

	$o_cancel = new itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'red ok big' );
	$o_order = new itButton(get_const('BUTTON_ORDER'), 'close', ['form' => $o_modal->form_id(), 'ajax'=>"calc_order('".$o_form->form_id()."','/".CMS_LANG."/order/');"], 'blue big' );

	$o_modal->add_field(
		TAB."<div class='calculator'>".
		$o_form->container().
		TAB."<div class='f2_form'>".
			TAB."<div class='modal_row f2_row compact'>".
				TAB."<div class='label compact full blue'>".get_const('CALC_ITEM_RESULT').":</div>".
				TAB."<div id='calculator-result-".FORM2_CALC."' class='value boxed compact blue calcres'>...</div>".
			TAB."</div>".
			TAB."<div class='modal_row'><div class='buttons_div green'>".get_const('DECORATION_DESCRIPTION')."</div></div>".
			TAB."<div class='buttons_div'>".$o_cancel->code().$o_order->code()."</div>".
		TAB."</div>".
		TAB."</div>");

 	$o_modal->compile();

	$o_button = new itButton(get_const('BUTTON_CALC'), 'modal', ['ajax' => "setTimeout(function(){ ajax_submit('#".$o_form->form_id()."');}, 1000);", 'form' => $o_modal->form_id()], 'blue big' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal, $o_cancel, $o_order);
	return $result;
	}

function get_price_item_event($row)
	{
	if (!is_array($row)) return '';
	if (!colibri_item_event_user_is_logged()) return;
	if (colibri_item_event_positive_id(colibri_item_event_row_value($row, 'rec_id', colibri_item_event_row_value($row, 'id', 0)))===NULL) return '';

	list($o_modal, $o_form) = colibri_item_event_modal_form(mstr_replace([
		'[VALUE]'	=> get_item_articul($row),
		], get_const('ITEM_PRICE_QUERY')), 'small', 'fadeAndUp');
	$o_form->add_input([
		'label'		=> 'стоимость, $',
		'name'		=> 'value',
		'value'		=> colibri_item_event_row_value($row, 'price', 0),
		'compact'	=> true,
		]);
	$o_form->add_data([
		'table_name' 	=> 'items',
		'rec_id'	=> colibri_item_event_positive_id(colibri_item_event_row_value($row, 'rec_id', colibri_item_event_row_value($row, 'id', 0))),
		'op'		=> 'item_price'
		]);
	colibri_item_event_add_ok_cancel($o_form, $o_modal);

	$result = colibri_item_event_modal_code($o_modal, $o_form, doubleval(colibri_item_event_row_value($row, 'price', 0)).' $', 'yellow big', '');
	return
		TAB."<div class='item_price'>".
		TAB."<div>".
		$result.
		TAB."</div>".
		TAB."</div>";
	}
?>

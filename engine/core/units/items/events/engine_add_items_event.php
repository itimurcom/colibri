<?php
//..............................................................................
// функция возвращает событие установки флага магазина
//..............................................................................
function get_item_shop_event($row)
	{
	global $_USER;

	$o_form = new itForm2();
	$o_form->add_data([
		'user_id'	=> $_USER->id(),
		'table_name' 	=> $row['table_name'],
		'rec_id'	=> $row['rec_id'],
		'op'		=> 'is_shop',
		]);
	$o_form->compile();	
	$o_button = new itButton(get_const('BUTTON_SHOP'), 'submit', ['form'=>$o_form->form_id(), 'class' => 'admin'], $row['is_shop'] ? 'blue' : 'gray');
	
	$result = $o_form->code().$o_button->code();
	unset($o_button, $o_form);
	return $result;
	}

//..............................................................................
// функция возвращает событие установки флага репликанта
//..............................................................................
function get_item_replicant_event($row)
	{
	global $_USER;

	$o_form = new itForm2();
	$o_form->add_data([
		'user_id'	=> $_USER->id(),
		'table_name' 	=> $row['table_name'],
		'rec_id'	=> $row['rec_id'],
		'op'		=> 'is_replicant',
		]);
	$o_form->compile();	
	$o_button = new itButton(get_const('BUTTON_REPLICANT'), 'submit', ['form'=>$o_form->form_id(), 'class' => 'admin'], $row['is_replicant'] ? 'blue' : 'gray');
	
	$result = $o_form->code().$o_button->code();
	unset($o_button, $o_form);
	return $result;
	}
	
//..............................................................................
// функция возвращает событие установки флага эконом класс
//..............................................................................
function get_item_econom_event($row)
	{
	global $_USER;

	$o_form = new itForm2();
	$o_form->add_data([
		'user_id'	=> $_USER->id(),
		'table_name' 	=> $row['table_name'],
		'rec_id'	=> $row['rec_id'],
		'op'		=> 'is_econom',
		]);
	$o_form->compile();	
	$o_button = new itButton(get_const('BUTTON_ECONOM'), 'submit', ['form'=>$o_form->form_id(), 'class' => 'admin'], $row['is_econom'] ? 'blue' : 'gray');
	
	$result = $o_form->code().$o_button->code();
	unset($o_button, $o_form);
	return $result;
	}

//..............................................................................
// функция возвращает событие установки флага новый
//..............................................................................
function get_item_new_event($row)
	{
	global $_USER;

	$o_form = new itForm2();
	$o_form->add_data([
		'user_id'	=> $_USER->id(),
		'table_name' 	=> $row['table_name'],
		'rec_id'	=> $row['rec_id'],
		'op'		=> 'is_new',
		]);
	$o_form->compile();	
	$o_button = new itButton(get_const('BUTTON_NEW'), 'submit', ['form'=>$o_form->form_id(), 'class' => 'admin'], $row['is_new'] ? 'blue' : 'gray');
	
	$result = $o_form->code().$o_button->code();
	unset($o_button, $o_form);
	return $result;
	}
	
//..............................................................................
// возвращает кнопку изменения заголовка товара
//..............................................................................
function get_item_title_event($row)
	{
	global $lang_cat;

	$o_modal = new itModal();
	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndUp');

	$o_form = new itForm2();
	$o_form->add_title(mstr_replace([
		'[VALUE]'	=> get_item_articul($row),
		'[LANG]'	=> $lang_cat[CMS_LANG]['name_orig'],
		], get_const('ITEM_TITLE_QUERY')));

	$o_form->add_input([
		'name'		=> 'value',
		'value'		=> get_field_by_lang($row['title_xml'], CMS_LANG, ''),
		]);
	$o_form->add_data([
		'table_name' 	=> $row['table_name'],
		'rec_id'	=> $row['rec_id'],
		'op'		=> 'ed_title'
		]);
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(get_const('BUTTON_ED_TITLE'), 'modal', ['class' => 'admin', 'form' => $o_modal->form_id()], 'green' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
	
//..............................................................................
// возвращает кнопку для удаления товара
//..............................................................................
function get_item_x_event($row)
	{	
/*	if ($row['status']=='MODERATE')
		{
		$button = get_const('BUTTON_OK');
		$color = 'blue';
		$color_ok = 'blue';
		$query =  'QUERY_CONTENT_PUBLISH';
		} else	{
			$button = 'BUTTON_MODERATE';
			$color = 'red';
			$color_ok = 'red';
			$query =  'QUERY_CONTENT_MODERATE';
			}
*/

		$button = 'X';
		$color = 'red';
		$color_ok = 'red';
		$query =  'QUERY_ITEM_REMOVE';
	
	$o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndPop');

	$o_form = new itForm2();
	$o_form->add_title(str_replace('[VALUE]', get_item_articul($row), get_const($query)));
	$o_form->add_data([
		'table_name'	=> $row['table_name'],
		'rec_id'	=> $row['rec_id'],
		'op'		=> 'item_x',
		]);
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], $color_ok );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(get_const($button), 'modal', ['class' => 'admin', 'form' => $o_modal->form_id()], $color );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;
	}
	
//..............................................................................
// событие для добавления товара
//..............................................................................
function get_item_add_event()
	{
	global $lang_cat, $prepared_arr, $_USER, $_RIGHTS;
	
	if (!$_USER->is_logged($_RIGHTS['EDIT'])) return;

	$o_modal = new itModal();
	$o_modal->set_size('medium');
	$o_modal->set_animation('fadeAndPop');
	
	$o_form = new itForm2();
	$o_form->add_title(str_replace ('[VALUE]', $lang_cat[CMS_LANG]['name_orig'], get_const('QUERY_ADD_ITEM')));
	
	$o_form->add_input([
		'name'		=> 'value',
		'value'		=> '',
		'label'		=> get_const('ITEM_LABEL'),
		]);

	if (isset($prepared_arr['items']))
		{
		$options = [
			'array' 	=> $prepared_arr['items'],
			'titles'        => 'title',
			'values'	=> 'value',
			'name'		=> 'category_id',
			'compact'	=> true,
			];			
		$o_form->add_itSelector('select', $options, '', NULL, get_const('ITEM_CATEGORY'));
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

	$o_form->add_input([
		'name'		=> 'serie',
		'value'		=> '',
		'label'		=> get_const('ITEM_SERIE'),
		'compact'	=> true,
		]);

	$o_form->add_input([
		'name'		=> 'version',
		'value'		=> '',
		'label'		=> get_const('ITEM_VERSION'),
		'compact'	=> true,
		]);

	
	$o_form->add_data([
		'table_name'	=> DEFAULT_ITEM_TABLE,
		'op'		=> 'add_item',
		]);
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton("<b>".get_const('BUTTON_PLUS_ITEM')."</b>", 'modal', ['class'=>'admin', 'form' => $o_modal->form_id()], 'green' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;	
	}

//..............................................................................
// событие смены артикула
//..............................................................................
function get_item_articul_event($row)
	{
	global $lang_cat, $prepared_arr, $_USER, $_RIGHTS;
	
	if (!$_USER->is_logged($_RIGHTS['EDIT'])) return;

	$o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndPop');
	
	$o_form = new itForm2();
	$o_form->add_title(str_replace ('[VALUE]', get_field_by_lang($row['title_xml']), get_const('QUERY_ARTICUL_ITEM')));

	if (isset($prepared_arr['items']))
		{
		$options = [
			'array' 	=> $prepared_arr['items'],
			'titles'	=> 'title',
			'values'	=> 'value',
			'name'		=> 'category_id',
			'compact'	=> true,
			'type'		=> 'select',
			'label'		=> 'ITEM_CATEGORY',
			'value'		=> $row['category_id'],
			];			
//		$o_form->add_itSelector('select', $options, '', $row['category_id'], get_const('ITEM_CATEGORY'));
		$o_form->add_itSelector($options);		
		}

	$o_form->add_input([
		'name'		=> 'serie',
		'value'		=> $row['serie'],
		'label'		=> get_const('ITEM_SERIE'),
		'compact'	=> true,
		]);

	$o_form->add_input([
		'name'		=> 'version',
		'value'		=> $row['version'],
		'label'		=> get_const('ITEM_VERSION'),
		'compact'	=> true,
		]);
	
	$o_form->add_data([
		'table_name'	=> DEFAULT_ITEM_TABLE,
		'rec_id'		=> $row['id'],
		'op'			=> 'item_articul',
		]);

	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(get_const('BUTTON_ARTICUL'), 'modal', ['class'=>'admin', 'form' => $o_modal->form_id()], 'blue' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return $result;	
	}

	
//..............................................................................
// возвращает код кнопки перехода на список товаров для выбора
//..............................................................................
function get_rewind_event()
	{
	return;
//	if (isset($_SESSION['rewind'])) unset($_SESSION['rewind']);
// echo print_rr($_SESSION['rewind']);

	$index = isset($_SESSION['rewind']) ? count($_SESSION['rewind']) : 0;

	$rewind_link = (isset($_SESSION['rewind'][$index-1]))
		? "/{$_SESSION['rewind'][$index-1]['controller']}/".
			( ($_SESSION['rewind'][$index-1]['view']!=$_SESSION['rewind'][$index-1]['controller']) ? "{$_SESSION['rewind'][$index-1]['view']}" : NULL).
			( intval($_SESSION['rewind'][$index-1]['rec_id']) ? "?anchor={$_REQUEST['rec_id']}" : NULL)
		: "/";

	$new_rewind = [
		'controller'	=> $_REQUEST['controller'],
		'view'		=> $_REQUEST['view'],
		'rec_id'	=> $_REQUEST['rec_id'],
		];

	if (!isset($_SESSION['rewind'][$index-1]) OR ($_SESSION['rewind'][$index-1]!=$new_rewind))
		{
		$_SESSION['rewind'][] = $new_rewind;
		}


	$_SESSION['rewind'] = array_values($_SESSION['rewind']);			
	$result = "<div id='rewind' class='easy_slow' onclick=\"document.location.href='{$rewind_link}';\"><span>".
//		count($index).
		"</span></div>";
	return $result;
	}


//..............................................................................
// ссылка на покупку товара в магазине
//..............................................................................
function get_buy_item_event($row)
	{
	$o_button = new itButton2([
		'type'	=> 'a',
		'href'	=> ($row['is_shop'] OR is_for_sale($row)) ? '/'.CMS_LANG."/buy/{$row['id']}/" : '/'.CMS_LANG."/contacts/{$row['id']}/",
		'title'	=> ($row['is_shop'] OR is_for_sale($row)) ? str_replace('[VALUE]', $row['price'], get_const('BUTTON_BUY')) : get_const('BUTTON_CONTACT'),
		'color' => ($row['is_shop'] OR is_for_sale($row)) ? 'brown big' : 'green big',
		]);

	$result = $o_button->code();
	unset($o_button);
	return $result;
	}

//..............................................................................
// ссылка на заказ товара
//..............................................................................
function get_order_item_event($row)
	{
	if ($row['is_shop'] OR is_for_sale($row)) return;
	$o_button = new itButton2([
		'type'	=> 'a',
		'href'	=> '/'.CMS_LANG."/order/{$row['id']}/",
		'title'	=> get_const('BUTTON_ORDER'),
		'color' => 'yellow big',
		]);

	$result = $o_button->code();
	unset($o_button);
	return $result;
	}

//..............................................................................
// событие калькулятора
//..............................................................................
function get_item_calc_event($row, $table_name=DEFAULT_ITEM_TABLE, $form_name=DEFAULT_FORM_TABLE)
	{
	global $lang_cat, $_USER, $_RIGHTS;
	
	if ($row['is_shop'] OR is_for_sale($row)) return;
	
	$o_modal = new itModal();
	$o_modal->set_size('large');
	$o_modal->set_animation('fadeAndPop');
	$o_modal->add_title(str_replace ('[VALUE]', get_item_articul($row), get_const('QUERY_CALC_ITEM')));
	
	$o_form = new itForm2([
		'rec_id'	=> FORM2_CALC,
		]);
	
	$o_form->hiddens_xml = NULL;	
	$o_form->add_data([
//		'table_name'	=> $table_name,
//		'rec_id'	=> $row['id'],
		'form_name'	=> $form_name,	
		'form_id'	=> FORM2_CALC,
		'price'		=> $row['price'],
		'articul'	=> get_item_articul($row),
		'op'		=> 'item_calc',
		]);

//	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
//	$o_form->add_button(get_const('BUTTON_CLEAR'), 'a', ['ajax'=>"f2_reset('".$o_form->form_id()."');"], 'green' );

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

//..............................................................................
// изменение стоимости товара
//..............................................................................
function get_price_item_event($row)
	{
	global $_USER;
	
	if (!$_USER->is_logged()) return;

	$o_modal = new itModal();
	$o_modal->set_size('small');
	$o_modal->set_animation('fadeAndUp');

	$o_form = new itForm2();
	$o_form->add_title(mstr_replace([
		'[VALUE]'	=> get_item_articul($row),
		], get_const('ITEM_PRICE_QUERY')));

	$o_form->add_input([
		'label'		=> 'стоимость, $',
		'name'		=> 'value',
		'value'		=> $row['price'],
		'compact'	=> true,
		]);

	$o_form->add_data([
		'table_name' 	=> 'items',
		'rec_id'	=> $row['rec_id'],
		'op'		=> 'item_price'
		]);
	$o_form->add_itButton(get_const('BUTTON_OK'), 'submit', ['form' => $o_form->form_id()], 'blue' );	
	$o_form->add_itButton(get_const('BUTTON_CANCEL'), 'close', ['form' => $o_modal->form_id()], 'green' );	
	$o_form->compile();

	$o_modal->add_field($o_form->code());
 	$o_modal->compile();

	$o_button = new itButton(doubleval($row['price'])." $", 'modal', ['class' => '', 'form' => $o_modal->form_id()], 'yellow big' );
	$result = $o_button->code().$o_modal->code();
	unset($o_button, $o_form, $o_modal);
	return 
		TAB."<div class='item_price'>".
		TAB."<div>".
		$result.
		TAB."</div>".
		TAB."</div>";
	}
?>
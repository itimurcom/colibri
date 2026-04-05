<?php
//..............................................................................
// модифицирует фильтр цвета для пользователей
//..............................................................................
function set_color_filter($color=NULL)
	{
	if ($color=='NULL')
		{
		unset($_SESSION['filter']['colors']);
		}
	else if (isset($_SESSION['filter']['colors'][$color]))
		{
		unset($_SESSION['filter']['colors'][$color]);
		} else $_SESSION['filter']['colors'][$color] = 1;
		
	if (empty($_SESSION['filter']['colors']))
		{
		unset($_SESSION['filter']['colors']);
		}
	}
	
//..............................................................................
// возвращает поле выборки по цвету
//..............................................................................
function filter_color_selector()
	{
	global $item_colors, $color_seq;
	
	$color_seq = 0;
	
	if (is_array($item_colors))
		{
		$result = TAB."<div class='col_selector boxed' data-sequence='35'>".
//			TAB."<span class='col_sel_title'>".get_const('SELECT_COLOR')." : </span>".
			TAB."<div class='color_set'>";
		
		foreach ($item_colors as $key=>$row)
			{
			$result .= get_color_selector_row($row);
			}
			
		$color_seq++;
		$result .= TAB."<span class='col_sel no_shadow rounded' data-id='{$color_seq}' title='".get_const('CLEAR_COLOR')."' onclick=\"select_filter('NULL','');\"></span>".
			TAB."</div>".
			TAB."</div>";
		}
	return $result;
	}
	
//..............................................................................
// возвращает одно поле селектора фильтра цветов
//..............................................................................
function get_color_selector_row($row)
	{
	global $color_seq;
	$color_seq++;
	$selected = (isset($_SESSION['filter']['colors'][$row['value']])) ? " selected" : "";
	return ($row['show']==1) ? TAB."<span class='col_sel{$selected} rounded' data-id='{$color_seq}' title='".get_const($row['title'])."' style='background:{$row['color']};' onclick=\"select_filter('{$row['value']}', '');\"></span>" : "";
	}

//..............................................................................
// возвращает поле выборки по цвету для товара
//..............................................................................
function filter_item_color_selector($item_rec)
	{
	global $_USER, $item_colors;
	
	$result = NULL;

	if ($_USER->is_logged())
		{
		if (is_array($item_colors))
			{
			$result =
				TAB."<div class='col_selector boxed'>".
//				TAB."<span class='col_sel_title'>".get_const('SELECT_COLOR')." : </span>".
				TAB."<div class='color_set'>";
		
			foreach ($item_colors as $key=>$row)
				{
				$selected = (is_array($item_rec['filter_xml']) and in_array($row['value'], $item_rec['filter_xml'])) ? " selected" : "";

				$data = itEditor::event_data([
					'id'	=> $item_rec['id'],
					'value'	=> $row['value'],
					]);
				$result .= TAB."<span class='col_sel rounded {$selected}' rel='{$data}' style='background:{$row['color']};' title='".get_const($row['title'])."' onclick=\"select_item_color(this);\"></span>";
				}

			$data = itEditor::event_data([
				'id'	=> $item_rec['id'],
				'value'	=> NULL,
				]);

			$result .=
				TAB."<span class='col_sel no_shadow rounded' rel='{$data}' title='".get_const('CLEAR_COLOR')."' onclick=\"select_item_color(this);\" clear></span>".
				TAB."</div>".
				TAB."</div>";
			}
		} else	{
			// это посетитель - просто покажем выбранные цвета
			if (is_array($item_rec['filter_xml']))
				{
				$result =
					TAB."<div class='col_selector item'>".
					TAB."<div class='subselect'>".get_const('COLOR_SELECTOR')." :</div>".
					TAB."<div class='color_set'>";					
				foreach ($item_rec['filter_xml'] as $key=>$row)
					{
					if (isset($item_colors[$row]))
						{
						$onclick = !isset($_SESSION['filter']['colors'][$row]) ? "onclick=\"select_filter('{$row}', '/".CMS_LANG."/items/'" : "onclick=\"window.location.href='/".CMS_LANG."/items/'\"";
						$result .= "<span class='col_sel rounded small' title='".get_const($item_colors[$row]['title'])."' style='background:{$item_colors[$row]['color']};' {$onclick});\"></span>";
						}
					}
				$result .= 
					TAB."</div>".
					TAB."</div>";
				}
			}
	return ($result)
		? 	TAB."<div class='colors boxed'>".
			$result.
			TAB."</div>"
		: NULL;
	}
	
//..............................................................................
// возвращает поле цветовой гаммы товара
//..............................................................................
function item_color_selector($item_rec)
	{
	global $_USER, $item_colors;
		
	$result = '';
	
	if ($_USER->is_logged())
		{
		// это администратор, делаем кнопки для выбора цвета в базе
		if (is_array($item_colors))
			{
			$result =
				TAB."<div class='col_selector' id='col_selector'>".
				TAB."<div class='color_set'>";
			foreach ($item_colors as $key=>$row)
				{
				$options = simple_encrypt(serialize([
					'id'	=> $item_rec['id'],
					'value'	=> $row['value'],
					]));
					
				$selected = (is_array($item_rec['filter_xml']) and in_array($row['value'], $item_rec['filter_xml'])) ? " selected" : "";
				$result .= TAB."<span class='col_sel{$selected}' rel='{$options}' style='background:{$row['color']};' title='".get_const($row['title'])."' onclick=\"select_item_color(this);\"></span>";
				}
			$options = simple_encrypt(serialize([
				'id'	=> $item_rec['id'],
				'value'	=> NULL,
				]));

			$result .= 
				TAB."<span class='col_sel no_shadow' rel='{$options}' title='".get_const('CLEAR_COLOR')."' onclick=\"select_item_color(this);\" clear></span>".
				TAB."</div>".
				TAB."</div>";
			}
		} else	{
			// это посетитель - просто покажем выбранные цвета
			if (is_array($item_rec['filter_xml']))
				{
				$result =
					TAB."<div class='col_selector' id='col_selector'>".
					TAB."<div class='color_set'>";					
				foreach ($item_rec['filter_xml'] as $key=>$row)
					{
					if (isset($item_colors[$row]))
						{
						$onclick = !isset($_SESSION['filter']['colors'][$row]) ? "onclick=\"select_filter('{$row}', '/".CMS_LANG."/items/'" : "onclick=\"window.location.href='/".CMS_LANG."/items/'\"";
						$result .= "<span class='col_sel' title='".get_const($item_colors[$row]['title'])."' style='background:{$item_colors[$row]['color']};' {$onclick});\"></span>";
						}
					}
				$result .= 
					TAB."</div>".
					TAB."</div>";
				}
			}
	return !empty($result) ? "<span class='col_sel_title'>".get_const('COLOR_SELECTOR')." : </span>".$result : "";
	}

//..............................................................................
// устанавливает флаг цвета для товара
//..............................................................................
function set_item_color($item_id=NULL, $value=NULL)
	{	
	$item_rec = itMySQL::_get_rec_from_db('items', $item_id);
	
	$item_rec['filter_xml'] = !is_array($item_rec['filter_xml']) ? [] : $item_rec['filter_xml'];

	if(is_array($item_rec['filter_xml']) and (($key = array_search($value, $item_rec['filter_xml'])) !== false))
		{
		unset($item_rec['filter_xml'][$key]);
		} else	{
			if (!is_null($value))
				{
				$item_rec['filter_xml'][] = $value;
				} else $item_rec['filter_xml']=[];
			}
		
	itMySQL::_update_value_db('items', $item_id, $item_rec['filter_xml'], 'filter_xml');		
	}
	

//..............................................................................
// селектор сортровки товаров
//..............................................................................
function get_items_sort_selector()
	{
// 	$o_form = new itForm2();
	
	$prepared_arr['itemsort'] = 
		[
			0 => [
				'title'		=> get_const('NEWFIRST_TITLE'),
				'value'		=> 'new',
			],

			1 => [
				'title'		=> get_const('PRICE_UP_TITLE'),
				'value'		=> 'price_up',
			],

			2 => [
				'title'		=> get_const('PRICE_DOWN_TITLE'),
				'value'		=> 'price_down',
			],
		];
		
// 	$o_form->add_field(get_items_price_selector());
	
	if (isset($prepared_arr['itemsort']))
		{
		$options = [
			'array' 	=> $prepared_arr['itemsort'],
			'titles'    => 'title',
			'values'	=> 'value',
			'name'		=> 'sort',
			'compact'	=> true,
// 			'form'		=> $o_form->form_id(),
// 			'type'		=> 'select',
			'value'		=> ready_val($_SESSION['filter']['sort']),
			'no_label'	=> true,
			'ajax'		=> 'sort_price(this);',
			'element_id'	=> 'sortsel',
			];			
		
		$o_selector = new itSelector($options);
		$result = $o_selector->code();
// 		$o_form->add_itSelector($options);
		}

/*
	$o_form->add_data([
		'table_name'	=> DEFAULT_ITEM_TABLE,
		'op'		=> 'itemsort',
		]));	
	$o_form->compile();
	$result = $o_form->code();
	unset($o_form);
*/
	
	return 
		$result.
// 		"<script>$('#sortsel).on('hidden.bs.dropdown',function(){alert(1);});</script>".
		"";
	}


//..............................................................................
// селектор стоимости ленты товаров
//..............................................................................
function get_items_price_selector($table_name='items', $db_prefix=DB_PREFIX)
	{
	$step = 10;
	
	$sql = "SELECT MAX(`price`) as max, MIN(`price`) AS min FROM `{$db_prefix}{$table_name}` WHERE`status`='PUBLISHED'";
	$request=itMySQL::_request($sql);
	
	$db_min = $request[0]['min'] - ($request[0]['min'] % $step);
	$db_max = $step*ceil($request[0]['max']/$step);	

	$min = ready_val($_SESSION['filter']['min']) ? $_SESSION['filter']['min'] : $db_min;
	$max = ready_val($_SESSION['filter']['max']) ? $_SESSION['filter']['max'] : $db_max;

	return minify_js("<script>
    $(function() {
      $('#slider-range').slider({
        range: true,
        min: {$db_min},
        max: {$db_max},
        values: [{$min}, {$max}],
        slide: function(event, ui) {
          $('#amount_min').html('$' + ui.values[0]);
          $('#amount_max').html('$' + ui.values[1]);
        }
      });
	  $('#amount_min').html('$' + $('#slider-range').slider('values', 0));
      $('#amount_max').html('$' + $('#slider-range').slider('values', 1));
      $('#amount_min').change(function() {
        $('#slider-range').slider('values', 0, $(this).val());
      });
      $('#amount_max').change(function() {
        $('#slider-range').slider('values', 1, $(this).val());
      })
    });
    </script>
    <div class='range_div'>
		<div class='range-min boxed' id='amount_min'></div>
			<div class='range boxed'><div id='slider-range'></div></div>
		<div class='range-max boxed' id='amount_max'></div>
		<div class='range-ok rounded boxed' onclick=\"sort_price('#sortsel');\"><img id='range-btn' width='16' height='16' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAACnklEQVRYhb2XW4hOURTHfyYlSkJ5UCJKxOQ6uYcZKbkUkb5cp+TBg1t4I4oX1zKJl8nw4JskLyJybxp3GaTcohQPIrfiyYyW1vk6tr332fvMN/Ov77rXWf//3mvvtdbuAlAoFOhk9AZWFIvFw11z8HYHRgADgb5AG/AJeA08A1oDfJwAKoEoAfOAVUA10Mdh8wG4AhwF7jhsjgDzgevyoyKAeLbO7hyw2EMu6A+sBG4DzcBIC/k6/d4aIuA4cAkYEiDUxGTgKbBN/9+fIi/BFYKewE1gTA5iE3uA5cBQ26BNQIXGpxzkCUelb9BEAzAuw+k9jfFLoBswDJihn9Hq0lgk59NjfwHYCLxyjE8D9gETQgWYm3Cnx3YDMNdDLmgCJgKPA7j/noL0CizzxGo9UBfgFJ3EqAC7HmkBkhqXOgzPRJDL8m8JtJW9UwrBFKDGYbi7A8gFv+UtWYHRyZIYuBoYz9PAkghyzBAMdhg1BThaq+n6FvDLsrFNSPHqp0e5JKCXw/hdhjMp541AfbKkschTjtOQ2Xxvj4Nkub45xge0x3mMgDeO8ekdLSAJQQvw03ISqvWEtOT0XwWsAb5q5mvTLuo5cCgtoFk7mQUWJzu0RuRBnaMuNCRfkhB80d1sw0JgUw7yA56idMwUICgCTxwPHAQ2R5Dv9difB+7aBAh2eZzKjC5b+rw0JKU/BLZ6bP5J7WYeOKvxWe14eJb2eQ90Fi+0qEi7NRUY7iEWbDe7ZVsiqtW+v8rjaLy+YnDKVthcebtGl7JcaNR+4z+4BPzQGdaXQYDsK+fdL6tySRKZqbGOhVxOxma0eUE3oxva7c7Ruv/RY/seOAlM0ovJoyznMdXwInBNZyXhGaRlXNLrZ+AtcF9zSa7S3PkA/gBqTHhAYn0fWgAAAABJRU5ErkJggg=='></div>
	</div>");
	}

//..............................................................................
// селектор выборки ленты товаров
//..............................................................................
function feed_selector()
	{
	return 
		TAB."<div class='feed_selector_div boxed'>".
			TAB."<div class='feed_color boxed'>".
				filter_color_selector().
			TAB."</div>".
			TAB."<div class='feed_price boxed'>".
				get_items_price_selector().
			TAB."</div>".
			TAB."<div class='feed_sort boxed'>".
				get_items_sort_selector().
			TAB."</div>".
		TAB."</div>";
		
	}
?>
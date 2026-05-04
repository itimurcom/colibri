<?php
function item_filter_row_value($row, $key, $default=NULL)
	{
	return (is_array($row) AND array_key_exists($key, $row)) ? $row[$key] : $default;
	}

function item_filter_value($key, $default=NULL)
	{
	return (isset($_SESSION['filter']) AND is_array($_SESSION['filter']) AND array_key_exists($key, $_SESSION['filter'])) ? $_SESSION['filter'][$key] : $default;
	}

function item_filter_user_logged()
	{
	global $_USER;
	return (is_object($_USER) AND method_exists($_USER, 'is_logged')) ? $_USER->is_logged() : false;
	}

function set_color_filter($color=NULL)
	{
	if (!isset($_SESSION['filter']) OR !is_array($_SESSION['filter']))
		{
		$_SESSION['filter'] = [];
		}
	if (!isset($_SESSION['filter']['colors']) OR !is_array($_SESSION['filter']['colors']))
		{
		$_SESSION['filter']['colors'] = [];
		}

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

function filter_color_selector()
	{
	global $item_colors, $color_seq;
	$color_seq = 0;

	if (!is_array($item_colors)) return NULL;

	$result = TAB."<div class='col_selector boxed' data-sequence='35'>".
		TAB."<div class='color_set'>";
	foreach ($item_colors as $row)
		{
		$result .= get_color_selector_row($row);
		}

	$color_seq++;
	return $result.
		TAB."<span class='col_sel no_shadow rounded' data-id='{$color_seq}' title='".get_const('CLEAR_COLOR')."' onclick=\"select_filter('NULL','');\"></span>".
		TAB."</div>".
		TAB."</div>";
	}

function get_color_selector_row($row)
	{
	global $color_seq;
	$row = is_array($row) ? $row : [];
	$color_seq++;
	if (item_filter_row_value($row, 'show')!=1) return NULL;

	$value = item_filter_row_value($row, 'value');
	$selected_colors = item_filter_value('colors', []);
	$selected = (is_array($selected_colors) AND isset($selected_colors[$value])) ? ' selected' : '';
	return TAB."<span class='col_sel{$selected} rounded' data-id='{$color_seq}' title='".get_const(item_filter_row_value($row, 'title'))."' style='background:".item_filter_row_value($row, 'color').";' onclick=\"select_filter('{$value}', '');\"></span>";
	}

function filter_item_color_event_data($item_rec, $value, $legacy=false)
	{
	$data = [
		'id'	=> item_filter_row_value($item_rec, 'id'),
		'value'	=> $value,
		];
	return $legacy ? simple_encrypt(serialize($data)) : itEditor::event_data($data);
	}

function filter_item_color_selected_class($item_rec, $value)
	{
	$filter_xml = item_filter_row_value($item_rec, 'filter_xml', []);
	return (is_array($filter_xml) AND in_array($value, $filter_xml)) ? ' selected' : '';
	}

function filter_item_admin_color_span($item_rec, $row, $class='col_sel rounded', $legacy=false)
	{
	$row = is_array($row) ? $row : [];
	$selected = filter_item_color_selected_class($item_rec, item_filter_row_value($row, 'value'));
	$data = filter_item_color_event_data($item_rec, item_filter_row_value($row, 'value'), $legacy);
	return TAB."<span class='{$class}{$selected}' rel='{$data}' style='background:".item_filter_row_value($row, 'color').";' title='".get_const(item_filter_row_value($row, 'title'))."' onclick=\"select_item_color(this);\"></span>";
	}

function filter_item_admin_clear_span($item_rec, $class='col_sel no_shadow rounded', $legacy=false)
	{
	$data = filter_item_color_event_data($item_rec, NULL, $legacy);
	return TAB."<span class='{$class}' rel='{$data}' title='".get_const('CLEAR_COLOR')."' onclick=\"select_item_color(this);\" clear></span>";
	}

function filter_item_public_color_span($row, $class='col_sel rounded small')
	{
	global $item_colors;
	if (!isset($item_colors[$row])) return NULL;
	$color_row = is_array($item_colors[$row]) ? $item_colors[$row] : [];

	$selected_colors = item_filter_value('colors', []);
	$onclick = (!is_array($selected_colors) OR !isset($selected_colors[$row]))
		? "onclick=\"select_filter('{$row}', '/".CMS_LANG."/items/'"
		: "onclick=\"window.location.href='/".CMS_LANG."/items/'\"";
	return "<span class='{$class}' title='".get_const(item_filter_row_value($color_row, 'title'))."' style='background:".item_filter_row_value($color_row, 'color').";' {$onclick});\"></span>";
	}

function filter_item_color_set($item_rec, $class='col_selector boxed', $legacy=false)
	{
	global $item_colors;
	if (!is_array($item_colors) OR !is_array($item_rec)) return NULL;

	$result = TAB."<div class='{$class}'>".
		TAB."<div class='color_set'>";
	foreach ($item_colors as $row)
		{
		$result .= filter_item_admin_color_span($item_rec, $row, $legacy ? 'col_sel' : 'col_sel rounded', $legacy);
		}

	return $result.
		filter_item_admin_clear_span($item_rec, $legacy ? 'col_sel no_shadow' : 'col_sel no_shadow rounded', $legacy).
		TAB."</div>".
		TAB."</div>";
	}

function filter_item_public_color_set($item_rec, $class='col_selector item', $with_title=true, $span_class='col_sel rounded small')
	{
	$filter_xml = item_filter_row_value($item_rec, 'filter_xml', []);
	if (!is_array($filter_xml)) return NULL;

	$result = TAB."<div class='{$class}'>".
		($with_title ? TAB."<div class='subselect'>".get_const('COLOR_SELECTOR')." :</div>" : NULL).
		TAB."<div class='color_set'>";
	foreach ($filter_xml as $row)
		{
		$result .= filter_item_public_color_span($row, $span_class);
		}

	return $result.
		TAB."</div>".
		TAB."</div>";
	}

function filter_item_color_selector($item_rec)
	{
	$result = item_filter_user_logged()
		? filter_item_color_set($item_rec)
		: filter_item_public_color_set($item_rec);

	return ($result)
		? TAB."<div class='colors boxed'>".
			$result.
			TAB."</div>"
		: NULL;
	}

function item_color_selector($item_rec)
	{
	$result = item_filter_user_logged()
		? filter_item_color_set($item_rec, "col_selector' id='col_selector", true)
		: filter_item_public_color_set($item_rec, "col_selector' id='col_selector", false, 'col_sel');

	return !empty($result) ? "<span class='col_sel_title'>".get_const('COLOR_SELECTOR')." : </span>".$result : '';
	}

function set_item_color($item_id=NULL, $value=NULL)
	{
	$item_rec = itMySQL::_get_rec_from_db('items', $item_id);
	if (!is_array($item_rec))
		{
		return false;
		}
	$item_rec['filter_xml'] = !is_array(item_filter_row_value($item_rec, 'filter_xml')) ? [] : $item_rec['filter_xml'];

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
	return true;
	}

function get_items_sort_options()
	{
	return [
		[
		'title'	=> get_const('NEWFIRST_TITLE'),
		'value'	=> 'new',
		],
		[
		'title'	=> get_const('PRICE_UP_TITLE'),
		'value'	=> 'price_up',
		],
		[
		'title'	=> get_const('PRICE_DOWN_TITLE'),
		'value'	=> 'price_down',
		],
		];
	}

function get_items_sort_selector()
	{
	$o_selector = new itSelector([
		'array' 	=> get_items_sort_options(),
		'titles'	=> 'title',
		'values'	=> 'value',
		'name'		=> 'sort',
		'compact'	=> true,
		'value'		=> ready_val(item_filter_value('sort')),
		'no_label'	=> true,
		'ajax'		=> 'sort_price(this);',
		'element_id'	=> 'sortsel',
		]);
	$result = $o_selector->code();
	unset($o_selector);
	return $result;
	}

function get_items_price_bounds($table_name='items', $db_prefix=DB_PREFIX, $step=10)
	{
	$sql = "SELECT MAX(`price`) as max, MIN(`price`) AS min FROM `{$db_prefix}{$table_name}` WHERE`status`='PUBLISHED'";
	$request=itMySQL::_request($sql);
	$row = (is_array($request) AND isset($request[0]) AND is_array($request[0])) ? $request[0] : [];
	$min = item_filter_row_value($row, 'min', 0);
	$max = item_filter_row_value($row, 'max', 0);
	return [
		'min'	=> $min - ($min % $step),
		'max'	=> $step*ceil($max/$step),
		];
	}

function get_items_price_selector($table_name='items', $db_prefix=DB_PREFIX)
	{
	$bounds = get_items_price_bounds($table_name, $db_prefix);
	$min = ready_val(item_filter_value('min')) ? item_filter_value('min') : $bounds['min'];
	$max = ready_val(item_filter_value('max')) ? item_filter_value('max') : $bounds['max'];

	return minify_js("<script>
    $(function() {
      $('#slider-range').slider({
        range: true,
        min: {$bounds['min']},
        max: {$bounds['max']},
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

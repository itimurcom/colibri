<?php

define ('MAX_LASTSEEN', 18);
define ('LASTSEEN_ARR','LASTSEEN');

function lastseen_normalize_arr($value=NULL)
	{
	if (!is_array($value)) return [];
	$result = [];
	foreach ($value as $item_id)
		{
		$item_id = (int)$item_id;
		if ($item_id > 0) $result[] = $item_id;
		}
	return array_slice(array_values(array_unique($result)), 0, MAX_LASTSEEN);
	}

function lastseen_request_item_id()
	{
	$item_id = isset($_REQUEST['rec_id']) ? ready_val($_REQUEST['rec_id']) : NULL;
	$item_id = (int)$item_id;
	return ($item_id > 0) ? $item_id : NULL;
	}

function get_lastseen_arr()
	{
	$key = SESSION_PREFIX.LASTSEEN_ARR;
	return isset($_SESSION[$key]) ? lastseen_normalize_arr(ready_val($_SESSION[$key])) : [];
	}

function set_lastseen_arr($last_arr = NULL)
	{
	$_SESSION[SESSION_PREFIX.LASTSEEN_ARR] = lastseen_normalize_arr($last_arr);
	}

function push_lastseen_item($item_id=NULL)
	{
	if ($item_id == NULL)
		{
		$item_id = lastseen_request_item_id();
		}

	$item_id = (int)$item_id;
	$last_arr = get_lastseen_arr();

	foreach ($last_arr as $key=>$row)
		{
		if ((int)$row === $item_id)
			{
			unset($last_arr[$key]);
			}
		}

	$new_arr = [];
	if ($item_id > 0)
		$new_arr[] = $item_id;

	if (count($last_arr))
		foreach ($last_arr as $key=>$row)
			{
			if (count($new_arr)>(MAX_LASTSEEN-1)) break;
			$new_arr[] = (int)$row;
			}

	set_lastseen_arr($new_arr);
	}

function get_lastseen_block($item_id=NULL, $table_name=DEFAULT_ITEM_TABLE, $db_prefix=DB_PREFIX)
	{
	push_lastseen_item($item_id);
	$result = NULL;

	if (is_array($last_arr = get_lastseen_arr()) AND count($last_arr))
		{
		$items = [];
		foreach ($last_arr as $key=>$id)
			{
			$id = (int)$id;
			if ($id > 0) $items[] = $id;
			}
		if (!count($items)) return NULL;
		$items_str = "('".implode("','", array_values(array_unique($items)))."')";
		$items_arr = itMySQL::_request("SELECT * FROM {$db_prefix}{$table_name} WHERE `id` IN {$items_str}");

		$items_res = [];
		if (is_array($items_arr))
		foreach ($items_arr as $key=>$row)
			{
			if (is_array($row) AND isset($row['id']))
				$items_res[(int)$row['id']] = $row;
			}

		foreach ($last_arr as $key=>$row)
			{
			$row = (int)$row;
			if (isset($items_res[$row]))
				$result .= get_items_feed_row($items_res[$row]);
			}
		}

	return ($result)
		? get_colibri_block(BLOCK_LASTSEEN).
			TAB."<div class='row lastseen'>".
			$result.
			TAB."</div>"
		: NULL;
	}
function get_lastseen_event()
	{
	$o_button = new itButton(get_const('BUTTON_CLEAR'), 'a', ['class' => 'lastseen', 'ajax' => 'clearlastseen();'], 'white', 'clearlastseen');
	$result = $o_button->code();
	unset($o_button);
	return
		TAB."<div class='ls_btn'>".
		$result.
		TAB."</div>";
	}
?>

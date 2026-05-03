<?php

define ('MAX_LASTSEEN', 18);
define ('LASTSEEN_ARR','LASTSEEN');

function get_lastseen_arr()
	{
	$key = SESSION_PREFIX.LASTSEEN_ARR;
	return isset($_SESSION[$key]) ? ready_val($_SESSION[$key]) : NULL;
	}

function set_lastseen_arr($last_arr = NULL)
	{
	$_SESSION[SESSION_PREFIX.LASTSEEN_ARR] = $last_arr;
	}

function push_lastseen_item($item_id=NULL)
	{
	if ($item_id == NULL)
		{
		$item_id = isset($_REQUEST['rec_id']) ? ready_val($_REQUEST['rec_id']) : NULL;
		}

if ($last_arr = get_lastseen_arr())
		foreach ($last_arr as $key=>$row)
		{
		if ($row==$item_id)
			{
			unset($last_arr[$key]);
			}
		}

	$new_arr = [];
	if (!is_null($item_id))
		$new_arr[] = $item_id;

	if (is_array($last_arr) AND count($last_arr))
		foreach ($last_arr as $key=>$row)
			{
			if (count($new_arr)>(MAX_LASTSEEN-1)) break;
			$new_arr[] = $row;
			}

	set_lastseen_arr($new_arr);
	}

function get_lastseen_block($item_id=NULL, $table_name=DEFAULT_ITEM_TABLE, $db_prefix=DB_PREFIX)
	{
	push_lastseen_item($item_id);
	$result = NULL;

	if (is_array($last_arr = get_lastseen_arr()))
		{
		$items = [];
		foreach ($last_arr as $key=>$id)
			{
			$items[] = $id;
			}
		if (!count($items)) return NULL;
		$items_str = "('".implode("','",$items)."')";
		$items_arr = itMySQL::_request("SELECT * FROM {$db_prefix}{$table_name} WHERE `id` IN {$items_str}");

		$items_res = [];
		if (!is_null($items_arr))
		foreach ($items_arr as $key=>$row)
			{
			$items_res[$row['id']] = $row;
			}

		foreach ($last_arr as $key=>$row)
			if (isset($items_res[$row]))
				$result .= get_items_feed_row($items_res[$row]);
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

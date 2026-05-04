<?php
function wishlist_normalize_list($value=NULL)
	{
	if (!is_array($value)) return [];
	$result = [];
	foreach ($value as $item_id)
		{
		$item_id = (int)$item_id;
		if ($item_id > 0) $result[] = $item_id;
		}
	return array_values(array_unique($result));
	}

function wishlist_user_id()
	{
	global $_USER;
	return (isset($_USER) AND is_object($_USER) AND isset($_USER->data) AND is_array($_USER->data) AND isset($_USER->data['id']))
		? (int)$_USER->data['id']
		: NULL;
	}

function wishlist_user_logged()
	{
	global $_USER;
	return (isset($_USER) AND is_object($_USER) AND method_exists($_USER, 'is_logged') AND $_USER->is_logged('ANY'));
	}

function wishlist_rec_id($data=NULL)
	{
	if (!is_array($data) OR !isset($data['rec_id'])) return NULL;
	$rec_id = (int)$data['rec_id'];
	return ($rec_id > 0) ? $rec_id : NULL;
	}

function wishlist_session_list()
	{
	return isset($_SESSION['wishlist'])
		? wishlist_normalize_list($_SESSION['wishlist'])
		: [];
	}

function transfer_wishlist()
	{
	if(wishlist_user_logged())
		{
		$user_id = wishlist_user_id();
		$session_wishlist = wishlist_session_list();
		if (!is_null($user_id) AND count($session_wishlist))
			{
			$wish_list = wishlist_normalize_list(load_wishlist($user_id));
			$wish_list = wishlist_normalize_list(array_merge($wish_list, $session_wishlist));
			store_wishlist($user_id, $wish_list);
			$_SESSION['wishlist'] = NULL;
			}
		}
	}

function load_wishlist($id_of_user=NULL)
	{
	$id_of_user = (int)$id_of_user;
	if ($id_of_user <= 0) return [];

	$wish_list = itMySQL::_request("SELECT * FROM `colibri_wishlist` WHERE `user_id`='{$id_of_user}'");
	return (is_array($wish_list) AND isset($wish_list[0]) AND is_array($wish_list[0]) AND isset($wish_list[0]['list_xml']))
		? wishlist_normalize_list($wish_list[0]['list_xml'])
		: [];
	}

function store_wishlist($id_of_user=NULL, $wish_arr=NULL)
	{
	$id_of_user = (int)$id_of_user;
	if ($id_of_user <= 0) return false;

	$wish_arr = is_null($wish_arr) ? NULL : wishlist_normalize_list($wish_arr);
	$wish_list = itMySQL::_request("SELECT * FROM `colibri_wishlist` WHERE `user_id`='{$id_of_user}'");
	if (is_array($wish_list) AND isset($wish_list[0]) AND is_array($wish_list[0]) AND isset($wish_list[0]['id']))
		{
		itMySQL::_update_value_db('wishlist', $wish_list[0]['id'], $wish_arr, 'list_xml');
		} else	{
			itMySQL::_insert_rec('wishlist', [
				'user_id'	=> $id_of_user,
				'list_xml'	=> $wish_arr,
				]);
			}
	return true;
	}

function wishlist($forced=false)
	{
	$controller = isset($_REQUEST['controller']) ? ready_val($_REQUEST['controller']) : NULL;
	$allowed = str_getcsv((string)get_const('ALOW_WISHLIST'));
	if (!$forced AND !in_array($controller, $allowed)) return;

	return !is_null($result = wishlist_body($counter))
		?	TAB."<div class='widget bordered rounded'>".
			TAB."<div class='title'>".get_const('ITEM_WHISHLIST').BR.
				"<small>( ".str_replace('[VALUE]', $counter, get_const('PROPOSITONS_TITLE'))." )</small>".
				TAB."</div>".
			get_wishlist_event().
			$result.
			TAB."</div>"
		: NULL;
	}

function wishlist_body(&$counter)
	{
	global $prepared_arr;
	$result = NULL;
	$counter = 0;

	$wish_arr = wishlist_user_logged()
		? (isset($prepared_arr['wishlist']) ? wishlist_normalize_list($prepared_arr['wishlist']) : [])
		: wishlist_session_list();

	if (is_array($wish_arr))
		{
		$counter = count($wish_arr);
		foreach($wish_arr as $item_id)
			{
			if ($item_row = itMySQL::_get_rec_from_db('items', $item_id))
				{
 				$result .= get_items_feed_row($item_row);
				}
			}
		}
	return $result;
	}

function wish_btn($row)
	{
	if (!is_array($row) OR !isset($row['id'])) return NULL;
	$item_id = (int)$row['id'];
	if ($item_id <= 0) return NULL;

	$data = itEditor::event_data([
		'rec_id'	=> $item_id,
		'op'		=> 'wish',
		]);

	$on = is_wish($item_id) ? " on" : NULL;

	return TAB."<div class='wish shadow{$on}' rel='{$item_id}' data='{$data}' onclick='add_whishlist(this);'></div>";
	}
function wish($data)
	{
	global $prepared_arr;
	$rec_id = wishlist_rec_id($data);
	if (is_null($rec_id)) return;

	if (wishlist_user_logged())
		{
		$prepared_arr['wishlist'] = isset($prepared_arr['wishlist']) ? wishlist_normalize_list($prepared_arr['wishlist']) : [];
		if (($key = array_search($rec_id, $prepared_arr['wishlist'])) !== false) {
			unset($prepared_arr['wishlist'][$key]);
			} else	{
				$prepared_arr['wishlist'][] = $rec_id;
				}
		$prepared_arr['wishlist'] = wishlist_normalize_list($prepared_arr['wishlist']);
		store_wishlist(wishlist_user_id(),  $prepared_arr['wishlist']);
		} else {
			$_SESSION['wishlist'] = wishlist_session_list();
			if (($key = array_search($rec_id, $_SESSION['wishlist'])) !== false) {
				unset($_SESSION['wishlist'][$key]);
				} else {
					$_SESSION['wishlist'][] = $rec_id;
					}
			$_SESSION['wishlist'] = wishlist_normalize_list($_SESSION['wishlist']);
			}
	}

function is_wish($item_id=NULL)
	{
	global $prepared_arr;
	$item_id = (int)$item_id;
	if ($item_id <= 0) return false;

	if (wishlist_user_logged())
		{
		$wishlist = isset($prepared_arr['wishlist']) ? wishlist_normalize_list($prepared_arr['wishlist']) : [];
		return in_array($item_id, $wishlist);
		} else {
			return (array_search($item_id, wishlist_session_list())!==false);
			}

	}

function wishlist_x($data)
	{
	$rec_id = wishlist_rec_id($data);
	if (is_null($rec_id) OR wishlist_user_logged()) return;

	$_SESSION['wishlist'] = wishlist_session_list();
	if (($key = array_search($rec_id, $_SESSION['wishlist'])) !== false) {
		unset($_SESSION['wishlist'][$key]);
		}
	$_SESSION['wishlist'] = wishlist_normalize_list($_SESSION['wishlist']);
	}

function get_wishlist_event()
	{
	$o_button = new itButton(get_const('BUTTON_CLEAR'), 'a', ['class' => 'lastseen', 'ajax' => 'clearwhish();'], 'white', 'clearwish');
	$result = $o_button->code();
	unset($o_button);
	return
		TAB."<div class='ls_btn'>".
		$result.
		TAB."</div>";
	}

function clear_wishlist()
	{
	$_SESSION['wishlist'] = NULL;
	if (wishlist_user_logged())
		{
		store_wishlist(wishlist_user_id(), NULL);
		}
	}
?>

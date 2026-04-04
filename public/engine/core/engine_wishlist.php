<?
//..............................................................................
// возвращает список желаний
//..............................................................................
function transfer_wishlist()
	{
	global $_USER;
	
	if($_USER->is_logged('ANY'))
		{
		if (isset($_SESSION['wishlist']) AND is_array($_SESSION['wishlist']))
			{
			// трансфер
			if (is_array($wish_list =  load_wishlist($_USER->data['id'])))
				{
				$wish_list = array_unique(array_values(array_merge($wish_list, $_SESSION['wishlist'])));
				} else 	{
					$wish_list = $_SESSION['wishlist'];
					}
			store_wishlist($_USER->data['id'], $wish_list);
			$_SESSION['wishlist'] = NULL;
			}
		}
	}

//..............................................................................
// функция загружает из базы список желаний для пользователя
//..............................................................................
function load_wishlist($id_of_user=NULL)
	{
	return (is_array($wish_list = itMySQL::_request("SELECT * FROM `colibri_wishlist` WHERE `user_id`='{$id_of_user}'")))
		? $wish_list[0]['list_xml']
		: [];
	}

//..............................................................................
// функция сохраняет в базу список желаний для пользователя
//..............................................................................
function store_wishlist($id_of_user=NULL, $wish_arr=NULL)
	{
	if (is_array($wish_list = itMySQL::_request("SELECT * FROM `colibri_wishlist` WHERE `user_id`='{$id_of_user}'")))
		{
		itMySQL::_update_value_db('wishlist', $wish_list[0]['id'], $wish_arr, 'list_xml');
		} else	{
			itMySQL::_insert_rec('wishlist', [
				'user_id'	=> $id_of_user,
				'list_xml'	=> $wish_arr,
				]);
			}
	}

//..............................................................................
// возвращает список желаний
//..............................................................................
function wishlist($forced=false)
	{
	if (!$forced AND !in_array($_REQUEST['controller'], str_getcsv(ALOW_WISHLIST))) return;

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

//..............................................................................
// тело списка желаний
//..............................................................................
function wishlist_body(&$counter)
	{
	global $_USER, $prepared_arr;
	$result = NULL;
	$counter = 0;
	
	$wish_arr = $_USER->is_logged('ANY')
		? $prepared_arr['wishlist']
		: ( (isset($_SESSION['wishlist']) AND is_array($_SESSION['wishlist']))
			? $_SESSION['wishlist']
			: NULL
		);
		
	if (is_array($wish_arr))
		{
		$id = 0;
		$counter = count($wish_arr);
		foreach($wish_arr as $item_id)
			{
			if ($item_row = itMySQL::_get_rec_from_db('items', $item_id))
				{
				$id++;
 				$result .= get_items_feed_row($item_row);
				}
			}
		}
	return $result;	
	}

//..............................................................................
// возвращает списко желаний
//..............................................................................
function wish_btn($row)
	{
	$data = itEditor::event_data([
		'rec_id'	=> $row['id'],
		'op'		=> 'wish',
		]);
	
	$on = is_wish($row['id']) ? " on" : NULL;
	
	return TAB."<div class='wish shadow{$on}' rel='{$row['id']}' data='{$data}' onclick='add_whishlist(this);'></div>";
	}
//..............................................................................
// добавляет товар в список желаний
//..............................................................................
function wish($data)
	{
	global $_USER, $prepared_arr;
	if ($_USER->is_logged('ANY'))
		{
		if (is_array($prepared_arr['wishlist']))
			{
			if (($key = array_search($data['rec_id'],  $prepared_arr['wishlist'])) !== false) {
				unset($prepared_arr['wishlist'][$key]);
				} else	{
					$prepared_arr['wishlist'][] = $data['rec_id'];
					}
			} else	{
				 $prepared_arr['wishlist'] = [$data['rec_id']];
				}
			
		store_wishlist($_USER->data['id'],  $prepared_arr['wishlist']);
		} else {
			if (($key = array_search($data['rec_id'], $_SESSION['wishlist'])) !== false) {
				unset($_SESSION['wishlist'][$key]);
				} else {
					$_SESSION['wishlist'][] = $data['rec_id'];
					}
			$_SESSION['wishlist'] = array_unique(array_values($_SESSION['wishlist']));
			}
	}

//..............................................................................
// проверка или в списке желаний
//..............................................................................
function is_wish($item_id=NULL)
	{
	global $_USER, $prepared_arr;
	
	if ($_USER->is_logged('ANY'))
		{
		return(is_array($prepared_arr['wishlist']) AND in_array($item_id, $prepared_arr['wishlist']));
		} else {
			return (array_search($item_id, $_SESSION['wishlist'])!==false);
			}
		
	}

//..............................................................................
// удаляет товар из списока желаний
//..............................................................................
function wishlist_x($data)
	{
	global $_USER;
	if ($_USER->is_logged('ANY'))
		{
		} else {
			if (!isset($_SESSION['wishlist'])) return;
			if (($key = array_search($data['rec_id'], $_SESSION['wishlist'])) !== false) {
				unset($_SESSION['wishlist'][$key]);
				}
			$_SESSION['wishlist'] = array_unique(array_values($_SESSION['wishlist']));
			}
	}

//..............................................................................
// событие очищает список желаний
//..............................................................................
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
	
//..............................................................................
// функция очищает список желаний
//..............................................................................
function clear_wishlist()
	{
	global $_USER;
	$_SESSION['wishlist'] = NULL;
	if ($_USER->is_logged('ANY'))
		{
		store_wishlist($_USER->data['id'], NULL);
		}
	}
?>
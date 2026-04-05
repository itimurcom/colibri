<?
$_CONTENT['admin'] = get_admin_button_set();
global $cat_cat, $cat_more;
$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();

switch($_REQUEST['view'])
	{
	case 	'items' 	: {
		if (isset($_REQUEST['url']))
			{
			$_REQUEST['rec_id'] = get_item_id_by_url($_REQUEST['url']);
			} else	if (isset($_REQUEST['rec_id']) AND $_REQUEST['rec_id']!=='')
				{
				$item_rec = itMySQL::_get_rec_from_db('items', $_REQUEST['rec_id']);
				check_item_url($item_rec);
				cms_redirect_page("/".CMS_LANG."/items/{$item_rec['url_xml'][CMS_LANG]}");
				}				

		if (intval($_REQUEST['rec_id']))
			{
			// это товар - отображаем его страницу
			$_CONTENT['content'] =
				get_item_panel($_REQUEST['rec_id']).
				get_lastseen_block().
				get_rewind_event().
				"";
			return;
			}
		$plug_og['title'] 	= get_const('NODE_ALL');
		break;
		}
	case 	'gymnastics' 	:
	case	'skating' 	:
// 	case	'aerobics'	:
	case	'unitards'	:	
	case	'acrobatics'	:
// 	case	'swimming'	:
	case	'watersports'	:	
	case	'uniform'	:
	case	'accessories'	:
		{
		$category_id = $cat_cat[$_REQUEST['view']]['id'];
//		$_CONTENT['content'] = $_REQUEST['view'].$category_id;
		
		// opengraph
		$plug_og['title'] 	= get_const( $cat_cat[$_REQUEST['view']]['title']);
		break;
		}
		
	case	'new'		: {
		// opengraph
		$plug_og['title'] 	= get_const( $cat_more[$_REQUEST['view']]['title']);
		break;
		}
	case	'econom'	: {
		// opengraph
		$plug_og['title'] 	= get_const( $cat_more[$_REQUEST['view']]['title']);
		break;
		}

	default	: {
		
		}
	}

$plug_og['subtitle'] 	= get_const('CMS_NAME');
$_CONTENT['content'] 	=
	get_colibri_banner_event(BLOCK_SHOP).
	get_items_feed().
	get_lastseen_block();
?>
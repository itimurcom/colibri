<?php 
$_CONTENT['admin'] = get_admin_button_set();
global $cat_cat, $cat_more;

function items_controller_view()
	{
	return ready_val($_REQUEST['view']);
	}

function items_controller_rec_id()
	{
	return ready_val($_REQUEST['rec_id']);
	}

function items_controller_redirect_to_item_url($rec_id)
	{
	$item_rec = itMySQL::_get_rec_from_db('items', $rec_id);
	if (is_array($item_rec))
		{
		check_item_url($item_rec);
		cms_redirect_page("/".CMS_LANG."/items/{$item_rec['url_xml'][CMS_LANG]}");
		}
	}

function items_controller_item_content($rec_id)
	{
	return
		get_item_panel($rec_id).
		get_lastseen_block().
		get_rewind_event().
		"";
	}

function items_controller_feed_content()
	{
	return
		get_colibri_banner_event(BLOCK_SHOP).
		get_items_feed().
		get_lastseen_block();
	}

$_CONTENT['widgets'] = get_widgets_set();
$_CONTENT['widgets-cell'] = get_widgets_set();

$view = items_controller_view();
switch($view)
	{
	case 'items' :
		{
		if (isset($_REQUEST['url']))
			{
			$_REQUEST['rec_id'] = get_item_id_by_url($_REQUEST['url']);
			}
		elseif (items_controller_rec_id()!=='')
			{
			items_controller_redirect_to_item_url(items_controller_rec_id());
			}

		if (intval(items_controller_rec_id()))
			{
			$_CONTENT['content'] = items_controller_item_content(items_controller_rec_id());
			return;
			}

		$plug_og['title'] = get_const('NODE_ALL');
		break;
		}
	case 'gymnastics' :
	case 'skating' :
	case 'unitards' :
	case 'acrobatics' :
	case 'watersports' :
	case 'uniform' :
	case 'accessories' :
		{
		$plug_og['title'] = isset($cat_cat[$view]) ? get_const($cat_cat[$view]['title']) : get_const('NODE_ALL');
		break;
		}
	case 'new' :
	case 'econom' :
		{
		$plug_og['title'] = isset($cat_more[$view]) ? get_const($cat_more[$view]['title']) : get_const('NODE_ALL');
		break;
		}
	default :
		{
		$plug_og['title'] = get_const('NODE_ALL');
		}
	}

$plug_og['subtitle'] = get_const('CMS_NAME');
$_CONTENT['content'] = items_controller_feed_content();
?>

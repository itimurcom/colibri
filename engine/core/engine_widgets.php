<?php
//..............................................................................
// набор виджетов
//..............................................................................
function get_widgets_set()
	{
	return
		TAB."<div class='widgets boxed'>".
		get_contacts_widet().
		get_seacrh_widet().
/*
		get_new_widget().
		get_shop_widget().
*/
		TAB."</div>";
	}	

//..............................................................................
// виджет поиска
//..............................................................................
function get_seacrh_widet()
	{
	global $_USER;

	$o_as = new itAutoSelect2([
		'element_id'	=> 'mainas'.rand_id(),
		'placeholder'	=> get_const('START_ENTER'),
		'class'		=> 'main-as',
		'op'		=> 'as_item',
		]);
	
	$o_block = new itBlock(BLOCK_SEARCHMAIN, [
		'no_data'	=> true,
		'no_lang'	=> true,
		]);
	$o_block->compile();
	
	$admin_code =
		($_USER->is_logged() AND !is_null($o_block->editor)) ?
			TAB."<div class='ed_devider'>".
//			(function_exists('get_content_remove_event') ? get_content_remove_event($o_block->editor->data) : "").
			(function_exists('get_content_title_event') ? get_content_title_event($o_block->editor->data) : "").			
			TAB."</div>"
			: NULL; 					

	$title = !is_null($o_block->editor) ? get_field_by_lang($o_block->editor->data['title_xml'], CMS_LANG, '') : NULL;
	
	$result = 
		TAB."<div class='widget o3 rounded bordered boxed search'>".
			TAB."<h2 class='title boxed'>{$title}</h2>".
//			TAB.get_block_content_event($o_block->data).			
			$admin_code.
			TAB."<div class='body boxed'>".
			$o_as->code().
			$o_block->editor->container().
//			(!is_null($o_block->editor) ? $o_block->editor->code() : NULL).
			TAB."</div>".
		TAB."</div>";
	unset($o_block, $o_as);
	return $result;
	}


	
//..............................................................................
// виджет контактов
//..............................................................................
function get_contacts_widet()
	{
	global $_CONTENTs, $_USER, $_SETTINGS;
	$o_block = new itBlock(BLOCK_CONTACTS, [
		'no_data'	=> true,
		'no_lang'	=> true,
		]);
	$o_block->compile();
	
	$admin_code =
		($_USER->is_logged() AND !is_null($o_block->editor)) ?
			TAB."<div class='ed_devider'>".
//			(function_exists('get_content_remove_event') ? get_content_remove_event($o_block->editor->data) : "").
			(function_exists('get_content_title_event') ? get_content_title_event($o_block->editor->data) : "").			
			TAB."</div>"
			: NULL; 					

	$title = !is_null($o_block->editor) ? get_field_by_lang($o_block->editor->data['title_xml'], CMS_LANG, '') : NULL;

	$result = 
		TAB."<div class='widget contacts o1 bordered rounded boxed'>".
			TAB."<h2 class='title boxed'>{$title}</h2>".
//			TAB.get_block_content_event($o_block->data).			
			$admin_code.
			TAB."<div class='body boxed'>".
			(!is_null($o_block->editor) ? $o_block->editor->code() : NULL).
			TAB."</div>".
		TAB."</div>";
	unset($o_block);
	return $result;
	}


//..............................................................................
// возвращает код случайного предложения из каталога новинок
//..............................................................................
function get_new_widget($table_name=DEFAULT_ITEM_TABLE, $db_prefix=DB_PREFIX)
	{
	$query = "SELECT * FROM {$db_prefix}{$table_name} WHERE `is_new` = 1 AND `status`<>'DELETED' ORDER BY RAND() LIMIT 1";
	
	if ( is_array($request = itMySQL::_request($query)))
		{
		return	TAB."<div class='widget o2 glass rounded boxed'>".
				TAB."<h2 class='title boxed'>".get_const('WIDGET_NEW')."</h2>".
				TAB."<div class='new_flash'><img src='/themes/".CMS_THEME."/images/sub_new_middle.png'></div>".
				TAB."<div class='body'>".
					get_items_feed_row($request[0], true).
					TAB."</div>".
				TAB."</div>";
		}
	}

//..............................................................................
// возвращает код случайного предложения из каталога магазина
//..............................................................................
function get_shop_widget($table_name=DEFAULT_ITEM_TABLE, $db_prefix=DB_PREFIX)
	{
	$query = "SELECT * FROM {$db_prefix}{$table_name} WHERE `is_shop` = 1  AND `status`<>'DELETED' ORDER BY RAND() LIMIT 1";
	
	if ( is_array($request = itMySQL::_request($query)))
		{
		return	TAB."<div class='widget o2 glass rounded boxed'>".
				TAB."<h2 class='title boxed'>".get_const('WIDGET_SHOP')."</h2>".
				TAB."<div class='new_flash'><img src='/themes/".CMS_THEME."/images/sub_shop_middle.png'></div>".
				TAB."<div class='body'>".
					get_items_feed_row($request[0], true).
					TAB."</div>".
				TAB."</div>";
		}
	}
?>
<?php
function items_row_value($row, $key, $default=NULL)
	{
	return (is_array($row) AND array_key_exists($key, $row)) ? $row[$key] : $default;
	}

function items_user_logged()
	{
	global $_USER;
	return (is_object($_USER) AND method_exists($_USER, 'is_logged')) ? $_USER->is_logged() : false;
	}

function items_filter_value($key, $default=NULL)
	{
	return (isset($_SESSION['filter']) AND is_array($_SESSION['filter']) AND array_key_exists($key, $_SESSION['filter'])) ? $_SESSION['filter'][$key] : $default;
	}

function get_item_images_container($row, $column='images', $type='slider', $f_title='item_gallery_title', $f_caption='item_gallery_caption')
	{
	if (!is_array($row) OR empty($row['id']))
		{
		return NULL;
		}

	$o_images = new itImages([
		'table_name'	=> 'items',
		'rec_id'	=> $row['id'],
		'column'	=> $column,
		'type'		=> $type,
		'data'		=> $row,
		'f_title'	=> $f_title,
		'f_caption'	=> $f_caption,
		'edclass'	=> 'dashed_green boxed',
		]);

	$result = $o_images->container();
	unset($o_images);
	return $result;
	}

function get_items_slider($row)
	{
	return get_item_images_container($row);
	}

function get_item_compiled_block($block_name, $options=[])
	{
	$o_block = new itBlock($block_name, array_merge([
		'no_data'	=> true,
		'no_title'	=> true,
		'no_related'	=> true,
		'no_lang'	=> true,
		'edclass'	=> 'dashed_blue',
	], is_array($options) ? $options : []));
	$o_block->compile();
	return $o_block;
	}

function get_item_runtime_url($row, $lang=NULL)
	{
	$row = is_array($row) ? $row : [];
	$lang = is_null($lang) ? CMS_LANG : $lang;
	$url_xml = items_row_value($row, 'url_xml', []);
	$id = items_row_value($row, 'id');
	return '/'.$lang.'/items/'.
		(isset($url_xml[$lang])
			? $url_xml[$lang]
			: "{$id}/");
	}

function get_item_markup_image($row)
	{
	$images = items_row_value($row, 'images', []);
	return CMS_CURRENT_BASE_URL.'/img/itemshot_'.
		(isset($images[0]) ? $images[0] : basename(DEFAULT_OG_IMAGE));
	}

function get_item_panel_admin_code($row)
	{
	return items_user_logged()
		? TAB."<div class='ed_devider admin'>".
			get_item_x_event($row).
			get_item_articul_event($row).
			get_item_shop_event($row).
			get_item_econom_event($row).
			get_item_replicant_event($row).
			get_item_new_event($row).
			get_item_title_event($row).
			TAB."</div>"
		: NULL;
	}

function get_item_panel_action_code($row)
	{
	return
		TAB."<div class='siterow boxed'>".
			TAB."<div class='item_control boxed'>".
				TAB."<div class='buttons boxed'>".
				get_item_calc_event($row).
				get_buy_item_event($row).
				get_order_item_event($row).
				TAB."</div>".
				filter_item_color_selector($row).
			TAB."</div>".
		TAB."</div>";
	}

function get_item_panel_block_code($row, $block)
	{
	if (!is_array($row) OR !is_object($block) OR !is_object($block->editor))
		{
		return NULL;
		}

	return (!(items_row_value($row, 'is_shop') OR is_for_sale($row)) AND !is_null($block->editor)) ? $block->editor->code() : NULL;
	}

function get_item_panel_color_gallery($row, $item_id)
	{
	if (!is_array($row)) return NULL;
	if (!(is_array(items_row_value($row, 'color_xml')) OR items_user_logged()))
		{
		return NULL;
		}

	return
		get_colibri_block(BLOCK_ITEMCOLOR).
		TAB."<div class='siterow color_xml boxed'>".
			get_item_images_container($row, 'color_xml', 'gallery', 'color_gallery_title', 'color_gallery_caption').
		TAB."</div>";
	}

function set_item_page_metadata($row, $category_row, $item_articul, $item_title, $description)
	{
	global $plug_og, $_MARKUP;
	$row = is_array($row) ? $row : [];
	$category_row = is_array($category_row) ? $category_row : [];
	$images = items_row_value($row, 'images', []);
	$category_title = get_const(items_row_value($category_row, 'title'));

	$plug_og['title'] = $item_articul;
	$plug_og['subtitle'] = $category_title.(!empty($item_title) ? " | {$item_title}" : NULL);
	$plug_og['image'] = isset($images[0]) ? $images[0] : DEFAULT_OG_IMAGE;
	$plug_og['description'] = $description;

	$_MARKUP = [
		'name'		=> $plug_og['subtitle'],
		'description'	=> $plug_og['description'],
		'image'		=> [0 => get_item_markup_image($row)],
		'price'		=> items_row_value($row, 'price', 0),
		'currency'	=> 'USD',
		'sku'		=> $item_articul,
		'url'		=> CMS_CURRENT_BASE_URL.get_item_runtime_url($row),
		];
	}

function get_items_feed_regexp_filter($session_key, $column)
	{
	$filter = items_filter_value($session_key, []);
	if (!is_array($filter) OR !count($filter))
		{
		return NULL;
		}

	$regexp = '"'.implode('\\"|\\"', array_keys($filter)).'"';
	return !empty($regexp) ? " AND `{$column}` REGEXP '{$regexp}'" : NULL;
	}

function get_items_feed_order()
	{
	$sort = items_filter_value('sort');
	$sort = ready_val($sort);
	switch ($sort)
		{
		case 'price_up' :
			return "`price` ASC";
		case 'price_down' :
			return "`price` DESC";
		default :
			return "`id` DESC";
		}
	}

function get_items_feed_options($sql, $order, $fewer=false)
	{
	return [
		'sql'		=> $sql,
		'name'		=> 'items',
		'start'		=> true,
		'async'		=> !$fewer,
		'appear'	=> false,
		'fewer'		=> $fewer,
		'order'		=> $fewer ? "`id` ASC" : $order,
		'weight'	=> false,
		'nodiv'		=> true,
		'need_total'	=> false,
		];
	}

function get_item_panel($item_id=NULL)
	{
	global $cat_cat, $cat_relations;

	$o_edit = new itEditor([
		'table_name'	=> 'items',
		'rec_id'	=> $item_id,
		'edclass'	=> 'dashed',
		]);

	if (!is_array($o_edit->data) OR empty($o_edit->data) OR items_row_value($o_edit->data, 'status')!='PUBLISHED') cms_redirect_page("/");

	$o_ext = new itEditor([
		'table_name'	=> 'items',
		'rec_id'	=> $item_id,
		'column'	=> 'extended_xml',
		'edclass'	=> 'dashed boxed',
		]);

	$row = $o_edit->data;
	$category_id = items_row_value($row, 'category_id');
	$category_key = isset($cat_relations[$category_id]) ? $cat_relations[$category_id] : NULL;
	$category_row = (!is_null($category_key) AND isset($cat_cat[$category_key]) AND is_array($cat_cat[$category_key])) ? $cat_cat[$category_key] : [];
	$item_articul = get_item_articul($row);
	$item_title = get_field_by_lang(items_row_value($row, 'title_xml'), CMS_LANG, '');
	$ext_code = $o_ext->container();
	$o_b_all = get_item_compiled_block(BLOCK_ITEMALL);
	$o_b_discl = get_item_compiled_block(BLOCK_ITEMDISCL, ['class' => 'bg_red']);
	$category_title = get_const(items_row_value($category_row, 'title'));

	$result =
		get_item_serie_feed($row).
		TAB."<h1 class='tit' id='item-tit-{$item_id}'>".
			"<small>".$category_title."</small><br>".
			"<span class='green'>{$item_articul}</span>".
			($item_title ? "&nbsp;&laquo;{$item_title}&raquo;" : NULL).
		TAB."</h1>".
		get_item_panel_admin_code($row).
		TAB."<div class='siterow boxed'>".
			TAB."<div class='left50 boxed glass'>".
				TAB."<div class='portofolio'>".
					get_items_slider($row).
					TAB."<div class='flags'>".
						wish_btn($row).
						get_item_flags($row).
					TAB."</div>".
				TAB."</div>".
				get_price_item_event($row).
				$o_edit->container().
				get_item_panel_block_code($row, $o_b_discl).
			TAB."</div>".
			TAB."<div class='right50 boxed padded glass bordered'>".
				get_item_panel_action_code($row).
				TAB."<div class='siterow boxed'>".get_item_panel_block_code($row, $o_b_all).TAB."</div>".
				TAB."<div class='siterow boxed'>".$ext_code.TAB."</div>".
			TAB."</div>".
		TAB."</div>".
		minify_js("<script>
			$(document).ready(function(){
				$('#item-tit-{$item_id}').ScrollTo({duration:800, callback:function(){}});
				});
			</script>").
		get_item_panel_color_gallery($row, $item_id);

	set_item_page_metadata($row, $category_row, $item_articul, $item_title, $o_edit->txt().$o_ext->txt());
	unset($o_edit, $o_ext, $o_b_all, $o_b_discl);
	return $result;
	}

function get_item_gallery_context($data, $key)
	{
	if (!is_array($data) OR empty($data['table_name']) OR empty($data['rec_id']))
		{
		return NULL;
		}
	if (!($row = itMySQL::_get_rec_from_db($data['table_name'], $data['rec_id'])))
		{
		return NULL;
		}
	$row = is_array($row) ? $row : [];

	return [
		'row'		=> $row,
		'index'		=> $key+1,
		'articul'	=> get_item_articul($row),
		'row_category'	=> get_category_by_id(items_row_value($row, 'category_id')),
		'data_category'	=> get_category_by_id(items_row_value($data, 'category_id')),
		'title'		=> stripQuotas(get_field_by_lang(items_row_value($row, 'title_xml'), CMS_LANG, '')),
		];
	}

function color_gallery_title($data, $key)
	{
	if ($ctx = get_item_gallery_context($data, $key))
		{
		return CMS_NAME." | {$ctx['row_category']} | {$ctx['articul']} | ".get_const('VARIANT')." #{$ctx['index']}";
		}
	}

function color_gallery_caption($data, $key)
	{
	if ($ctx = get_item_gallery_context($data, $key))
		{
		return "<center><span class='blue'>{$ctx['articul']}</span> <span class='green'>".get_const('VARIANT')." #{$ctx['index']}</span></center>";
		}
	}

function item_gallery_title($data, $key)
	{
	if ($ctx = get_item_gallery_context($data, $key))
		{
		return "{$ctx['data_category']} | ".CMS_NAME." | ( {$ctx['articul']} )";
		}
	}

function item_gallery_caption($data, $key)
	{
	if ($ctx = get_item_gallery_context($data, $key))
		{
		return "<center><b>{$ctx['title']} <span class='green'>{$ctx['data_category']}</span> <span class='blue'>{$ctx['articul']}</span></b></center>";
		}
	}

function get_item_letter($item_rec=NULL)
	{
	global $cat_cat, $cat_relations;
	if (!is_array($item_rec)) return NULL;
	$letter = NULL;

	if (items_row_value($item_rec, 'is_shop'))
		{
		$letter = 'S_';
		}

	if (items_row_value($item_rec, 'is_replicant'))
		{
		return $letter.'R';
		}

	$category_id = items_row_value($item_rec, 'category_id');
	$category_key = isset($cat_relations[$category_id]) ? $cat_relations[$category_id] : NULL;
	return $letter.((!is_null($category_key) AND isset($cat_cat[$category_key]['letter'])) ? $cat_cat[$category_key]['letter'] : '');
	}

function get_category_by_id($category_id)
	{
	global $cat_cat, $cat_relations;
	return isset($cat_relations[$category_id])
		? get_const(items_row_value($cat_cat[$cat_relations[$category_id]], 'title'))
		: NULL;
	}

function get_item_articul($item_rec=NULL)
	{
	if (!is_array($item_rec)) return NULL;
	return get_item_letter($item_rec)."_".stripslashes((string)items_row_value($item_rec, 'serie'))."_".stripslashes((string)items_row_value($item_rec, 'version'));
	}

function get_items_feed_view_sql($view)
	{
	$map = [
		'new'	=> ' AND `is_new`',
		'econom'=> ' AND `is_econom`',
		'shop'	=> ' AND `is_shop`',
		];
	return isset($map[$view]) ? $map[$view] : NULL;
	}

function get_items_feed_price_sql()
	{
	$min = items_filter_value('min');
	$max = items_filter_value('max');
	return
		(ready_val($min) ? " AND `price`>={$min}" : NULL).
		(ready_val($max) ? " AND `price`<={$max}" : NULL);
	}

function get_items_feed_sql($view)
	{
	global $cat_cat;

	$sql = "SELECT * FROM `colibri_items` WHERE `status`='PUBLISHED' ".
		(isset($cat_cat[$view]) ? "AND `category_id`='{$cat_cat[$view]['id']}' " : NULL).
		(!items_user_logged() ? "AND `images` NOT IN ('', '[]') " : NULL);

	if ($view != 'shop' AND !in_array(isset($cat_cat[$view]['id']) ? $cat_cat[$view]['id'] : NULL, str_getcsv(get_const('CATEGORY_FOR_SALE'))))
		{
		$sql .= " AND `is_shop`<>'1'";
		}

	return $sql.
		get_items_feed_view_sql($view).
		get_items_feed_regexp_filter('colors', 'filter_xml').
		get_items_feed_regexp_filter('tags', 'tags_xml').
		get_items_feed_price_sql().
		(isset($_REQUEST['anchor']) ? " AND `id`<='{$_REQUEST['anchor']}'" : '');
	}

function get_items_compiled_feed($sql, $order_str, $fewer=false)
	{
	$o_feed = new itFeed(get_items_feed_options($sql, $order_str, $fewer));
	$o_feed->compile();
	return $o_feed;
	}

function get_items_fewer_feed_code($sql, $order_str)
	{
	$anchor = isset($_REQUEST['anchor']) ? $_REQUEST['anchor'] : NULL;
	if (!ready_val($anchor)) return ['counter' => NULL, 'code' => NULL];

	$o_fewer = get_items_compiled_feed($sql, $order_str, true);
	$counter = $o_fewer->count_all();
	$result = ['counter' => $counter, 'code' => $counter ? $o_fewer->code() : NULL];
	unset($o_fewer);
	return $result;
	}

function get_items_feed_title_code($title, $counter)
	{
	return
		TAB."<h1 class='tit'>".
		get_const($title)."<br>".
		"<small>( ".str_replace('[VALUE]', $counter, get_const('PROPOSITONS_TITLE')).
		(isset($_SESSION['filter']['colors']) ? "<font size='2' color='green'> ✔ ".get_const('WITH_COLORS')."</font>" : NULL).
		" )</small>".
		TAB."</h1>";
	}

function get_items_feed()
	{
	global $plug_og;

	$feed_view = isset($_REQUEST['view']) ? $_REQUEST['view'] : NULL;
	$feed_view = ready_val($feed_view);
	$sql = get_items_feed_sql($feed_view);
	$order_str = get_items_feed_order();
	$o_feed = get_items_compiled_feed($sql, $order_str);
	$fewer = get_items_fewer_feed_code($sql, $order_str);

	$result =
		get_items_feed_title_code(items_row_value($plug_og, 'title'), $o_feed->count_all() + $fewer['counter']).
		TAB."<div class='ed_devider'></div>".
		feed_selector().
		TAB."<div class='siterow boxed'>".
		$fewer['code'].
		$o_feed->code().
		TAB."</div>";
	unset($o_feed);
	return $result;
	}

function get_item_avatar_image($item_rec=NULL, $class='SERIE_AVATAR')
	{
	$images = items_row_value($item_rec, 'images', []);
	$image = (is_array($images) and count($images))
		? $images[0]
		: NULL;
	return get_thumbnail($image, $class);
	}

function get_items_feed_row_animation($row)
	{
	$row_id = items_row_value($row, 'id');
	$active_id = isset($_REQUEST['rec_id']) ? $_REQUEST['rec_id'] : NULL;
	$active_id = ready_val($active_id);
	$anchor_id = isset($_REQUEST['anchor']) ? $_REQUEST['anchor'] : NULL;
	$anchor_id = ready_val($anchor_id);
	return (($row_id == $active_id) OR ($row_id == $anchor_id))
		? [' animatedParent animateOnce', ' animated tada']
		: [NULL, NULL];
	}

function get_items_feed_row_price_code($row)
	{
	return (items_row_value($row, 'is_shop') OR is_for_sale($row))
		? TAB."<div class='price sale rounded boxed'>".get_const('FOR_SALE')."</div>"
		: TAB."<div class='price rounded shadow-white boxed'>".items_row_value($row, 'price', 0)."<small>&nbsp;$</small></div>";
	}

function get_items_feed_row($row, $full=false)
	{
	if (!is_array($row))
		{
		return TAB."<div class='item_div boxed'>&nbsp;</div>";
		}

	$img_src = get_item_avatar_image($row);
	$title = get_item_articul($row)."<br/>".get_field_by_lang(items_row_value($row, 'title_xml'), CMS_LANG, '');
	$link = get_item_runtime_url($row);
	list($animated_parent, $animated) = get_items_feed_row_animation($row);
	$full_str = $full ? ' full' : NULL;
	$row_id = items_row_value($row, 'id');

	return
		TAB."<div class='item_div boxed{$animated_parent}{$full_str}' id='item-{$row_id}'>".
        	wish_btn($row).
		(items_user_logged() ? TAB."<div class='id'># {$row_id}</div>" : NULL).
		(!$full ? get_item_flags($row) : NULL).
		get_items_feed_row_price_code($row).
		TAB."<a href='{$link}' target='_blank'>".
		TAB."<img class='avatar boxed{$animated}' src='{$img_src}'>".
		TAB."<div class='title boxed'>{$title}</div>".
		TAB."</a>".
		TAB."</div>";
	}

function is_for_sale($row=NULL)
	{
	return is_array($row) && isset($row['category_id'])
		? in_array($row['category_id'], str_getcsv(get_const('CATEGORY_FOR_SALE')))
		: false;
	}

function get_item_flags($row=NULL)
	{
	$row = is_array($row) ? $row : [];
	return
		(items_row_value($row, 'is_new') ? TAB."<div class='new_flash shadow-white'><img src='/themes/".CMS_THEME."/images/sub_new_gray.png'></div>" : NULL).
		(items_row_value($row, 'is_econom') ? TAB."<div class='econom_flash shadow-white'><img src='/themes/".CMS_THEME."/images/sub_econom_gray.png'></div>" : NULL);
	}

function get_item_serie_feed_sql($row)
	{
	if (!is_array($row)) return NULL;
	return "SELECT * FROM `colibri_items` WHERE `serie` = '".items_row_value($row, 'serie')."' ".
 		"AND `category_id` = '".items_row_value($row, 'category_id')."' ".
 		"AND `is_replicant` = '".items_row_value($row, 'is_replicant')."' ".
 		"AND `status` = 'PUBLISHED'";
	}

function get_item_serie_feed($row=NULL)
	{
	$sql = get_item_serie_feed_sql($row);
	if (is_null($sql)) return NULL;

	$o_feed = new itFeed([
		'sql'	=> $sql,
		'name'	=> 'items',
		'start'		=> true,
		'async'		=> false,
		'order'		=> "CONVERT(`version`, decimal)",
		'weight'	=> false,
		'need_total'	=> false,
		]);
	$o_feed->compile();

	$result = ($o_feed->count_all()>1) ? $o_feed->code() : NULL;
	unset($o_feed);

	return !is_null($result)
		?
			TAB."<div class='tit'>".
			get_const('ITEM_SERIE_TITLE')." : <span class='green'>".get_item_letter($row)."_".items_row_value($row, 'serie')."</span>".
			"</div>".
			$result
		: NULL;
	}
?>

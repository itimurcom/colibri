<?php
function get_items_slider($row)
	{
	$o_slider = new itImages([
		'table_name'	=> 'items',
		'rec_id'	=> $row['id'],
		'column'	=> 'images',
		'type'		=> 'slider',
		'data'		=> $row,
		'f_title'	=> 'item_gallery_title',
		'f_caption'	=> 'item_gallery_caption',
		'edclass'	=> 'dashed_green boxed',
		]);

	$result = $o_slider->container();
	unset($o_slider);
	return $result;
	}

function get_item_compiled_block($block_name, $options=[])
	{
	$o_block = new itBlock($block_name, array_merge([
		'no_data'	=> true,
		'no_title'	=> true,
		'no_related'	=> true,
		'no_lang'	=> true,
		'edclass'	=> 'dashed_blue',
	], $options));
	$o_block->compile();
	return $o_block;
	}

function get_item_runtime_url($row, $lang=NULL)
	{
	$lang = is_null($lang) ? CMS_LANG : $lang;
	return '/'.$lang.'/items/'.
		(isset($row['url_xml'][$lang])
			? $row['url_xml'][$lang]
			: "{$row['id']}/");
	}

function get_item_markup_image($row)
	{
	return CMS_CURRENT_BASE_URL.'/img/itemshot_'.
		(isset($row['images'][0]) ? $row['images'][0] : basename(DEFAULT_OG_IMAGE));
	}

function get_items_feed_regexp_filter($session_key, $column)
	{
	if (!isset($_SESSION['filter'][$session_key]) || !count($_SESSION['filter'][$session_key]))
		{
		return NULL;
		}

	$regexp = '"'.implode('\"|\"', array_keys($_SESSION['filter'][$session_key])).'"';
	return !empty($regexp) ? " AND `{$column}` REGEXP '{$regexp}'" : NULL;
	}

function get_items_feed_order()
	{
	switch (ready_val($_SESSION['filter']['sort']))
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
	global $_USER, $cat_cat, $cat_relations, $plug_og, $_MARKUP;

	$o_edit = new itEditor([
		'table_name'	=> 'items',
		'rec_id'	=> $item_id,
		'edclass'	=> 'dashed',
		]);

	if ($o_edit->data['status']!='PUBLISHED') cms_redirect_page("/");

	$o_ext = new itEditor([
		'table_name'	=> 'items',
		'rec_id'	=> $item_id,
		'column'	=> 'extended_xml',
		'edclass'	=> 'dashed boxed',
		]);

	$category_row = $cat_cat[$cat_relations[$o_edit->data['category_id']]];
	$item_articul = get_item_articul($o_edit->data);
	$item_title = get_field_by_lang($o_edit->data['title_xml'], CMS_LANG, '');
	$ext_code = $o_ext->container();
	$o_b_all = get_item_compiled_block(BLOCK_ITEMALL);
	$o_b_discl = get_item_compiled_block(BLOCK_ITEMDISCL, ['class' => 'bg_red']);

	$result =
		get_item_serie_feed($o_edit->data).
		TAB."<h1 class='tit' id='item-tit-{$item_id}'>".
			"<small>".get_const($category_row['title'])."</small><br>".
			"<span class='green'>{$item_articul}</span>".
			($item_title ? "&nbsp;&laquo;{$item_title}&raquo;" : NULL).
		TAB."</h1>".
			($_USER->is_logged()
				?	TAB."<div class='ed_devider admin'>".
					get_item_x_event($o_edit->data).
					get_item_articul_event($o_edit->data).
					get_item_shop_event($o_edit->data).
					get_item_econom_event($o_edit->data).
					get_item_replicant_event($o_edit->data).
					get_item_new_event($o_edit->data).
					get_item_title_event($o_edit->data).
					TAB."</div>"
				: NULL).
		TAB."<div class='siterow boxed'>".
			TAB."<div class='left50 boxed glass'>".
				TAB."<div class='portofolio'>".
					get_items_slider($o_edit->data).
					TAB."<div class='flags'>".
						wish_btn($o_edit->data).
						get_item_flags($o_edit->data).
					TAB."</div>".
				TAB."</div>".
				get_price_item_event($o_edit->data).
				$o_edit->container().
				((!($o_edit->data['is_shop'] OR is_for_sale($o_edit->data) AND !is_null($o_b_discl->editor))) ? $o_b_discl->editor->code() : NULL).
			TAB."</div>".
			TAB."<div class='right50 boxed padded glass bordered'>".
				TAB."<div class='siterow boxed'>".
					TAB."<div class='item_control boxed'>".
						TAB."<div class='buttons boxed'>".
						get_item_calc_event($o_edit->data).
						get_buy_item_event($o_edit->data).
						get_order_item_event($o_edit->data).
						TAB."</div>".
						filter_item_color_selector($o_edit->data).
					TAB."</div>".
				TAB."</div>".
				TAB."<div class='siterow boxed'>".
					((!($o_edit->data['is_shop'] OR is_for_sale($o_edit->data) AND !is_null($o_b_all->editor))) ? $o_b_all->editor->code() : NULL).
				TAB."</div>".
				TAB."<div class='siterow boxed'>".$ext_code.TAB."</div>".
			TAB."</div>".
		TAB."</div>".
		minify_js("<script>
			$(document).ready(function(){
				$('#item-tit-{$item_id}').ScrollTo({duration:800, callback:function(){}});
				});
			</script>");

	if (is_array($o_edit->data['color_xml']) OR $_USER->is_logged())
		{
		$o_images = new itImages([
			'table_name'	=> 'items',
			'rec_id'	=> $item_id,
			'column'	=> 'color_xml',
			'type'		=> 'gallery',
			'data'		=> $o_edit->data,
			'f_title'	=> 'color_gallery_title',
			'f_caption'	=> 'color_gallery_caption',
			'edclass'	=> 'dashed_green boxed',
			]);

		$result .=
			get_colibri_block(BLOCK_ITEMCOLOR).
			TAB."<div class='siterow color_xml boxed'>".
				$o_images->container().
			TAB."</div>";
		unset($o_images);
		}

	$plug_og['title'] = $item_articul;
	$plug_og['subtitle'] = get_const($category_row['title']).(!empty($item_title) ? " | {$item_title}" : NULL);
	$plug_og['image'] = isset($o_edit->data['images'][0]) ? $o_edit->data['images'][0] : DEFAULT_OG_IMAGE;
	$plug_og['description'] = $o_edit->txt().$o_ext->txt();

	$_MARKUP = [
		'name'		=> $plug_og['subtitle'],
		'description'	=> $plug_og['description'],
		'image'		=> [0 => get_item_markup_image($o_edit->data)],
		'price'		=> $o_edit->data['price'],
		'currency'	=> 'USD',
		'sku'		=> $item_articul,
		'url'		=> CMS_CURRENT_BASE_URL.get_item_runtime_url($o_edit->data),
		];

	unset($o_edit, $o_ext, $o_b_all, $o_b_discl);
	return $result;
	}

function color_gallery_title($data, $key)
	{
	if ($row = itMySQL::_get_rec_from_db($data['table_name'], $data['rec_id']))
		{
		$index = $key+1;
		$category_str = get_category_by_id($row['category_id']);
		$articul = get_item_articul($row);
		return CMS_NAME." | {$category_str} | {$articul} | ".get_const('VARIANT')." #{$index}";
		}
	}

function color_gallery_caption($data, $key)
	{
	if ($row = itMySQL::_get_rec_from_db($data['table_name'], $data['rec_id']))
		{
		$index = $key+1;
		$articul = get_item_articul($row);
		return "<center><span class='blue'>{$articul}</span> <span class='green'>".get_const('VARIANT')." #{$index}</span></center>";
		}
	}

function item_gallery_title($data, $key)
	{
	if ($row = itMySQL::_get_rec_from_db($data['table_name'], $data['rec_id']))
		{
		$category_str = get_category_by_id($data['category_id']);
		$articul = get_item_articul($row);
		return "{$category_str} | ".CMS_NAME." | ( {$articul} )";
		}
	}

function item_gallery_caption($data, $key)
	{
	if ($row = itMySQL::_get_rec_from_db($data['table_name'], $data['rec_id']))
		{
		$category_str = get_category_by_id($data['category_id']);
		$articul = get_item_articul($row);
		return "<center><b>".stripQuotas(get_field_by_lang($row['title_xml'], CMS_LANG, '')).
			" <span class='green'>{$category_str}</span> <span class='blue'>{$articul}</span></b></center>";
		}
	}

function get_item_letter($item_rec=NULL)
	{
	global $cat_cat, $cat_relations;
	if (is_null($item_rec)) return;
	$letter = NULL;

	if ($item_rec['is_shop'])
		{
		$letter = 'S_';
		}

	if ($item_rec['is_replicant'])
		{
		return $letter.'R';
		}

	return $letter.$cat_cat[$cat_relations[$item_rec['category_id']]]['letter'];
	}

function get_category_by_id($category_id)
	{
	global $cat_cat, $cat_relations;
	return isset($cat_relations[$category_id])
		? get_const($cat_cat[$cat_relations[$category_id]]['title'])
		: NULL;
	}

function get_item_articul($item_rec=NULL)
	{
	return get_item_letter($item_rec)."_".stripslashes($item_rec['serie'])."_".stripslashes($item_rec['version']);
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

function get_items_feed_sql($view)
	{
	global $cat_cat, $_USER;

	$sql = "SELECT * FROM `colibri_items` WHERE `status`='PUBLISHED' ".
		(isset($cat_cat[$view]) ? "AND `category_id`='{$cat_cat[$view]['id']}' " : NULL).
		(!$_USER->is_logged() ? "AND `images` NOT IN ('', '[]') " : NULL);

	if ($view != 'shop' AND !in_array(@$cat_cat[$view]['id'], str_getcsv(get_const('CATEGORY_FOR_SALE'))))
		{
		$sql .= " AND `is_shop`<>'1'";
		}

	return $sql.
		get_items_feed_view_sql($view).
		get_items_feed_regexp_filter('colors', 'filter_xml').
		get_items_feed_regexp_filter('tags', 'tags_xml').
		(ready_val($_SESSION['filter']['min']) ? " AND `price`>={$_SESSION['filter']['min']}" : NULL).
		(ready_val($_SESSION['filter']['max']) ? " AND `price`<={$_SESSION['filter']['max']}" : NULL).
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
	if (!ready_val($_REQUEST['anchor'])) return ['counter' => NULL, 'code' => NULL];

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

	$sql = get_items_feed_sql(ready_val($_REQUEST['view']));
	$order_str = get_items_feed_order();
	$o_feed = get_items_compiled_feed($sql, $order_str);
	$fewer = get_items_fewer_feed_code($sql, $order_str);

	$result =
		get_items_feed_title_code($plug_og['title'], $o_feed->count_all() + $fewer['counter']).
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
	$image = (is_array($item_rec['images']) and count($item_rec['images']))
		? $item_rec['images'][0]
		: NULL;
	return get_thumbnail($image, $class);
	}

function get_items_feed_row($row, $full=false)
	{
	if (is_null($row))
		{
		return TAB."<div class='item_div boxed'>&nbsp;</div>";
		}

	global $_USER;

	$img_src = get_item_avatar_image($row);
	$title = get_item_articul($row)."<br/>".get_field_by_lang($row['title_xml'], CMS_LANG, '');
	$link = get_item_runtime_url($row);

	$animated_parent = $animated = NULL;
	if (($row['id'] == $_REQUEST['rec_id']) OR ($row['id'] == ready_val($_REQUEST['anchor'])))
		{
		$animated_parent = ' animatedParent animateOnce';
		$animated = ' animated tada';
		}

	$full_str = $full ? ' full' : NULL;
	return
		TAB."<div class='item_div boxed{$animated_parent}{$full_str}' id='item-{$row['id']}'>".
        	wish_btn($row).
		($_USER->is_logged() ? TAB."<div class='id'># {$row['id']}</div>" : NULL).
		(!$full ? get_item_flags($row) : NULL).
		(($row['is_shop'] OR is_for_sale($row))
			? TAB."<div class='price sale rounded boxed'>".get_const('FOR_SALE')."</div>"
			: TAB."<div class='price rounded shadow-white boxed'>{$row['price']}<small>&nbsp;$</small></div>").
		TAB."<a href='{$link}' target='_blank'>".
		TAB."<img class='avatar boxed{$animated}' src='{$img_src}'>".
		TAB."<div class='title boxed'>{$title}</div>".
		TAB."</a>".
		TAB."</div>";
	}

function is_for_sale($row=NULL)
	{
	return in_array($row['category_id'], str_getcsv(get_const('CATEGORY_FOR_SALE')));
	}

function get_item_flags($row=NULL)
	{
	return
		($row['is_new'] ? TAB."<div class='new_flash shadow-white'><img src='/themes/".CMS_THEME."/images/sub_new_gray.png'></div>" : NULL).
		($row['is_econom'] ? TAB."<div class='econom_flash shadow-white'><img src='/themes/".CMS_THEME."/images/sub_econom_gray.png'></div>" : NULL);
	}

function get_item_serie_feed($row=NULL)
	{
 	$sql = "SELECT * FROM `colibri_items` WHERE `serie` = '{$row['serie']}' ".
 		"AND `category_id` = '{$row['category_id']}' ".
 		"AND `is_replicant` = '{$row['is_replicant']}' ".
 		"AND `status` = 'PUBLISHED'";

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
			get_const('ITEM_SERIE_TITLE')." : <span class='green'>".get_item_letter($row)."_{$row['serie']}</span>".
			"</div>".
			$result
		: NULL;
	}
?>

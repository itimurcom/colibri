<?php
//..............................................................................
// возвращает слайдер товара
//..............................................................................
function get_items_slider($row)
	{
	global $_USER;
	$result = NULL;

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
	
//..............................................................................
// панель товара с описанием
//..............................................................................	
function get_item_panel($item_id=NULL)
	{
	global $_USER, $cat_cat, $cat_relations, $plug_og, $_MARKUP;
	
	$result = NULL;
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

		
	$ext_code = $o_ext->container();

	
	$o_b_all = new itBlock(BLOCK_ITEMALL,[
		'no_data'	=> true,
		'no_title'	=> true,
		'no_related'	=> true,
		'no_lang'	=> true,
		'edclass'	=> 'dashed_blue',
		]);
	$o_b_all->compile();

	$o_b_all = new itBlock(BLOCK_ITEMALL,[
		'no_data'	=> true,
		'no_title'	=> true,
		'no_related'	=> true,
		'no_lang'	=> true,
		'edclass'	=> 'dashed_blue',
		]);
	$o_b_all->compile();

	$o_b_discl = new itBlock(BLOCK_ITEMDISCL,[
		'no_data'	=> true,
		'no_title'	=> true,
		'no_related'	=> true,
		'no_lang'	=> true,
		'class'		=> 'bg_red',
		'edclass'	=> 'dashed_blue',
		]);
	$o_b_discl->compile();



	$admin_code = NULL;
//		($_USER->is_logged() AND !is_null($o_b_all->editor)) ?
//			TAB."<div clas='ed_devider'>".
//			(function_exists('get_content_remove_event') ? get_content_remove_event($o_b_all->editor->data) : "").
//			TAB."</div>"
//			: NULL;

	$category_row = $cat_cat[$cat_relations[$o_edit->data['category_id']]];
	
	$result =
// 		filter_color_selector().
		get_item_serie_feed($o_edit->data).
		TAB."<h1 class='tit' id='item-tit-{$item_id}'>".
//			TAB."<span class='articul'>".
			"<small>".get_const($category_row['title'])."</small><br>".
			"<span class='green'>".($item_articul = get_item_articul($o_edit->data))."</span>".
//			"</span>".
			( ($item_title = get_field_by_lang($o_edit->data['title_xml'], CMS_LANG, '') ) ? "&nbsp;&laquo;{$item_title}&raquo;" : NULL).
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
				( ( !($o_edit->data['is_shop'] OR is_for_sale($o_edit->data) AND !is_null($o_b_discl->editor))) ? $o_b_discl->editor->code() : NULL).
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
//					TAB.get_block_content_event($o_b_all->data).
					(( !($o_edit->data['is_shop'] OR is_for_sale($o_edit->data) AND !is_null($o_b_all->editor))) ? $o_b_all->editor->code() : NULL).
					$admin_code.
				TAB."</div>".
				TAB."<div class='siterow boxed'>".
				$ext_code.TAB.
				"</div>".
			TAB."</div>".
		TAB."</div>".minify_js(
			"<script>
			$(document).ready(function(){
				$('#item-tit-{$item_id}').ScrollTo({duration:800, callback:function(){}});
				});
			</script>");

	// поле других вариантов товаров
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
	
	$plug_og['title']	= $item_articul;
	$plug_og['subtitle'] 	= get_const($category_row['title']).(!empty($item_title) ? " | {$item_title}" : NULL);
	$plug_og['image'] 	= isset($o_edit->data['images'][0]) ? $o_edit->data['images'][0] : DEFAULT_OG_IMAGE;
	$plug_og['description']	=  $o_edit->txt().$o_ext->txt();

	// установим микроразметку!
	$_MARKUP = [
		'name'		=> $plug_og['subtitle'],
		'description'	=>$plug_og['description'],
		'image'	=> [
			0	=> 'https://www.atelier-colibri.com/img/itemshot_'.(isset($o_edit->data['images'][0]) ? $o_edit->data['images'][0] : basename(DEFAULT_OG_IMAGE)),
			],
		'price'		=> $o_edit->data['price'],
		'currency'	=> 'USD',
		'sku'		=> $item_articul,
		'url'		=> "https://atelier-colibri.com/".CMS_LANG."/items/{$o_edit->data['id']}/",
		];
		
	unset($o_edit, $o_ext, $o_b_all, $o_b_discl);
	return $result;
	}

function color_gallery_title($data, $key)
	{
	if ($row = itMySQL::_get_rec_from_db($data['table_name'], $data['rec_id']))
		{
		$index = $key+1;		
		$category_str =  get_category_by_id($row['category_id']);
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
		$category_str =  get_category_by_id($data['category_id']);
		$articul = get_item_articul($row);
		return "{$category_str} | ".CMS_NAME." | ( {$articul} )";
		}
	}	

	
function item_gallery_caption($data, $key)
	{
	if ($row = itMySQL::_get_rec_from_db($data['table_name'], $data['rec_id']))
		{
		$category_str =  get_category_by_id($data['category_id']);
		$articul = get_item_articul($row);
		return 
			"<center><b>".stripQuotas(get_field_by_lang($row['title_xml'], CMS_LANG, '')).
			" <span class='green'>{$category_str}</span> <span class='blue'>{$articul}</span></b></center>";

		}	
	}	

//..............................................................................
// возвращает букву артикула в зависимости от типа тавара
//..............................................................................
function get_item_letter($item_rec=NULL)
	{
	global $cat_cat, $cat_relations;
	if (is_null($item_rec)) return;	
	$letter = NULL;
	
	if ($item_rec['is_shop'])
		{
		$letter = "S_";
		}

	if ($item_rec['is_replicant'])
		{
		return $letter.'R';
		} else return $letter.$cat_cat[$cat_relations[$item_rec['category_id']]]['letter'];
	}

	
//..............................................................................
// возвращает название категории из ее номера
//..............................................................................
function get_category_by_id($category_id)
	{
	global $cat_cat, $cat_relations;
	return isset($cat_relations[$category_id])
		? get_const($cat_cat[$cat_relations[$category_id]]['title'])
		: NULL;
	}

//..............................................................................
// возвращает строку артикула товара
//..............................................................................
function get_item_articul($item_rec=NULL)
	{
	$result = get_item_letter($item_rec)."_".stripslashes($item_rec['serie'])."_".stripslashes($item_rec['version']);
        return $result;
	}
	
//..............................................................................
// возвращает поток товаров по установленным критериям
//..............................................................................
function get_items_feed()
	{
	global $cat_cat, $plug_og, $_USER;
	
	// сформируем запрос на список товаров, исходя из условий
	$sql = "SELECT * FROM `colibri_items` WHERE `status`='PUBLISHED' ".
	(isset($cat_cat[$_REQUEST['view']]) ? "AND `category_id`='{$cat_cat[$_REQUEST['view']]['id']}' " : NULL).
 	(!$_USER->is_logged() ? "AND `images` NOT IN ('', '[]') " : NULL).
	"";
	
	if ($_REQUEST['view'] != 'shop' AND !in_array(@$cat_cat[$_REQUEST['view']]['id'],str_getcsv(get_const('CATEGORY_FOR_SALE'))))
		{
		$sql .= " AND `is_shop`<>'1'";
		}

	switch ($_REQUEST['view'])
		{
		case 'new' :
			{
			$sql .= " AND `is_new`";
			break;
			}

		case 'econom' :
			{
			$sql .= " AND `is_econom`";
			break;
			}

		case 'shop' :
			{
			$sql .= " AND `is_shop`";
			break;
			}
		}

	// проверим установлены ли фильтры цвета
	if (isset($_SESSION['filter']['colors']) and count($_SESSION['filter']['colors']))
		{
		$colors_regexp = "\"".implode('\"|\"', array_keys($_SESSION['filter']['colors']))."\"";
		$sql .=  " AND `filter_xml` REGEXP '{$colors_regexp}'";
		}

	// проверим установлены ли фильтры тегов
	if (isset($_SESSION['filter']['tags']) and count($_SESSION['filter']['tags']))
		{
		$tags_regexp = "\"".implode('\"|\"', array_keys($_SESSION['filter']['tags']))."\"";
		$sql .=  !empty($tags_regexp) ? " AND `tags_xml` REGEXP '{$tags_regexp}'" : "";
		}		

	switch (ready_val($_SESSION['filter']['sort']))
		{
		case 'price_up' : {
			$order_str = "`price` ASC";
			break;
			}
		case 'price_down' : {
			$order_str = "`price` DESC";
			break;
			}
		default : {
			$order_str = "`id` DESC";
			break;
			}
		}

	$sql .= ready_val($_SESSION['filter']['min']) ? " AND `price`>={$_SESSION['filter']['min']}" : NULL;
	$sql .= ready_val($_SESSION['filter']['max']) ? " AND `price`<={$_SESSION['filter']['max']}" : NULL;

	
	// тут надо кнопки больше и меньше присабачить!!!!!
	// перед тем как запрос к базе данных изменится
	
	$sql .= (isset($_REQUEST['anchor']) ? " AND `id`<='{$_REQUEST['anchor']}'" : '');
	
	$o_feed = new itFeed([
		'sql'		=> $sql,
		'name'		=> 'items',
		'start'		=> true,
		'async'		=> true,
		'fewer'		=> false,
		'order'		=> $order_str,
		'weight'	=> false,
		'nodiv'		=> true,
		'appear'	=> false,
		]);
	$o_feed->compile();

	$fewer_counter = NULL;
	$o_fewer_code = NULL;

	if(ready_val($_REQUEST['anchor']))
		{
		// добавим поток предыдущих товаров
		$options = array(
			'sql'		=> $sql,
			'start'		=> true,
			'name' 		=> 'items',
			'async'		=> false,
			'appear'	=> false,
			'fewer'		=> true,
			'order'		=> "`id` ASC",	// по возрастанию вверх!
			'nodiv'		=> true,			
			);
		$o_fewer = new itFeed($options);
		$o_fewer->compile();
		$o_fewer_code = ($fewer_counter = $o_fewer->count_all()) ? $o_fewer->code() : NULL;
		unset($o_fewer);		
		}

	$result = 
		TAB."<h1 class='tit'>".
		get_const($plug_og['title'])."<br>".
		"<small>( ".str_replace('[VALUE]', $o_feed->count_all() + $fewer_counter, get_const('PROPOSITONS_TITLE')).
		(isset($_SESSION['filter']['colors']) ? "<font size='2' color='green'> ✔ ".get_const('WITH_COLORS')."</font>" : NULL).
		" )</small>".
		TAB."</h1>".
		TAB."<div class='ed_devider'></div>".		
			feed_selector().
			TAB."<div class='siterow boxed'>".
			$o_fewer_code.
			$o_feed->code().
			TAB."</div>";
	unset($o_feed);
	return	
// 		TAB."<div class='siterow boxed'>".
		$result.
// 		TAB."</div>".
		"";
	}

//..............................................................................
// возвращает линк на первое изображение в галлерее для аватарки товара 
//..............................................................................
function get_item_avatar_image($item_rec=NULL, $class='SERIE_AVATAR')
	{
	$image = (is_array($item_rec['images']) and count($item_rec['images']))
		? $item_rec['images'][0]
		: NULL;
	return get_thumbnail($image, $class);
	}
	
//..............................................................................
// возвращает код карточки списка для товара
//..............................................................................
function get_items_feed_row($row, $full=false)
	{
	// если пустое поле
	if (is_null($row))
		{
		return TAB."<div class='item_div boxed'>&nbsp;</div>";
		}
				
	global $_USER, $cat_cat;
	$result = NULL;
			
        $img_src = get_item_avatar_image($row);
        $title = get_item_articul($row)."<br/>".get_field_by_lang($row['title_xml'], CMS_LANG, '');
        $link = "/".CMS_LANG."/items/".
        	(isset($row['url_xml'][CMS_LANG])
		        ?	$row['url_xml'][CMS_LANG]
			: 	"{$row['id']}/");
        
        $animated_parent = $animated = NULL;
        if (($row['id'] == $_REQUEST['rec_id']) OR ($row['id'] == ready_val($_REQUEST['anchor'])))
        	{
	        $animated_parent = ' animatedParent animateOnce';
	        $animated = ' animated tada';
        	}
        	
        $full_str = $full ? " full" : NULL;
        return
        	TAB."<div class='item_div boxed{$animated_parent}{$full_str}' id='item-{$row['id']}'>".
        	wish_btn($row).
		($_USER->is_logged() ? TAB."<div class='id'># {$row['id']}</div>" : NULL).
		( !$full ? get_item_flags($row) : NULL ).
		( ($row['is_shop'] OR is_for_sale($row))
			? TAB."<div class='price sale rounded boxed'>".get_const('FOR_SALE')."</div>" 
			: ( true //$_USER->is_logged()
				? TAB."<div class='price rounded shadow-white boxed'>{$row['price']}<small>&nbsp;$</small></div>"
				: NULL)).
        	TAB."<a href='{$link}' target='_blank'>".
        	TAB."<img class='avatar boxed{$animated}' src='{$img_src}'>".
        	TAB."<div class='title boxed'>{$title}</div>".
        	TAB."</a>".
		TAB."</div>";
	}	


//..............................................................................
// проверяет, включена ли категория на продажу
//..............................................................................
function is_for_sale($row=NULL)
	{
	return in_array($row['category_id'], str_getcsv(get_const('CATEGORY_FOR_SALE')));
	}
	
//..............................................................................
// возвращает флаги для товара
//..............................................................................
function get_item_flags($row=NULL)
	{
	return 
		($row['is_new'] ? TAB."<div class='new_flash shadow-white'><img src='/themes/".CMS_THEME."/images/sub_new_gray.png'></div>" : NULL).
		($row['is_econom'] ? TAB."<div class='econom_flash shadow-white'><img src='/themes/".CMS_THEME."/images/sub_econom_gray.png'></div>" : NULL);
	}

//..............................................................................
// возвращает ленту серии для товара
//..............................................................................
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
		'order'		=> "CONVERT(`version`, decimal)",		// по убыванию вниз!
		'weight'	=> false,
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
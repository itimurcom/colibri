<?php
include ("engine/kernel.php");

function colibri_sitemap_has_value($value)
    {
    return isset($value) && $value!=='';
    }

function colibri_sitemap_base_url()
    {
    $server_http_debug = defined('SERVER_HTTP_DEBUG') ? SERVER_HTTP_DEBUG : NULL;
    if (colibri_sitemap_has_value($server_http_debug)) return rtrim($server_http_debug, '/');

    $current_base_url = defined('CMS_CURRENT_BASE_URL') ? CMS_CURRENT_BASE_URL : NULL;
    if (colibri_sitemap_has_value($current_base_url)) return rtrim($current_base_url, '/');

    return '';
    }

function colibri_sitemap_row_value($row, $key, $default=NULL)
    {
    if (!is_array($row)) return $default;
    return array_key_exists($key, $row) ? $row[$key] : $default;
    }

function colibri_sitemap_link($row)
    {
    $controller = colibri_sitemap_row_value($row, 'controller');
    $view = colibri_sitemap_row_value($row, 'view');

    return
        (colibri_sitemap_has_value($controller) ? "{$controller}/" : "").
        ((!is_null($view) AND colibri_sitemap_has_value($view) AND ($controller!=$view)) ? "{$view}/" : "");
    }

function colibri_sitemap_add_page(&$pages, $url, $period='monthly', $priority='0.5')
    {
    if (!colibri_sitemap_has_value($url)) return;

    $pages[] = [
        'url'       => $url,
        'period'    => $period,
        'priority'  => $priority,
        ];
    }

function colibri_sitemap_language_list()
    {
    global $lang_cat;

    $langs = [];
    if (is_array($lang_cat))
        foreach ($lang_cat as $row)
            {
            $short = colibri_sitemap_row_value($row, 'short');
            if (is_array($row) AND colibri_sitemap_has_value($short))
                $langs[] = $short;
            }

    if (!count($langs)) $langs = ['ru', 'en'];
    return array_values(array_unique($langs));
    }

function colibri_sitemap_add_menu_pages(&$pages, $menu_rows, $base_url, $langs)
    {
    if (!is_array($menu_rows)) return;

    foreach ($menu_rows as $row)
        {
        if (!is_array($row) OR !colibri_sitemap_row_value($row, 'sitemap')) continue;

        $link = colibri_sitemap_link($row);
        foreach ($langs as $lang)
            colibri_sitemap_add_page($pages, $base_url."/{$lang}/".$link, 'monthly', '0.8');
        }
    }

function colibri_sitemap_add_item_pages(&$pages, $base_url, $langs)
    {
    $items = itMySQL::_request("SELECT * FROM `".DB_PREFIX."items` WHERE `status`='PUBLISHED' ORDER BY `id` DESC");
    if (!is_array($items)) return;

    foreach ($items as $item)
        {
        if (!is_array($item)) continue;

        $url_xml = colibri_sitemap_row_value($item, 'url_xml');
        if (!is_array($url_xml)) continue;

        foreach ($langs as $lang)
            {
            $localized_url = colibri_sitemap_row_value($url_xml, $lang);
            if (colibri_sitemap_has_value($localized_url))
                colibri_sitemap_add_page($pages, $base_url."/{$lang}/items/".$localized_url, 'monthly', ($lang=='en' ? '0.8' : '0.5'));
            }
        }
    }

$base_url = colibri_sitemap_base_url();
$langs = colibri_sitemap_language_list();
$pages = [];

colibri_sitemap_add_page($pages, $base_url, 'monthly', '1');
colibri_sitemap_add_menu_pages($pages, isset($cat_cat) ? $cat_cat : NULL, $base_url, $langs);
colibri_sitemap_add_menu_pages($pages, isset($cat_more) ? $cat_more : NULL, $base_url, $langs);
colibri_sitemap_add_item_pages($pages, $base_url, $langs);

$o_sitemap = new itSiteMap($pages);

header("Content-Type: application/xml; charset=utf-8");
echo $o_sitemap->code;
unset($o_sitemap);
?>

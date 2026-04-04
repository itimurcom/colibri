<?php
include ("engine/kernel.php");

$pages[] = [
    'url'	    => SERVER_HTTP_DEBUG,
    'period'    => 'monthly',
    'priority'  => '1',
];

foreach ($cat_cat as $row)
    {
    $link = 
		($row['controller'] ? "{$row['controller']}/" : "").
		((!is_null($row['view']) AND ($row['controller']!=$row['view']))  ? "{$row['view']}/" : "");
    if (isset($row['sitemap']) AND $row['sitemap']) {
        $pages[] = [
            'url'	    => SERVER_HTTP_DEBUG.'/ru/'.$link,
            'period'    => 'monthly',
            'priority'  => '0.8',
            ];
        $pages[] = [
            'url'	    => SERVER_HTTP_DEBUG.'/en/'.$link,
            'period'    => 'monthly',
            'priority'  => '0.8',
            ];
        }
    }

foreach ($cat_more as $row)
    {
    $link = 
		($row['controller'] ? "{$row['controller']}/" : "").
		((!is_null($row['view']) AND ($row['controller']!=$row['view']))  ? "{$row['view']}/" : "");
    if (isset($row['sitemap']) AND $row['sitemap']) {
        $pages[] = [
            'url'	    => SERVER_HTTP_DEBUG.'/ru/'.$link,
            'period'    => 'monthly',
            'priority'  => '0.8',
            ];
        $pages[] = [
            'url'	    => SERVER_HTTP_DEBUG.'/en/'.$link,
            'period'    => 'monthly',
            'priority'  => '0.8',
            ];
        }
    }

foreach (itMySQL::_request("SELECT * FROM `".DB_PREFIX."items` WHERE `status`='PUBLISHED' ORDER BY `id` DESC") as $item ) {
    if (is_array($item['url_xml']) AND isset($item['url_xml']['ru'])) {
        $pages[] = [
            'url'	    => SERVER_HTTP_DEBUG.'/ru/items/'.$item['url_xml']['ru'],
            'period'    => 'monthly',
            'priority'  => '0.5',
            ];
        }
    if (is_array($item['url_xml']) AND isset($item['url_xml']['en'])) {
        $pages[] = [
            'url'	    => SERVER_HTTP_DEBUG.'/en/items/'.$item['url_xml']['en'],
            'period'    => 'monthly',
            'priority'  => '0.8',
            ];
        }
}


$o_sitemap = new itSiteMap($pages);

header("Content-Type: application/xml; charset=utf-8");
echo $o_sitemap->code;
unset($o_sitemap);
?>
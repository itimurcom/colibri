<?
//..............................................................................
// itSiteMap : класс построения файла sitemap.xml
//..............................................................................
class itSiteMap
	{
    public function __construct($data=NULL) {
        $this->code = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
        if (is_array($data)) {
            foreach ($data as $page)
                {
                $this->code .= 
                    '<url>'.
                    '<loc>'.$page['url'].'</loc>'.
                    (isset($page['datetime']) ? "<lastmod>".strftime ( "%Y-%m-%d", strtotime($page['datetime']))."</lastmod>" : NULL).
                    '<changefreq>'.(isset($page['period']) ? $page['period'] : 'monthly').'</changefreq>'.
                    '<priority>'.(isset($page['priority']) ? $page['priority'] : '0.5').'</priority>'.                 
                    '</url>'.PHP_EOL;
                }
            }
        $this->code .= '</urlset>'.PHP_EOL;
        // file_put_contents('./sitemap.xml', $this->code);
        }
    }

/*    <?xml version="1.0" encoding="UTF-8"?>

    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    
       <url>
    
          <loc>http://www.example.com/</loc>
    
          <lastmod>2005-01-01</lastmod>
    
          <changefreq>monthly</changefreq>
    
          <priority>0.8</priority>
    
       </url>
    
    </urlset> 
*/
?>
<?php
// itSiteMap : класс построения файла sitemap.xml
class itSiteMap
	{
	public $code;

	public function __construct($data=NULL)
		{
		$this->code = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.
			'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;

		if (is_array($data))
			foreach ($data as $page)
				$this->append_page($page);

		$this->code .= '</urlset>'.PHP_EOL;
		// file_put_contents('./sitemap.xml', $this->code);
		}

	private function append_page($page)
		{
		if (!is_array($page) OR !isset($page['url']) OR trim((string)$page['url'])=='') return;

		$this->code .=
			'<url>'.
			'<loc>'.$this->xml_value($page['url']).'</loc>'.
			$this->lastmod_code(isset($page['datetime']) ? $page['datetime'] : NULL).
			'<changefreq>'.$this->xml_value(isset($page['period']) ? $page['period'] : 'monthly').'</changefreq>'.
			'<priority>'.$this->xml_value(isset($page['priority']) ? $page['priority'] : '0.5').'</priority>'.
			'</url>'.PHP_EOL;
		}

	private function lastmod_code($datetime=NULL)
		{
		if (is_null($datetime) OR trim((string)$datetime)=='') return NULL;

		$timestamp = strtotime((string)$datetime);
		if ($timestamp===false) return NULL;

		return '<lastmod>'.date('Y-m-d', $timestamp).'</lastmod>';
		}

	private function xml_value($value)
		{
		return htmlspecialchars((string)$value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
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

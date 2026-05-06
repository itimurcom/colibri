<?php
global $_MARKUP_ORG;
global $_LDJSON, $RDFA, $_SCHEMA;

// itMarkOrg :  класс автоматизации разметки организации для поисковых систем
class itMarkOrg
	{
	public $logo, $url, $name, $legal, $found, $founders, $address, $contacts, $sameAs;
	// конструктор класса (ему можно передать конкретные данные)

	private static function option_value($row, $key, $default=NULL)
		{
		return (is_array($row) AND array_key_exists($key, $row))
			? ready_value($row[$key], $default)
			: $default;
		}

	public function __construct($options = NULL)
		{
		global $_MARKUP_ORG;

		if (!is_array($options)) $options = [];
		if (!is_array($_MARKUP_ORG)) $_MARKUP_ORG = [];

   		$this->logo = self::option_value($options, 'logo', self::option_value($_MARKUP_ORG, 'logo'));  // логотип компании

        $this->url = self::option_value($options, 'url', SERVER_HTTP_DEBUG);  // ссылка на сайт компании
		
		$this->name = self::option_value($options, 'name', get_const(CMS_NAME));		// Название компании

        $this->legal = self::option_value($options, 'legalName', get_const(CMS_NAME));		// Название компании
		$this->found = self::option_value($options, 'foundingDate', self::option_value($options, 'found'));
		
        if (isset($options['founders']) AND is_array($options['founders'])) { // Основатели компании
            foreach ($options['founders'] as $founder) {
				if ($founder==='') continue;
                $this->founders[] = $founder;
                }
            } else {
                $this->founders = NULL;
                }

        $this->address = (isset($options['address']) AND is_array($options['address'])) ? [    // Адрес компании
                'streetAddress'     => self::option_value($options['address'], 'street'),
                'addressLocality'   => self::option_value($options['address'], 'local'),
                'addressRegion'     => self::option_value($options['address'], 'region'),
                'postalCode'        => self::option_value($options['address'], 'postal'),
                'addressCountry'    => self::option_value($options['address'], 'country'),
                ] : NULL;

        if (isset($options['contacts']) AND is_array($options['contacts'])) { // Контакты компании
                foreach ($options['contacts'] as $contact) {
					if (!is_array($contact)) continue;
                    $this->contacts[] = [
                        'type'      => self::option_value($contact, 'type'),
                        'telephone' => self::option_value($contact, 'phone'),
                        'email'     => self::option_value($contact, 'email'),
                        ];
                    }
                } else {
                    $this->contacts = NULL;
                    }

        if (isset($options['same']) AND is_array($options['same'])) { // Контакты компании
                foreach ($options['same'] as $same) {
					if ($same==='') continue;
                    $this->sameAs[] = $same;
                    }
                } else {
                    $this->sameAs = NULL;
                    }
                
		$this->prepare();
		}		

	// готовим три типа структурированных данных
	public function prepare()
		{
		global $_LDJSON, $_RDFA, $_SCHEMA;

		if (!isset($_LDJSON) OR !is_string($_LDJSON)) $_LDJSON = '';
		if (!isset($_RDFA) OR !is_string($_RDFA)) $_RDFA = '';
		if (!isset($_SCHEMA) OR !is_string($_SCHEMA)) $_SCHEMA = '';

		$_LDJSON .=
TAB.'<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Organization",
  "name": "'.$this->name.'",
  "legalName": "'.$this->legal.'",'.
  (!is_null($this->logo) ? '"logo": "'.$this->logo.'",' : NULL).  
  (!is_null($this->found) ? '"foundingDate": "'.$this->found.'",' : NULL).
  '"url": "'.$this->url.'"
}
</script>';

    
    
    // "founders": [
    // {
    // "@type": "Person",
    // "name": "Patrick Coombe"
    // },
    // {
    // "@type": "Person",
    // "name": ""
    // } ],
    // "address": {
    // "@type": "PostalAddress",
    // "streetAddress": "900 Linton Blvd Suite 104",
    // "addressLocality": "Delray Beach",
    // "addressRegion": "FL",
    // "postalCode": "33444",
    // "addressCountry": "USA"
    // },
    // "contactPoint": {
    // "@type": "ContactPoint",
    // "contactType": "customer support",
    // "telephone": "[+561-526-8457]",
    // "email": "info@elite-strategies.com"
    // },
    // "sameAs": [ 
    // "http://www.freebase.com/m/0_h96pq",
    // "http://www.facebook.com/elitestrategies",
    // "http://www.twitter.com/delraybeachseo",
    // "http://pinterest.com/elitestrategies/",
    // "http://elitestrategies.tumblr.com/",
    // "http://www.linkedin.com/company/elite-strategies",
    // "https://plus.google.com/106661773120082093538"
    // ]}

		}

	}

?>

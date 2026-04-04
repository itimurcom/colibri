<?php
// ================ CRC ================
// version: 1.43.03
// hash: 9c41fb6e949c8df33c28d39177b4334972942ae613ec22df5c0159bdad44bc86
// date: 21 May 2021 10:57
// ================ CRC ================
global $_MARKUP_ORG;
global $_LDJSON, $RDFA, $_SCHEMA;

//..............................................................................
// itMarkOrg :  класс автоматизации разметки организации для поисковых систем
//..............................................................................
class itMarkOrg
	{
	//..............................................................................
	// конструктор класса (ему можно передать конкретные данные)
	//..............................................................................

	public function __construct($options = NULL)
		{
		global $_MARKUP_ORG;

   		$this->logo = isset($options['logo'])
		 		? $options['logo']
		 		: ready_val($_MARKUP_ORG['logo']);  // логотип компании

        $this->url = isset($options['url'])
		 		? $options['url']
	 		    : SERVER_HTTP_DEBUG;  // ссылка на сайт компании
		
		$this->name = isset($options['name'])
				? $options['name']
				: get_const(CMS_NAME);		// Название компании

        $this->legal = isset($options['legalName'])
				? $options['legalName']
				: get_const(CMS_NAME);		// Название компании
		
        if (isset($options['founders']) AND is_array($options['founders'])) { // Основатели компании
            foreach ($options['founders'] as $founder) {
                $this->founders[] = $founder;
                }
            } else {
                $this->founders = NULL;
                }

        $this->address = (isset($options['address'])) ? [    // Адрес компании
                'streetAddress'     => ready_val($options['address']['street']),
                'addressLocality'   => ready_val($options['address']['local']),
                'addressRegion'     => ready_val($options['address']['region']),
                'postalCode'        => ready_val($options['address']['postal']),
                'addressCountry'    => ready_val($options['address']['country']),
                ] : NULL;

        if (isset($options['contacts']) AND is_array($options['contacts'])) { // Контакты компании
                foreach ($options['contacts'] as $contact) {
                    $this->contacts[] = [
                        'type'      => ready_val($contact['type']),
                        'telephone' => ready_val($contact['phone']),
                        'email'     => ready_val($contact['email']),
                        ];
                    }
                } else {
                    $this->contacts = NULL;
                    }

        if (isset($options['same']) AND is_array($options['same'])) { // Контакты компании
                foreach ($options['same'] as $same) {
                    $this->sameAs[] = $same;
                    }
                } else {
                    $this->sameAs = NULL;
                    }
                
		$this->prepare();
		}		

	//..............................................................................
	// готовим три типа структурированных данных
	//..............................................................................	
	public function prepare()
		{
		global $_LDJSON, $_RDFA, $_SCHEMA;
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
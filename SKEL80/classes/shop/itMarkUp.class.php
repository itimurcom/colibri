<?php
// ================ CRC ================
// version: 1.37.04
// hash: 026d986d2075655239b75c5d5794db83e0eb9982cbe52ff8528c9e5c99a68a88
// date: 21 May 2021 10:57
// ================ CRC ================
global $_MARKUP;
global $_LDJSON, $_RDFA, $_SCHEMA;

//..............................................................................
// itMarkUp :  класс автоматизации разметки товара для поисковых систем
//..............................................................................
class itMarkUp
	{
	//..............................................................................
	// конструктор класса (ему можно передать конкретные данные)
	//..............................................................................

	public function __construct($options = NULL)
		{
		global $_MARKUP;
		
		$this->image = isset($options['image'])
		 		? $options['image']
		 		: ready_val($_MARKUP['image']);		// Повторяющееся поле ImageObject или URL на картинки 1x1, 4x3, 16x9

		// перепакуем изображения		 		
		if (!is_array($this->image))
			{
			$image = $this->image;
			$this->image = NULL;
			$this->image[0] = $image;
			} 

		if (!isset($this->image[1]))
			{
			$this->image[1] = $this->image[0];
			}

		if (!isset($this->image[2]))
			{
			$this->image[2] = $this->image[1];
			}

		
		$this->name = isset($options['name'])
				? $options['name']
				: ready_val($_MARKUP['name']);		// Название товара
		$this->brand = isset($options['brand'])
				? $options['brand']
				: ready_val($_MARKUP['brand'], CMS_NAME);// Бренд товара
		$this->description = isset($options['description'])
				? $options['description']
				: ready_val($_MARKUP['description']);	// Описание товара
				
		$this->brand = isset($options['brand'])
				? $options['brand']
				: ready_val($_MARKUP['brand'], CMS_NAME);// Бренд

		$this->offers = isset($options['offers'])
				? $options['offers']
				: ready_val($_MARKUP['offers']); 	// Условия продажи товара. Включает вложенный элемент Offer или AggregateOffer

		// Offer
		$this->url = isset($options['url'])
				? $options['url']
				: ready_val($_MARKUP['url'], get_request_url()); 	// ссылка на товар


		$this->price = str_replace(',', '.', (isset($options['price'])
				? $options['price']
				: ready_val($_MARKUP['price'], '0.00'))); 		// Цена товара. Следуйте инструкциям schema.org 

		$this->currency = isset($options['currency'])
				? $options['currency']
				: ready_val($_MARKUP['currency'], 'USD');		// Валюта, в которой указана цена товара. Используйте трехбуквенный формат ISO 4217

		$this->condition  = isset($options['condition'])
				? $options['condition']
				: ready_val($_MARKUP['condition'], 'NewCondition');	// состояние товара NewCondition,DamagedCondition,RefurbishedCondition,UsedCondition

		$this->availability  = isset($options['availability'])
				? $options['availability']
				: ready_val($_MARKUP['availability'], 'InStock');	// наличие Discontinued,InStock,InStoreOnly,LimitedAvailability,OnlineOnly,OutOfStock,PreOrder,PreSale,SoldOut

		$this->expire  = isset($options['expire'])
				? $options['expire']
				: ready_val($_MARKUP['expire'], strftime("%Y-01-01",strtotime('now +1 year'))); 			// Дата (в формате ISO 8601), после которой цена перестанет действовать.

		$this->seller  = isset($options['seller'])
				? $options['seller']
				: ready_val($_MARKUP['seller'], CMS_NAME); // продавец

		$this->sku  = isset($options['sku'])
				? $options['sku']
				: ready_val($_MARKUP['sku'], $_REQUEST['rec_id']); 	// SKU товара на складе


		$this->mpn  = isset($options['mpn'])
				? $options['mpn']
				: ready_val($_MARKUP['mpn'], $this->sku ); 		// MPN код товара для тех изелий, у которых нет GTIN
		
		
		//  review
		$this->review['count']	= isset($options['review'])
			? ready_val($options['review']['count'], 1)
			: (isset($_MARKUP['review'])
				? ready_val($_MARKUP['review']['count'], 1)
				: 1);						// колиичество оценок

		$this->review['value']	= isset($options['review'])
			? ready_val($options['review']['value'], 5)
			: (isset($_MARKUP['review'])
				? ready_val($_MARKUP['review']['value'], 5)
				: 5);						// оценока

		$this->review['author']	= isset($options['review'])
			? ready_val($options['review']['author'], CMS_NAME)
			: (isset($_MARKUP['review'])
				? ready_val($_MARKUP['review']['author'], CMS_NAME)
				: CMS_NAME);					// автор оценки

		$this->prepare();
		}		

	//..............................................................................
	// готовим три типа структурированных данных
	//..............................................................................	
	public function prepare()
		{
		global $_LDJSON, $_RDFA, $_SCHEMA;
		$_LDJSON .= TAB.'<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "'.$this->name.'",
  "image": ["'.implode('"'.",\n".'"', $this->image).'"],
  "description": "'.$this->description.'",
  "sku": "'.$this->sku.'",
  "mpn": "'.$this->mpn.'",
  "brand": {
    "@type": "Thing",
    "name": "'.$this->brand.'"
  },
   "review": {
    "@type": "Review",
    "reviewRating": {
      "@type": "Rating",
      "ratingValue": "'.$this->review['value'].'",
      "bestRating": "'.$this->review['value'].'"
    },
    "author": {
      "@type": "Person",
      "name": "'.$this->review['author'].'"
    }
  },
    "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "'.$this->review['value'].'",
    "reviewCount": "'.$this->review['count'].'"
  },
  "offers": {
    "@type": "Offer",
    "url": "'.$this->url.'",
    "priceCurrency": "'.$this->currency.'",
    "price": "'.$this->price.'",
    "priceValidUntil": "'.$this->expire.'",
    "itemCondition": "https://schema.org/'.$this->condition.'",
    "availability": "https://schema.org/'.$this->availability.'",
    "seller": {
      "@type": "Organization",
      "name": "'.$this->seller.'"
    }
  }
}
</script>';

	$_RDFA .= 
'<div typeof="schema:Product">
    <div rel="schema:review">
      <div typeof="schema:Review">
        <div rel="schema:reviewRating">
          <div typeof="schema:Rating">
            <div property="schema:ratingValue" content="'.$this->review['value'].'"></div>
            <div property="schema:bestRating" content="'.$this->review['value'].'"></div>
          </div>
        </div>
        <div rel="schema:author">
          <div typeof="schema:Person">
            <div property="schema:name" content="'.$this->review['author'].'"></div>
          </div>
        </div>
      </div>
    </div>	
    <div rel="schema:image" resource="'.$this->image[1].'"></div>
    <div property="schema:mpn" content="'.$this->mpn.'"></div>    
    <div property="schema:name" content="'.$this->name.'"></div>
    <div property="schema:description" content="'.$this->description.'"></div>
    <div rel="schema:image" resource="'.$this->image[0].'"></div>
    <div rel="schema:brand">
      <div typeof="schema:Thing">
        <div property="schema:name" content="'.$this->name.'"></div>
      </div>
    </div>
    <div rel="schema:aggregateRating">
      <div typeof="schema:AggregateRating">
        <div property="schema:reviewCount" content="'.$this->review['count'].'"></div>
        <div property="schema:ratingValue" content="'.$this->review['value'].'"></div>
      </div>
    </div>    
    <div rel="schema:offers">
      <div typeof="schema:Offer">
        <div property="schema:price" content="'.$this->price.'"></div>
        <div property="schema:availability" content="https://schema.org/'.$this->availability.'"></div>
        <div rel="schema:seller">
          <div typeof="schema:Organization">
            <div property="schema:name" content="'.$this->seller.'"></div>
          </div>
        </div>
        <div property="schema:priceCurrency" content="'.$this->currency.'"></div>
        <div property="schema:priceValidUntil" datatype="xsd:date" content="'.$this->expire.'"></div>
        <div rel="schema:url" resource="'.$this->url.'"></div>
        <div property="schema:itemCondition" content="https://schema.org/'.$this->condition.'"></div>
      </div>
    </div>
    <div rel="schema:image" resource="'.$this->image[2].'"></div>
    <div property="schema:sku" content="'.$this->sku.'"></div>
 </div>';
 
 $_SCHEMA .= '<div>
  <div itemtype="http://schema.org/Product" itemscope>
  <meta itemprop="mpn" content="'.$this->mpn.'" />  
    <meta itemprop="name" content="'.$this->name.'" />
    <link itemprop="image" href="'.$this->image[0].'" />
    <link itemprop="image" href="'.$this->image[1].'" />
    <link itemprop="image" href="'.$this->image[2].'" />
    <meta itemprop="description" content="'.$this->description.'" />
    <div itemprop="offers" itemtype="http://schema.org/Offer" itemscope>
      <link itemprop="url" href="'.$this->url.'" />
      <meta itemprop="availability" content="https://schema.org/'.$this->availability.'" />
      <meta itemprop="priceCurrency" content="'.$this->currency.'" />
      <meta itemprop="itemCondition" content="https://schema.org/'.$this->condition.'" />
      <meta itemprop="price" content="'.$this->price.'" />
      <meta itemprop="priceValidUntil" content="'.$this->expire.'" />
      <div itemprop="seller" itemtype="http://schema.org/Organization" itemscope>
        <meta itemprop="name" content="'.$this->seller.'" />
      </div>
    </div>
    <div itemprop="aggregateRating" itemtype="http://schema.org/AggregateRating" itemscope>
      <meta itemprop="reviewCount" content="'.$this->review['count'].'" />
      <meta itemprop="ratingValue" content="'.$this->review['value'].'" />
    </div>
    <div itemprop="review" itemtype="http://schema.org/Review" itemscope>
      <div itemprop="author" itemtype="http://schema.org/Person" itemscope>
        <meta itemprop="name" content="'.$this->review['author'].'" />
      </div>
      <div itemprop="reviewRating" itemtype="http://schema.org/Rating" itemscope>
        <meta itemprop="ratingValue" content="'.$this->review['value'].'" />
        <meta itemprop="bestRating" content="'.$this->review['value'].'" />
      </div>
    </div>    
    <meta itemprop="sku" content="'.$this->sku.'" />    
    <div itemprop="brand" itemtype="http://schema.org/Thing" itemscope>
      <meta itemprop="name" content="'.$this->brand.'" />
    </div>
  </div>
</div>';
    
		}

	}

?>
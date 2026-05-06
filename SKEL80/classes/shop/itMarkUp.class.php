<?php
global $_MARKUP;
global $_LDJSON, $_RDFA, $_SCHEMA;

class itMarkUp
	{
	public $image, $name, $brand, $description, $offers, $url, $price, $currency, $condition, $availability, $expire, $seller, $sku, $mpn, $review;

	private static function option_value($row, $key, $default=NULL)
		{
		return (is_array($row) AND array_key_exists($key, $row))
			? ready_value($row[$key], $default)
			: $default;
		}

	private static function request_value($key, $default=NULL)
		{
		return (isset($_REQUEST) AND is_array($_REQUEST) AND array_key_exists($key, $_REQUEST))
			? ready_value($_REQUEST[$key], $default)
			: $default;
		}

	private static function review_value($row, $key, $default)
		{
		$review = (is_array($row) AND isset($row['review']) AND is_array($row['review']))
			? $row['review']
			: [];
		return self::option_value($review, $key, $default);
		}

	private static function normalize_images($image)
		{
		if (!is_array($image))
			{
			$image = [0 => $image];
			}

		if (!array_key_exists(0, $image))
			{
			$image[0] = NULL;
			}

		if (!array_key_exists(1, $image))
			{
			$image[1] = $image[0];
			}

		if (!array_key_exists(2, $image))
			{
			$image[2] = $image[1];
			}

		return $image;
		}

	public function __construct($options = NULL)
		{
		global $_MARKUP;

		if (!is_array($options)) $options = [];
		if (!is_array($_MARKUP)) $_MARKUP = [];

		$this->image = self::normalize_images(self::option_value($options, 'image', self::option_value($_MARKUP, 'image')));		// Повторяющееся поле ImageObject или URL на картинки 1x1, 4x3, 16x9

		$this->name = self::option_value($options, 'name', self::option_value($_MARKUP, 'name'));		// Название товара
		$this->brand = self::option_value($options, 'brand', self::option_value($_MARKUP, 'brand', CMS_NAME));// Бренд товара
		$this->description = self::option_value($options, 'description', self::option_value($_MARKUP, 'description'));	// Описание товара

		$this->brand = self::option_value($options, 'brand', self::option_value($_MARKUP, 'brand', CMS_NAME));// Бренд

		$this->offers = self::option_value($options, 'offers', self::option_value($_MARKUP, 'offers')); 	// Условия продажи товара. Включает вложенный элемент Offer или AggregateOffer

		$this->url = self::option_value($options, 'url', self::option_value($_MARKUP, 'url', get_request_url())); 	// ссылка на товар

		$this->price = str_replace(',', '.', self::option_value($options, 'price', self::option_value($_MARKUP, 'price', '0.00'))); 		// Цена товара. Следуйте инструкциям schema.org

		$this->currency = self::option_value($options, 'currency', self::option_value($_MARKUP, 'currency', 'USD'));		// Валюта, в которой указана цена товара. Используйте трехбуквенный формат ISO 4217

		$this->condition  = self::option_value($options, 'condition', self::option_value($_MARKUP, 'condition', 'NewCondition'));	// состояние товара NewCondition,DamagedCondition,RefurbishedCondition,UsedCondition

		$this->availability  = self::option_value($options, 'availability', self::option_value($_MARKUP, 'availability', 'InStock'));	// наличие Discontinued,InStock,InStoreOnly,LimitedAvailability,OnlineOnly,OutOfStock,PreOrder,PreSale,SoldOut

		$this->expire  = self::option_value($options, 'expire', self::option_value($_MARKUP, 'expire', skel80_strftime_compat("%Y-01-01", strtotime('now +1 year'), 'en'))); 			// Дата (в формате ISO 8601), после которой цена перестанет действовать.

		$this->seller  = self::option_value($options, 'seller', self::option_value($_MARKUP, 'seller', CMS_NAME)); // продавец

		$this->sku  = self::option_value($options, 'sku', self::option_value($_MARKUP, 'sku', self::request_value('rec_id'))); 	// SKU товара на складе

		$this->mpn  = self::option_value($options, 'mpn', self::option_value($_MARKUP, 'mpn', $this->sku)); 		// MPN код товара для тех изелий, у которых нет GTIN

		$this->review['count']	= self::review_value($options, 'count', self::review_value($_MARKUP, 'count', 1));						// колиичество оценок

		$this->review['value']	= self::review_value($options, 'value', self::review_value($_MARKUP, 'value', 5));						// оценока

		$this->review['author']	= self::review_value($options, 'author', self::review_value($_MARKUP, 'author', CMS_NAME));					// автор оценки

		$this->prepare();
		}

	public function prepare()
		{
		global $_LDJSON, $_RDFA, $_SCHEMA;

		if (!isset($_LDJSON) OR !is_string($_LDJSON)) $_LDJSON = '';
		if (!isset($_RDFA) OR !is_string($_RDFA)) $_RDFA = '';
		if (!isset($_SCHEMA) OR !is_string($_SCHEMA)) $_SCHEMA = '';

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

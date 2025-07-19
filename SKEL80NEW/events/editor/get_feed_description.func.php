<?
//..............................................................................
// возвращает текст описания новости, генерируемой из блоков
//..............................................................................
function get_feed_description($row=NULL, $cut=DEFAULT_DESCRIPTION_CUT)
	{

	if ($row==NULL) return;
	if ($cut==0) return;

	
	$selector = ($row['lang']=='ALL') ? 'ALL' : CMS_LANG;

	$value = '';

	if (isset($row['ed_xml'][$selector]) and is_array($row['ed_xml'][$selector]))
		{
		foreach ($row['ed_xml'][$selector] as $ed_key=>$ed_row)
			{
			if ($ed_row['type']=='text')
				{
				$value .= (isset($ed_row['value'][CMS_LANG]) and trim($ed_row['value'][CMS_LANG])) ? html2txt($ed_row['value'][CMS_LANG])." " : "";
				}
			}
		}

	$result = get_str_cut($value, $cut);
	return $result;
	}
?>
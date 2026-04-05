<?php
// ================ CRC ================
// version: 1.15.02
// hash: 6749b3e059b3ca493b95f6838ca53daf481a83a2a3e5551102070daa500069bf
// date: 16 September 2018 19:58
// ================ CRC ================
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
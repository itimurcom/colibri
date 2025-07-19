<?
//..............................................................................
// превод html кода в чистый текст
//..............................................................................
function html2txt($document='', $br=false, $forced=false)
	{
	$result = strip_tags($document, ($br==false) ? '<br/><br>' : '');

	if ($forced)
		{
		// уберем все пробелы и знаки припинания
		$result = preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '', $result);
		} else	{
			// уберем повтор пробелов
			$result = preg_replace('/ {2,}/',' ',$result);
			}

	if ($br==false)
		{
		// чистим переводы строки и табуляции
		$result = preg_replace("/[\t\r\n]+/",'',$result);
		$result = str_replace(['<br>','<br/>'],' ',$result);
		} else  {
			$result = preg_replace("/[\t\r\n]+/",'<br/>',$result);
			}

	return $result;
	}
?>
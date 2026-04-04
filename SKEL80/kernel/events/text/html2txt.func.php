<?php
// ================ CRC ================
// version: 1.35.02
// hash: 294ed0f4314106ab0198b26c28caf0e83a86577f03fe1216a55e5f598874aedb
// date: 09 September 2019  7:09
// ================ CRC ================
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
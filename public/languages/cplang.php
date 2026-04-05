<?
chdir($_SERVER['DOCUMENT_ROOT']);
include('engine/kernel.php');
chdir(dirname(__FILE__));

if (!function_exists('rglob'))
	{
function rglob($pattern, $flags = 0) {
    $files = glob($pattern, $flags); 
    foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
        $files = array_merge($files, rglob($dir.'/'.basename($pattern), $flags));
    }
    return $files;
}
}

$res_colors = ['red', 'blue', 'red', 'brown'];
$index = 0;

foreach (rglob("./*.php", GLOB_BRACE) as $file)
	{
	if (in_array($lang = str_replace('.php','',basename($file)), ['common', 'cplang'])) continue;
	
	$langs[] = $lang;
	
	echo "Найден язык <font color='{$res_colors[$index]}'><b>$lang</b></font><br/><br/>";
	$index++;
	$line = 1;	
	foreach(file($file) as $text_line)
		{
		$matches=[];
		if (preg_match('/DEFINE\s?\([\'|\"](.*?)[\'|\"]\s?+,\s?+[\'|\"](.*)[\'|\"]\);/i', str_replace("\t","",$text_line), $matches))
			{
			$name=$matches[1];
			$value=$matches[2];

			$res_arr[$name][$lang]['line'] = $line;			
			$res_arr[$name][$lang]['value'] = $value;
//			echo "$name<xmp>$value</xmp><br/>";
			}
		$line++;
		}
    }
    

$need_count = 0;
foreach($res_arr as $const=>$row)
	{
	if (count($langs)>count($row))
		{
		$need = NULL;
		$present = NULL;
		$preset_value = NULL;
		foreach($langs as $key=>$lang)
			{
			if (!isset($row[$lang]))
				{
				$need[] = "<font color='{$res_colors[$key]}'><b>{$lang}</b></font><br/>";
				$need_count++;
				} else {
					$present[] = "<font color='{$res_colors[$key]}'><b>{$lang}</b></font>";
					$preset_line = $row[$lang]['line'];
					$preset_value = $row[$lang]['value'];
					}
			}
		$code[] = "define('{$const}', \"{$preset_value}\");";
		echo "<b>{$const}</b> есть в ".implode(', ',$present).":[$preset_line] нужно добавить в ".implode(', ',$need).
			"<xmp>define('{$const}', \"{$preset_value}\");</xmp>";
		}
	$line ++;
	}
   
echo "<h2>Всего нужно добавить <font color='red'><b>{$need_count}</b></font> констант</h2>".
	($need_count ? "<xmp>".implode("\n",$code)."</xmp>" : NULL);
?>
<?php 
$document_root = isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT'] !== ''
	? rtrim($_SERVER['DOCUMENT_ROOT'], '/')
	: dirname(__DIR__);
chdir($document_root);
include('engine/kernel.php');
chdir(dirname(__FILE__));

if (!function_exists('rglob'))
	{
function rglob($pattern, $flags = 0) {
    $files = glob($pattern, $flags);
    $files = is_array($files) ? $files : [];
    $dirs = glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT);
    $dirs = is_array($dirs) ? $dirs : [];
    foreach ($dirs as $dir) {
        $files = array_merge($files, rglob($dir.'/'.basename($pattern), $flags));
    }
    return $files;
}
}

$res_colors = ['red', 'blue', 'red', 'brown'];
$index = 0;
$langs = [];
$res_arr = [];
$code = [];

foreach (rglob("./*.php", GLOB_BRACE) as $file)
	{
	if (in_array($lang = str_replace('.php','',basename($file)), ['common', 'cplang'])) continue;
	
	$langs[] = $lang;
	$color = $res_colors[$index % count($res_colors)];
	
	echo "Найден язык <font color='{$color}'><b>$lang</b></font><br/><br/>";
	$index++;
	$line = 1;
	$file_lines = file($file);
	$file_lines = is_array($file_lines) ? $file_lines : [];
	foreach($file_lines as $text_line)
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
		$need = [];
		$present = [];
		$preset_value = NULL;
		$preset_line = 0;
		foreach($langs as $key=>$lang)
			{
			$color = $res_colors[$key % count($res_colors)];
			if (!isset($row[$lang]))
				{
				$need[] = "<font color='{$color}'><b>{$lang}</b></font><br/>";
				$need_count++;
				} else {
					$present[] = "<font color='{$color}'><b>{$lang}</b></font>";
					$preset_line = isset($row[$lang]['line']) ? $row[$lang]['line'] : 0;
					$preset_value = isset($row[$lang]['value']) ? $row[$lang]['value'] : '';
					}
			}
		$code[] = "define('{$const}', \"{$preset_value}\");";
		echo "<b>{$const}</b> есть в ".implode(', ',$present).":[$preset_line] нужно добавить в ".implode(', ',$need).
			"<xmp>define('{$const}', \"{$preset_value}\");</xmp>";
		}
	}
   
echo "<h2>Всего нужно добавить <font color='red'><b>{$need_count}</b></font> констант</h2>".
	($need_count ? "<xmp>".implode("\n",$code)."</xmp>" : NULL);
?>

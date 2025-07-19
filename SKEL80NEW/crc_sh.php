<?
define ('PUT_SH', 0);

define('DATACONTAINER_CODE','// ================ CRC ================');
define('VERSION_CODE',	'// version: ');
define('MD5HASH_CODE',	'// hash: ');
define('DATEIME_CODE',	'// date: ');
define('CR', 		"\n");
define('ALGO',		'sha256');


$ver = isset($_REQUEST['ver']) ? $_REQUEST['ver'] : "1.00";
$path = isset($_REQUEST['path']) ? $_REQUEST['path'] : realpath(".");

echo "Версия от  скелетона: \033[1;34m".($skeletver = trim(file_get_contents("{$path}/ver")))."\033[0m\n";
$md5hash = NULL;
$changes = NULL;

foreach(rglob("{$path}/*.{php,js,css}", GLOB_BRACE) as $file)
	{
	// пропустим сами себя
	if ($file == "{$path}/".basename(__FILE__)) continue;
	
	$process_lines = $orig_lines = file($file);
	$version = NULL;
	$extention = pathinfo($file, PATHINFO_EXTENSION);	
	
	foreach ($process_lines as $key=>$line)
		{			
		if (strpos($line, DATACONTAINER_CODE)!==false)
			{
			// найдена строка начала данных
			unset($process_lines[$key]);
			} else
		if (strpos($line, VERSION_CODE)!==false)
			{
			// найдена строка версии файла
			$version = trim(str_replace(VERSION_CODE, '',$line));
			unset($process_lines[$key]);
			} else
		if (strpos($line, MD5HASH_CODE)!==false)
			{
			// найдена строка хеша
			$md5hash = trim(str_replace(MD5HASH_CODE, '', $line));
			unset($process_lines[$key]);			
			} else
		if (strpos($line, DATEIME_CODE)!==false)
			{
			// найдена строка даты
			unset($process_lines[$key]);			
			}
		}

	$new_hash = hash(ALGO, implode('',$process_lines));
	if (($new_hash != $md5hash) OR (!PUT_SH))
		{
		// внесены изменения - запомним файл
		$changes[] = $file;
		$version = is_null($version) ? "{$skeletver}.00": $version;
		$newversion = update_version($version);
		echo "хешируем {$file}\n".
		"старый: {$md5hash} | новый: {$new_hash}\n".
		"старая версия: {$version} | новая версия: {$newversion}\n\n";
		
		if ($extention=='php')
			{
			unset($process_lines[0]);
			}

		file_put_contents($file,
			(($extention=='php') ? "<?".CR : NULL).
			(($extention=='css') ? "/*" : NULL).
			( PUT_SH ? 
			DATACONTAINER_CODE.CR.
			VERSION_CODE.$newversion.CR.
			MD5HASH_CODE.$new_hash.CR.
			DATEIME_CODE.strftime("%d %B %Y %k:%M", strtotime('now')).CR.
			DATACONTAINER_CODE.(($extention=='css') ? "*/" : NULL).CR : NULL).
			implode('', $process_lines)
			);
		}
	}
	
echo  is_array($changes)
	? "Внесено изменений в \033[1;34m".count($changes)."\033[0m файлов:\n"
	: "Нет изменений...\n";


function rglob($pattern, $flags = 0) {
    $files = glob($pattern, $flags); 
    foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
        $files = array_merge($files, rglob($dir.'/'.basename($pattern), $flags));
    }
    return $files;
}


function major_version($ver)
	{
	$ver_arr = explode('.',$ver);
	unset($ver_arr[2]);
	$ver_arr[1] = str_pad($ver_arr[1], 2, "0", STR_PAD_LEFT);
	return implode('.',$ver_arr);
	}
	
function update_version($ver)
	{
	$ver_arr = explode('.',$ver);

	$ver_arr[2] = intval($ver_arr[2])+1;
	
	if ($ver_arr[2]==100)
		{
		$ver_arr[1] = intval($ver_arr[1])+1;
		$ver_arr[2] = 1;		
		}
		
	if ($ver_arr[1]==100)
		{
		$ver_arr[0] = intval($ver_arr[0])+1;
		$ver_arr[1] = 1;
		}
			
	$ver_arr[1] = str_pad($ver_arr[1], 2, "0", STR_PAD_LEFT);
	$ver_arr[2] = str_pad($ver_arr[2], 2, "0", STR_PAD_LEFT);
	return implode('.',$ver_arr);		
	}
?>
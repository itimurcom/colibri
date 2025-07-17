<?php
error_reporting (E_ALL & ~E_NOTICE & ~E_DEPRECATED);
session_start();
define('REFRESH_SEC',10);

echo "<head><meta http-equiv='refresh' content='".REFRESH_SEC."'></head>";
global $count;
echo "free    : ".format_size(disk_free_space($_SERVER['DOCUMENT_ROOT']))."<br/>";
echo "total   : ".format_size(disk_total_space($_SERVER['DOCUMENT_ROOT']))."<br/>";

$size = dirsize($_SERVER['DOCUMENT_ROOT'], true);
if ($_SESSION['current'] != $count) 
		{
		$plus = "+ ".($count - $_SESSION['current']);
		$_SESSION['current'] = $count;
		} else unset($plus);
echo "current web : ".format_size($size)." [$count file(s)] $plus<br/>";

$size = dirsize($_SERVER['DOCUMENT_ROOT'].'/uploads/', true);
if ($_SESSION['uploads'] != $count) 
		{
		$plus = "+ ".($count - $_SESSION['uploads']);
		$_SESSION['uploads'] = $count;
		} else unset($plus);
echo "uploads dir : ".format_size($size)." [$count file(s)] $plus<br/>";

$size = dirsize($_SERVER['DOCUMENT_ROOT'].'/img/', true);
if ($_SESSION['img'] != $count) 
		{
		$plus = "+ ".($count - $_SESSION['img']);
		$_SESSION['img'] = $count;
		} else unset($plus);
echo "gen img dir : ".format_size($size)." [$count file(s)] $plus<br/>";

echo "<br/> refreshing page after ".REFRESH_SEC." sec...";

function format_size($size)
{
$gb = 1024*1024*1024;
$mb = 1024*1024;
$kb = 1024;

		if($size / $gb > 1)
		{
			return round($size / $gb, 2).' Gb';
		}elseif($size / $mb > 1)
		{
			return round($size / $mb, 2).' Mb';
		}elseif($size / $kb > 1)
		{
			return round($size / $kb, 2).' Kb';
		}else{
			return round($size, 2).' bytes';
		}
}


 function dirsize($dir, $reset=false)
    {
	global $count;
	if ($reset==true) $count = 0;
      @$dh = opendir($dir);
      $size = 0;
      while ($file = @readdir($dh))
      {
        if ($file != "." and $file != "..") 
        {
          $path = $dir."/".$file;
          if (is_dir($path))
          {
            $size += dirsize($path); // recursive in sub-folders
          }
          elseif (is_file($path))
          {
            $size += filesize($path); // add file
		$count++;
          }
        }
      }
      @closedir($dh);
      return $size;
    }
?>
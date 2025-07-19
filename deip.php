<?
include ("engine/kernel.php");
$ip = get_user_ip();

// echo file_get_contents('.htaccess') ;

$lines  = file('.htaccess');
$search = "allow from {$ip}";

$result = NULL;
foreach($lines as $line) {
    if ( (stripos($line, $search) === false) OR (stripos($line, 'Allow from ALL') !== false) ) {
        $result .= $line;
    }
}

echo "<pre>{$result}</pre>";

$f = fopen(".htaccess", "w");
fwrite($f, $result);
fclose($f);

cms_redirect_page("/");
?>
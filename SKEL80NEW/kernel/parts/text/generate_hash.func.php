<?
//..............................................................................
// для генерации случайной строки длиной HASH_LEN
//..............................................................................
function generate_hash($hashlen=HASH_LEN)
	{
	$chars 	= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$code	= "";
	$clen	= strlen($chars)-1;
	while (strlen($code)<$hashlen)
		{
		$code .= $chars[mt_rand(0,$clen)];
		}
	return $code;
	}
?>
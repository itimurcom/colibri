<?php
define('NO_PREPARED_ARR', 1);	
include ("engine/kernel.php");	
$_REQUEST['controller'] = $_REQUEST['view'] = '403';
$o_site = new itSite();
$o_site->compile();
unset($o_site);
?>
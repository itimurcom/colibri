<?php
include ("engine/kernel.php");

$_USER->logout();

cms_smart_redirect('/');
?>
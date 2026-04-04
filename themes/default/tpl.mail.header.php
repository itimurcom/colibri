<?php
/* Установки для всех писем */
if (!defined('HTTP_PATH'))
	define('HTTP_PATH', 'https://'.$_SERVER['HTTP_HOST'].'/');
if (!defined('CMS_THEME'))
	{
	define('CMS_THEME','default');
	}
?>
<html>
<head>
<style>
.info
	{
	margin:0;
	padding:0;
	}
.label
	{
	margin:0;
	padding:0;
	color:rgba(0,0,0,.7);	
	}
	
</style>
</head>
<body>
<div style="
	background-color: #efefef;
	background-position:top center;
	background-repeat:no-repeat;
	background-size:cover;
	width:100%;
	display:table;
	position: relative;">
<div style="display:inline-table; float:left; max-width:10%;"><a href='<?=HTTP_PATH?>'><img src='<?=HTTP_PATH?>themes/default/images/top_left_logo[MAILID].png' style="width:90%;height:auto; margin:.8em;display:inline-table;"/></a></div>
<div style="display:inline-table; float:right; max-width:90%;text-align:center;";><a href='<?=HTTP_PATH?>'><img style="max-width:70%; display:inline-table; margin:.8em;" src='<?=HTTP_PATH?>themes/default/images/ateliecolibri-<?=CMS_LANG?>.png'></a></div>
</div>
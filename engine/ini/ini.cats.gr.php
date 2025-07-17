<?php
//------------------------------------------------------------------------------
// массив данных групп категорий
//------------------------------------------------------------------------------
global $cats_gr;
$cats_gr[GR_BLOCK] = array
	(
	'id' 	=> 	GR_BLOCK,
	'name'	=>	'block',
	'title'	=>	'GR_BLOCK_TITLE',
	'header'=>	'GR_BLOC_HEADER',
	'protected'	=> 0,
	'show'	=>	1,
	);

$cats_gr[GR_CONTENT] = array
	(
	'id' 	=> 	GR_CONTENT,
	'name'	=>	'content',
	'title'	=>	'GR_CONTENT_TITLE',
	'header'=>	'GR_CONTENT_HEADER',
	'show'	=>	0,
	);              
?>
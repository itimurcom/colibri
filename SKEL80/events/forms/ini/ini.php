<?php
// ================ CRC ================
// version: 1.15.05
// hash: 2d8c7c5896aab681784d080fd3e3ec32264e962f95c64ebc5dd6563a982f8eb6
// date: 10 March 2021  9:27
// ================ CRC ================
global $plug_css;
//$plug_css[] = 'class.itForm.css';
//$plug_css[] = 'class.itUpGal.css';
	
	
//-----------------------------------------------------------------------------
//
//	БЛОКИ СТАНДАРТНОЙ ФОРМЫ
//
//-----------------------------------------------------------------------------
global $form_blocks;
// стандартное поле для ввода
$form_blocks['ROW'] = [
	'code' 	=>
		TAB."<div class=\"label[COMPACT]\">[TITLE]</div>[EDITOR]".
		TAB."<div class=\"field[COMPACT]\">[CODE]</div>",
	];


$form_blocks['TITLE'] = [
	'code' 	=> TAB."<div class=\"modal_row [CLASS]\">[VALUE]".TAB."</div>",
	];

$form_blocks['DESC'] = [
	'code' 	=> TAB."<div class=\"modal_row [CLASS]\">[VALUE]".TAB."</div>",
	];

$form_blocks['HIDDEN'] = [
	'code' 	=> TAB."<input type=\"hidden\" id=\"[ID]\" name=\"[NAME]\" value=\"[VALUE]\" />",
	];

$form_blocks['INPUT'] = [
	'code' 	=> 
		$form_blocks['ROW']['code'],
	];

$form_blocks['PASSWORD'] = [
	'code' 	=> 
		$form_blocks['ROW']['code'],
	];

$form_blocks['AREA'] = [
	'code' 	=> 
		$form_blocks['ROW']['code'],
	];

$form_blocks['SELECTOR'] = [
	'code' 	=> 
		$form_blocks['ROW']['code'],
	];
	
$form_blocks['AUTOSELECT'] = [
	'code' 	=> 
		$form_blocks['ROW']['code'],
	];
	
$form_blocks['DATE'] = [
	'code' 	=> 
		$form_blocks['ROW']['code'],
	];	

$form_blocks['TIME'] = [
	'code' 	=> 
		$form_blocks['ROW']['code'],
	];	

$form_blocks['UPGAL'] = [
	'code' 	=> 
		$form_blocks['ROW']['code'],
	];
	
$form_blocks['FIELD'] = [
	'code' 	=> 
		TAB."<div id=\"[ID]\" class=\"modal_row[COMPACT]\">[CODE]".TAB."</div>",
	];

?>

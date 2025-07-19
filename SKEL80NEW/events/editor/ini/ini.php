<?
//-----------------------------------------------------------------------------
//
//	БЛОКИ СТАНДАРТНОГО РЕДАКТОРА
//
//-----------------------------------------------------------------------------
global $editor_blocks;
$editor_blocks['title'] = array
	(
	'code' 	=> "\n".TAB."\t[AVATAR]<h2 id='[ID]' class='ed_title'>[VALUE]".TAB."\t</h2>".ED_DEVIDER,
	'class'	=> 'itEdTitle'
	);

$editor_blocks['text'] = array
	(
	'code' 	=> "\n".html_comment("Начало текстового блока [ID]").
		TAB."\t[AVATAR]<span id='[ID]'></span><div id='[ID]' class='ed_text[EDCLASS]'[EDITABLE] rel-data='[REL]' placeholder='[PLACEHOLDER]'>[VALUE]".TAB."\t</div>".ED_DEVIDER.
		html_comment("Конец текстового блока [ID]"),
	'class'	=> 'itEdText'
	);

$editor_blocks['media'] = array
	(
	'code' 	=> "\n".html_comment("Начало блока media [ID]").
		TAB."\t<div id='[ID]' class='ed_media'>[VALUE]".TAB."\t</div>".ED_DEVIDER.
		html_comment("Конец блока media [ID]"),
	'class'	=> 'itEdMedia'
	);

$editor_blocks['gallery'] = array
	(
	'code' 	=> "\n".html_comment("Начало блока изображений [ID]").
		TAB."\t<div id='[ID]' class='ed_gallery fancygall'>[VALUE]".TAB."\t</div>".ED_DEVIDER.
		html_comment("Конец блока изображений [ID]"),
	'class'	=> 'itEdGallery'
	);

$editor_blocks['avatar'] = array
	(
	'code' 	=> "\n".TAB."\t<div id='[ID]' class='ed_avatar'><img class='gallery_avatar fancygall' src='[VALUE]'/>".	TAB."\t</div>[EDIT]".BR,
	'class'	=> NULL
	);
	
?>
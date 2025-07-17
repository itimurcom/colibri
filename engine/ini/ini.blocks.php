<?php
global $editor_blocks;
$editor_blocks['text'] = array
	(
	'code' 	=> 
	
//		TAB."<span id='[ID]'></span>".
		TAB."<div class='ed_block boxed glass'>".
				"[AVATAR]".
				TAB."<div id='[ID]' class='ed_text[EDCLASS]'[EDITABLE] rel-data='[REL]' placeholder='[PLACEHOLDER]'>[VALUE]</div>".
		TAB."</div>".TAB."<div class='ed_devider[EDIT]'>[BUTTONS]\t</div>",
	'class'	=> 'itEdText'
	);
?>
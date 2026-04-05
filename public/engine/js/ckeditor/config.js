/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	 //config.language = 'ru';
	//config.uiColor = '#FFFFAA';

// Toolbar configuration generated automatically by the editor based on config.toolbarGroups.
config.toolbar = [
	{name: 'basicstyles', groups: ['basicstyles'], items: [
		'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript','-',
		'NumberedList', 'BulletedList', '-',
		'Outdent', 'Indent', '-',
		'SelectAll', 'RemoveFormat']}, 
		'/',
	{name: 'paragraph', groups: ['paragraph'], items: [
		'FontSize',  '-',
		'TextColor', '-',
		'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-',
		'Link', 'Unlink' ]},
/*	{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
	{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
	{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
	{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
	'/',
	{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
	{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
	{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
	{ name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
	'/',

	{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
	{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
	
	{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
	{ name: 'others', items: [ '-' ] },
	{ name: 'about', items: [ 'About' ] } */
];

// Toolbar groups configuration.
config.toolbarGroups = [
//	{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
//	{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
//	{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ] },
//	{ name: 'forms' },
//	'/',
	{ name: 'basicstyles', groups: [ 'basicstyles'] },
	'/',	
	{ name: 'paragraph', groups: [ 'paragraph'] },
//	{ name: 'links' },
//	{ name: 'insert' },
//	'/',
//	{ name: 'styles' },
//	{ name: 'colors' },
//	{ name: 'tools' },
//	{ name: 'others' },
//	{ name: 'about' }
];

config.fontSize_sizes = 'Маленький/.8em;Оригинальный/1em;Большой/1.2em;Гигантский/1.4em;';
config.fontSize_defaultLabel = 'Original';
config.enterMode = CKEDITOR.ENTER_BR; // inserts <br />
config.removePlugins = 'magicline,elementspath';
config.forcePasteAsPlainText = true;
//config.extraAllowedContent = 'u;span{color}';
//config.resize_enabled = false;

config.colorButton_colors = '800000,FF0000,FFA500,FFFF00,808000,008000,800080,FF00FF,00FF00,008080,00FFFF,0000FF,000080,000000,808080,C0C0C0,FFFFFF';

// config.allowedContent = 'p b i; a[!href]; font[!color]; img;',

config.coreStyles_bold = { element : 'b', overrides : 'b' };
config.coreStyles_bold =
    {
        element : 'b'
//        attributes : { 'class' : 'Bold' }
    };

};


/**
 * @license Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_config.html

	// Set editor skin.
	config.skin = 'moono-lisa';

	// Simple toolbar configuration.
	config.toolbar = [
			['Maximize'],
			['Bold', 'Italic'],
			['NumberedList', 'BulletedList'],
			['RemoveFormat', 'PasteText', 'PasteFromWord'],
			['Link', 'Anchor'],
			['Source'],
			['Image'],
			'/',
			['Embed', 'Media', 'Record', 'BookList']
	];

	// Remove unnecessary buttons.
	config.removeButtons = 'Styles,About,Find,Replace,Strike,Subscript,Superscript,Outdent,Indent,Blockquote,Underline,Format,Scayt,Table,HorizontalRule,SpecialChar,Cut,Copy,Paste,Undo,Redo,Unlink';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';

	// Set Embed Provider.
	config.embed_provider = '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}';

	// Set Extra Plugins.
	config.extraPlugins = 'media,recordtoken,booklist,image2,embed,find';

	// Remove plugins.
	config.removePlugins = 'image';

	// Set Base Z-Index
	config.baseFloatZIndex = 100001;
};

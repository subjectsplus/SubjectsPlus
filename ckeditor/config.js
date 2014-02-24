/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	//added by dgonzalez
	config.extraPlugins = 'subsplus_asset,subsplus_cat_link,subsplus_resource,subsplus_faq,abbr';

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
	{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
	{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
	{ name: 'links' },
	{ name: 'insert' },
	{ name: 'forms' },
	{ name: 'tools' },
	{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
	{ name: 'others' },
	'/',
	{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
	{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
	{ name: 'styles' },
	{ name: 'colors' },
	{ name: 'subjectsplus', groups: [ 'subsplus_resource', 'subsplus_asset' , 'subsplus_faq', 'subsplus_cat_link' ] },
	{ name: 'about' }
	];

	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	config.removeButtons = 'Underline';

	config.language = 'en';

	//added by dgonzalez
	config.toolbar_SubsPlus_Narrow =
	[

	['Bold','Italic','-','BulletedList','NumberedList','-','Link','Unlink','-','Image','-', 'PasteText','PasteFromWord','-', 'Source'],

	'/',

	['subsplus_resource', 'subsplus_asset' , 'subsplus_faq' , 'subsplus_cat_link', 'Abbr']

	];

	config.toolbar_SubsPlus =
	[

	['Bold','Italic','-','BulletedList','NumberedList','-','Link','Unlink','-','Image','-', 'PasteText','PasteFromWord','-', 'Source', '-', 'GetResourceToken', 'GetFile', 'GetFAQs', 'GetCatalogLink']

	];

	config.toolbar_Basic =
	[
	['Bold','Italic','-','BulletedList','NumberedList','-','Link','Unlink','-','Image','-', 'PasteText','PasteFromWord','-', 'Source']
	];

	config.enterMode = CKEDITOR.ENTER_BR;
	config.shiftEnterMode = CKEDITOR.ENTER_BR;

	config.allowedContent = true;
};
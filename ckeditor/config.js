/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	//added by dgonzalez
	config.extraPlugins = 'subsplus_asset,subsplus_cat_link,subsplus_resource,subsplus_faq,maximize,justify,panelbutton,panel,floatpanel,button,colorbutton';

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
		// { name: 'subjectsplus', groups: [ 'subsplus_resource', 'subsplus_asset' , 'subsplus_faq', 'subsplus_cat_link' ] },
		{ name: 'about' }
	];

        // Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	config.removeButtons = 'Underline';

	config.language = 'en';

	//added by dgonzalez
	// removed , 'Maximize' from SubsPlus_Narrow temporarily
	config.toolbar_SubsPlus_Narrow =
		[

			['Bold','Italic','JustifyLeft','JustifyCenter','JustifyRight','-','BulletedList','NumberedList', 'Table', '-','Link','Unlink','Anchor','-','Image','-', 'PasteText','PasteFromWord','-', 'HorizontalRule','-','RemoveFormat', '-','Styles','-','Source','TextColor','BGColor'],

			'/',

			// ['subsplus_resource', 'subsplus_asset' , 'subsplus_faq' , 'subsplus_cat_link', 'Abbr']


		];

	config.toolbar_SubsPlus =
		[

			['Bold','Italic','-','BulletedList','NumberedList','-','Link','Unlink','-','Image','-', 'PasteText','PasteFromWord','-', 'Source', '-', 'GetResourceToken', 'GetFile', 'GetFAQs', 'GetCatalogLink', 'Maximize']

		];

	config.toolbar_Basic =
		[
			['Bold','Italic','-','BulletedList','NumberedList','-','Link','Unlink','-','Image','-', 'PasteText','PasteFromWord','-', 'Source']
		];


	config.toolbar_ImageOnly =
		[
			['Image', 'Source']
		];


	config.toolbar_TextFormat =
		[

			['Bold','Italic','-','BulletedList','NumberedList','-','Link','Unlink','-', 'PasteText','PasteFromWord','-', 'Source', 'Maximize']

		];
	config.colorButton_enableAutomatic = true;

	config.colorButton_colors =
		'000,800000,8B4513,2F4F4F,008080,000080,4B0082,696969,' +
		'B22222,A52A2A,DAA520,006400,40E0D0,0000CD,800080,808080,' +
		'F00,FF8C00,FFD700,008000,0FF,00F,EE82EE,A9A9A9,' +
		'FFA07A,FFA500,FFFF00,00FF00,AFEEEE,ADD8E6,DDA0DD,D3D3D3,' +
		'FFF0F5,FAEBD7,FFFFE0,F0FFF0,F0FFFF,F0F8FF,E6E6FA,FFF';

	config.enterMode = CKEDITOR.ENTER_BR;
	config.shiftEnterMode = CKEDITOR.ENTER_BR;

	config.allowedContent = true;
};

CKEDITOR.on('dialogDefinition', function(ev) {
	// Take the dialog window name and its definition from the event data.
	var dialogName = ev.data.name;
	var dialogDefinition = ev.data.definition;

});

//plugin.js
/*
 * this is plugin for subjetsplus asset link
 *
 * insert asset
 * @author: David Gonzalez
 */
// Register the related command.
CKEDITOR.plugins.add( 'subsplus_asset', {

	// Register the icons.
	icons: 'subsplus_asset',

	//which languages are available for plugin
	lang: [ 'en', 'es' ],

	// The plugin initialization logic goes inside this method.
	init: function( editor ) {

		// Define an editor command that opens our dialog.
		editor.addCommand( 'subsplus_asset', new CKEDITOR.dialogCommand( 'subsplus_assetDialog' ) );

		// Create a toolbar button that executes the above command.
		editor.ui.addButton( 'subsplus_asset', {

			// The text part of the button (if available) and tooptip.
			label: editor.lang['subsplus_asset.Label'],

			// The command to execute on click.
			command: 'subsplus_asset',

			// The button placement in the toolbar (toolbar group name).
			toolbar: 'subjectsplus',

			//icon specification
			icon: '../assets/images/icons/folder_add.png'
		});

        editor.on( 'doubleclick', function( evt )
        {
            var element = evt.data.element;

            if ( $(element.$).is('span.subsplus_asset') )
            {
                evt.data.dialog = 'subsplus_assetDialog';
                editor.getSelection().selectElement( element );
            }
        });

		// Register our dialog file. this.path is the plugin folder path.
		CKEDITOR.dialog.add( 'subsplus_assetDialog', this.path + 'dialogs/subsplus_asset.js' );
	}
});

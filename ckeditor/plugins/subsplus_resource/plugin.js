//plugin.js
/*
 * this is plugin for subjectsplus resource link
 *
 * insert resource
 * @author: David Gonzalez
 */
// Register the related command.
CKEDITOR.plugins.add( 'subsplus_resource', {

	// Register the icons.
	icons: 'subsplus_resource',

	//which languages are available for plugin
	lang: [ 'en', 'es' ],

	// The plugin initialization logic goes inside this method.
	init: function( editor ) {

		// Define an editor command that opens our dialog.
		editor.addCommand( 'subsplus_resource', new CKEDITOR.dialogCommand( 'subsplus_resourceDialog' ) );

		// Create a toolbar button that executes the above command.
		editor.ui.addButton( 'subsplus_resource', {

			// The text part of the button (if available) and tooptip.
			label: editor.lang['subsplus_resource.Label'],

			// The command to execute on click.
			command: 'subsplus_resource',

			// The button placement in the toolbar (toolbar group name).
			toolbar: 'subjectsplus',

			//icon specification
			icon: '../assets/images/icons/database_add.png'
		});

        editor.on( 'doubleclick', function( evt )
        {
            var element = evt.data.element;

            if ( $(element.$).is('span.subsplus_resource') )
            {
            	var selection = editor.getSelection();
            	selection.fake(element);
                evt.data.dialog = 'subsplus_resourceDialog';
            }
        });

		// Register our dialog file. this.path is the plugin folder path.
		CKEDITOR.dialog.add( 'subsplus_resourceDialog', this.path + 'dialogs/subsplus_resource.js' );
	}
});

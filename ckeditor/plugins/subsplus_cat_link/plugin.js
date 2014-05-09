//plugin.js
/*
 * this is plugin for subjetsplus catalog record link
 *
 * insert catalog item
 * @author: David Gonzalez
 */
// Register the related command.
CKEDITOR.plugins.add( 'subsplus_cat_link', {

	// Register the icons.
	icons: 'subsplus_cat_link',

	//set languages that the plugin is available for
	lang: [ 'en' , 'es' ],

	// The plugin initialization logic goes inside this method.
	init: function( editor ) {

		// Define an editor command that opens our dialog.
		editor.addCommand( 'subsplus_cat_link', new CKEDITOR.dialogCommand( 'subsplus_cat_linkDialog' ) );

		// Create a toolbar button that executes the above command.
		editor.ui.addButton( 'subsplus_cat_link', {

			// The text part of the button (if available) and tooptip.
			label: editor.lang['subsplus_cat_link.Label'],

			// The command to execute on click.
			command: 'subsplus_cat_link',

			// The button placement in the toolbar (toolbar group name).
			toolbar: 'subjectsplus',

			//icon specification
			icon: '../assets/images/icons/book_add.png'
		});

        editor.on( 'doubleclick', function( evt )
        {
            var element = evt.data.element;

            if ( $(element.$).is('span.subsplus_cat_link') )
            {
                evt.data.dialog = 'subsplus_cat_linkDialog';
                editor.getSelection().selectElement( element );
            }
        });

		// Register our dialog file. this.path is the plugin folder path.
		CKEDITOR.dialog.add( 'subsplus_cat_linkDialog', this.path + 'dialogs/subsplus_cat_link.js' );
	}
});

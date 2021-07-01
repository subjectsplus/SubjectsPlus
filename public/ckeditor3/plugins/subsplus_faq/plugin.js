//plugin.js
/*
 * this is plugin for subjectsplus faq link
 *
 * insert faq
 * @author: David Gonzalez
 */
// Register the related command.
CKEDITOR.plugins.add( 'subsplus_faq', {

	// Register the icons.
	icons: 'subsplus_faq',

	//which languages are available for plugin
	lang: [ 'en', 'es' ],

	// The plugin initialization logic goes inside this method.
	init: function( editor ) {

		// Define an editor command that opens our dialog.
		editor.addCommand( 'subsplus_faq', new CKEDITOR.dialogCommand( 'subsplus_faqDialog' ) );

		// Create a toolbar button that executes the above command.
		editor.ui.addButton( 'subsplus_faq', {

			// The text part of the button (if available) and tooptip.
			label: editor.lang['subsplus_faq.Label'],

			// The command to execute on click.
			command: 'subsplus_faq',

			// The button placement in the toolbar (toolbar group name).
			toolbar: 'subjectsplus',

			//icon specification
			icon: '../assets/images/icons/faq_add.png'
		});

        editor.on( 'doubleclick', function( evt )
        {
            var element = evt.data.element;

            if ( $(element.$).is('span.subsplus_faq') )
            {
                evt.data.dialog = 'subsplus_faqDialog';
                editor.getSelection().selectElement( element );
            }
        });

		// Register our dialog file. this.path is the plugin folder path.
		CKEDITOR.dialog.add( 'subsplus_faqDialog', this.path + 'dialogs/subsplus_faq.js' );
	}
});

//plugin.js
/*
 * this is plugin for subjectsplus resource link
 *
 * insert resource
 * @author: David Gonzalez
 */
// Register the related command.
CKEDITOR.plugins.add( 'subsplus_toc', {

	// Register the icons.
	icons: 'subsplus_toc',

	//which languages are available for plugin
	lang: [ 'en' ],

	// The plugin initialization logic goes inside this method.
	init: function( editor ) {

		// Define an editor command that opens our dialog.
		editor.addCommand( 'subsplus_toc', new CKEDITOR.dialogCommand( 'subsplus_tocDialog' ) );

		editor.on( 'doubleclick', function( evt )
		{
			var element = evt.data.element;

			if ( $(element.$).is('span.subsplus_toc') )
			{
				var selection = editor.getSelection();
				selection.fake(element);
				evt.data.dialog = 'subsplus_tocDialog';
			}
		});

		// Register our dialog file. this.path is the plugin folder path.
		CKEDITOR.dialog.add( 'subsplus_tocDialog', this.path + 'dialogs/subsplus_toc.js' );
	}
});

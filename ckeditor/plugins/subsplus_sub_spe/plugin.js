//plugin.js
/*
 * this is plugin for subjectsplus resource link
 *
 * insert resource
 * @author: David Gonzalez
 */
// Register the related command.
CKEDITOR.plugins.add( 'subsplus_sub_spe', {

	// Register the icons.
	icons: 'subsplus_sub_spe',

	//which languages are available for plugin
	lang: [ 'en' ],

	// The plugin initialization logic goes inside this method.
	init: function( editor ) {

		// Define an editor command that opens our dialog.
		editor.addCommand( 'subsplus_sub_spe', new CKEDITOR.dialogCommand( 'subsplus_sub_speDialog' ) );

		editor.on( 'doubleclick', function( evt )
		{
			var element = evt.data.element;

			if ( $(element.$).is('span.subsplus_sub_spe') )
			{
				var selection = editor.getSelection();
				selection.fake(element);
				evt.data.dialog = 'subsplus_sub_speDialog';
			}
		});

		// Register our dialog file. this.path is the plugin folder path.
		CKEDITOR.dialog.add( 'subsplus_sub_speDialog', this.path + 'dialogs/subsplus_sub_spe.js' );
	}
});

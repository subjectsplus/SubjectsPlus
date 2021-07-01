/**
 * Basic sample plugin inserting abbreviation elements into CKEditor editing area.
 *
 * Created out of the CKEditor Plugin SDK:
 * http://docs.ckeditor.com/#!/guide/plugin_sdk_sample_1
 */

// Register the plugin within the editor.
CKEDITOR.plugins.add( 'abbr', {

	// Register the icons.
	icons: 'abbr',

	// The plugin initialization logic goes inside this method.
	init: function( editor ) {

		// Define an editor command that opens our dialog.
		editor.addCommand( 'abbr', new CKEDITOR.dialogCommand( 'abbrDialog' ) );

		// Create a toolbar button that executes the above command.
		editor.ui.addButton( 'Abbr', {

			// The text part of the button (if available) and tooptip.
			label: 'Insert Abbreviation',

			// The command to execute on click.
			command: 'abbr',

			// The button placement in the toolbar (toolbar group name).
			toolbar: 'insert'
		});

		if ( editor.contextMenu ) {
			editor.addMenuGroup( 'abbrGroup' );
			editor.addMenuItem( 'abbrItem', {
				label: 'Edit Abbreviation',
				icon: this.path + 'icons/abbr.png',
				command: 'abbr',
				group: 'abbrGroup'
			});

			editor.contextMenu.addListener( function( element ) {
				if ( element.getAscendant( 'img', true ) ) {
					return { abbrItem: CKEDITOR.TRISTATE_OFF };
				}
			});
		}

		editor.on( 'doubleclick', function( evt )
		{
			var element = evt.data.element;

			if ( !element.isReadOnly() )
			{
				if ( $(element.$).closest('div.spimg').length > 0 )
				{
				evt.data.dialog = 'abbrDialog';
				editor.getSelection().selectElement( element );
				}
			}
		});

		//contentEditable='false'

		// Register our dialog file. this.path is the plugin folder path.
		CKEDITOR.dialog.add( 'abbrDialog', this.path + 'dialogs/abbr.js' );
	}
});


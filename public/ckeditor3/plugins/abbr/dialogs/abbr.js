/**
 * The abbr dialog definition.
 *
 * Created out of the CKEditor Plugin SDK:
 * http://docs.ckeditor.com/#!/guide/plugin_sdk_sample_1
 */

// Our dialog definition.
CKEDITOR.dialog.add( 'abbrDialog', function( editor ) {
	return {

		// Basic properties of the dialog window: title, minimum size.
		title: 'Abbreviation Properties',
		minWidth: 400,
		minHeight: 200,

		// Dialog window contents definition.
		contents: [
			{
				// Definition of the Basic Settings dialog tab (page).
				id: 'tab-basic',
				label: 'Basic Settings',

				// The tab contents.
				elements: [
					{
						// Text input field for the abbreviation text.
						type: 'text',
						id: 'abbr',
						label: 'Abbreviation',

						// Validation checking whether the field is not empty.
						validate: CKEDITOR.dialog.validate.notEmpty( "Abbreviation field cannot be empty" ),

						// Called by the main setupContent call on dialog initialization.
						setup: function( element ) {
							this.setValue( element.getAttribute('title') );
						},

						// Called by the main commitContent call on dialog confirmation.
						commit: function( element ) {
							element.Attribute('title', this.getValue() );
						}
					},
					{
						// Text input field for the abbreviation title (explanation).
						type: 'text',
						id: 'title',
						label: 'Explanation',
						validate: CKEDITOR.dialog.validate.notEmpty( "Explanation field cannot be empty" ),

						// Called by the main setupContent call on dialog initialization.
						setup: function( element ) {
							this.setValue( element.getAttribute( "title" ) );
						},

						// Called by the main commitContent call on dialog confirmation.
						commit: function( element ) {
							element.setAttribute( "title", this.getValue() );
						}
					}
				]
			},

			// Definition of the Advanced Settings dialog tab (page).
			{
				id: 'tab-adv',
				label: 'Advanced Settings',
				elements: [
					{
						// Another text field for the abbr element id.
						type: 'text',
						id: 'id',
						label: 'Id',

						// Called by the main setupContent call on dialog initialization.
						setup: function( element ) {
							this.setValue( element.getAttribute( "id" ) );
						},

						// Called by the main commitContent call on dialog confirmation.
						commit: function ( element ) {
							var id = this.getValue();
							if ( id )
								element.setAttribute( 'id', id );
							else if ( !this.insertMode )
								element.removeAttribute( 'id' );
						}
					}
				]
			}
		],

		// Invoked when the dialog is loaded.
		onShow: function() {

			// Get the selection in the editor.
			var selection = editor.getSelection();

			// Get the element at the start of the selection.
			var element = selection.getStartElement();

			// Get the <abbr> element closest to the selection, if any.
			if ( element )
				element = element.getAscendant( 'img', true );

			// Create a new <abbr> element if it does not exist.
			if ( !element || !element.hasClass('spimg') ) {
				element = editor.document.createElement( 'abbr' );

				// Flag the insertion mode for later use.
				this.insertMode = true;
			}
			else
				this.insertMode = false;

			// Store the reference to the <abbr> element in an internal property, for later use.
			this.element = element;

			// Invoke the setup methods of all dialog elements, so they can load the element attributes.
			if ( !this.insertMode )
				this.setupContent( this.element );
		},

		// This method is invoked once a user clicks the OK button, confirming the dialog.
		onOk: function() {

			// The context of this function is the dialog object itself.
			// http://docs.ckeditor.com/#!/api/CKEDITOR.dialog
			var dialog = this;

			// Creates a new <abbr> element.
			var abbr = this.element;

			// Invoke the commit methods of all dialog elements, so the <abbr> element gets modified.
			this.commitContent( abbr );

			// Finally, in if insert mode, inserts the element at the editor caret position.
			if ( this.insertMode )
				editor.insertElement( abbr );
		}
	};
});
// Register a new CKEditor plugin.
// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.resourceManager.html#add
CKEDITOR.plugins.add( 'simpleLink',
{
	// The plugin initialization logic goes inside this method.
	// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.pluginDefinition.html#init
	init: function( editor )
	{
		// Create an editor command that stores the dialog initialization command.
		// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.command.html
		// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialogCommand.html
		editor.addCommand( 'simpleLinkDialog', new CKEDITOR.dialogCommand( 'simpleLinkDialog' ) );
 
		// Create a toolbar button that executes the plugin command defined above.
		// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.html#addButton
		editor.ui.addButton( 'SimpleLink',
		{
			// Toolbar button tooltip.
			label: 'Insert a Link',
			// Reference to the plugin command name.
			command: 'simpleLinkDialog',
			// Button's icon file path.
			icon: this.path + 'images/icon.png'
		} );
 
		// Add a new dialog window definition containing all UI elements and listeners.
		// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.html#.add
		// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.dialogDefinition.html
		CKEDITOR.dialog.add( 'simpleLinkDialog', function( editor )
		{
			return {
				// Basic properties of the dialog window: title, minimum size.
				// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.dialogDefinition.html
				title : 'Link Properties',
				minWidth : 400,
				minHeight : 200,
				// Dialog window contents.
				// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.definition.content.html
				contents :
				[
					{
						// Definition of the Settings dialog window tab (page) with its id, label and contents.
						// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.contentDefinition.html
						id : 'general',
						label : 'Settings',
						elements :
						[
							// Dialog window UI element: HTML code field.
							// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.html.html
							{
								type : 'html',
								// HTML code to be shown inside the field.
								// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.html.html#constructor
								html : 'This dialog window lets you create simple links for your website.'
							},
							// Dialog window UI element: a textarea field for the link text.
							// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.textarea.html
							{
								type : 'textarea',
								id : 'contents',
								// Text that labels the field.
								// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.labeledElement.html#constructor
								label : 'Displayed Text',
								// Validation checking whether the field is not empty.
								validate : CKEDITOR.dialog.validate.notEmpty( 'The Displayed Text field cannot be empty.' ),
								// This field is required.
								required : true,
								// Function to be run when the commitContent method of the parent dialog window is called.
								// Get the value of this field and save it in the data object attribute.
								// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dom.element.html#getValue
								commit : function( data )
								{
									data.contents = this.getValue();
								}
							},
							// Dialog window UI element: a text input field for the link URL.
							// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.textInput.html
							{
								type : 'text',
								id : 'url',
								label : 'URL',
								validate : CKEDITOR.dialog.validate.notEmpty( 'The link must have a URL.' ),
								required : true,
								commit : function( data )
								{
									data.url = this.getValue();
								}
							},
							// Dialog window UI element: a selection field with link styles.
							// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.select.html
							{
								type : 'select',
								id : 'style',
								label : 'Style',
								// Items that will appear inside the selection field, in pairs of displayed text and value.
								// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.select.html#constructor
								items : 
								[
									[ '<none>', '' ],
									[ 'Bold', 'b' ],
									[ 'Underline', 'u' ],
									[ 'Italics', 'i' ]
								],
								commit : function( data )
								{
									data.style = this.getValue();
								}
							},
							// Dialog window UI element: a checkbox for opening in a new page.
							// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.checkbox.html
							{
								type : 'checkbox',
								id : 'newPage',
								label : 'Opens in a new page',
								// Default value.
								// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.checkbox.html#constructor
								'default' : true,
								commit : function( data )
								{
									data.newPage = this.getValue();
								}
							}
						]
					}
				],
				onOk : function()
				{
					// Create a link element and an object that will store the data entered in the dialog window.
					// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dom.document.html#createElement
					var dialog = this,
						data = {},
						link = editor.document.createElement( 'a' );
					// Populate the data object with data entered in the dialog window.
					// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.html#commitContent
					this.commitContent( data );

					// Set the URL (href attribute) of the link element.
					// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dom.element.html#setAttribute
					link.setAttribute( 'href', data.url );

					// In case the "newPage" checkbox was checked, set target=_blank for the link element.
					if ( data.newPage )
						link.setAttribute( 'target', '_blank' );

					// Set the style selected for the link, if applied.
					// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dom.element.html#setStyle
					switch( data.style )
					{
						case 'b' :
							link.setStyle( 'font-weight', 'bold' );
						break;
						case 'u' :
							link.setStyle( 'text-decoration', 'underline' );
						break;
						case 'i' :
							link.setStyle( 'font-style', 'italic' );
						break;
					}

					// Insert the Displayed Text entered in the dialog window into the link element.
					// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dom.element.html#setHtml
					link.setHtml( data.contents );

					// Insert the link element into the current cursor position in the editor.
					// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.editor.html#insertElement
					editor.insertElement( link );
				}
			};
		} );
	}
} );
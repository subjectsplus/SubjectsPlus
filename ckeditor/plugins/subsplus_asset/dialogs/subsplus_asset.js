/**
 * The subsplus_asset dialog definition.
 *
 */

function getDocumentRadioButtons( callback )
{
	jQuery.ajax({
			url: lstrPathToCkEditor + 'plugins/subsplus_asset/php/getExistingRadioButtons.php',
			success: function( response )
			{
				return callback( response );
			}
		});
}

function html_entity_decode(str)
{
	var ta=document.createElement("textarea");
	ta.innerHTML=str.replace(/</g,"<").replace(/>/g,">");
	return ta.value;
}

//get current script url so we can get path
var lobjScripts = document.getElementsByTagName("script");

for(var i = 0; i < lobjScripts.length; i++)
{
	if(lobjScripts[i].src.indexOf('subsplus_asset.js') != -1)
	{
		var lstrPathToCkEditor = lobjScripts[i].src.substring(0,(lobjScripts[i].src.indexOf('/ckeditor/') + 10));

		break;
	};
}

// Our dialog definition.
CKEDITOR.dialog.add( 'subsplus_assetDialog', function( editor ) {
	return {

		// Basic properties of the dialog window: title, minimum size.
		title: editor.lang['subsplus_asset.Title'],
		minWidth: 500,
		minHeight: 200,
		resizable: CKEDITOR.DIALOG_RESIZE_NONE,

		// Dialog window contents definition.
		contents: [
			{
				// Definition of the Document Settings dialog tab (page).
				id: 'tab-document-link',
				label: editor.lang['subsplus_asset.CurrDocLink'],

				// The tab contents.
				elements: [
					{
						// Insert html to label the radio buttons
						type: 'html',
						html: '<strong>'+ editor.lang['subsplus_asset.ChooseHTMLStrong'] + '</strong> ' + editor.lang['subsplus_asset.ChooseHTML']
					},
					{
						// Insert html to display radio buttons for the list of documents in asset folder
						type: 'html',
						onShow: function()
						{
							getDocumentRadioButtons( function( lstrHTML ) {

								var elements = this.CKEDITOR.dialog.getCurrent().getElement().getElementsByTag('div');

								var element = elements.getItem(4);

								element.setHtml(lstrHTML);
							});


						},
						html: '<div id="radio-buttons-div" style="max-height:200px; overflow:auto;"><strong>' + editor.lang['subsplus_asset.BeforeRadioButtonsLoad'] + '</strong></div>'

					},
					{
						// Insert html to label the link text.
						type: 'html',
						html: '<strong>' + editor.lang['subsplus_asset.LinkTextStrong'] + '</strong> ' + editor.lang['subsplus_asset.LinkText']
					},
					{
						// Input for the Link Text
						type: 'text',
						id: 'filetext'
					}
				]
			},
			{
				// Definition of the Upload dialog tab (page).
				id: 'tab-as-upload',
				label: editor.lang['subsplus_asset.UploadDoc'],

				// The tab contents.
				elements: [
					{
						//html to label input
						type: 'html',
						html: '<strong>' + editor.lang['subsplus_asset.UploadAFileStrong'] + '</strong> ' + editor.lang['subsplus_asset.UploadAFile']
					},
					{
						// File input field to upload document
						type: 'file',
						id: 'upload',
						size: 50
					},
					{
						//button to do a file upload
						type: 'fileButton',
						id: 'fileId',
						label: editor.lang.image.btnUpload,
						'for': [ 'tab-as-upload', 'upload' ],
						filebrowser: {
							action: 'QuickUpload',
							//this is the function that will execute after file upload on php side
							onSelect: function( fileUrl, errorMessage ) {

								//get current dialog
								var dialog = this.getDialog();

								//if error message exists, alert it and close dialog.
								if(errorMessage != '')
								{
									//display error message and close dialog
									alert(editor.lang['subsplus_asset.UploaderError'] + ' -> ' + errorMessage);

									dialog.hide();
								}

								//get radio-button-div that is in current dialog not in any other dialog
								var elements = dialog.getElement().getElementsByTag('div');

								//always 4 in dialog
								var element = elements.getItem(4);

								//set the element
								element.setHtml( '<strong>' + editor.lang['subsplus_asset.BeforeRadioButtonsChange'] + '</strong>' );

								getDocumentRadioButtons( function( lstrHTML ) {

									//get radio-button-div that is in current dialog not in any other dialog
									var elements = this.CKEDITOR.dialog.getCurrent().getElement().getElementsByTag('div');

									var element = elements.getItem(4);

									//set the element
									element.setHtml( lstrHTML );

								});

								//go to first tab
								dialog.selectPage('tab-document-link');

								// Do not call the built-in onSelect command
								return false;
							}
						}
					}
				]
			}
		],
		onCancel: function()
		{
			//uncheck any checked radio buttons
			jQuery('input[name="but"]:checked').attr('checked', false);
		},
		onOk: function ()
		{
			//get current dialog
			var dialog = this;

			//get the link text and token from radio button inputs
			var lstrLinkText = dialog.getValueOf( 'tab-document-link' , 'filetext');
			var lstrToken = jQuery('input[name="but"]:checked').val();

			//if no radio button was selected, send alert and have user select one
			if(typeof lstrToken == 'undefined')
			{
				alert(html_entity_decode(editor.lang['subsplus_asset.ValidateRadio']));

				return false;
			}

			//if the link text is not blank, put the link text in the appropriate spot in token
			if(lstrLinkText != '')
			{
				var lobjTemp = lstrToken.split(',');

				lobjTemp[lobjTemp.length - 1] = ' {' + lstrLinkText + '}}';

				lstrToken = lobjTemp.join(',');
			}

			//place the token in the editor
			editor.insertHtml( '&nbsp;<span class="subsplus_asset" style="background: #E488B6;" contentEditable=false>' + lstrToken + '</span>&nbsp;' );

			//uncheck any checked radio buttons
			jQuery('input[name="but"]:checked').attr('checked', false);
		}
	};
});
/**
 * The subplus_sub_spe dialog definition.
 *
 */

//get current script url so we can get path
var lobjScripts = document.getElementsByTagName("script");

for(var i = 0; i < lobjScripts.length; i++)
{
	if(lobjScripts[i].src.indexOf('subsplus_sub_spe.js') != -1)
	{
		var lstrPathToCkEditor = lobjScripts[i].src.substring(0,(lobjScripts[i].src.indexOf('/ckeditor/') + 10));

		break;
	};
}

function getStaffCheckboxList( callback )
{
	var lstrChosenEmails = '';

	if( CKEDITOR.dialog.getCurrent().element.getText() != '' )
	{
		var lobjToken = CKEDITOR.dialog.getCurrent().element.getText().split(/{{|}}|}[^{]*{/);

		lstrChosenEmails = lobjToken[2];
	}

	jQuery.ajax({
		url: lstrPathToCkEditor + 'plugins/subsplus_sub_spe/php/getStaffCheckboxes.php',
		data: {
			emails: lstrChosenEmails
		},
		type: 'post',
		success: function( response )
		{
			return callback( response );
		}
	});
}

function generateSSToken( lobjStaff )
{
	var lstrToken = '{{sss},{';

	lstrToken += lobjStaff.join(',');
	lstrToken += '}}';

	return lstrToken;
}

// Our dialog definition.
CKEDITOR.dialog.add( 'subsplus_sub_speDialog', function( editor ) {
	return {

		// Basic properties of the dialog window: title, minimum size.
		title: editor.lang['subsplus_sub_spe.title'],
		minWidth: 400,
		minHeight: 350,
		resizable: CKEDITOR.DIALOG_RESIZE_NONE,

		// Dialog window contents definition.
		contents: [
			{
				// Definition of the Document Settings dialog tab (page).
				id: 'tab-main',

				// The tab contents.
				elements: [
					{
						// Insert html to show instructions
						type: 'html',
						html: '1. <strong>'+ editor.lang['subsplus_sub_spe.InstructionsStrong1'] + '</strong>'
					},
					{
						//html to add radio list
						type: 'html',
						html: '<div id="subsplus-sub-spe-checkbox-list" class="clear-after-close" style="overflow: auto; max-height: 300px;"><div>Retrieving Staff...</div></div>',
						onShow: function()
						{
							getStaffCheckboxList( function( lstrHTML )
							{
								var elements = this.CKEDITOR.dialog.getCurrent().getElement().getElementsByTag('div');

								var element = elements.getItem(4);

								element.setHtml(lstrHTML);
							});
						}
					}
				]
			}
		],
		onHide: function()
		{
			//remove the div contents from dialog box when closed
			jQuery('div.clear-after-close').html('<div>Retrieving Staff...</div>');

			//uncheck any checked boxes
			jQuery('input[type="checkbox"].clear-after-close').attr('checked', false);
		},
		onShow: function() {
			var selection = editor.getSelection(),
			element = selection.getStartElement();
			if ( element )
				element = element.getAscendant( 'span', true );

			if ( !element || element.getName() != 'span' || element.data( 'cke-realelement' ) ) {
				element = editor.document.createElement( 'span' );
				element.addClass('subsplus_sub_spe');
				element.setStyle('background', '#E488B6');
				element.setAttribute('contentEditable', 'false');
				this.insertMode = true;
			}
			else
				this.insertMode = false;

			this.element = element;

			if ( !this.insertMode )
				this.setupContent( this.element );
		},
		onOk: function()
		{
			//get current dialog
			var dialog = this;

			if( $('input[name="selected_staff"]:checked').length > 0 )
			{
				var lobjStaff = [];

				$('input[name="selected_staff"]:checked').each(function(index, checkbox)
				{
					lobjStaff.push( $(checkbox).val() );
				});

				var token = generateSSToken( lobjStaff );

				if( token )
					this.element.setText( token );
				else
					return false;

				if ( this.insertMode )
					editor.insertElement( this.element );
			}
		}
	};
});
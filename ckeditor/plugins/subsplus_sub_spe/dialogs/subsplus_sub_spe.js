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
	jQuery.ajax({
		url: lstrPathToCkEditor + 'plugins/subsplus_sub_spe/php/getStaffCheckboxes.php',
		success: function( response )
		{
			return callback( response );
		}
	});
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

				jQuery.ajax({
					url: lstrPathToCkEditor + 'plugins/subsplus_sub_spe/php/getStaffToken.php',
					data: {
						'staff_list' : lobjStaff
					},
					type: 'post',
					success: function( response )
					{
						editor.insertHtml(response);
					}
				});
			}

		}
	};
});
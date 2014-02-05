/**
 * The subsplus_resource dialog definition.
 *
 */

var focusID = '';

function getResourceRadioList( lstrSearch, callback )
{
	jQuery.ajax({
		type: 'POST',
		url: lstrPathToCkEditor + 'plugins/subsplus_resource/php/getResourceRadioListSearch.php',
		data: {'search_terms' : lstrSearch},
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
	if(lobjScripts[i].src.indexOf('subsplus_resource.js') != -1)
	{
		var lstrPathToCkEditor = lobjScripts[i].src.substring(0,(lobjScripts[i].src.indexOf('/ckeditor/') + 10));

		break;
	};
}

// Our dialog definition.
CKEDITOR.dialog.add( 'subsplus_resourceDialog', function( editor ) {
	return {

		// Basic properties of the dialog window: title, minimum size.
		title: editor.lang['subsplus_resource.title'],
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
						html: '1. <strong>'+ editor.lang['subsplus_resource.InstructionsStrong1'] + '</strong> ' + editor.lang['subsplus_resource.Instructions1']
					},
					{
						// Insert html to show instructions 2
						type: 'html',
						html: '2. <strong>'+ editor.lang['subsplus_resource.InstructionsStrong2'] + '</strong> ' + editor.lang['subsplus_resource.Instructions2']
					},
					{
						// Input for the search keywoRD
						type: 'text',
						id: 'searchtext',
						size: 10,
						onLoad: function()
						{
							var element = this.getInputElement().getId();

							jQuery( '#' + element ).focus(function()
							{
								focusID = element;
							})

							jQuery( '#' + element ).focusout(function()
							{
								focusID = '';
							})
						}
					},
					{
						//button to submit search with keyword
						type: 'button',
						id: 'resource-search-button',
						label: editor.lang['subsplus_resource.Button'],
						title: editor.lang['subsplus_resource.Button'],
						onClick: function()
						{
							var dialog = this.getDialog();

							var lstrSearch = dialog.getValueOf( 'tab-main' , 'searchtext');

							getResourceRadioList( lstrSearch, function( lstrHTML )
							{
								var elements = this.CKEDITOR.dialog.getCurrent().getElement().getElementsByTag('div');

								var element = elements.getItem(7);

								element.setHtml(lstrHTML);
							})
						}
					},
					{
						//checkbox for include icons
						type: 'checkbox',
						id: 'check-icons',
						label: html_entity_decode(editor.lang['subsplus_resource.IconsCheckbox']),
						className: 'clear-after-close'
					},
					{
						//checkbox for include description
						type: 'checkbox',
						id: 'check-desc',
						label: html_entity_decode(editor.lang['subsplus_resource.DescCheckbox']),
						className: 'clear-after-close'
					},
					{
						//html to add radio list
						type: 'html',
						html: '<div id="resource-radio-list" class="clear-after-close" style="overflow: auto; max-height: 150px;"></div>'
					}
				]
			}
		],
		onHide: function()
		{
			//remove the div contents from dialog box when closed
			jQuery('div.clear-after-close').html('');

			//uncheck any checked boxes
			jQuery('input[type="checkbox"].clear-after-close').attr('checked', false);
		},
		onOk: function()
		{
			//get current dialog
			var dialog = this;

			//if focus is recorded, search for resource
			if( focusID != '')
			{
				//get the search keywords
				var lstrSearch = dialog.getValueOf( 'tab-main' , 'searchtext');

				//get resource radio buttons list
				getResourceRadioList( lstrSearch, function( lstrHTML )
				{
					var elements = this.CKEDITOR.dialog.getCurrent().getElement().getElementsByTag('div');

					var element = elements.getItem(7);

					//set html for radio buttons list
					element.setHtml(lstrHTML);
				})

				return false;
			}else
			{
				//get the token chosen and if check boxes checked
				var lstrToken = jQuery('input[name="but"]:checked').val();
				var lboolIcons = dialog.getValueOf( 'tab-main' , 'check-icons');
				var lboolDesc = dialog.getValueOf( 'tab-main' , 'check-desc');

				//if no radio button chosen, alert error
				if(typeof lstrToken == 'undefined')
				{
					alert(html_entity_decode(editor.lang['subsplus_resource.ValidateRadio']));

					return false;
				}

				//finish token depending on check boxes
				lstrToken = lstrToken + ',{';

				//1 if icons, 0 is no icons
				if(lboolIcons)
				{
					lstrToken = lstrToken + '1';
				}else
				{
					lstrToken = lstrToken + '0';
				}

				//1 if description, 0 is no description
				if(lboolDesc)
				{
					lstrToken = lstrToken + '1}}';
				}else
				{
					lstrToken = lstrToken + '0}}';
				}

				//place the token in the editor
				editor.insertHtml( lstrToken );
			}
		}
	};
});
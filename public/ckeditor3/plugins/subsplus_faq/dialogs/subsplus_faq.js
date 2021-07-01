/**
 * The subsplus_faq dialog definition.
 *
 */

function getFaqsCheckboxList( lstrBrowse , callback )
{
	jQuery.ajax({
		url: lstrPathToCkEditor + 'plugins/subsplus_faq/php/getFaqCheckboxes.php',
		data: {'browse' : lstrBrowse},
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
	if(lobjScripts[i].src.indexOf('subsplus_faq.js') != -1)
	{
		var lstrPathToCkEditor = lobjScripts[i].src.substring(0,(lobjScripts[i].src.indexOf('/ckeditor/') + 10));

		break;
	};
}

// Our dialog definition.
CKEDITOR.dialog.add( 'subsplus_faqDialog', function( editor ) {
	return {

		// Basic properties of the dialog window: title, minimum size.
		title: editor.lang['subsplus_faq.title'],
		minWidth: 600,
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
						html: '<span style="color: blue; cursor: pointer; text-decoration:underline;">'+ editor.lang['subsplus_faq.AllLink'] + '</span>',
						onLoad: function()
						{
							var element = this.getInputElement().getId();

							jQuery('#' + element).click(function()
							{
								getFaqsCheckboxList( 'all' , function( lstrHTML )
								{
									var elements = this.CKEDITOR.dialog.getCurrent().getElement().getElementsByTag('div');

									var element = elements.getItem(4);

									element.setHtml(lstrHTML);
								});
							});
						}
					},				
					{
						// Insert html to show instructions
						type: 'html',
						html: '<span style="color: blue; cursor: pointer; text-decoration:underline;">'+ editor.lang['subsplus_faq.BySubjectLink'] + '</span>',
						onLoad: function()
						{
							var element = this.getInputElement().getId();

							jQuery('#' + element).click(function()
							{
								getFaqsCheckboxList( 'subject' , function( lstrHTML )
								{
									var elements = this.CKEDITOR.dialog.getCurrent().getElement().getElementsByTag('div');

									var element = elements.getItem(4);

									element.setHtml(lstrHTML);
								});
							});
						}
					},
					{
						// Insert html to show instructions
						type: 'html',
						html: '<span style="color: blue; cursor: pointer; text-decoration:underline;">'+ editor.lang['subsplus_faq.ByCollectionLink'] + '</span>',
						onLoad: function()
						{
							var element = this.getInputElement().getId();

							jQuery('#' + element).click(function()
							{
								getFaqsCheckboxList( 'collection' , function( lstrHTML )
								{
									var elements = this.CKEDITOR.dialog.getCurrent().getElement().getElementsByTag('div');

									var element = elements.getItem(4);

									element.setHtml(lstrHTML);
								});
							});
						}
					},
					{
						//html to add radio list
						type: 'html',
						html: '<div id="faq-checkbox-list" class="clear-after-close" style="overflow: auto; max-height: 200px;"><div>Retrieving FAQs for current guide...</div></div>',
						onShow: function()
						{
							getFaqsCheckboxList( 'no' , function( lstrHTML )
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
			jQuery('div.clear-after-close').html('');
		},
		onOk: function()
		{
			var lobjIds = [];

			//get all ids of checked checkboxes
			jQuery('input[name="but"]:checked').each(function ()
			{
       			lobjIds.push(this.checked ? jQuery(this).val() : "");
  			});

			//if no checkbox button chosen, alert error
			if(lobjIds.length == 0)
			{
				alert(html_entity_decode(editor.lang['subsplus_faq.ValidateCheckbox']));

				return false;
			}

			var lstrToken = '{{faq},{';

			jQuery.each( lobjIds , function( index , value)
			{
				lstrToken = lstrToken + value;

				if(index != (lobjIds.length - 1) )
				{
					lstrToken = lstrToken + ',';
				}
			});

			lstrToken = lstrToken + '}}';

			//place the token in the editor
			editor.insertHtml( '&nbsp;<span class="subsplus_faq" style="background: #E488B6;" contentEditable=false>' + lstrToken + '</span>&nbsp;' );
		}
	};
});
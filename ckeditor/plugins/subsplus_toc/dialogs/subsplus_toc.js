/**
 * The subplus_toc dialog definition.
 *
 */

//get current script url so we can get path
var lobjScripts = document.getElementsByTagName("script");

for(var i = 0; i < lobjScripts.length; i++)
{
	if(lobjScripts[i].src.indexOf('subsplus_toc.js') != -1)
	{
		var lstrPathToCkEditor = lobjScripts[i].src.substring(0,(lobjScripts[i].src.indexOf('/ckeditor/') + 10));

		break;
	};
}

function getLinkCheckboxList( callback )
{
	var lstrChosenPluslets = '';

	if( CKEDITOR.dialog.getCurrent().element.getText() != '' )
	{
		var lobjToken = CKEDITOR.dialog.getCurrent().element.getText().split(/{{|}}|}[^{]*{/);

		lstrChosenPluslets = lobjToken[2];
	}

	jQuery.ajax({
		url: lstrPathToCkEditor + 'plugins/subsplus_toc/php/getLinkCheckboxes.php',
		data: {
			pluslets: lstrChosenPluslets
		},
		type: 'post',
		success: function( response )
		{
			return callback( response );
		}
	});
}

function generateTOCToken( lobjPluslets )
{
	var lstrToken = '{{toc},{';

	lstrToken += lobjPluslets.join(',');
	lstrToken += '}}';

	return lstrToken;
}

// Our dialog definition.
CKEDITOR.dialog.add( 'subsplus_tocDialog', function( editor ) {
	return {

		// Basic properties of the dialog window: title, minimum size.
		title: editor.lang['subsplus_toc.title'],
		minWidth: 700,
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
						html: '1. <strong>'+ editor.lang['subsplus_toc.InstructionsStrong1'] + '</strong>'
					},
					{
						//html to add radio list
						type: 'html',
						id: 'link-checkboxes',
						html: '<div id="subsplus-toc-checkbox-list" class="clear-after-close"><div>Retrieving links...</div></div>',
						onShow: function()
						{
							getLinkCheckboxList( function( lstrHTML )
							{
								var elements = this.CKEDITOR.dialog.getCurrent().getElement().getElementsByTag('div');

								var element = elements.getItem(4);

								element.setHtml(lstrHTML);

								$('a').blur();
							});
						}
					}
				]
			}
		],
		onHide: function()
		{
			//remove the div contents from dialog box when closed
			jQuery('div.clear-after-close').html('<div>Retrieving links...</div>');

			//uncheck any checked boxes
			jQuery('input[type="checkbox"].clear-after-close').attr('checked', false);

			//go back to original tab
			jQuery('#tabs').tabs('select', this.tab);

			setTimeout(function(){
				$(window).scrollTop($(CKEDITOR.document.getActive().$).offset().top);
			}, 100);
		},
		onShow: function() {
			var selection = editor.getSelection(),
			element = selection.getStartElement();
			if ( element )
				element = element.getAscendant( 'span', true );

			if ( !element || element.getName() != 'span' || element.data( 'cke-realelement' ) ) {
				element = editor.document.createElement( 'span' );
				element.addClass('subsplus_toc');
				element.setStyle('background', '#E488B6');
				element.setAttribute('contentEditable', 'false');
				this.insertMode = true;
			}
			else
				this.insertMode = false;

			this.element = element;

			if ( !this.insertMode )
				this.setupContent( this.element );

			//record current tab
			this.tab = $("#tabs").tabs('option', 'selected');
		},
		onOk: function()
		{
			//get current dialog
			var dialog = this;

			if( $('div#subsplus-toc-checkbox-list').find('input[name^="checkbox-"]:checked').length > 0 )
			{
				jQuery('#tabs').tabs('select', this.tab);

				var lobjPluslets = [];

				$('div#subsplus-toc-checkbox-list').find('input[name^="checkbox-"]:checked').each(function(index, checkbox)
				{
					lobjPluslets.push( $(checkbox).val() );
				});

				var token = generateTOCToken( lobjPluslets );

				if( token )
					this.element.setText( token );
				else
					return false;

				editor.focus();

				if ( this.insertMode )
					editor.insertElement( this.element );

				setTimeout(function(){
					$(window).scrollTop($(CKEDITOR.document.getActive().$).offset().top);
				}, 100);
			}
		}
	};
});
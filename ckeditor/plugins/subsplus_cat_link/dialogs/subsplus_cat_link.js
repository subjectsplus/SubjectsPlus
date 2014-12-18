/**
 * The subsplus_cat_link dialog definition.
 *
 */

function html_entity_decode(str)
{
	var ta=document.createElement("textarea");
	ta.innerHTML=str.replace(/</g,"<").replace(/>/g,">");
	return ta.value;
}

// Our dialog definition.
CKEDITOR.dialog.add( 'subsplus_cat_linkDialog', function( editor ) {
	return {

		// Basic properties of the dialog window: title, minimum size.
		title: html_entity_decode(editor['lang']['subsplus_cat_link.Title']),
		minWidth: 700,
		minHeight: 200,
		resizable: CKEDITOR.DIALOG_RESIZE_NONE,

		//buttons
		buttons: [CKEDITOR.dialog.cancelButton],

		// Dialog window contents definition.
		contents: [
			{
				// Definition of the Link to Subject Heading Settings dialog tab (page).
				id: 'tab-sub-head',
				label: editor.lang['subsplus_cat_link.Tab1Label'],

				// The tab contents.
				elements: [
					{
						// Text input field for the Prefix text.
						type: 'text',
						id: 'prefix',
						label: editor.lang['subsplus_cat_link.Tab1Prefix']
					},
					{
						// Text input field for the Subject Heading title (explanation).
						type: 'text',
						id: 'terms',
						label: editor.lang['subsplus_cat_link.Tab1Subject']
					},
					{
						// html hint (explanation).
						type: 'html',
						html: '<p><strong>' + editor.lang['subsplus_cat_link.Tab1HtmlStrong'] + '</strong> ' + editor.lang['subsplus_cat_link.Tab1Html'] + '</p><blockquote>PN 1997 - Screenplays - see <span class="linkie">Motion picture plays</span><br>TR 899 <span class="linkie">Motion Pictures--Editing<span></span></span></blockquote>'
					},
					{
						// html break
						type: 'html',
						html: '<div></div>'
					},
					{
						//button to add link to subject heading
						type: 'button',
						id: 'sub-head-button',
						label: editor.lang['subsplus_cat_link.Tab1Button'],
						title: editor.lang['subsplus_cat_link.Tab1Button'],
						className: 'cke_dialog_ui_button cke_dialog_ui_button_ok',
						onClick: function()
						{
							var dialog = this._.dialog;

							var lstrPrefix = dialog.getValueOf( 'tab-sub-head', 'prefix' );
							var lstrSubject = dialog.getValueOf( 'tab-sub-head', 'terms' );

							//validate the input
							if(lstrSubject == '')
							{
								alert(html_entity_decode(editor.lang['subsplus_cat_link.Tab1ValidateAlert']));
							}else{
								//create token
								var lstrToken = "{{cat}, {" + lstrSubject + "},{" + lstrPrefix + "},{subject}}";

								// Finally, inserts the element at the editor caret position.
								editor.insertHtml( '&nbsp;<span class="subsplus_cat_link" style="background: #E488B6;" contentEditable=false>' + lstrToken + '</span>&nbsp;' );
								//close dialog box
								CKEDITOR.dialog.getCurrent().hide()
							}
						}
					}
				]
			},
			{
				// Definition of the Link to Keyword Search Results Settings dialog tab (page).
				id: 'tab-key-sea',
				label: editor.lang['subsplus_cat_link.Tab2Label'],

				// The tab contents.
				elements: [
				{
					// Text input field for the keywords text.
					type: 'text',
					id: 'keywords',
					label: editor.lang['subsplus_cat_link.Tab2Keywords']
				},
				{
					// Text input field for the html hint (explanation).
					type: 'html',
					html: '<span style="font-size: 10px;">E.g., ' + editor.lang['subsplus_cat_link.Tab2EG'] + '</span>'
				},
				{
					// html break
					type: 'html',
					html: '<div></div>'
				},
				{
					//button to add link to subject heading
					type: 'button',
					id: 'key-sea-button',
					label: editor.lang['subsplus_cat_link.Tab2Button'],
					title: editor.lang['subsplus_cat_link.Tab2Button'],
					className: 'cke_dialog_ui_button cke_dialog_ui_button_ok',
					onClick: function()
					{
						var dialog = this._.dialog;

						var lstrKeyword = dialog.getValueOf( 'tab-key-sea', 'keywords' );

						//validate the input
						if(lstrKeyword == '')
						{
							alert(html_entity_decode(editor.lang['subsplus_cat_link.Tab2ValidateAlert']));
						}else{
							//create token
							var lstrToken = "{{cat}, {" + lstrKeyword + "},{" + lstrKeyword + "},{keywords}}";

							// Finally, inserts the element at the editor caret position.
							editor.insertHtml( '&nbsp;<span class="subsplus_cat_link" style="background: #E488B6;" contentEditable=false>' + lstrToken + '</span>&nbsp;' );
							//close dialog box
							CKEDITOR.dialog.getCurrent().hide()
						}
					}
				}
				]
			},
			{
				// Definition of the Link to Call Number (DVDs and Reserves only in Voyager) Settings dialog tab (page).
				id: 'tab-call-num',
				label: editor.lang['subsplus_cat_link.Tab3Label'],

				// The tab contents.
				elements: [
				{
					// Text input field for the Call Number text.
					type: 'text',
					id: 'call_number',
					label: editor.lang['subsplus_cat_link.Tab3CallNum']
				},
				{
					// Text input field for the Title or link Text text.
					type: 'text',
					id: 'cn_label',
					label: editor.lang['subsplus_cat_link.Tab3Text']
				},
				{
					// html break
					type: 'html',
					html: '<div></div>'
				},
				{
					//button to add link to subject heading
					type: 'button',
					id: 'key-sea-button',
					label: editor.lang['subsplus_cat_link.Tab3Button'],
					title: editor.lang['subsplus_cat_link.Tab3Button'],
					className: 'cke_dialog_ui_button cke_dialog_ui_button_ok',
					onClick: function()
					{
						var dialog = this._.dialog;

						var lstrCallNum = dialog.getValueOf( 'tab-call-num', 'call_number' );
						var lstrLabel = dialog.getValueOf( 'tab-call-num', 'cn_label' );

						//validate the input
						if(lstrCallNum == '' && lstrLabel == '')
						{
							alert(html_entity_decode(editor.lang['subsplus_cat_link.Tab3ValidateCallLabel']));
						}else if(lstrCallNum == '')
						{
							alert(html_entity_decode(editor.lang['subsplus_cat_link.Tab3ValidateCall']));
						}else if(lstrLabel == '')
						{
							alert(html_entity_decode(editor.lang['subsplus_cat_link.Tab3ValidateLabel']));
						}else{
							//create token
							var lstrToken = "{{cat}, {" + lstrCallNum + "},{" + lstrLabel + "},{call_num}}";

							// Finally, inserts the element at the editor caret position.
							editor.insertHtml( '&nbsp;<span class="subsplus_cat_link" style="background: #E488B6;" contentEditable=false>' + lstrToken + '</span>&nbsp;' );
							//close dialog box
							CKEDITOR.dialog.getCurrent().hide()
						}
					}
				}
				]
			},
			{
				// Definition of the Link to Bib Record dialog tab (page).
				id: 'tab-bib-num',
				label: editor.lang['subsplus_cat_link.Tab4Label'],

				// The tab contents.
				elements: [
				{
					// Text input field for the bib record text.
					type: 'text',
					id: 'bib',
					label: editor.lang['subsplus_cat_link.Tab4Text']
				},
				{
					// Text input field for the html hint (explanation).
					type: 'html',
					html: '<span style="font-size: 10px;">E.g., ' + editor.lang['subsplus_cat_link.Tab4EG'] + '</span>'
				},
				{
					// Text input field for the Title or link Text text.
					type: 'text',
					id: 'bib_label',
					label: editor.lang['subsplus_cat_link.Tab4Title']
				},
				{
					// html break
					type: 'html',
					html: '<div></div>'
				},
				{
					//button to add link to subject heading
					type: 'button',
					id: 'bib-num-button',
					label: editor.lang['subsplus_cat_link.Tab4Button'],
					title: editor.lang['subsplus_cat_link.Tab4Button'],
					className: 'cke_dialog_ui_button cke_dialog_ui_button_ok',
					onClick: function()
					{
						var dialog = this._.dialog;

						var lstrBib = dialog.getValueOf( 'tab-bib-num', 'bib' );
						var lstrLabel = dialog.getValueOf( 'tab-bib-num', 'bib_label' );

						//validate the input
						if(lstrBib == '' && lstrLabel == '')
						{
							alert(html_entity_decode(editor.lang['subsplus_cat_link.Tab4ValidateAlertBoth']));
						}else if(lstrBib == '')
						{
							alert(html_entity_decode(editor.lang['subsplus_cat_link.Tab4ValidateAlertBib']));
						}else if(lstrLabel == '')
						{
							alert(html_entity_decode(editor.lang['subsplus_cat_link.Tab4ValidateAlertTitle']));
						}else{
							//create token
							var lstrToken = "{{cat}, {" + lstrBib + "},{" + lstrLabel + "},{bib}}";

							// Finally, inserts the element at the editor caret position.
							editor.insertHtml( '&nbsp;<span class="subsplus_cat_link" style="background: #E488B6;" contentEditable=false>' + lstrToken + '</span>&nbsp;' );
							//close dialog box
							CKEDITOR.dialog.getCurrent().hide()
						}
					}
				}
				]
			}
		]
	};
});
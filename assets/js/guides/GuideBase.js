/**
 * @author little9 (Jamie Little), based on guide.php
 */


function getGuideBase() {
	
	var GuideBase = { 
			settings : {
				globalHeader : $("#header, #subnavcontainer"),
				sections : $('div[id^="section_"]'),
				newBox : $('#newbox'),
				sliderOptions : $("#slider_options"),
				layoutBox : $("#layoutbox"),
				slider : jQuery( "#slider" ),
				extra :  jQuery( "#extra" ),
				mainColumnWidth : jQuery( "#main_col_width" ),
				saveLayout : jQuery("#save_layout"),
				tabs : $('#tabs'),
				tabTitle :  $( "#tab_title" ),
				tabContent : $( "#tab_content" ),
				tabExteralDataLink : $('li[data-external-link]'),
				autoCompletebox : $('.find-guide-input')
				
			},
			strings : {
				   tabTemplate :  "<li class=\"dropspotty\"><a href='#{href}'>#{label}</a><span class='alter_tab' role='presentation'><i class=\"fa fa-cog\"></i></span></li>",
				   
			}, 
			bindUiActions : function() {
				
			},
			init : function() {
				 // Hides the global nav on load
				GuideBase.settings.globalHeader.hide();
				GuideBase.layoutSections();
				GuideBase.settings.newBox.hoverIntent(GuideBase.hoverIntentConfig);
				GuideBase.hoverIntentLayoutBox();
				GuideBase.setupTabs();

			},
			layoutSections : function () {
				GuideBase.settings.sections.each(function()
				 {
				       //section id
				       var sec_id = $(this).attr('id').split('section_')[1];
				       var lobjLayout = $('div#section_' + sec_id).attr('data-layout').split('-');

				       var lw = parseInt(lobjLayout[0]) * 7;
				       var mw = parseInt(lobjLayout[1]) * 7;
				       var sw = parseInt(lobjLayout[2]) * 7;

				       console.log(lw, mw, sw);
				       
				       try {
					 reLayout(sec_id, lw, mw, sw);
				       } catch (e) {



				       }


				     });
			},
			hoverIntentConfig : {
				 interval: 50,
			     sensitivity: 4,
			     timeout: 500
			},
			hoverIntentSliderConfig : {
				     interval: 50,
				     sensitivity: 4,
				     over: this.addSlider,
				     timeout: 500,
				     out: this.removeSlider
	        },
	        hoverIntentLayoutBox : function() {
	        	GuideBase.settings.layoutBox.hoverIntent(this.hoverIntentSliderConfig);
	        },
	        addSlider : function() {
	        	GuideBase.settings.sliderOptions.show();
	        },
	        removeSlider : function() {
	        	GuideBase.settings.sliderOptions.hide();

	        	
	        },
	        moveToHash : function() {
	        		
	        	   if( window.location.hash )
	        	   {
	        	     setTimeout(function()
	        	 		{
	        		 if( window.location.hash.split('-').length == 3  )
	        		 {
	        		   var tab_id = window.location.hash.split('-')[1];
	        		   var box_id = window.location.hash.split('-')[2];
	        		   var selected_box = ".pluslet-" + box_id;

	        		   $('#tabs').tabs('select', tab_id);

	        		   $('html, body').animate({scrollTop:jQuery('a[name="box-' + box_id + '"]').offset().top}, 'slow');

	        		   $(selected_box).effect("pulsate", {
	        		     times:1
	        		   }, 2000);
	        		 }
	        	       }, 500);
	        	   }
	        },
	        setupTabs : function() {
	        	
	        		   var tabTitle = $( "#tab_title" ),
	        		   tabContent = $( "#tab_content" ),
	        		   tabTemplate = "<li class=\"dropspotty\"><a href='#{href}'>#{label}</a><span class='alter_tab' role='presentation'><i class=\"fa fa-cog\"></i></span></li>",
	        		   tabCounter = $('#tabs').data().tabCount;
	        		   var tabs = $( "#tabs" ).tabs();
	        		   var dialog = $( "#dialog" ).dialog({
	   	        	     autoOpen: false,
	   	        	     modal: true,
	   	        	     buttons: {
	   	        	       Add: function() {
	   	        	         addTab();
	   	        	         $( this ).dialog( "close" );
	   	        	       },
	   	        	       Cancel: function() {
	   	        	         $( this ).dialog( "close" );
	   	        	       }
	   	        	     },
	   	        	     open: function() {
	   	        	       $(this).find('input[name="tab_external_link"]').hide();
	   	        	       $(this).find('input[name="tab_external_link"]').prev().hide();
	   	        	       if( tabCounter > 0 )
	   	        	       {
	   	        	    	 $(this).find('input[name="tab_external_link"]').show();
	   	        	    	 $(this).find('input[name="tab_external_link"]').prev().show();
	   	        	       }
	   	        	     },
	   	        	     close: function() {
	   	        	       form[ 0 ].reset();
	   	        	     }
	   	        	   });
	        		   
	        		   	
	        		   //add click event for external url tabs
	        		   jQuery('li[data-external-link]').each(function()
	        							 {
	        		       if($(this).attr('data-external-link') != "")
	        		       {
	        			 jQuery(this).children('a[href^="#tabs-"]').on('click', function(evt)
	        								       {
	        			     window.open($(this).parent('li').attr('data-external-link'), '_blank');
	        			     evt.stopImmediatePropagation();
	        			   });

	        			 jQuery(this).children('a[href^="#tabs-"]').each(function() {
	        			   var elementData = jQuery._data(this),
	        			   events = elementData.events;

	        			   var onClickHandlers = events['click'];

	        			   // Only one handler. Nothing to change.
	        						 if (onClickHandlers.length == 1) {
	        			     return;
	        			   }

	        			   onClickHandlers.splice(0, 0, onClickHandlers.pop());
	        			 });
	        		       }
	        		     });

	        		   //preselect first
	        		   tabs.tabs('select', 0);
	        		   
	        		   // edit icon: removing or renaming tab on click
	        		   tabs.delegate( "span.alter_tab", "click", function(lobjClicked) {
	        		     var List = $(this).parent().children("a");
	        		     var Tab = List[0];
	        		     window.lastClickedTab = $(Tab).attr("href");
	        		     $('#dialog_edit').dialog("open");
	        		   });
	        		   
	        		   // addTab button: just opens the dialog
	        		   $( "#add_tab" ).button().click(function() {
	        		     dialog.dialog( "open" );
	        		   });

	        		   
	        		// addTab form: calls addTab function on submit and closes the dialog
	        		   var form = dialog.find( "form" ).submit(function( event ) {
	        		     addTab();
	        		     dialog.dialog( "close" );
	        		     event.preventDefault();
	        		   });

	        		   // actual addTab function: adds new tab using the input from the form above
	        		   function addTab() {
	        		     var label = tabTitle.val() || "Tab " + tabCounter,
	        		     external_link = $('input#tab_external_link').val(),
	        		     id = "tabs-" + tabCounter,
	        		     li = $( tabTemplate.replace( /#\{href\}/g, "#" + id ).replace( /#\{label\}/g, label ) ),
	        		     tabContentHtml = tabContent.val() || "Tab " + tabCounter + " content.";
	        		     $(li).attr('data-external-link', external_link);
	        		     $(li).attr('data-visibility', 1);
	        		     tabs.find( ".ui-tabs-nav" ).append( li );

	        		     
	        			  //make tabs sortable
	        			  jQuery(function() {
	        			    $(tabs).find( ".ui-tabs-nav" ).sortable({
	        		              axis: "x",
	        		              stop: function(event, ui) {
	        				if(jQuery(ui.item).attr("id") == 'add_tab' || jQuery(ui.item).parent().children(':first').attr("id") != 'add_tab' || jQuery(ui.item).attr('data-external-link') != '')
	        		                $(tabs).find( ".ui-tabs-nav" ).sortable("cancel");
	        				else
	        				{
	        				  // $(tabs).tabs( "refresh" );
	        		            	  $(tabs).tabs("destroy");
	        		            	  $(tabs).tabs();
	        		            	  $(tabs).tabs('select', 0);
	        		            	  jQuery("#response").hide();
	        		                  jQuery("#save_guide").fadeIn();
	        		                  //jQuery("#save_template").fadeIn();
	        				}
	        		              }
	        			    });
	        			  });
	        		     
	        		     
	        		     var slim = jQuery.ajax
	        		     ({
	        		       url: "helpers/section_data.php",
	        		       type: "POST",
	        		       data: { action : 'create' },
	        		       dataType: "html",
	        		       success: function(html) {
	        		         tabs.tabs("destroy");

	        		         tabs.append( "<div id='" + id + "' class=\"sptab\">" + html
	        		                      + "</div>" );

	        		         jQuery("#response").hide();
	        		         jQuery("#save_guide").fadeIn();
	        		         //jQuery("#save_template").fadeIn();

	        		         tabs.tabs();

	        		         if( external_link == '' )
	        		         {
	        		           tabs.tabs('select', tabCounter);
	        		         }else
	        		         {
	        		           tabs.tabs('select', 0);
	        		         }

	        		         if( $(li).attr('data-external-link') != '' )
	        		         {
	        		           jQuery(li).children('a[href^="#tabs-"]').on('click', function(evt)
	        		         					       {
	        			       window.open($(this).parent('li').attr('data-external-link'), '_blank');
	        			       evt.stopImmediatePropagation();
	        			     });
	        		         }

	        		         jQuery(li).children('a[href^="#tabs-"]').each(function() {
	        		           var elementData = jQuery._data(this),
	        		           events = elementData.events;

	        		           var onClickHandlers = events['click'];

	        		           // Only one handler. Nothing to change.
	        		           if (onClickHandlers.length == 1) {
	        		             return;
	        		           }

	        		           onClickHandlers.splice(0, 0, onClickHandlers.pop());
	        		         });

	        		         tabCounter++;
	        		       }
	        		     });
	        		   
	        		     //setup dialog to edit tab
	        		     $( "#dialog_edit" ).dialog({
	        		       autoOpen: false,
	        		       modal: true,
	        		       width: "auto",
	        		       height: "auto",
	        		       buttons: {
	        		         "Save": function() {
	        		           var id = window.lastClickedTab.replace("#tabs-", "");

	        		           $( 'a[href="#tabs-' + id + '"]' ).text( $('input[name="rename_tab_title"]').val() );
	        		           $( 'a[href="#tabs-' + id + '"]' ).parent('li').attr( 'data-visibility', $('select[name="visibility"]').val() );

	        		           if( $( 'a[href="#tabs-' + id + '"]' ).parent('li').attr( 'data-external-link') != '' )
	        		           {
	        		             $( 'a[href="#tabs-' + id + '"]' ).each(function() {
	        		               var elementData = jQuery._data(this),
	        		               events = elementData.events;

	        		               var onClickHandlers = events['click'];

	        		               // Only one handler. Nothing to change.
	        		               if (onClickHandlers.length == 1) {
	        		                 return;
	        		               }

	        		               onClickHandlers.splice(0, 1);
	        		             });
	        		           }

	        		           $( 'a[href="#tabs-' + id + '"]' ).parent('li').attr( 'data-external-link', $('input[name="tab_external_url"]').val() );

	        		           if( $('input[name="tab_external_url"]').val() != '')
	        		           {
	        		             $( 'a[href="#tabs-' + id + '"]' ).on('click', function(evt)
	        		                					{
	        		                 window.open($(this).parent('li').attr('data-external-link'), '_blank');
	        		                 evt.stopImmediatePropagation();
	        		               });

	        		             $( 'a[href="#tabs-' + id + '"]' ).each(function() {
	        		               var elementData = jQuery._data(this),
	        		               events = elementData.events;

	        		               var onClickHandlers = events['click'];

	        		               // Only one handler. Nothing to change.
	        		               if (onClickHandlers.length == 1) {
	        		                 return;
	        		               }

	        		               onClickHandlers.splice(0, 0, onClickHandlers.pop());
	        		             });
	        		           }

	        		  	 //add/remove class based on tab visibility
	        		         	 if( $('select[name="visibility"]').val() == 1 )
	        		         	 {
	        		         	   $( 'a[href="#tabs-' + id + '"]' ).parent('li').removeClass('hidden_tab');
	        		         	 }else
	        		         	 {
	        		  	   $( 'a[href="#tabs-' + id + '"]' ).parent('li').addClass('hidden_tab');
	        		         	 }

	        		           $( this ).dialog( "close" );
	        		         	 $("#response").hide();
	        		           $('#save_guide').fadeIn();
	        		           //$('#save_template').fadeIn();
	        		         },
	        		         "Delete" : function() {
	        		           var id = window.lastClickedTab.replace("#tabs-", "");

	        		           $( 'a[href="#tabs-' + id + '"]' ).parent().remove();
	        		           $( 'div#tabs-' + id ).remove();
	        		           tabs.tabs("destroy");
	        		           tabs.tabs();
	        		           tabCounter--;
	        		           $( this ).dialog( "close" );
	        		     	 $("#response").hide();
	        		           $('#save_guide').fadeIn();
	        		           //$('#save_template').fadeIn();
	        		         },
	        		         Cancel: function() {
	        		           $( this ).dialog( "close" );
	        		         }
	        		       },
	        		       open: function(event, ui) {
	        		         var id = window.lastClickedTab.replace("#tabs-", "");
	        		         $(this).find('input[name="rename_tab_title"]').val($( 'a[href="#tabs-' + id + '"]' ).text());
	        		         $(this).find('select[name="visibility"]').val($( 'a[href="#tabs-' + id + '"]' ).parent('li').attr('data-visibility'));

	        		         //external url add text input unless first tab
	        		         $(this).find('input[name="tab_external_url"]').val('');
	        		         $(this).find('input[name="tab_external_url"]').hide();
	        		         $(this).find('input[name="tab_external_url"]').prev().hide();
	        		         $(this).find('input[name="tab_external_url"]').val($( 'a[href="#tabs-' + id + '"]' ).parent('li').attr('data-external-link'));
	        		         if( id != '0' )
	        		         {
	        		           $(this).find('input[name="tab_external_url"]').show();
	        		           $(this).find('input[name="tab_external_url"]').prev().show();
	        		         }
	        		       },
	        		       close: function() {
	        		         form[ 0 ].reset();
	        		       }
	        		     });

	        		     //override submit for form in edit tab dialog to click rename button
	        		     $( "#dialog_edit" ).find( "form" ).submit(function( event ) {
	        		       $(this).parent().parent().find('span:contains("Rename")').click();
	        		       event.preventDefault();
	        		     });

	        		     
	        		   
	        		   }
	        
	        },
	        setupModalWindows : function() {
	        
	        },
	        setupAutoCompleteBox : function () {
	        	
	        }
	};
	
	return GuideBase;
}
/**
 * 
 *  @author Jamie Little (little9)
 * 
 * Object used to do things with the hashes contained in Urls. This should allow you
 * to link to a specific pluslet or tab.
 * 
 */

var hash = {
		
		init : function() {
			
			hash.bindUiActions();
			hash.processTabHash();
			hash.processPlusletHash();
		
		},
		
		bindUiActions : function() {
		
			hash.addTabHash();
			hash.tocHashClick();
			
		},
		processTabHash : function() {
			/**
			 * If the url has a hash like this: #tab-0, the tab should open with that index
			 *  
			 */
           
			if (window.location.hash.indexOf('#tab') >= 0) {
				var tabInfo = window.location.hash.split('-');
				var tabIndex = tabInfo[1];
				
				$(document).ready(function() {
					$('#tabs').tabs('select', tabIndex);
				});
			};

		},
		
		processPlusletHash : function () {
			/**
			 * 
			 * If you come in from a url with #box-29 this should move 
			 * to the tab that the pluslet is on and pulsate the box.
			 * 
			 * 
			 */
			if (window.location.hash.indexOf('#box') >= 0) {
				var plusletInfo = window.location.hash.split('-');
				var plusletIndex = plusletInfo[1];
				var tabIndex = $("#pluslet-" + plusletIndex).parent().parent().parent().attr('id').split('-')[1];

				
				
				$(document).ready(function() {
					$('#tabs').tabs('select', tabIndex);

					
					 $("#pluslet-" + plusletIndex).effect("pulsate", {
			               times:2
			           }, 2000);

				});
			}
			
		},
		addTabHash : function() {
			/**
			 * 
			 * This ads the hash to the url when you click on a tab
			 * to surface the hash links
			 * 
			 */
		   
			$('a[href*="#tabs-"]').on('click', function(event, ui) {
		           event.preventDefault();
		         
		           var tabId = event.target.hash.split('-')[1];
		           var boxId = event.target.hash.split('-')[2];
		           var selectedBox = ".pluslet-" + boxId;
		           if ($('#tabs-1').text()) {
		               $('#tabs').tabs('select', tabId);
		           }
		           $(selectedBox).effect("pulsate", {
		               times:1
		           }, 2000);
		           window.location.hash = 'tab-' + tabId;
		       });
		},
		
		tocHashClick : function() {
			/**
			 * This addes the click events to TOC boxes that move you
			 * to specific boxes. 
			 * 
			 */
		$('.table-of-contents').on('click', function(e){
	           e.preventDefault();
	           var tabIndex = $(this).data("tab_index");
	           var plusletId = $(this).data("pluslet_id");
	           var tabId = $(this).attr("id");

	           $('#tabs').tabs('select', tabIndex);
	          
	           window.location.hash = 'box-' + plusletId;
		   
		});
		
		}
		
}
/**
 * @constructor Hash
 * @author Jamie Little (little9)
 * 
 */

var Hash = {
		
		init : function() {
			
			Hash.bindUiActions();
		},
		
		bindUiActions : function() {
		
			Hash.addTabHash();
			Hash.tocHashClick();
			
		},
		
		addTabHash : function() {
		   
			$('a[href*="#tabs-"]').on('click', function(event, ui) {
		           event.preventDefault();
		         
		           var tab_id = event.target.hash.split('-')[1];
		           var box_id = event.target.hash.split('-')[2];
		           var selected_box = ".pluslet-" + box_id;
		           if ($('#tabs-1').text()) {
		               $('#tabs').tabs('select', tab_id);
		           }
		           $(selected_box).effect("pulsate", {
		               times:1
		           }, 2000);
		           window.location.hash = 'tab-' + tab_id;
		       });
		},
		
		tocHashClick : function() {
		$('.table-of-contents').on('click', function(e){
	           e.preventDefault();
	           var tab_index = $(this).data("tab_index");
	           var pluslet_id = $(this).data("pluslet_id");
	           var box_id = $(this).attr("id");
	           //console.log(box_id);

	           $('#tabs').tabs('select', tab_index);

	           $("#pluslet-" + pluslet_id).effect("pulsate", {
	               times:2
	           }, 2000);
	           window.location.hash = 'box-' + pluslet_id;
		   
		});
		
		}
		
}
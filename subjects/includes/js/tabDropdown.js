/**
 * 
 * 
 * @author little9 (Jamie Little)
 * 
 * Creates the tab dropdown interface from imported legacy guides 
 *  
 */

var tabDropdown = {
		
		init : function() {
			tabDropdown.hideChildren();
			tabDropdown.addDropdownSymbol();
			tabDropdown.bindUiActions();
		},
		bindUiActions : function(){
			tabDropdown.parentTabClick();
		},
		
		
		hideChildren : function() {
	    // If the tab is child tab, hide it
		$('.dropspotty').each(function() { 

			try {
				
		var data_children =	$(this).attr('data-children'); 
		var childs = data_children.split();

		childs.forEach(function(data) {
			
			var split_ids = data.split(',');
			split_ids.forEach(function(data) {
				
				$('#' + data ).hide();
			})

		});


			} catch(e) {
				
			}

		});
		},

		addDropdownSymbol : function() {
		// If the tab is a parent tab add a dropdown symbol
			
		$('.dropspotty').each(function() {

			if ($(this).attr('data-children')) {

				$(this).find('a').append(' ▾');
				
			} else {
			}
			
		});
			
		},
		parentTabClick : function () {
			
		// If you click on a parent tab show its children 
		$('.dropspotty').click(function() {

			if ($(this).attr('data-children')) {
				
				var children = $(this).attr('data-children').split(',');
			    children.forEach(function(data){
			           $('#' + data).addClass("child-tab");

			           $('#' + data ).addClass( "ui-tabs-vertical ui-helper-clearfix" );
			           
			           $('#' + data).toggle();

			          
			           
				    });
			}

		});
		}


		
}
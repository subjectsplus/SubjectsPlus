/**
 * @author little9 (Jamie Little)
 * 
 * This sets up the events that are tracked by the SP analytics
 * scripts 
 * 
 * 
 */

var track = {
		
		init : function() {
			track.addLinkTracker();
			track.bindUiActions();
		},
		settings : {
			trackUrl : './track.php'
		},
		
		addLinkTracker : function() {
			$('a').each(function(){
				$(this).addClass('track-me');
			});
		},
		
		bindUiActions : function () {
			// Get subject shortform from data attribute on #main-content
			var subject = $('#main-content').data().subject;
			
			track.trackLinks(subject);
			track.trackTabs(subject);
		},
		trackLinks : function (subject) {
			
			// Capture links
			
			$('a').each(function(){
				$(this).addClass('track-me');
			});

			$('body').on('click', '.track-me', function() {
				$.get(track.settings.trackUrl, 
						{'event_type':'link', 'link_url':$(this).attr('href'),'subject': subject});	
			});
			
		},
	
		trackTabs : function(subject) {
			
			
			$('body').on('click','.ui-tabs-anchor' , function() { 
			     var tab_name = $(this).text();
			     
			     // Capture tab clicks 
			     
				$.get(track.settings.trackUrl,{'subject' : subject  , 
					'page_title' : document.title , 
					'event_type' : 'tab_click',
					'tab_name' : tab_name}, function(data) {

				});

				
			});
			
		}
};
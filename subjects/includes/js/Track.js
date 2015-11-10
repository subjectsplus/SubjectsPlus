/**
 * 
 * @constructor Track
 * @author little9 (Jamie Little)
 * 
 * This sets up the events that are tracked by the SP analytics
 * scripts 
 * 
 * 
 */

var Track = {
		
		init : function() {
			Track.addLinkTracker();
			Track.bindUiActions();
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
			
			Track.trackLinks(subject);
			Track.trackTabs(subject);
		},
		trackLinks : function (subject) {
			
			// Capture links
			
			$('a').each(function(){
				$(this).addClass('track-me');
			});

			$('body').on('click', '.track-me', function() {
				$.get(Track.settings.trackUrl, 
						{'event_type':'link', 'link_url':$(this).attr('href'),'subject': subject});	
			});
			
		},
	
		trackTabs : function(subject) {
			
			
			$('body').on('click','.ui-tabs-anchor' , function() { 
			     var tab_name = $(this).text();
			     
			     // Capture tab clicks 
			     
				$.get(Track.settings.trackUrl,{'subject' : subject  , 
					'page_title' : document.title , 
					'event_type' : 'tab_click',
					'tab_name' : tab_name}, function(data) {

				});

				
			});
			
		}
};
/**
 * 
 * @author little9 (Jamie Little)
 * 
 * Activates the autocomplete search box  
 * 
 */

var autoComplete = {
	
		init : function() {
			autoComplete.bindUiActions();
		},
		
		bindUiActions : function() {
			autoComplete.activateAutoComplete();
		},
		
		settings : {
			 subjectsUrl : $('#main-content').data().url,
			 subjectId : $('#main-content').data().subjectId,
			 autoCompletePath : "/includes/autocomplete_data.php?collection=guide&subject_id="
		},
		activateAutoComplete : function() {
			  $('#sp_search').autocomplete({

				     minLength	: 3,
				     source		: autoComplete.settings.subjectsUrl + autoComplete.settings.autoCompletePath + autoComplete.settings.subjectId,
				     focus: function(event, ui) {

				       event.preventDefault();
				       $(".find-guide-input").val(ui.item.label);


				     },
				     select: function(event, ui) {
				     	var tab_id = ui.item.hash.split('-')[1];
				     	var box_id = ui.item.hash.split('-')[2];
				     	var selected_box = ".pluslet-" + box_id;
			     
			     if ($('#tabs-1').text()) {
			       	$('#tabs').tabs('select', tab_id);
			     }
			     
				     

				     	$(selected_box).effect("pulsate", {
				     		times:1
				     	}, 2000);
				     	window.location.hash = 'box-' + box_id;
			         }
				   });
		}
		
};
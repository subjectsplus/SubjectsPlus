// Loads the select box that you can clone boxes from 

function loadCloneMenu() {
	
		$.get("../includes/autocomplete_data.php?collection=all_guides&term=", function(data) { 

			for(var i = 0; i<data.length;i++) {
		        var subject_id = data[i].id;
				$('.guide-list').append("<option data-subject-id='" + subject_id + "' class=\"guide-listing\">" + data[i].label + "</li>");

			}

		});

		$('.guide-list').on('change', function(data) {
			var subject_id = $("option:selected", this).attr('data-subject-id');
			console.log(subject_id);

			$('.pluslet-list').empty();

			$.get("../includes/autocomplete_data.php?collection=all_guides&subject_id=" + subject_id + " &term="
					,function(data) {

					for(var i = 0; i<data.length;i++) {
						$('.pluslet-list').append("<li data-pluslet-id='" + data[i].id + "' class=\"pluslet-listing\"><div class=\"pure-g\"><div class=\"pure-u-3-5 box-search-label\" title=\""+ data[i].label + "\">"  + data[i].label + "</div><div class=\"pure-u-2-5\" style=\"text-align:right;\"><button class=\"clone-button pure-button pure-button-secondary\">Link</button>&nbsp;<button class=\"copy-button pure-button pure-button-secondary\">Copy</button></div></div></li>");
			
					}
			});	
			
		});

}

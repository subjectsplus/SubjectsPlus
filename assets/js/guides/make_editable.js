////////////////////////////////
// MODIFY PLUSLET -- on click of edit (pencil) icon
////////////////////////////////

function makeEditable(lstrSelector) {

	$(document.body).on('click', lstrSelector, function(event) {
		var isclone;
		var edit_id = $(this).attr("id").split("-");

		////////////
		// Clone?
		////////////

		var clone = $("#pluslet-" + edit_id[1]).attr("class");
		if (clone.indexOf("clone") !== -1) {
			isclone = 1;
		} else {
			isclone = 0;
		}



		/////////////////////////////////////
		// Load the form elements for editing
		/////////////////////////////////////

		$('#' + 'pluslet-' + edit_id[1]).load("helpers/guide_data.php", {
			edit : edit_id[1],
			clone : isclone,
			flag : 'modify',
			type : edit_id[2],
			this_subject_id : subject_id
		}, function() {
			///////////////////////////////////////////////
			// 1.  remove the wrapper
			// 2. put the contents of the div into a variable
			// 3.  replace parent div (i.e., id="xxxxxx") with the content made by loaded file
			///////////////////////////////////////////////

			var cnt = $('#' + 'pluslet-' + edit_id[1]).contents();
			$('#' + 'pluslet-' + edit_id[1]).replaceWith(cnt);

			/////////////////////////////////////
			// Make unsortable for the time being
			/////////////////////////////////////

			$("#pluslet-" + edit_id[1]).addClass("unsortable");

			//////////////////////////////////////
			// We're changing the attribute here for the global save
			//////////////////////////////////////

			if (edit_id[2] !== undefined) {
				var new_name = "modified-pluslet-" + edit_id[2];
				$("#pluslet-" + edit_id[1]).attr("name", new_name);
			} else {
				$("#pluslet-" + edit_id[1]).attr("name", "modified-pluslet");
			}

			makeHelpable("img[class*=help-]");

			//display box_settings for editable pluslet
			$('#' + 'pluslet-' + edit_id[1]).find('.box_settings').delay(500).show();

			//close box settings panel
			$( ".close-settings" ).click(function() {      
			      $('#' + 'pluslet-' + edit_id[1]).find('.box_settings').hide();
			  });


		});

		//Make save button appear, since there has been a change to the page
		$("#response").hide();
		$("#save_guide").fadeIn();

		return false;
	});
}
////////////////
// Help Buttons
// unbind click events from class and redeclare click event
////////////////

function makeHelpable(lstrSelector) {

	$(lstrSelector).unbind('click');
	$(lstrSelector).on('click', function() {
		var help_type = $(this).attr("class").split("-");
		var popurl = "helpers/popup_help.php?type=" + help_type[1];

		$(this).colorbox({
			href : popurl,
			iframe : true,
			innerWidth : "600px",
			innerHeight : "60%",
			maxWidth : "1100px",
			maxHeight : "800px"
		});
	});
}

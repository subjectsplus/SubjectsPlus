

function setupSaveButton( lstrSelector )
{
////////////////////////////
// SAVE GUIDE'S LAYOUT
// -- this saves everything on page
////////////////////////////
	

    $(document.body).on('click',lstrSelector, function(event) {

    var staff_id = $('#guide-parent-wrap').data.staffId;
    var subject_id = $('#guide-parent-wrap').data.SubjectId;
    
	// make sure our required fields have values before continuing
	var test_req = checkRequired();

	if (test_req === 1) {
	    alert("You must complete all required form fields.");
	    return false;
	}

	// 1.  Look for new- or modified-pluslet
	// 2.  Check to make sure data is okay
	// 3.  Save to DB
	// 4.  Recreate pluslet with ID
	// 5.  Save layout

	////////////////////
	// modified-pluslet
	// loop through each pluslet
	///////////////////
	$('div[name*=modified-pluslet]').each(function() {

	    var update_id = $(this).attr("id").split("-");
	    var this_id = update_id[1];

	    //prepare the pluslets for saving
	    preparePluslets("modified", this_id, this, staff_id, subject_id);
	});

	////////////////////////
	// Now the new pluslets
	////////////////////////

	$('div[name*=new-pluslet]').each(function() {

	    var insert_id = $(this).attr("id"); // just a random gen number

	    //prepare pluslets for saving
	    preparePluslets("new", insert_id, this, staff_id, subject_id);
	});

	//////////////////////
	// We're good, save the guide layout
	// insert a pause so the new pluslet is found
	//////////////////////
	$("#response").hide();
	$("#save_guide").fadeOut();

	saveGuide();

	return false;

    });
    
}
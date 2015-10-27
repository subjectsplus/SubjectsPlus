///////////////////////
// checks whether all required inputs are not blank
///////////////////////

function checkRequired() {
    // If a required field is empty, set req_field to 1, and change the bg colour of the offending field
    var req_field = 0;

    $("*[class=required_field]").each(function() {
	var check_this_field = $(this).val();

	if (check_this_field === '' || check_this_field === null) {
	    $(this).attr("style", "background-color:#FFDFDF");
	    req_field = 1;
	} else {
	    $(this).attr("style", "background-color:none");
	}

    });

    return req_field;

}

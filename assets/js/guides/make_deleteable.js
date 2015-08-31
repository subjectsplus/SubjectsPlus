/////////////////////////////
// DELETE SECTION
/////////////////////////////


function makeDeleteable( lstrSelector, lstrType )
{
    if( lstrType === 'sections' )
    {

	$('.guidewrapper').on('click', lstrSelector ,function(event) {

	    var delete_id = $(this).parent().parent().attr("id").split("_")[1];
	    var element_deletion = this;
	    $('<div class="delete_confirm" title="Are you sure?">All content in this section will be deleted.</div>').dialog({
		autoOpen: true,
		modal: true,
		width: "auto",
		height: "auto",
		resizable: false,
		buttons: {
		    "Yes": function() {
			// Remove node
			$(element_deletion).parent().parent().remove();
			$("#response").hide();
			window.saveGuide();
			$("#save_guide").fadeOut();
			$( this ).dialog( "close" );
			return false;
		    },
		    Cancel: function() {
			$( this ).dialog( "close" );
		    }
		}
	    });
	    return false;
	});
    }else
    {
	////////////////////////////
	// DELETE PLUSLET
	// removes pluslet from DOM; change must be saved to persist
	/////////////////////////////

	$('.guidewrapper').on('click', lstrSelector ,function(event) {

	    var delete_id = $(this).attr("id").split("-")[1];
	    var element_deletion = this;
	    $('<div class="delete_confirm" title="Are you sure?"></div>').dialog({
		autoOpen: true,
		modal: true,
		width: "auto",
		height: "auto",
		resizable: false,
		buttons: {
		    "Yes": function() {
			// Delete pluslet from database
			$('#response').load("helpers/guide_data.php", {
			    delete_id: delete_id,
			    subject_id: subject_id,
			    flag: 'delete'
			},
					    function() {
						$("#response").fadeIn();

					    });

			// Remove node
			$(element_deletion).parent().parent().parent().parent().remove();
			$( this ).dialog( "close" );
			return false;
		    },
		    Cancel: function() {
			$( this ).dialog( "close" );
		    }
		}
	    });
	    return false;
	});
    }
}
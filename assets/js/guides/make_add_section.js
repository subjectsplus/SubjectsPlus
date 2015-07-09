///////////////
// function to add section to current tab
//////////////

function makeAddSection( lstrSelector )
{

    $( lstrSelector ).on( 'click', function()
			  {

                  var lintSelected = $(tabs).tabs('option', 'selected');

			      $.ajax({
				  url: "helpers/section_data.php",
				  type: "POST",
				  data: { action : 'create' },
				  dataType: "html",
				  success: function(html) {
				      
				      $('div#tabs-' + lintSelected).append(html);
				      $(document).scrollTop($('body').height());

                                      // Make sure that the new section can accept drops
                                      makeDropable(".dropspotty");
				  }
			      });
			  });
}


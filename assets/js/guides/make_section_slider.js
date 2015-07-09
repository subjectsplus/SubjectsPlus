////////////////
// function to make section sliders
///////////////

function makeSectionSlider( lstrSelector )
{
    $( lstrSelector ).each(function()
			   {
			       //section id
			       var sec_id = $(this).attr('id').split('slider_section_')[1];
			       var lobjLayout = $('div#section_' + sec_id).attr('data-layout').split('-');

			       $( this ).slider({
				   range: true,
				   min: 0,
				   max: 12,
				   step: 1,
				   values: [lobjLayout[0], parseInt(lobjLayout[0]) + parseInt(lobjLayout[1])],
				   slide: function( event, ui ) {
				       // figure out our vals
				       var left_col = ui.values[0];
				       var right_col = 12 - ui.values[1];
				       var center_col = 12 - (left_col + right_col);
				       var extra_val = left_col + "-" + center_col + "-" + right_col;

				       var lw = parseInt(left_col) * 8;
	    			       var mw = parseInt(center_col) * 8;
	    			       var sw = parseInt(right_col) * 8 - 3;

				       $( "div#section_" + sec_id ).attr( 'data-layout', extra_val);

				       reLayout(sec_id, lw, mw, sw);
				       
			               
				       
				       
				       // Hide or show the third column if needed 

				       if (sw < 0) {
					   $('#container-2').hide();
				       }

				       if (sw > 0) {
					   $('#container-2').show();
				       }
				       

				       //show save guide button
				       $("#response").hide();
				       $("#save_guide").fadeIn();
				   }
			       });
			   });



    

}
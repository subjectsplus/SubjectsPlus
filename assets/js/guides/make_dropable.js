		 // SET UP DROP SPOTS
		 // --makes divs with class of "dropspotty" droppables
		 // accepts things with class of "draggable"
		 //
		 ////////////////////////////////


		 function makeDropable( lstrSelector )
		 {


		     var dropspot = $(lstrSelector);
		     var drop_id;
		     var drag_id;
		     var drop_tab;
		     var pluslet_title;
		     var subject_id = $('#guide-parent-wrap').data().subjectId;
		   
		     console.log(dropspot);
		     dropspot.droppable({

		 	iframeFix: true,
		 	accept: ".draggable, .pluslet",
		 	drop: function(event, props) {

		 	    $(this).removeClass("drop_hover");
		 	    $(this).css("background", "");

		 	    //do if droppable tab
		 	    if($(this).children('a[href^="#tabs-"]').length > 0 )
		 	    {
		 		if( $(props.draggable).hasClass('pluslet') )
		 		{
		 		    if( !$(this).hasClass('ui-state-active') )
		 		    {
		 			drop_tab = this;

		 			//make sure to show all of pluslet before hiding
		 			$(props.draggable).children('.pluslet_body').show();
		 			$(props.draggable).children().children('.titlebar_text').show();
		 			$(props.draggable).children().children('.titlebar_options').show();


		                         $(props.draggable).hide('slow', function()
		 						{
		 						    $(this).remove();
		                                                     console.log($(this));
		                                                     console.log(drop_tab);
		 						    $(drop_tab).children('a[href^="#tabs-"]').click();
		 						    $('.portal-column-1:visible').first().prepend(this);

		                                                     console.log("Drop tab drop tab!");
		 						    $(this).height("auto");
		 						    $(this).width("auto");
		 						    $(this).show("slow");

		 						    $("#response").hide();
		 						    //Make save button appear, since there has been a change to the page
		 						    $("#save_guide").fadeIn();
		 						});
		 		    }
		 		}

		 		return;
		 	    }

		 	    //only do for class draggable
		 	    if( $(props.draggable).hasClass('draggable') )
		 	    {

		 		drop_id = $(this).attr("id");
		 		drag_id = $(props.draggable).attr("id");
		 		
		 		pluslet_title = $(props.draggable).html();
		 		if( $(this).hasClass('cke') )
		 		{
		 		    if( $(props.draggable).attr("ckclass") !== "" )
		 		    {
		 			CKEDITOR.instances[$(this).attr('id').replace('cke_', '')].openDialog($(props.draggable).attr("ckclass") + 'Dialog');
		 		    }else
		 		    {
		 			alert( 'This pluslet isn\'t configured to drag into CKEditor!' );
		 		    }
		 		}else
		 		{
		 		    // if there can only be one, could remove from list items
		 		    drop_id = $(this).attr("id");
		 		    drag_id = $(props.draggable).attr("id");
		 		    
		 		    pluslet_title = $(props.draggable).html();

		 		    // Create new node below, using a random number

		 		    var randomnumber=Math.floor(Math.random()*1000001);
		 		    $(this).next('div').prepend("<div class=\"dropspotty\" id=\"new-" + randomnumber + "\"></div>");
		 		    

		 		    // Load new data, on success (or failure!) change class of container to "pluslet", and thus draggable in theory
		 		    $("#new-" + randomnumber).fadeIn("slow").load("helpers/guide_data.php", {
		 			from: drag_id,
		 			to: drop_id,
		 			pluslet_title: pluslet_title,
		 			flag: 'drop',
		 			this_subject_id:subject_id
		 		    },
		 								  function() {

		 								      // 1.  remove the wrapper
		 								      // 2. put the contents of the div into a variable
		 								      // 3.  replace parent div (i.e., id="new-xxxxxx") with the content made by loaded file
		 								      var cnt = $("#new-" + randomnumber).contents();
		 								      $("#new-" + randomnumber).replaceWith(cnt);
		 								      
		 								      $(this).addClass("unsortable");

		 								      $("#response").hide();
		 								      //Make save button appear, since there has been a change to the page
		 								      $("#save_guide").fadeIn();


		 								      $("a[class*=showmedium]").colorbox({
		 									  iframe: true,
		 									  innerWidth:"90%",
		 									  innerHeight:"80%",
		 									  maxWidth: "1100px",
		 									  maxHeight: "800px"
		 								      });

		 								      makeHelpable("img[class*=help-]");

		 								      //Close main flyout when a pluslet is dropped
		 						              $('#main-options').slideReveal("hide");

		 								  });
		 		}
		 	    }
		 	},
		 	over: function(event, ui)
		 	{
		 	    if($(this).children('a[href^="#tabs-"]').length > 0 && $(ui.draggable).hasClass('pluslet')
		 	       && !$(this).hasClass('ui-state-active'))
		 	    {
		 		$(this).css("background", "none repeat scroll 0% 0% #C03957");
		 	    }

		 	    if($(this).children('a[href^="#tabs-"]').length < 1 && !$(ui.draggable).hasClass('pluslet'))
		 	    {
		 		$(this).addClass("drop_hover");
		 	    }
		 	},
		 	out: function(event, ui)
		 	{
		 	    if($(this).children('a[href^="#tabs-"]').length > 0 && $(ui.draggable).hasClass('pluslet'))
		 	    {
		 		$(this).css("background", "");
		 	    }

		 	    if($(this).children('a[href^="#tabs-"]').length < 1 && !$(ui.draggable).hasClass('pluslet'))
		 	    {
		 		$(this).removeClass("drop_hover");
		 	    }
		 	}
		     });
		 }
		    
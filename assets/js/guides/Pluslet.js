function Pluslet() {
    var myPluslet = {
    settings : {

    }, 
    strings : {

    },
    init : function() {
    myPluslet.markAsLinked();
    myPluslet.markAsFavorite();
    myPluslet.bindUiActions();
    },
    bindUiActions : function() {
    myPluslet.expandPluslet();
    },
    dropPluslet : function(clone_id, item_type, origin_id, clone_title) {
    var subject_id = myPluslet.getParameterByName('subject_id');
    // Create new node below, using a random number

    var randomnumber=Math.floor(Math.random()*1000001);
    $(".portal-column-1:visible").prepend("<div class=\"dropspotty\" id=\"new-" + randomnumber + "\"></div>");

    // cloneid is used to tell us this is a clone
    var new_id = "pluslet-cloneid-" + clone_id;
    // Load new data, on success (or failure!) change class of container to "pluslet", and thus draggable in theory

    $("#new-" + randomnumber).fadeIn("slow").load("helpers/guide_data.php", {
        from: new_id,
        flag: 'drop',
		this_subject_id: subject_id,
        item_type: item_type
    },
						  function() {
						      // 1.  remove the wrapper
						      // 2. put the contents of the div into a variable
						      // 3.  replace parent div (i.e., id="new-xxxxxx") with the content made by loaded file
						      var content = $("#new-" + randomnumber).contents();
						   
						      if (content.find('input.clone-input')) {
						    	  
						      content.find('input.clone-input').val(origin_id);
						      }
						      
						      if (clone_title) {
							      content.find("[id^=pluslet-new-title]").val(clone_title);
						      }

						      $("#new-" + randomnumber).replaceWith(content);
						      $("#response").hide();
						      //Make save button appear, since there has been a change to the page
						      $("#save_guide").fadeIn();
						      //Close main flyout when a pluslet is dropped
						      $('#main-options').slideReveal("hide");

						  });
    }, 
    getParameterByName : function(name) {
	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		results = regex.exec(location.search);
	return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    },
    markAsLinked : function() {
    /**
     * Created by cbrownroberts on 8/28/15.
     */
    //identify pluslets marked as linked aka cloned and addClass linked_pluslet
    var linkedBoxes = $("div.pluslet[name='Clone']");
    linkedBoxes.each(function() {
        $(this).children(".titlebar").children(".titlebar_text").addClass('linked_pluslet');
    });      

    },
    markAsFavorite : function () {
    
   /**
   * Created by cbrownroberts on 8/28/15.
   */

    //identify pluslets marked as favorites and addClass favorite_pluslet
    var $favBoxes = $("input.favorite_pluslet_input:checked")

    $favBoxes.each(function() {
        $(this).parent().parent().parent().parent().find(".titlebar_text").addClass('favorite_pluslet');

    });

        },
    expandPluslet : function() {
           //Expand/Collapse Trigger CSS for all Pluslets on a Tab
     $( "#expand_tab" ).click(function() { 
       $(this).find('i').toggleClass('fa-chevron-up fa-chevron-down');
       $('.pluslet_body').toggle();
       $('.pluslet_body').toggleClass('pluslet_body_closed');
     });
    }
    
    }
    
    
   return myPluslet;   
}
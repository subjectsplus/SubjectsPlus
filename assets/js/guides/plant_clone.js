// Places a pluslet on the guide page

function plantClone(clone_id, item_type, origin_id, clone_title) {

    // Create new node below, using a random number

    var randomnumber=Math.floor(Math.random()*1000001);
    $(".portal-column-1:visible").prepend("<div class=\"dropspotty\" id=\"new-" + randomnumber + "\"></div>");
    

    // cloneid is used to tell us this is a clone
    var new_id = "pluslet-cloneid-" + clone_id;
    // Load new data, on success (or failure!) change class of container to "pluslet", and thus draggable in theory

    $("#new-" + randomnumber).fadeIn("slow").load("helpers/guide_data.php", {
        from: new_id,
        flag: 'drop',
        item_type: item_type
    },
						  function() {

						      // 1.  remove the wrapper
						      // 2. put the contents of the div into a variable
						      // 3.  replace parent div (i.e., id="new-xxxxxx") with the content made by loaded file
						      var cnt = $("#new-" + randomnumber).contents();
						      
						      if (cnt.find('input.clone-input')) {
						    	  
						      cnt.find('input.clone-input').val(origin_id);
						      
						      }
						      
						      if (clone_title) {
							      cnt.find("[id^=pluslet-new-title]").val(clone_title);

						      }

						      
						      $("#new-" + randomnumber).replaceWith(cnt);

						      
						      
						      $("#response").hide();
						      //Make save button appear, since there has been a change to the page
						      $("#save_guide").fadeIn();

						      //Close main flyout when a pluslet is dropped
						      $('#main-options').slideReveal("hide");

						  });
}
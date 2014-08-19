<?php
namespace SubjectsPlus\Control;

/**
 *   @file sp_CompleteMe
 *   @brief Another page calls sp_CompleteMe, passes in paramaters.
 *      sp_CompleteMe passes GET parameters to includes/autocomplete_data.php
 *      and this generates our list of json possibilities
 *   Called by sp_BuildNav in the admin, and various public pages
 *
 *   @author adarby
 *   @date mar 2011
 *   @todo can only have one autocomplete on a page; this stinks!
 */
class CompleteMe {

  protected static $_counter = 0;
  public $input_id;
  public $action;
  public $target_url;
  public $default_text;
  
  
  public function __construct($input_id, $action, $target_url, $default_text = "Search", $collection = "guides", $box_size="", $display="public") {

    self::$_counter++;
    $this->num = self::$_counter;
    $this->input_id = $input_id;
    $this->action = $action;
    $this->target_url = $target_url;
    $this->default_text = $default_text;
    $this->collection = $collection;
    $this->search_box_size = $box_size;
    $this->display = $display;
  }

  public function displayBox() {

    global $CpanelPath;
    global $PublicPath;
    $auto_complete_url = "";

    //print "input_id = $this->input_id, action = $this->action, target_url = $this->target_url, collection = $this->collection";
    if ($this->display == "public") {
      $data_location = $PublicPath . "includes/autocomplete_data.php?collection=" . $this->collection;
    } else {
      $data_location = $CpanelPath . "includes/autocomplete_data.php?collection=" . $this->collection;
    }


    switch($this->display) {

      case "public":
	$auto_complete_url = $PublicPath;
	break;
      case "control":
	$auto_complete_url = $CpanelPath;
	break;
    }


    echo "
   <div id=\"autoC\" class=\"autoC\">
  		<form action=\"$this->action\" method=\"post\" class=\"pure-form\" id=\"sp_admin_search\">
 		<input type=\"text\" id=\"$this->input_id\" size=\"$this->search_box_size\" name=\"searchterm\" autocomplete=\"on\" placeholder=\"" . $this->default_text . "\" /> <input type=\"submit\" value=\"" . _("Go") . "\"  class=\"pure-button pure-button-topsearch\" id=\"topsearch_button\" name=\"submitsearch\" alt=\"Search\" />
  		</form>
	</div>";

    // now print out some variables for the js

echo "<script type=\"text/javascript\">

jQuery(document).ready(function() {

 var parents = [];

$.widget(\"custom.catcomplete\", $.ui.autocomplete, {
  
_renderMenu: function( ul, items ) {
      var that = this;
      pluslet_category = [];
         
      currentCategory = \"\";

      $.each( items, function( index, item ) {
       
if (item.content_type === 'Pluslet') {

if (parents.indexOf(item.parent) > -1) {

} else {
parents.push(item.parent);

ul.append('<li class=\'ui-autocomplete-category content-in\'>Content in ' + '\"' + item.parent + '\"' + '</li>');

}

var autocomplete_labels = jQuery('.autocomplete-parent-guide');

} else {

if (item.content_type != undefined) {

 if( item.content_type != jQuery('.ui-autocomplete-category').html()) {

          ul.append( \"<li class='ui-autocomplete-category'>\" + item.content_type + \"</li>\" );
}
}


}
   

        that._renderItemData( ul, item );
      });
    }
  });


	var startURL = '$auto_complete_url';
        
	jQuery('#" . $this->input_id . "').catcomplete({
		minLength	: 3,
		source		: '" . $data_location . "',
		focus: function(event, ui) {

                event.preventDefault();


		},
		select: function(event, ui) {
 
        if (ui.item.url.indexOf('http://') === 0) {
            
            location.href = ui.item.url;      
            
        } else {
        
	        location.href = startURL + ui.item.url;
}   

    
    
               }
	});

});
      
	</script>";
 
 }

}

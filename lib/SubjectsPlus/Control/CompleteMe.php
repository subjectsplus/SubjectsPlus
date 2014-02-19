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
    

    //print "input_id = $this->input_id, action = $this->action, target_url = $this->target_url, collection = $this->collection";
    if ($this->display == "public") {
      $data_location = $PublicPath . "includes/autocomplete_data.php?collection=" . $this->collection;
    } else {
      $data_location = $CpanelPath . "includes/autocomplete_data.php?collection=" . $this->collection;
    }
    echo "
   <div class=\"autoC\">
  		<form action=\"$this->action\" method=\"post\" class=\"pure-form\" id=\"sp_admin_search\">
 		<input type=\"text\" id=\"$this->input_id\" size=\"$this->search_box_size\" name=\"searchterm\" placeholder=\"" . $this->default_text . "\" /><input type=\"submit\" value=\"" . _("Go") . "\"  class=\"pure-button\" id=\"topsearch_button\" name=\"submitsearch\" alt=\"Search\" />
  		</form>   
	</div>";

    // now print out some variables for the js
    echo "<script type=\"text/javascript\">
	var startURL = '$this->target_url';

	jQuery(document).ready(function(){

	// Caching 
	var autoC = jQuery('#" . $this->input_id . "');
	
	
	// Using jQuery UI's autocomplete widget:
	autoC.autocomplete({
		minLength	: 3,
		source		: '" . $data_location . "',
		focus: function(event, ui) {
			return;
		},
		select: function(event, ui) {
            
			location.href = startURL + ui.item.value;
		}
	});
        //autoC.defaultText(defaultSearchText_" . $this->num . ");
	});
	</script>";
  }

}

?>
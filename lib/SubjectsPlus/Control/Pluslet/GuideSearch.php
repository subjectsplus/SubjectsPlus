<?php
/**
 *   @file GuideSearch.php
 *   @brief A Pluslet that searches guides
 *   @author little9 (Jamie Little)
 *   @date June 2015
 */
namespace SubjectsPlus\Control;
require_once("Pluslet.php");

class Pluslet_GuideSearch extends Pluslet {

	
  public function __construct($pluslet_id, $flag="", $subject_id, $isclone=0) {
    parent::__construct($pluslet_id, $flag, $subject_id, $isclone);
  
    $this->_type = "GuideSearch";
    $this->_pluslet_bonus_classes = "type-guidesearch";
  }

  protected function onEditOutput()
  {
  	
    $this->_body = "<p class=\"faq-alert\">" . _("Click 'Save' to view your search box.") . "</p>";
   
  }

  protected function onViewOutput()
  {

  	global $PublicPath;
  	$data_location = $PublicPath . "includes/autocomplete_data.php?collection=guides";
  	$auto_complete_url = $PublicPath;
  	 
  	//$input_box = new CompleteMe("quick_search", "search.php", '', "Quick Search", "guides", '');
    $this->_body = '<div class="autoC">
  			<form action="search.php" method="post" class="pure-form sp_admin_search">
 			<input type="text" size="" name="searchterm" autocomplete="off" placeholder="Find Guides" class="ui-autocomplete-input quick_search"><span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span> <input type="submit" value="Search" class="pure-button pure-button-pluslet" name="submitsearch" alt="Search">
  			</form>
		</div>';
    
    
    $this->_body .=  "<script type=\"text/javascript\">

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
        
	jQuery('.quick_search').catcomplete({
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

  static function getMenuName()
  {
    return _('Guide Search');
  }

  static function getMenuIcon()
    {
        $icon="<span class=\"icon-text guidesearch-text\">" . _("Guide Search") . "</span>";
        return $icon;
    }


}
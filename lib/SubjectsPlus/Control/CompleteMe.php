<?php

namespace SubjectsPlus\Control;

/**
 * @file sp_CompleteMe
 * @brief Another page calls sp_CompleteMe, passes in paramaters.
 *      sp_CompleteMe passes GET parameters to includes/autocomplete_data.php
 *      and this generates our list of json possibilities
 *   Called by sp_BuildNav in the admin, and various public pages
 *
 * @author adarby
 * @date mar 2011
 */
class CompleteMe
{

    protected static $_counter = 0;
    public $input_id;
    public $action;
    public $target_url;
    public $default_text;


    public function __construct($input_id, $action, $target_url, $default_text = "Search", $collection = "guides", $box_size = "", $display = "public", $value = "", $sortby = "")
    {

        self::$_counter++;
        $this->num = self::$_counter;

        // Sanitize input
        $this->input_id = scrubData($input_id, "text");
        $this->action = scrubData($action, "url");
        $this->target_url = scrubData($target_url, "url");
        $this->default_text = scrubData($default_text, "text");
        $this->collection = scrubData($collection, "text");
        $this->search_box_size = scrubData($box_size, "integer");
        $this->display = (scrubData($display, "text") === 'public') ? 'public' : 'private';
        $this->value = scrubData($value, "text");
        $this->sortby = scrubData($sortby, "text");


    }

    public function displayBox($printout = true)
    {

        global $CpanelPath;
        global $PublicPath;
        global $proxyURL;
        $auto_complete_url = "";

        // Define the whitelist
        $valid_collections = array(
            "guides",
            "records",
            "talkback",
            "faq",
            "all",
            "ebooks",
            "admin"
        );

        // Sanitize the collection input
        $sanitized_collection = scrubData($this->collection, "text");

        // Check against the whitelist
        if (in_array($sanitized_collection, $valid_collections)) {
            $category = $sanitized_collection;

            // Special case for "admin"
            if ($category === "admin") {
                $category = "staff";
            }
        } else {
            // Default to "all" if not in whitelist
            $category = "all";
        }

        // Now use $category in your URL construction
        if ($this->display == "public") {
            $data_location = $PublicPath . "includes/autocomplete_data.php?collection=" . urlencode($category);
        } else {
            $data_location = $CpanelPath . "includes/autocomplete_data.php?collection=" . urlencode($category);
        }

        switch ($this->display) {
            case "public":
                $auto_complete_url = $PublicPath;
                break;
            case "control":
                $auto_complete_url = $CpanelPath;
                break;
        }


        // Handle category selection when searching on a page other than search.php
        $category = $this->collection;
        switch ($category) {
            // Intentional fall-through condition
            // if the category is of any of these, no change necessary
            case "guides":
            case "records":
            case "talkback":
            case "faq":
            case "all":
            case "ebooks":
                break;

            // Admin refers to staff category for the purposes of searching
            case "admin":
                $category = "staff";
                break;

            // Any other values will refer to all categories for the purposes of searching
            default:
                $category = "all";
                break;
        }


        // HTML for the Search Form
        $search_form_html = "
    <div id=\"autoC\" class=\"autoC\">
       <form action=\"$this->action\" method=\"get\" class=\"pure-form\" id=\"sp_admin_search\">
        <input type=\"text\" id=\"$this->input_id\" title=\"$this->default_text\" size=\"$this->search_box_size\" name=\"searchterm\" autocomplete=\"on\" placeholder=\"" . $this->default_text . "\" value=\"" . $this->value . "\" />
        <input type=\"hidden\" value=\"" . $category . "\" name=\"category\"/>
        <input type=\"submit\" value=\"" . _("Go") . "\" class=\"pure-button pure-button-topsearch\" id=\"topsearch_button\" name=\"\" alt=\"Search\" />
       </form>
   </div>";

        // Script Tag for the Autocomplete
        $js_autocomplete_html = "<script type=\"text/javascript\">

    jQuery(document).ready(function() {
    console.log('completeme:');
     var parents = [];
    
    $.widget(\"custom.catcomplete\", $.ui.autocomplete, {
      
    _renderMenu: function( ul, items ) {
    console.log('items: ' + items);
          var that = this;
          console.log('that: ' + this);
          pluslet_category = [];
             
          currentCategory = \"\";
    
          $.each( items, function( index, item ) {
          console.log('item: ' + item);
           
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
    
    var category = '$this->collection';
    var proxyURL = '$proxyURL';
    console.log(proxyURL);
    console.log(category);
      var startURL = '$auto_complete_url';
      console.log(startURL);
           
      jQuery('#" . $this->input_id . "').catcomplete({
        minLength	: 3,
        source		: '" . $data_location . "',
        focus: function(event, ui) {
            event.preventDefault();
        },
        select: function(event, ui) {
        
        if(category == 'ebooks') {
            url = proxyURL + ui.item.url;
        } else {
            url = ui.item.url;
        }
        console.log(url);
            if (ui.item.url.indexOf('https://') === 0) {  
                location.href = url;              
            } else if (ui.item.url.indexOf('http://') === 0) {  
                location.href = url;  
            } else {
              location.href = startURL + ui.item.url;
            }   
        }
      });
      $('#" . $this->input_id . "').attr('autocomplete', 'on');
    });
      </script>";

        if ($printout) {
            echo $search_form_html;
            echo $js_autocomplete_html;
        } else {
            return $search_form_html . $js_autocomplete_html;
        }

    }

}

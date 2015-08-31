<?php
/**
 *   @file guide.php
 *   @brief Create and arrange the contents of a research guide
 *   @description This is where the magic happens.  Process like this:
 *   1. Determine if it's a cloned guide or not --REMOVED THIS
 *   2. Load guide's metadata (query $q)
 *   3. Load guide's pluslets and arrange intro appropriate column (query $qc)
 *   4. Pull in local css (guide.css) and javascript (guide.js)
 *   5. Put together page
 *
 *   @author adarby
 *   @date Dec 2012
 *   @todo Help popups not pointing to correct files
 *   @todo Edit history not present
 *   @todo Make sure user is allowed to modify this guide (NOFUN not set)
 */

use SubjectsPlus\Control\Guide;
use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\FavoritePluslet;

if (!isset($_GET["subject_id"])) {
  header("location:index.php");
}

// necessary for jquery slider
$use_jquery = array("ui_styles");

// clear out existing cookies

ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);
ini_set('log_errors','On');

setcookie("our_guide", "", 0, '/', $_SERVER['HTTP_HOST']);
setcookie("our_guide_id", "", 0, '/', $_SERVER['HTTP_HOST']);
setcookie("our_shortform", "", 0, '/', $_SERVER['HTTP_HOST']);

$subcat = "guides";
$page_title = "Modify Guide";
$tertiary_nav = "yes";

ob_start();

include("../includes/header.php");


$postvar_subject_id = scrubData($_GET['subject_id']);

$this_id = $_GET["subject_id"];
$clone = 0;

// See if they have permission to edit this guide
if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
  $q = "SELECT staff_id from staff_subject WHERE subject_id = '$this_id'
    AND staff_id = '" . $_SESSION["staff_id"] . "'";

  $r = $db->query($q);
  $num_rows = count($r);

  if ($num_rows < 1) {
    $no_permission =  _("You do not have permission to edit this guide.  Ask the guide's creator to add you as a co-editor.");

    print noPermission($no_permission);

    include("../includes/footer.php");
    exit;
  }
}



// See if anything has been added through the Find button

if (isset($_GET["insert_pluslet"])) {
  $qa = "SELECT p.pluslet_id, p.title, p.body, ps.pcolumn, p.type, p.extra
    FROM pluslet p WHERE p.pluslet_id = '" . $_GET["insert_pluslet"] . "'";
  $ra = $db->query($qa);


}

if (isset($this_id)) {
  $subject_id = $_GET["subject_id"];
  // get name of quide
  $q = "SELECT subject, shortform, active, extra from subject where subject_id = '$subject_id'";

  $r = $db->query($q);

  // If this guide doesn't exist, send them away
  if (count($r) == 0) {
    header("location:index.php");
  }


  $subject_name = $r[0][0];
  $shortform = $r[0][1];


  // Is there a selected tab?
  if (isset($_GET["t"]) && $_GET["t"] != "") {
    $selected_tab = scrubData($_GET["t"]);
  } else {
    $selected_tab = 0;
  }

  //create new guide object and set admin view to true
  $lobjGuide = new Guide($this_id);
  $lobjGuide->_isAdmin = TRUE;


  
  $all_tabs = $lobjGuide->getTabs();

  
} else {
  print "no guide";
}






////////////////////////////
// Now, get our pluslets //
///////////////////////////
global $pluslets_activated;

//get related guides
$related_guides = $lobjGuide->getRelatedGuides();

$related_guides = array_filter($related_guides);
// if none exist then do not display related guide pluslet icon
if(empty($related_guides)) {
  if(($key = array_search('Related', $pluslets_activated)) !== false) {
    unset($pluslets_activated[$key]);
  }

}

$all_boxes = "<p>" . _("Drag box selection, then drop it to the right") . "</p>

<ul id=\"box_options\">";


foreach( $pluslets_activated as $lstrPluslet )
{
  if( file_exists( dirname(dirname(__DIR__)) . "/lib/SubjectsPlus/Control/Pluslet/$lstrPluslet.php" ) )
  {
    $lstrObj = "SubjectsPlus\Control\Pluslet_" . $lstrPluslet;

    if( method_exists( $lstrObj, 'getMenuIcon' ) )
    {
      $all_boxes .= "<li class=\"box-item draggable\" id=\"pluslet-id-$lstrPluslet\" ckclass='" . call_user_func(array( $lstrObj, 'getCkPluginName' )) . "'>" . call_user_func(array( $lstrObj, 'getMenuIcon' )) . "</li>";
    }else
    {
      $all_boxes .= "<li class=\"box-item draggable\" id=\"pluslet-id-$lstrPluslet\" ckclass='" . call_user_func(array( $lstrObj, 'getCkPluginName' )) . "'>" . $lstrPluslet . "</li>";
    }
  }
}

// Now get Special ones
// make sure:  a) there are some linked resources (to show All Items by Source)

$conditions = "";

$q1 = "SELECT rank_id FROM rank WHERE subject_id = '$this_id'";

$r1 = $db->query($q1);

$num_resources = count($r1);

if ($num_resources == 0) {
  $conditions = "AND pluslet_id != '1'";
}

//$q = "SELECT distinct pluslet_id, title, body
//FROM pluslet
//WHERE type = 'Special'
//";

//$r = $db->query($q);

//foreach ($r as $myrow) {
 // $lstrObj = "SubjectsPlus\Control\Pluslet_" . $myrow[0];
 // $all_boxes .= "<li class=\"box-item draggable\" id=\"pluslet-id-$lstrPluslet\" ckclass='" . call_user_func(array( $lstrObj, 'getCkPluginName' )) . "'>" . call_user_func(array( $lstrObj, 'getMenuIcon' )) . "</li>";
//}

$all_boxes .= "</ul>";

// END DRAGGABLE //
// print_r($_SESSION);
// Let's set some cookies to be used by ckeditor
setcookie("our_guide", $subject_name, 0, '/', $_SERVER['HTTP_HOST']);
setcookie("our_guide_id", $postvar_subject_id, 0, '/', $_SERVER['HTTP_HOST']);
setcookie("our_shortform", $shortform, 0, '/', $_SERVER['HTTP_HOST']);
ob_end_flush();

?>



<script type="text/javascript">
 // We're just setting up a few vars that we'll need
 var user_id = "<?php print $_SESSION["staff_id"]; ?>";
 var user_name = "<?php print $_SESSION["fname"] . " " . $_SESSION["lname"]; ?>";
 var subject_id = "<?php print $postvar_subject_id; ?>";
 var cloned_guide = "<?php print $clone; ?>";

 // This will be changed by using the Find button, and selecting a clone to insert
 window.addItem = 0;

 // Hides the global nav on load
 jQuery("#header, #subnavcontainer").hide();



 jQuery(document).ready(function(){
   
   //layout each section
   $('div[id^="section_"]').each(function()
    				 {
       //section id
       var sec_id = $(this).attr('id').split('section_')[1];
       var lobjLayout = $('div#section_' + sec_id).attr('data-layout').split('-');

       var lw = parseInt(lobjLayout[0]) * 7;
       var mw = parseInt(lobjLayout[1]) * 7;
       var sw = parseInt(lobjLayout[2]) * 7;

       console.log(lw, mw, sw);
       
       try {
	 reLayout(sec_id, lw, mw, sw);
       } catch (e) {



       }


     });

   var boxyConfig = {
     interval: 50,
     sensitivity: 4,
     timeout: 500
   };

   jQuery("#newbox").hoverIntent(boxyConfig);

   ///////////////////////////////////
   // config our box for layout slider
   ///////////////////////////////////

   function addSlider(){
     jQuery("#slider_options").show();
     return;

   }

   function removeSlider(){
     jQuery("#slider_options").hide();
     //alert ($( "#extra" ).val());
     return;
   }

   var sliderConfig = {
     interval: 50,
     sensitivity: 4,
     over: addSlider,
     timeout: 500,
     out: removeSlider
   };

   jQuery("#layoutbox").hoverIntent(sliderConfig);

   var ov = '<?php // print $jobj->{'maincol'}; ?>';
   var ourval = ov.split("-");
   var lc = parseInt(ourval[0]);
   var cc = parseInt(ourval[1]);
   var rc = lc + cc;

   jQuery( "#slider" ).slider({
     range: true,
     min: 0,
     max: 12,
     step: 1,
     values: [lc, rc],
     slide: function( event, ui ) {
       // figure out our vals
       var left_col = ui.values[0];
       var right_col = 12 - ui.values[1];
       var center_col = 12 - (left_col + right_col);
       var extra_val = left_col + "-" + center_col + "-" + right_col;
       jQuery( "#extra" ).val(extra_val);
       jQuery( "#main_col_width" ).html(left_col + "-" + center_col + "-" + right_col);
       jQuery("#save_layout").show();
     }
   });

   jQuery("div#tabs ul li a").dblclick(function () {

     alert("doublclickitude!");
   })


 });

 //setup jQuery UI tabs and dialogs
 jQuery(function() {
   var tabTitle = $( "#tab_title" ),
   tabContent = $( "#tab_content" ),
   tabTemplate = "<li class=\"dropspotty\"><a href='#{href}'>#{label}</a><span class='alter_tab' role='presentation'><i class=\"fa fa-cog\"></i></span></li>",
   tabCounter = <?php echo ( count($all_tabs) ); ?>;
   var tabs = $( "#tabs" ).tabs();

   //add click event for external url tabs
   jQuery('li[data-external-link]').each(function()
					 {
       if($(this).attr('data-external-link') != "")
       {
	 jQuery(this).children('a[href^="#tabs-"]').on('click', function(evt)
						       {
	     window.open($(this).parent('li').attr('data-external-link'), '_blank');
	     evt.stopImmediatePropagation();
	   });

	 jQuery(this).children('a[href^="#tabs-"]').each(function() {
	   var elementData = jQuery._data(this),
	   events = elementData.events;

	   var onClickHandlers = events['click'];

	   // Only one handler. Nothing to change.
				 if (onClickHandlers.length == 1) {
	     return;
	   }

	   onClickHandlers.splice(0, 0, onClickHandlers.pop());
	 });
       }
     });

   //preselect first
   tabs.tabs('select', 0);

   //go to tab and pulsate pluslet if hash exists in url
   if( window.location.hash )
   {
     setTimeout(function()
 		{
	 if( window.location.hash.split('-').length == 3  )
	 {
	   var tab_id = window.location.hash.split('-')[1];
	   var box_id = window.location.hash.split('-')[2];
	   var selected_box = ".pluslet-" + box_id;

	   $('#tabs').tabs('select', tab_id);

	   jQuery('html, body').animate({scrollTop:jQuery('a[name="box-' + box_id + '"]').offset().top}, 'slow');

	   jQuery(selected_box).effect("pulsate", {
	     times:1
	   }, 2000);
	 }
       }, 500);
   }

   // modal dialog init: custom buttons and a "close" callback reseting the form inside
   var dialog = $( "#dialog" ).dialog({
     autoOpen: false,
     modal: true,
     buttons: {
       Add: function() {
         addTab();
         $( this ).dialog( "close" );
       },
       Cancel: function() {
         $( this ).dialog( "close" );
       }
     },
     open: function() {
       $(this).find('input[name="tab_external_link"]').hide();
       $(this).find('input[name="tab_external_link"]').prev().hide();
       if( tabCounter > 0 )
       {
    	 $(this).find('input[name="tab_external_link"]').show();
    	 $(this).find('input[name="tab_external_link"]').prev().show();
       }
     },
     close: function() {
       form[ 0 ].reset();
     }
   });

   //setup dialog to edit tab
   $( "#dialog_edit" ).dialog({
     autoOpen: false,
     modal: true,
     width: "auto",
     height: "auto",
     buttons: {
       "Save": function() {
         var id = window.lastClickedTab.replace("#tabs-", "");

         $( 'a[href="#tabs-' + id + '"]' ).text( $('input[name="rename_tab_title"]').val() );
         $( 'a[href="#tabs-' + id + '"]' ).parent('li').attr( 'data-visibility', $('select[name="visibility"]').val() );

         if( $( 'a[href="#tabs-' + id + '"]' ).parent('li').attr( 'data-external-link') != '' )
         {
           $( 'a[href="#tabs-' + id + '"]' ).each(function() {
             var elementData = jQuery._data(this),
             events = elementData.events;

             var onClickHandlers = events['click'];

             // Only one handler. Nothing to change.
             if (onClickHandlers.length == 1) {
               return;
             }

             onClickHandlers.splice(0, 1);
           });
         }

         $( 'a[href="#tabs-' + id + '"]' ).parent('li').attr( 'data-external-link', $('input[name="tab_external_url"]').val() );

         if( $('input[name="tab_external_url"]').val() != '')
         {
           $( 'a[href="#tabs-' + id + '"]' ).on('click', function(evt)
              					{
               window.open($(this).parent('li').attr('data-external-link'), '_blank');
               evt.stopImmediatePropagation();
             });

           $( 'a[href="#tabs-' + id + '"]' ).each(function() {
             var elementData = jQuery._data(this),
             events = elementData.events;

             var onClickHandlers = events['click'];

             // Only one handler. Nothing to change.
             if (onClickHandlers.length == 1) {
               return;
             }

             onClickHandlers.splice(0, 0, onClickHandlers.pop());
           });
         }

	 //add/remove class based on tab visibility
       	 if( $('select[name="visibility"]').val() == 1 )
       	 {
       	   $( 'a[href="#tabs-' + id + '"]' ).parent('li').removeClass('hidden_tab');
       	 }else
       	 {
	   $( 'a[href="#tabs-' + id + '"]' ).parent('li').addClass('hidden_tab');
       	 }

         $( this ).dialog( "close" );
       	 $("#response").hide();
         $('#save_guide').fadeIn();
         //$('#save_template').fadeIn();
       },
       "Delete" : function() {
         var id = window.lastClickedTab.replace("#tabs-", "");

         $( 'a[href="#tabs-' + id + '"]' ).parent().remove();
         $( 'div#tabs-' + id ).remove();
         tabs.tabs("destroy");
         tabs.tabs();
         tabCounter--;
         $( this ).dialog( "close" );
   	 $("#response").hide();
         $('#save_guide').fadeIn();
         //$('#save_template').fadeIn();
       },
       Cancel: function() {
         $( this ).dialog( "close" );
       }
     },
     open: function(event, ui) {
       var id = window.lastClickedTab.replace("#tabs-", "");
       $(this).find('input[name="rename_tab_title"]').val($( 'a[href="#tabs-' + id + '"]' ).text());
       $(this).find('select[name="visibility"]').val($( 'a[href="#tabs-' + id + '"]' ).parent('li').attr('data-visibility'));

       //external url add text input unless first tab
       $(this).find('input[name="tab_external_url"]').val('');
       $(this).find('input[name="tab_external_url"]').hide();
       $(this).find('input[name="tab_external_url"]').prev().hide();
       $(this).find('input[name="tab_external_url"]').val($( 'a[href="#tabs-' + id + '"]' ).parent('li').attr('data-external-link'));
       if( id != '0' )
       {
         $(this).find('input[name="tab_external_url"]').show();
         $(this).find('input[name="tab_external_url"]').prev().show();
       }
     },
     close: function() {
       form[ 0 ].reset();
     }
   });

   //override submit for form in edit tab dialog to click rename button
   $( "#dialog_edit" ).find( "form" ).submit(function( event ) {
     $(this).parent().parent().find('span:contains("Rename")').click();
     event.preventDefault();
   });

   // addTab form: calls addTab function on submit and closes the dialog
   var form = dialog.find( "form" ).submit(function( event ) {
     addTab();
     dialog.dialog( "close" );
     event.preventDefault();
   });

   // actual addTab function: adds new tab using the input from the form above
   function addTab() {
     var label = tabTitle.val() || "Tab " + tabCounter,
     external_link = $('input#tab_external_link').val(),
     id = "tabs-" + tabCounter,
     li = $( tabTemplate.replace( /#\{href\}/g, "#" + id ).replace( /#\{label\}/g, label ) ),
     tabContentHtml = tabContent.val() || "Tab " + tabCounter + " content.";
     $(li).attr('data-external-link', external_link);
     $(li).attr('data-visibility', 1);
     tabs.find( ".ui-tabs-nav" ).append( li );

     var slim = jQuery.ajax
     ({
       url: "helpers/section_data.php",
       type: "POST",
       data: { action : 'create' },
       dataType: "html",
       success: function(html) {
         tabs.tabs("destroy");

         tabs.append( "<div id='" + id + "' class=\"sptab\">" + html
                      + "</div>" );

         jQuery("#response").hide();
         jQuery("#save_guide").fadeIn();
         //jQuery("#save_template").fadeIn();

         tabs.tabs();

         if( external_link == '' )
         {
           tabs.tabs('select', tabCounter);
         }else
         {
           tabs.tabs('select', 0);
         }

         if( $(li).attr('data-external-link') != '' )
         {
           jQuery(li).children('a[href^="#tabs-"]').on('click', function(evt)
         					       {
	       window.open($(this).parent('li').attr('data-external-link'), '_blank');
	       evt.stopImmediatePropagation();
	     });
         }

         jQuery(li).children('a[href^="#tabs-"]').each(function() {
           var elementData = jQuery._data(this),
           events = elementData.events;

           var onClickHandlers = events['click'];

           // Only one handler. Nothing to change.
           if (onClickHandlers.length == 1) {
             return;
           }

           onClickHandlers.splice(0, 0, onClickHandlers.pop());
         });

         tabCounter++;
       }
     });
   }

   // addTab button: just opens the dialog
   $( "#add_tab" ).button().click(function() {
     dialog.dialog( "open" );
   });

   // edit icon: removing or renaming tab on click
   tabs.delegate( "span.alter_tab", "click", function(lobjClicked) {
     var List = $(this).parent().children("a");
     var Tab = List[0];
     window.lastClickedTab = $(Tab).attr("href");
     $('#dialog_edit').dialog("open");
   });
 });

 jQuery(window).load(function(){
   // jQuery functions to initialize after the page has loaded.
   try {
     refreshFeeds();
   }catch(e) {


   }


 });
</script>



<!-- ///////////////////////////////////
   // Structure for Guide Backend - PV
   ///////////////////////////////////-->

<style>
.guidewrapper, #main-options {display:none;}
</style>

<div class="guide-parent-wrap" id ="guide-parent-wrap">
  
      <div class="panel-wrap">
        <div id="hide_header">
          <img src="<?php print $AssetPath; ?>images/icons/menu-26.png" title="<?php print _("Show/Hide Header"); ?>" alt="<?php print _("Show/Hide Header"); ?>" />
        </div>         
      </div><!--end .panel-wrap-->
      
      
      <div class="guide-wrap">
          
          <!--GUIDE HEADER CONTAINER-->
          <div id="guide_header">
              <div class="pure-g">
                <div class="pure-u-2-5 pure-u-md-1-3 pure-u-lg-1-2 pure-u-xl-5-8 guide-title-area">
                    <h2><?php print "<a target=\"_blank\" href=\"$PublicPath" . "guide.php?subject=$shortform\">$subject_name</a>"; ?></h2>
                </div> <!-- end pure 5-8-->

                <div class="pure-u-2-5 pure-u-md-1-2 pure-u-lg-3-8 pure-u-xl-1-4 guide-commands-area">
                    <!-- Save Button -->
                    <div id="savour"><button class="button pure-button pure-button-primary" id="save_guide"><?php print _("SAVE CHANGES"); ?></button></div>
                    <!--<div id="savour2"><button class="button pure-button pure-button-primary" id="save_template"><?php //print _("SAVE TEMPLATE"); ?></button></div>-->
                </div> <!-- end pure 1-4-->                
              
                <div class="pure-u-1-5 pure-u-md-1-6 pure-u-lg-1-8 pure-u-xl-1-8 guide-options-area">

                  <ul id="guide_nav">
                    <li><a href="<?php print $PublicPath . "guide.php?subject=$shortform"; ?>" target="_blank"><i class="fa fa-eye" title="<?php print _("View Guide"); ?>"></i></a></li>
                    <li><a class="showmeta" href="<?php print $CpanelPath . "guides/metadata.php?subject_id=$subject_id" . "&amp;wintype=pop"; ?>"><i class="fa fa-cog" title="<?php print _("Edit Guide Metadata"); ?>"></i></a></li>
                    <li><a href="#" id="find-trigger"><i class="fa fa-search" title="<?php print _("Find in Guide"); ?>"></i></a></li>
                  </ul>              
                </div><!-- end pure 1-8-->
              </div> <!-- end pure -->
          </div> <!-- end guide header-->

          <div id="find-in-guide-container">
            <div class="pure-g">
                <div class="pure-u-5-6 pure-u-lg-7-8">&nbsp;</div>
                <div class="pure-u-1-6 pure-u-lg-1-8 find-guide-parent">
                    <form class="pure-form" id="guide_search">    
                        <input class="find-guide-input" type="text" placeholder="<?php print _("Find in Guide"); ?>"></input>
                    </form>
                </div>
            </div>
          </div>
<script>
     var startURL = '../guides/guide.php?subject_id=';
     var sp_path = document.URL.split('/')[3];

     jQuery('.find-guide-input').autocomplete({

       minLength  : 3,
       source   : "../includes/autocomplete_data.php?collection=guide&subject_id=" + <?php echo $this_id; ?> ,
       focus: function(event, ui) {

         event.preventDefault();
         jQuery(".find-guide-input").val(ui.item.label);
       },
       select: function(event, ui) {
        var tab_id = ui.item.hash.split('-')[1];
        var box_id = ui.item.hash.split('-')[2];
        var selected_box = ".pluslet-" + box_id;

        $('#tabs').tabs('select', tab_id);

        jQuery(selected_box).effect("pulsate", {
          times:1
        }, 2000);

        window.location.hash = 'box-' + box_id;
         }


     });
</script>     

          

          <input id="extra" type="hidden" size="1" value="<?php

            if (isset($lobj)) {
             print $jobj->{'maincol'}; 

            }

            ?>" name="extra" />         


          <!--GUIDE BUILDER CONTAINER-->
          <div class="guidewrapper">
               <div id="tabs">

                 <?php $lobjGuide->outputNavTabs(); ?>

                 <?php
                 $lobjGuide->outputTabs();
                 ?>

               </div>
          </div>

      </div><!--end .guide-wrap-->
  
      <!-- Feedback -->
      <div id="response"></div>
	 

	 <!-- new tab form (suppressed until tab gears clicked) -->
	 <div id="dialog" title="Tab data">
	   <form class="pure-form pure-form-aligned">
	     <fieldset class="ui-helper-reset">
               <div class="pure-control-group">
		 <label for="tab_title"><?php print _("Title"); ?></label>
		 <input type="text" name="tab_title" id="tab_title" value="" class="ui-widget-content ui-corner-all" />
               </div>
               <div class="pure-control-group">
		 <label for="tab_external_link"><?php print _("Redirect URL"); ?></label>
		 <input type="text" name="tab_external_link" id="tab_external_link" />
               </div>
	     </fieldset>
	   </form>
	 </div>

	 <!-- edit tab form (suppressed until tab gears clicked) -->
	 <div id="dialog_edit" title="Tab edit">
	   <form class="pure-form pure-form-aligned">
             <fieldset class="ui-helper-reset">
               <div class="pure-control-group">
		 <label for="tab_title"><?php print _("New Title"); ?></label>
		 <input type="text" name="rename_tab_title" id="tab_title" value="" class="ui-widget-content ui-corner-all" />
               </div>
               <div class="pure-control-group">
		 <label for="tab_external_url"><?php print _("Redirect URL"); ?></label>
		 <input type="text" name="tab_external_url" id="tab_external_url" />
               </div>
               <div class="pure-control-group">
		 <label><?php print _("Visibility"); ?></label>
		 <select name="visibility">
		   <option value="1">Public</option>
		   <option value="0">Hidden</option>
		 </select>
               </div>
             </fieldset>
	   </form>
	 </div>

	 <script>
	  //make tabs sortable
	  jQuery(function() {
	    $(tabs).find( ".ui-tabs-nav" ).sortable({
              axis: "x",
              stop: function(event, ui) {
		if(jQuery(ui.item).attr("id") == 'add_tab' || jQuery(ui.item).parent().children(':first').attr("id") != 'add_tab' || jQuery(ui.item).attr('data-external-link') != '')
                $(tabs).find( ".ui-tabs-nav" ).sortable("cancel");
		else
		{
		  // $(tabs).tabs( "refresh" );
            	  $(tabs).tabs("destroy");
            	  $(tabs).tabs();
            	  $(tabs).tabs('select', 0);
            	  jQuery("#response").hide();
                  jQuery("#save_guide").fadeIn();
                  //jQuery("#save_template").fadeIn();
		}
              }
	    });
	  });

	  
 
	 </script>

</div> <!--end .guide-parent-wrap-->  


<!-- FLYOUT PANEL-->
<div id="main-options">

  <!--Flyout trigger-->
  <div class="trigger-main-options">
    <i id="trigger-pointer" class="fa fa-chevron-right"></i>
  </div>

  <!-- Top level -->
  <div class="top-panel-options">          
      <ul class="top-panel-options-list">
          
          <li id="show_box_options" class="top-panel-option-item active-item"><a href="#"><img src="<?php print $AssetPath; ?>images/icons/boxes1.svg" title="<?php print _("Boxes"); ?>" class="custom-icon" /><br /><?php print _("Boxes");?></a></li>

          <li id="show_findbox_options" class="top-panel-option-item"><a href="#"><i class="fa fa-search" title="<?php print _("Find Boxes"); ?>" /></i><br /><?php print _("Find Boxes"); ?></a></li>

          <li id="show_layout_options" class="top-panel-option-item"><a href="#"><i class="fa fa-columns" title="<?php print _("Layouts"); ?>" /></i><br /><?php print _("Layouts"); ?></a></li>
          
          <!--<li class="top-panel-option-item"><a id="add_section" href="#"><img src="<?php //print $AssetPath; ?>images/icons/section1.svg" title="<?php //print _("New Section"); ?>" class="custom-icon" /><br />
            <?php //print _("New Section"); ?></a></li>-->           

          <li id="show_dblist_options" class="top-panel-option-item"><a href="#"><i class="fa fa-list" title="<?php print _("Custom List"); ?>" /></i><br /><?php print _("Custom List"); ?></a></li>

          <li id="show_analytics_options" class="top-panel-option-item"><a href="#"><i class="fa fa-pie-chart" title="<?php print _("Analytics"); ?>" /></i><br /><?php print _("Analytics"); ?></a></li>

          <li><a href="#" id="main-options-close"><?php print _("Close"); ?></a></li>
      </ul>

  </div>
  
  <!-- Second-level-->
  <div class="second-level-options">
      
      <div class="second-level-container">

          <!--boxes-->
          <div id="box_options_content" class="second-level-content">
              <h3><?php print _("Add Boxes"); ?></h3>
              <?php print $all_boxes; ?>
              
              <h3><?php print _("Favorite Boxes"); ?></h3>
              <div class="fav-boxes-content">
                  <ul class="fav-boxes-list"></ul>
              </div>

              <h3><?php print _("Remove Boxes"); ?></h3>
              <div class="remove_boxes_content">
                <a class="remove_pluslets" href="#"><?php print _("Remove Boxes from Current Tab"); ?></a>
              </div>            
              
          </div>

       

          <!--find boxes-->
          <div id="findbox_options_content" class="second-level-content" style="display:none;">
              <h3><?php print _("Find Boxes"); ?></h3>
              <!--Find Box Tabs-->
              <div id="find-box-tabs">
                  <ul class="find-box-tab-list">
                    <li><a href="#browse-tab"><?php print _("Browse"); ?></a></li>
                    <li><a href="#search-tab"><?php print _("Search"); ?></a></li>
                  </ul>
                  <div id="browse-tab" class="find-box-tab-list-content">
                        <div class="guides-display">
                            <select class="guide-list">
                                <option>Please select a guide</option>
                            </select>
                            <ul class="pluslet-list"></ul>                          
                        </div>
                  </div>
                  <div id="search-tab" class="find-box-tab-list-content">
                        <div class="searchbox-results-display">
                            <input class="findbox-search" type="text" placeholder="<?php print _("Enter box title..."); ?>"></input>
                            <ul class="findbox-searchresults"></ul>
                        </div>
                  </div>
              </div>
          </div>

          
          <!--layout-->          
          <div id="layout_options_content" class="second-level-content" style="display:none;">
            <h3><?php print _("Choose Layout"); ?></h3>
            
                <ul class="layout_options">
                  <li class="layout-icon" id="col-single" title="<?php print _("1 Column"); ?>"></li>
                  <li class="layout-icon" id="col-double" title="<?php print _("2 Columns"); ?>"></li>
                  <li class="layout-icon" id="col-48" title="<?php print _("Sidebar + Column"); ?>"></li>
                  <li class="layout-icon" id="col-84" title="<?php print _("Column + Sidebar"); ?>"></li>
                  <li class="layout-icon" id="col-triple" title="<?php print _("3 Columns"); ?>"></li>
                  <li class="layout-icon" id="col-363" title="<?php print _("2 Sidebars"); ?>"></li>
                </ul>

            <h3><?php print _("Add New Section"); ?></h3>
                <ul class="layout_options">
                  <li class="top-panel-option-item"><a id="add_section" href="#"><img src="<?php print $AssetPath; ?>images/icons/section2.svg" title="<?php print _("New Section"); ?>" class="custom-icon" /></a></li>
                </ul>           
          </div>

          
          <!--custom database list-->
          <div id="dblist_options_content" class="second-level-content" style="display:none;">
              <h3><?php print _("Create Database List Box"); ?></h3>
              <div class="dblist-display">
                  <select class="customdb-list">
                      <option>Please select a database</option>
                  </select>
                  
                  <div class="databases-results-display pure-form">
                            <input class="databases-search" type="text" placeholder="<?php print _("Enter database title..."); ?>"></input>
                            <ul class="databases-searchresults"></ul>
                        </div>
                        
                  <div class="db-list-content">
                   
                 
                      <h4>Databases Selected:</h4>
                      <ul class="db-list-results">
                         
                      </ul>  
                  </div>
                  <div class="db-list-buttons">
                  <button class="pure-button pure-button-primary dblist-button"><?php print _("Create List Box"); ?></button>
                  <button class="pure-button pure-button-primary dblist-reset-button"><?php print _("Reset List Box"); ?></button>
                   </div>                       
              </div>
          </div>

          
          <!--analytics-->
          <div id="analytics_options_content" class="second-level-content" style="display:none;">
            <h3><?php print _("Analytics"); ?></h3>
            
            <div class="analytics_display">
          
            <span class="total-views-header">Total Views:</span><span class="total-views-count"></span>
           </br>
            <span class="tab-click-header">Clicks on Tabs:</span>
            <ul class="tab-clicks">
            
            </ul>
            </div>
            
            
            <script>             

                $('.tab-clicks').empty();
                     
				$.get("./helpers/stats_data.php?short_form=<?php echo scrubData($_COOKIE['our_shortform']); ?>", function(data) {

					console.log(data);
					console.log(data.total_views);
				$(".total-views-count").html(data.total_views);

				
				for (key in data.tab_clicks) {

					console.log(key);
					
					$(".tab-clicks").append("<li class='tab-click'>" + key + " : " + data.tab_clicks[key] + "</li>");
					
				}

				});
            </script>
            
            
          </div>
       


      </div>
  </div>

</div><!--end #main-options-->

<script>

jQuery(function() {
  
//Top Level Panel Flyout 
  var mainslider = $('#main-options').slideReveal({
    trigger: $(".trigger-main-options"),
    push:false,
    width: 440,
    shown: function(slider, trigger){
      $("#trigger-pointer").addClass("fa-chevron-left");
      $("#trigger-pointer").removeClass("fa-chevron-right");
    },
    hidden: function(slider, trigger){
      $("#trigger-pointer").addClass("fa-chevron-right");
      $("#trigger-pointer").removeClass("fa-chevron-left");
    }
  }); 

  $( "#main-options-close" ).click(function() {
      mainslider.slideReveal("hide");
  });


//Top level Panel Open by default
window.onload = function() {
  mainslider.slideReveal("show");
};


// Show/Hide "Find in Guide" form
$( "#find-trigger" ).click(function() {
      $("#guide_search").toggle("fade", 700 );
  });


// Show "Boxes" options
$( "#show_box_options" ).click(function() {
      selectedPanelDisplay();
      $("#box_options_content").show();
      $(this).addClass("active-item");
  });

// Show "Find Boxes" options
$( "#show_findbox_options" ).click(function() {
      selectedPanelDisplay();
      $("#findbox_options_content").show();
      $(this).addClass("active-item");
  });


// Show "Layout" options
$( "#show_layout_options" ).click(function() {
      selectedPanelDisplay();
      $("#layout_options_content").show();
      $(this).addClass("active-item");
  });


// Show "Custom DB List" options
$( "#show_dblist_options" ).click(function() {      
      selectedPanelDisplay(); 
      $("#dblist_options_content").show();
      $(this).addClass("active-item");
  });


// Show "Analytics" options
$( "#show_analytics_options" ).click(function() {      
      selectedPanelDisplay();    
      $("#analytics_options_content").show(); 
      $(this).addClass("active-item");
  });



//show user favorite boxes
  var staff_id = '<?php echo $_SESSION["staff_id"]; ?>';
  get_user_favorite_boxes(staff_id);

// Select ONLY Active Panel for coresponding Top Level Item
function selectedPanelDisplay(){
     $('.second-level-content').not(this).each(function(){
         $(this).hide();        
       });
     $('.top-panel-option-item').not(this).each(function(){
         $(this).removeClass("active-item");        
       });
   }

 
 //Find Box Tabs - Browse and Search
 $( "#find-box-tabs" ).tabs();

 
 //Load Clone Menu
 loadCloneMenu();

  





  //close box settings panel
  $( ".close-settings" ).click(function() { 
      $(this).parent(".box_settings").hide();
  });



//remove all pluslets from current tab
  $('a.remove_pluslets').on('click', function() {
      var currPanel = $("#tabs").tabs('option', 'active');
      $("#tabs-" + currPanel).find('.pluslet').remove();
      $("#save_guide").fadeIn();
  });






 //Change layout click events
$( "#col-single" ).click(function() {      
      changeLayout(0, 14);
      selectedLayout();
      $(this).addClass("active-layout-icon");
  });

$( "#col-double" ).click(function() {      
      changeLayout(6, 12);
      selectedLayout();
      $(this).addClass("active-layout-icon");
  });

$( "#col-48" ).click(function() {      
      changeLayout(4, 24);
      selectedLayout();
      $(this).addClass("active-layout-icon");
  });

$( "#col-84" ).click(function() {      
      changeLayout(8, 12);
      selectedLayout();
      $(this).addClass("active-layout-icon");
  });

$( "#col-triple" ).click(function() {      
      changeLayout(4, 8);
      selectedLayout();
      $(this).addClass("active-layout-icon");
  });

$( "#col-363" ).click(function() {      
      changeLayout(3, 9);
      selectedLayout();
      $(this).addClass("active-layout-icon");
  });



// Highlight ONLY Selected/Active Layout 
function selectedLayout(){
      $('.layout-icon').not(this).each(function(){
         $(this).removeClass("active-layout-icon");        
       });
   }


//Check section data-layout on pageLoad and hide empty containers
// Highlight "current/active" layout
function checkDataLayout() {

   var dataLayoutConfig =  $("div.sp_section").attr('data-layout');

  if (dataLayoutConfig === "0-12-0") {
    $(".sp_section #container-2").hide();
    $( "#col-single" ).addClass("active-layout-icon");
  }
  else if (dataLayoutConfig === "6-6-0") {
    $(".sp_section #container-2").hide();
    $( "#col-double" ).addClass("active-layout-icon");
  }
  else if (dataLayoutConfig === "4-8-0") {
    $(".sp_section #container-2").hide();
    $( "#col-48" ).addClass("active-layout-icon");
  }
  else if (dataLayoutConfig === "8-4-0") {
    $(".sp_section #container-2").hide();
    $( "#col-84" ).addClass("active-layout-icon");    
  }
  else if (dataLayoutConfig === "4-4-4") {
    $( "#col-triple" ).addClass("active-layout-icon");
  }
  else if (dataLayoutConfig === "3-6-3") {
    $( "#col-363" ).addClass("active-layout-icon");
  }

}

 //Load checkDataLayout
checkDataLayout();


//Fix for FOUC
function fixFlashFOUC() {
  $(".guidewrapper").css("display", "block");
  $("#main-options").css("display", "block");
}

//Load Fix for FOUC
fixFlashFOUC();


//Expand/Collapse Trigger CSS for all Pluslets on a Tab

  $( "#expand_tab" ).click(function() { 
    $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
    $('.pluslet_body').toggle();
    $('.pluslet_body').toggleClass('pluslet_body_closed');
  });




});
</script>


</div> <!--end .guide-parent-wrap-->

<?php include("../includes/footer.php"); ?>
 

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

if (!isset($_GET["subject_id"])) {
  header("location:index.php");
}

// necessary for jquery slider
$use_jquery = array("ui_styles");

// clear out existing cookies


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

  $jobj = json_decode($r[0]["extra"]);

  // In this section, we get the widths of the three columns, which add up to 12
  // We then do a little bit of math to get them into columns that add up to a bit under 100
  // In order to convert to css %.  If this page were bootstrapped, this wouldn't be necessary.
  if (isset($lobj) ) {
  $col_widths = explode("-", $jobj->{'maincol'});
  }

  if (isset($col_widths[0]) && $col_widths[0] > 0) {
    $left_width = $col_widths[0] * 8;
  } else {
    $left_width = 0;
  }

  if (isset($col_widths[1])) {
    $main_width = $col_widths[1] * 8;
  } else {
    $main_width = 0;
  }

  if (isset($col_widths[2]) && $col_widths[2] > 0) {
    $side_width = ($col_widths[2] * 8) - 3; // we make this a squidgen narrower so it doesn't wrap nastily
  } else {
    $side_width = 0;
  }

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

$all_boxes = "
<ul id=\"box_options\">
<li class=\"box_note box-item\">" . _("Drag selection, then drop to right") . "</li>";

foreach( $pluslets_activated as $lstrPluslet )
{
  if( file_exists( dirname(dirname(__DIR__)) . "/lib/SubjectsPlus/Control/Pluslet/$lstrPluslet.php" ) )
  {
    $lstrObj = "SubjectsPlus\Control\Pluslet_" . $lstrPluslet;

    if( method_exists( $lstrObj, 'getMenuName' ) )
    {
      $all_boxes .= "<li class=\"box-item draggable\" id=\"pluslet-id-$lstrPluslet\" ckclass='" . call_user_func(array( $lstrObj, 'getCkPluginName' )) . "'>" . call_user_func(array( $lstrObj, 'getMenuName' )) . "</li>";
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

$q = "SELECT distinct pluslet_id, title, body
FROM pluslet
WHERE type = 'Special'
$conditions
";

$r = $db->query($q);

foreach ($r as $myrow) {
  $lstrObj = "SubjectsPlus\Control\Pluslet_" . $myrow[0];
  $all_boxes .= "<li class=\"box-item draggable clone\" id=\"pluslet-id-" . $myrow[0] . "\" ckclass='" . call_user_func(array( $lstrObj, 'getCkPluginName' )) . "'>\n";
  $all_boxes .= $myrow[1] . "</li>";
}

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
 var l_c = "<?php print $left_width; ?>";
 var new_left_width = "<?php print $left_width; ?>%";
 var new_main_width = "<?php print $main_width; ?>%";
 var r_c = "<?php print $side_width; ?>";
 var new_sidebar_width = "<?php print $side_width; ?>%";

 // This will be changed by using the Find button, and selecting a clone to insert
 window.addItem = 0;

 // Hides the global nav on load
 jQuery("#header, #subnavcontainer").hide();



 jQuery(document).ready(function(){
   jQuery("#box_options").hide();

   //layout each section
   $('div[id^="section_"]').each(function()
    				 {
       //section id
       var sec_id = $(this).attr('id').split('section_')[1];
       var lobjLayout = $('div#section_' + sec_id).attr('data-layout').split('-');

       var lw = parseInt(lobjLayout[0]) * 8;
       var mw = parseInt(lobjLayout[1]) * 8;
       var sw = parseInt(lobjLayout[2]) * 8 - 3;
       try {
	 reLayout(sec_id, lw, mw, sw);
       } catch (e) {



       }


     });

   function addBoxy(){
     jQuery("#box_options").show();
     return;

   }

   function removeBoxy(){
     jQuery("#box_options").hide();
     return;
   }

   var boxyConfig = {
     interval: 50,
     sensitivity: 4,
     over: addBoxy,
     timeout: 500,
     out: removeBoxy
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

   var ov = '<?php print $jobj->{'maincol'}; ?>';
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
   tabTemplate = "<li class=\"dropspotty\"><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-wrench' role='presentation'>Remove Tab</span></li>",
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

     var slim = jQuery.ajax({
       url: "helpers/section_data.php",
       type: "POST",
       data: { action : 'create' },
       dataType: "html",
       success: function(html) {
         tabs.tabs("destroy");

         tabs.append( "<div id='" + id + "' class=\"sptab ui-tabs-hide\">" + html
                      + "</div>" );

         jQuery("#response").hide();
         jQuery("#save_guide").fadeIn();

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
   tabs.delegate( "span.ui-icon-wrench", "click", function(lobjClicked) {
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

<div id="guide_header">
  <div class="pure-g">

    <div class="pure-u-1-2">
      <form class="pure-form" id="guide_search">
        <ul id="guide_nav">
          <li id="hide_header"><img src="<?php print $AssetPath; ?>images/icons/menu-26.png" title="<?php print _("show/hide header"); ?>" /></li>
          <li id="newbox" class="togglenewz"><a href="#"><img src="<?php print $AssetPath; ?>images/icons/down_circular-white-26.png" alt="" /><?php print _("New Box");?></a>
            <?php print $all_boxes; ?>
          </li>
          <li><a id="add_section" href="#"><img src="<?php print $AssetPath; ?>images/icons/section-white.png" title="<?php print _("New Section"); ?>" /><span class="desktop"><?php print _("New Section"); ?></span></a></li>
          <li><a class="showdisco" href="helpers/discover.php"><img src="<?php print $AssetPath; ?>images/icons/find-white.png" title="<?php print _("Find Box"); ?>" /><span class="desktop"><?php print _("Find Box"); ?></span></a></li>


	  <li class="find-guide-parent">
	    <input class="find-guide-input" type="text" placeholder="<?php print _("Find in Guide"); ?>"></input>
	  </li>

	  <script>

	   var startURL = '../guides/guide.php?subject_id=';
	   var sp_path = document.URL.split('/')[3];

	   jQuery('.find-guide-input').autocomplete({

	     minLength	: 3,
	     source		: '//' + document.domain + "/" + sp_path + "/control/includes/autocomplete_data.php?collection=guide&subject_id=" + <?php echo $this_id; ?> ,
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

          <!--<li class="selected"></li>-->
        </ul>
      </form>
    </div> <!-- end pure 1-2-->
    <div class="pure-u-1-2"> <h2>
      <?php print "<a target=\"_blank\" href=\"$PublicPath" . "guide.php?subject=$shortform\">$subject_name</a>"; ?>
      <a href="<?php print $PublicPath . "guide.php?subject=$shortform"; ?>" target="_blank"><img class="icon-view-guide" src="<?php print $AssetPath; ?>images/icons/visible-white-26.png" title="<?php print _("View Guide"); ?>" /></a>
      <a class="showmeta" href="<?php print $CpanelPath . "guides/metadata.php?subject_id=$subject_id" . "&amp;wintype=pop"; ?>"><img class="icon-edit-guide" src="<?php print $AssetPath; ?>images/icons/settings-white-26.png" title="<?php print _("Edit Guide Metadata"); ?>" /></a>


    </h2></div><!-- end pure 1-2-->
  </div> <!-- end pure -->
</div> <!-- end guide header-->



<input id="extra" type="hidden" size="1" value="<?php

if (isset($lobj)) {
 print $jobj->{'maincol'}; 

}

?>" name="extra" />

<!-- Save Button -->
<p align="center" id="savour"><button class="button pure-button pure-button-primary" id="save_guide"><?php print _("SAVE CHANGES"); ?></button></p>
	 </div>
	 <!-- end guide header -->

	 <!-- Feedback -->
	 <div id="response"></div>

	 <!-- new tab form (suppressed until wrench clicked) -->
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

	 <!-- edit tab form (suppressed until wrench clicked) -->
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
		}
              }
	    });
	  });




 
	 </script>

	 <div class="guidewrapper">
	   <div id="tabs">

	     <?php $lobjGuide->outputNavTabs(); ?>

	     <?php
	     $lobjGuide->outputTabs();
	     ?>

	   </div>
	 </div>
	 <?php include("../includes/footer.php"); ?>

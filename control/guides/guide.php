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

try {$dbc = new sp_DBConnector($uname, $pword, $dbName_SPlus, $hname);} catch (Exception $e) { echo $e;}

$postvar_subject_id = scrubData($_GET['subject_id']);

$this_id = $_GET["subject_id"];
$clone = 0;

// See if they have permission to edit this guide
if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
    $q = "SELECT staff_id from staff_subject WHERE subject_id = '$this_id'
    AND staff_id = '" . $_SESSION["staff_id"] . "'";

    $r = mysql_query($q);
    $num_rows = mysql_num_rows($r);

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
    $ra = mysql_query($qa);

    //$new_clone = mysql_fetch_row($ra);
}

if (isset($this_id)) {
    $subject_id = $_GET["subject_id"];
    // get name of quide
    $q = "SELECT subject, shortform, active, extra from subject where subject_id = '$subject_id'";

    $r = mysql_query($q);

    // If this guide doesn't exist, send them away
    if (mysql_numrows($r) == 0) {
        header("location:index.php");
    }



    $mysub = mysql_fetch_array($r);

    $subject_name = $mysub["0"];
    $shortform = $mysub["1"];

    $jobj = json_decode($mysub["extra"]);

    // In this section, we get the widths of the three columns, which add up to 12
    // We then do a little bit of math to get them into columns that add up to a bit under 100
    // In order to convert to css %.  If this page were bootstrapped, this wouldn't be necessary.
    $col_widths = explode("-", $jobj->{'maincol'});

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
    $lobjGuide = new sp_Guide($this_id);
	$lobjGuide->_isAdmin = TRUE;

    $all_tabs = $lobjGuide->getTabs();

} else {
    print "no guide";
}






////////////////////////////
// Now, get our pluslets //
///////////////////////////

$all_boxes = "
<ul id=\"box_options\" style=\"display: none;\">
<li class=\"box_note\">" . _("Drag selection, then drop to right") . "</li>
<li class=\"draggable special-new\" id=\"pluslet-id-Basic\">" . _("Editable Box") . "</li>
<li class=\"draggable special-heading\" id=\"pluslet-id-Heading\">" . _("Heading") . "</li>
<li class=\"draggable special-rss\" id=\"pluslet-id-Feed\">" . _("Delicious/RSS/Flickr/Twitter") . "</li>
<li class=\"draggable special-rss\" id=\"pluslet-id-TOC\">" . _("Table of Contents") . "</li>
";

// Now get Special ones
// make sure:  a) there are some linked resources (to show All Items by Source)

$conditions = "";

$q1 = "SELECT rank_id FROM rank WHERE subject_id = '$this_id'";

$r1 = mysql_query($q1);

$num_resources = mysql_num_rows($r1);

if ($num_resources == 0) {
    $conditions = "AND pluslet_id != '1'";
}

$q = "SELECT distinct pluslet_id, title, body
FROM pluslet
WHERE type = 'Special'
$conditions
";

$r = mysql_query($q);

while ($myrow = mysql_fetch_array($r)) {
    $all_boxes .= "<li class=\"draggable clone\" style=\"z-index: 10\" id=\"pluslet-id-" . $myrow[0] . "\">\n";
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

<style type="text/css">@import url(../../assets/css/guide.css);</style>

<script type="text/javascript" src="guide.js"></script>

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
    //jQuery("#header, #subnavcontainer").hide();

    jQuery(document).ready(function(){

        // Adjust our three columns according to data from extra field
        if (l_c < 8) {
                    jQuery('.sptab div#container-0').width(0);
                    jQuery('.sptab div#container-0').hide();
        } else {
                    jQuery('.sptab div#container-0').show();
                    jQuery('.sptab div#container-0').width(new_left_width);
        }


        jQuery('#container-1').width(new_main_width);

        if (r_c < 8) {
                    jQuery('.sptab div#container-2').width(0);
                    jQuery('.sptab div#container-2').hide();
        } else {

                    jQuery('.sptab div#container-2').show();
                    jQuery('.sptab div#container-2').width(new_sidebar_width);
        }


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

    // config our box for tabs
/*
    function addTabOptionsBox(){
        jQuery("#tabs_options").show();
        return;

    }

    function removeTabOptionsBox(){
        jQuery("#tabs_options").hide();
        //alert ($( "#extra" ).val());
        return;
    }

    var tabsOptionsConfig = {
        interval: 50,
        sensitivity: 4,
        over: addTabOptionsBox,
        timeout: 500,
        out: removeTabOptionsBox
    };

    jQuery("#tabsbox").hoverIntent(tabsOptionsConfig);
    jQuery("#tabsbox input[type='text']").change(function() {
        jQuery("#save_tab").show();
    });

  */

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

    jQuery( "#extra" ).val(jQuery( "#slider" ).slider( "value" ) );

    jQuery('button[id=save_layout]').click(function(event) {
        var ourcols = jQuery( "#extra" ).val();
        new_layout = jQuery.ajax({
            url: "helpers/save_layout.php",
            type: "POST",
            data: ({cols: ourcols, subject_id: subject_id}),
            dataType: "html",
            success: function(html) {
                var ourval = jQuery( "#extra" ).val().split("-");

                var lc = parseInt(ourval[0]) *8 + "%";
                var cc = parseInt(ourval[1]) *8 + "%";
                var rc = parseInt(ourval[2]) *8 + "%";
                //alert("left: " + lc + "; center " + cc + ";right: " + rc);

                // now insert these vals to update columns
                if (parseInt(ourval[0]) == 0) {
                    jQuery('.sptab div#container-0').width(0);
                    jQuery('.sptab div#container-0').hide();
                } else {
                    jQuery('.sptab div#container-0').show();
                    jQuery('.sptab div#container-0').width(lc);
                }

                jQuery('#container-1').width(cc);

                if (parseInt(ourval[2]) == 0) {
                    jQuery('.sptab div#container-2').width(0);
                    jQuery('.sptab div#container-2').hide();
                } else {
                    jQuery('.sptab div#container-2').show();
                    jQuery('.sptab div#container-2').width(rc);
                }


            }
        });
    });


     jQuery("div#tabs ul li a").dblclick(function () {

        alert("doublclickitude!");
     })
});

//setup jQuery UI tabs and dialogs
jQuery(function() {
    var tabTitle = $( "#tab_title" ),
    tabContent = $( "#tab_content" ),
    tabTemplate = "<li class=\"dropspotty\" style=\"height: auto;\"><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-wrench' role='presentation'>Remove Tab</span></li>",
    tabCounter = <?php echo ( count($all_tabs) ); ?>;
    var tabs = $( "#tabs" ).tabs();

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
            "Rename": function() {
              var id = window.lastClickedTab.replace("#tabs-", "");

              $( 'a[href="#tabs-' + id + '"]' ).text( $('input[name="rename_tab_title"]').val() );

              $( this ).dialog( "close" );
              $('#save_guide').fadeIn();
            },
            "Delete" : function() {
              var id = window.lastClickedTab.replace("#tabs-", "");

              $( 'a[href="#tabs-' + id + '"]' ).parent().remove();
              $( 'div#tabs-' + id ).remove();
              tabs.tabs("destroy");
              tabs.tabs();
              $( this ).dialog( "close" );
              $('#save_guide').fadeIn();
            },
            Cancel: function() {
            $( this ).dialog( "close" );
            }
        },
        open: function(event, ui) {
          var id = window.lastClickedTab.replace("#tabs-", "");
          $(this).find('input[name="rename_tab_title"]').val($( 'a[href="#tabs-' + id + '"]' ).text());
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
        id = "tabs-" + tabCounter,
        li = $( tabTemplate.replace( /#\{href\}/g, "#" + id ).replace( /#\{label\}/g, label ) ),
        tabContentHtml = tabContent.val() || "Tab " + tabCounter + " content.";
        tabs.find( ".ui-tabs-nav" ).append( li );

        var slim = jQuery.ajax({
                    url: "helpers/create_tab.php",
                    type: "GET",
                    data: {},
                    dataType: "html",
                    success: function(html) {
                        tabs.tabs("destroy");

                        tabs.append( "<div id='" + id + "'>" + html
                         + "</div>" );

                        jQuery("#response").hide();
                        jQuery("#save_guide").fadeIn();

                        tabs.tabs();
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
        refreshFeeds();
    });
</script>

<div id="guide_header">
    <div id="guide_nav">
        <ul>
            <li id="hide_header"><img src="<?php print $AssetPath; ?>images/icons/expand.png" alt="show/hide header" /></li>
            <li id="newbox" class="togglenewz"><img src="<?php print "$IconPath/box.png"; ?>" alt="new box" border="0" /> <?php
            print _("New Box");
            print $all_boxes;
            ?></li>
        <li class="showdisco" href="helpers/discover.php"><?php print _("Find Box"); ?></li>
        <li class="showrecord" href="../records/record.php?wintype=pop&amp;caller_id=<?php print $subject_id; ?>"><?php print _("New Record"); ?></li>
        <li class="showmeta" href="metadata.php?subject_id=<?php print $subject_id; ?>&amp;wintype=pop"><?php print _("Metadata"); ?></li>
        <li id="layoutbox" class="togglelayout"><?php print _("Layout"); ?>
            <div id="slider_options" style="display: none; width: 200px; padding: 1em;">
                <p><?php print _("Adjust column sizes"); ?></p>
                <div id="slider"></div>
                <button id="save_layout" style="display:none;clear: left;margin-top: 1em;font-size: smaller;"><?php print _("SAVE CHANGES"); ?></button>
            </div>
        </li>
        <!--<li id="tabsbox" class="toggletab"><?php print _("Tabs"); ?>
            <div id="tabs_options" style="display: none; width: 200px; padding: 1em;">
                <p><?php print _("Rename/Add Tabs"); ?></p>
                <?php print $tabs_input; ?>
                <button id="save_tab_options" style="display:none;clear: left;margin-top: 1em;font-size: smaller;"><?php print _("SAVE CHANGES"); ?></button>
            </div>
        </li>-->
    </ul>
</div> <!-- end guide_nav -->
<input id="extra" type="hidden" size="1" value="<?php print $jobj->{'maincol'}; ?>" name="extra" />

<div id="subject_title"><h2><?php print "<a target=\"_blank\" href=\"$PublicPath" . "guide.php?subject=$shortform\">$subject_name</a>"; ?></h2></div>

</div>  <!-- end guide header -->

<!-- Feedback -->
<div id="response" style="position: relative; float: left; background-color: #DD647F; width: 100%; text-align:center; display: none;"></div>

<!-- Save Button -->
<p align="center" id="savour" style="clear: both;float:left; margin-top: 5px; height: 30px; width: 100%;"><button id="save_guide" style="display:none;"><?php print _("SAVE CHANGES"); ?></button></p>
<div id="dialog" title="Tab data">
<form>
<fieldset class="ui-helper-reset">
<label for="tab_title">Title</label>
<input type="text" name="tab_title" id="tab_title" value="" class="ui-widget-content ui-corner-all" />
</fieldset>
</form>
</div>
<div id="dialog_edit" title="Tab edit">
<form>
<fieldset class="ui-helper-reset">
<label for="tab_title">New Title</label>
<input type="text" name="rename_tab_title" id="tab_title" value="" class="ui-widget-content ui-corner-all" />
</fieldset>
</form>
</div>
<script>
//make tabs sortable
jQuery(function() {
    $(tabs).find( ".ui-tabs-nav" ).sortable({
        axis: "x",
        stop: function(event, ui) {
            if(jQuery(ui.item).attr("id") == 'add_tab' || jQuery(ui.item).parent().children(':first').attr("id") != 'add_tab')
                $(tabs).find( ".ui-tabs-nav" ).sortable("cancel");
            else
            {
               // $(tabs).tabs( "refresh" );
            	tabs.tabs("destroy");
            	tabs.tabs();
            	jQuery("#response").hide();
                jQuery("#save_guide").fadeIn();
            }
        }
    });
});
</script>

<div id="tabs" style="clear:both; position: relative; width: 96%; margin: 0 auto;">

<?php $lobjGuide->outputNavTabs(); ?>

<?php
$lobjGuide->outputTabs();
?>
</div>

<?php include("../includes/footer.php"); ?>

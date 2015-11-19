<?php

/**
 *   @file guide_data.php
 *   @brief Manages some CRUDdy stuff.  Called by guide.js and guide.php.
 *
 *   @author adarby
 *   @date Dec 2012
 */
use SubjectsPlus\Control\Querier;

$subcat = "guides";
$header = "noshow"; // authentication only

include("../../includes/header.php");

$location = "";
$is_sidebar = "";
$our_subject_id = "";

//print_r($_POST);

///////////////////////////
// Determine Column (main or sidebar)
// * establishes differently sized input and textareas
///////////////////////////

if (isset($_REQUEST["to"])) {
    $location = $_REQUEST["to"];
    $is_sidebar = strpos($_REQUEST["to"], "sidebar");
}

if (isset($_REQUEST["this_subject_id"])) {
  $our_subject_id = $_REQUEST["this_subject_id"];
}



if ($is_sidebar !== false) {
    $cols = 30;
    $rows = 4;
    $title_input_size = "";
} else {
    $cols = 50;
    $rows = 4;
    $title_input_size = "";
}



/////////////////////////
// Route Request
/////////////////////////

switch ($_POST["flag"]) {
    case "drop":

        // post[from] could be: pluslet-cloneid-1481
        $box_type = explode("-", $_POST["from"]);

        // New or Clone or Special?
        // TODO special type
        if ($box_type[1] == "cloneid") {
            $isclone = 1;
            $our_type = $_POST["item_type"];
            $our_id = $box_type[2];
            //print "Okay, it's a clone! <p>";
        } else {
            $isclone = 0;
            $our_type = $box_type[2];
            $our_id = "";
        }

        $obj = "SubjectsPlus\Control\Pluslet_" . $our_type;
        //global $obj;
        $record = new $obj($our_id, "", $our_subject_id, $isclone);
        print $record->output("edit", "admin");


        break;
    case "modify":

        $obj = "SubjectsPlus\Control\Pluslet_" . $_POST["type"];
        //global $obj;
        $record = new $obj($_POST["edit"], "", $our_subject_id);
        print $record->output("edit", "admin");

        return;
        break;

    case "insert":
        //print "<p>now we're doing an insert, sez guide_data.php";
        // if it's a clone, note that
        $this_id = modifyDB("", "insert");


        if ($this_id) {
            $obj = "SubjectsPlus\Control\Pluslet_" . $_POST["item_type"];
            //print "obj = $obj<p>";
            //global $obj;
            $record = new $obj($this_id, "", $our_subject_id);

            print $record->output("view", "admin");
        } else {
            print "<p>data2.php says there was a problem</p>";
        }

        break;

    case "update":
        //print "<p>now we're doing an update, sez data2.php";
        // find out if this is a clone or not

        $this_id = modifyDB($_POST["update_id"], "update");

        $obj = "SubjectsPlus\Control\Pluslet_" . $_POST["item_type"];
        //global $obj;
        $record = new $obj($_POST["update_id"], "", $our_subject_id);
        print $record->output("view", "admin");

        break;

    case "settings":
    	$this_id = modifyDB($_POST["update_id"], "settings");

    	$obj = "SubjectsPlus\Control\Pluslet_" . $_POST["item_type"];
    	//global $obj;
    	$record = new $obj($_POST["update_id"], "", $our_subject_id);
    	print $record->output("view", "admin");

    	break;

    case "delete":
    	$db = new Querier();

        $delete_id = scrubData($_POST["delete_id"], "int");
        $subject_id = scrubData($_POST["subject_id"], "int");

        $q = "DELETE FROM `pluslet` where pluslet_id = '$delete_id' AND type != 'Special'";
        $r = $db->exec($q);

    	//added by dgonzalez because if pluslet is special, no deletetion so need to manually delete relationship
    	if( count($r) == 0 )
    	{
    		$q2 = "DELETE ps FROM `pluslet_section` ps
    				INNER JOIN section sec
    				ON ps.section_id = sec.section_id
    				INNER JOIN tab t
    				ON sec.tab_id = t.tab_id
    				INNER JOIN subject s
    				ON t.subject_id = s.subject_id
    				WHERE pt.pluslet_id = '$delete_id' AND s.subject_id = '$subject_id'";

    		$r2 = $db->query($q2);
    	}

    	//removed by david because new db referential integrity does this automatically
    /*    
    $q2 = "DELETE FROM `pluslet_subject` where pluslet_id = '$delete_id' AND subject_id = '$subject_id'";
        $r2 = $db->query($q2);
*/      
  //print $q2;

        print _("<div class=\"feedback\" style=\"display: block;\">Box Removed</div>");
        break;
}

/////////////////
// modifyDB
//////////////////

function modifyDB($id, $type) {
    $db = new Querier;
   /*  print "<pre>";
      print_r($_POST);
      print "</pre>"; */
    // Uses the data from the POST vars to update
    $pluslet_title = isset($_POST["pluslet_title"]) ? $_POST["pluslet_title"] : '';
    $pluslet_body = isset($_POST["pluslet_body"])? $_POST["pluslet_body"] : '';
    $pluslet_type = isset($_POST["item_type"]) ? $_POST["item_type"] : '';
    $pluslet_extra = isset($_POST["special"]) ? $_POST["special"] : '';
    $pluslet_hide_titlebar = $_POST["boxsetting_hide_titlebar"];
    $pluslet_collapse_body = $_POST["boxsetting_collapse_titlebar"];
    $pluslet_favorite_box = $_POST["favorite_box"];
    $pluslet_target_blank_links = $_POST['boxsetting_target_blank_links'];


    if (isset($_POST["boxsetting_titlebar_styling"])) {

        $pluslet_titlebar_styling = $_POST["boxsetting_titlebar_styling"];

    } else {

        $pluslet_titlebar_styling = null;
    }

    // If clone isn't set, set to 0
    if (isset($_POST["clone"])) {
        $pluslet_clone = $_POST["clone"];
    } else {
        $pluslet_clone = 0;
    }
    // let's not have those errant slashes
    if (get_magic_quotes_gpc ()) {
        $pluslet_title = stripcslashes(stripcslashes($pluslet_title));
        $pluslet_body = stripslashes(stripslashes($pluslet_body));
        $pluslet_extra = stripslashes(stripslashes($pluslet_extra));
    } else {
        $pluslet_title = stripcslashes($pluslet_title);
        $pluslet_body = stripslashes($pluslet_body);
        $pluslet_extra = stripslashes($pluslet_extra);
    }
    switch ($type) {
        case "insert":
            $q = sprintf("INSERT INTO pluslet (title, body, type, clone, extra, hide_titlebar, collapse_body, titlebar_styling, favorite_box, target_blank_links) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", $db->quote($pluslet_title), $db->quote($pluslet_body), $db->quote($pluslet_type), $db->quote($pluslet_clone), $db->quote($pluslet_extra), $db->quote($pluslet_hide_titlebar), $db->quote($pluslet_collapse_body), $db->quote($pluslet_titlebar_styling), $db->quote($pluslet_favorite_box), $db->quote($pluslet_target_blank_links));
            $db = new Querier;
            $r = $db->exec($q);
            if ($r) {
                               $id = $db->last_id();

            	            } else {
                print "<p>There was a problem with your insert:</p>";
                print "<p>$q</p>";

                                $id = false;
            }
            break;
        case "update":
            // update pluslet table
            //print "$pluslet_extra";
            //$q = sprintf("UPDATE pluslet set title = '%s', body = '%s', type = '%s', extra = '%s' WHERE pluslet_id = '$id'", $db->quote($pluslet_title), $db->quote($pluslet_body), $db->quote($pluslet_type), $db->quote($pluslet_clone), $pluslet_extra);
            $q = "UPDATE pluslet SET
                title=" . $db->quote($pluslet_title) . ",
                body=" . $db->quote($pluslet_body) . ",
                type=" . $db->quote($pluslet_type) . ",
                extra=" . $db->quote($pluslet_extra) . ",
                hide_titlebar  = '$pluslet_hide_titlebar',
                collapse_body = '$pluslet_collapse_body',
                titlebar_styling = '$pluslet_titlebar_styling',
                favorite_box = '$pluslet_favorite_box',
                target_blank_links = '$pluslet_target_blank_links'
                WHERE pluslet_id ='$id'";
            $r = $db->exec($q);
            //print $q;
            if ($r === FALSE) {
                print "<p>There was a problem with your insert:</p>";
                print "<p>$q</p>";
                $id = false;
            }
            break;
        case "settings":
        	// update pluslet table for only settings
        	$q = "UPDATE pluslet SET
                hide_titlebar  = '$pluslet_hide_titlebar',
                collapse_body = '$pluslet_collapse_body',
                titlebar_styling = '$pluslet_titlebar_styling',
                favorite_box = '$pluslet_favorite_box',
                target_blank_links = '$pluslet_target_blank_links'
                WHERE pluslet_id ='$id'";
        	$r = $db->exec($q);
        	//print $q;
        	if ($r === FALSE) {
        		print "<p>There was a problem with your insert:</p>";
        		print "<p>$q</p>";
        		$id = false;
        	}
        	break;
        case "delete":
            $q = "DELETE FROM pluslets WHERE pluslet_id = '$id'";
            $r = $db->query($q);
            break;
    }
    return $id;
}
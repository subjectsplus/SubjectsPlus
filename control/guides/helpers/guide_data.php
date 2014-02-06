<?php

/**
 *   @file guide_data.php
 *   @brief Manages some CRUDdy stuff.  Called by guide.js and guide.php.
 *
 *   @author adarby
 *   @date Dec 2012
 */
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
    $title_input_size = "30";
} else {
    $cols = 50;
    $rows = 4;
    $title_input_size = "40";
}


// Connect to database
try {$dbc = new sp_DBConnector($uname, $pword, $dbName_SPlus, $hname);} catch (Exception $e) { echo $e;}

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

        $obj = "sp_Pluslet_" . $our_type;
        global $obj;
        $record = new $obj($our_id, "", $our_subject_id, $isclone);
        print $record->output("edit", "admin");


        break;
    case "modify":

        $obj = "sp_Pluslet_" . $_POST["type"];
        global $obj;
        $record = new $obj($_POST["edit"], "", $our_subject_id);
        print $record->output("edit", "admin");

        return;
        break;

    case "insert":
        //print "<p>now we're doing an insert, sez guide_data.php";
        // if it's a clone, note that
        $this_id = modifyDB("", "insert");

        //print "this id = $this_id; our sub id = $our_subject_id<p>";
        if ($this_id) {
            $obj = "sp_Pluslet_" . $_POST["item_type"];
            //print "obj = $obj<p>";
            global $obj;
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

        $obj = "sp_Pluslet_" . $_POST["item_type"];
        global $obj;
        $record = new $obj($_POST["update_id"], "", $our_subject_id);
        print $record->output("view", "admin");

        break;

    case "delete":
        $delete_id = scrubData($_POST["delete_id"], "int");
        $subject_id = scrubData($_POST["subject_id"], "int");

        $q = "DELETE FROM `pluslet` where pluslet_id = '$delete_id' AND type != 'Special'";
        $r = mysql_query($q);

    	//added by dgonzalez because if pluslet is special, no deletetion so need to manually delete relationship
    	if( mysql_affected_rows() == 0 )
    	{
    		$q2 = "DELETE pt FROM `pluslet_tab` pt INNER JOIN tab t
    				ON pt.tab_id = t.tab_id
    				INNER JOIN subject s
    				ON t.subject_id = s.subject_id
    				WHERE pt.pluslet_id = '$delete_id' AND s.subject_id = '$subject_id'";

    		$r2 = mysql_query($q2);
    	}

    	//removed by david because new db referential integrity does this automatically
        //$q2 = "DELETE FROM `pluslet_subject` where pluslet_id = '$delete_id' AND subject_id = '$subject_id'";
        //$r2 = mysql_query($q2);
        //print $q2;

        print _("<script type='text/javascript'>$.growl({message: 'The box was removed.', title:'" . _("Box Removed") . "'})</script>");
        break;
}

/////////////////
// modifyDB
//////////////////

function modifyDB($id, $type) {
    /* print "<pre>";
      print_r($_POST);
      print "</pre>"; */
    // Uses the data from the POST vars to update
    $pluslet_title = $_POST["pluslet_title"];
    $pluslet_body = $_POST["pluslet_body"];
    $pluslet_type = $_POST["item_type"];
    $pluslet_extra = $_POST["special"];
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
            $q = sprintf("INSERT INTO pluslet (title, body, type, clone, extra) VALUES ('%s', '%s', '%s', '%s', '%s')", mysql_real_escape_string($pluslet_title), mysql_real_escape_string($pluslet_body), mysql_real_escape_string($pluslet_type), mysql_real_escape_string($pluslet_clone), mysql_real_escape_string($pluslet_extra));
            $r = mysql_query($q);
            if ($r) {
                $id = mysql_insert_id();
            	// If successful inserted, add link to plulset_staff table
            	// removed 2013 as unnecessary
            	/*
                //print "INSERT ID = $id";
                $staff_id = $_POST["staff_id"];
                $q2 = "INSERT INTO pluslet_staff (pluslet_id, staff_id) VALUES ('$id', '$staff_id')";
                //print $q2;
                $r2 = mysql_query($q2);
                */
            } else {
                print "<p>There was a problem with your insert:</p>";
                print "<p>$q</p>";
                $id = false;
            }
            break;
        case "update":
            // update pluslet table
            //print "$pluslet_extra";
            //$q = sprintf("UPDATE pluslet set title = '%s', body = '%s', type = '%s', extra = '%s' WHERE pluslet_id = '$id'", mysql_real_escape_string($pluslet_title), mysql_real_escape_string($pluslet_body), mysql_real_escape_string($pluslet_type), mysql_real_escape_string($pluslet_clone), $pluslet_extra);
            $q = "UPDATE pluslet SET
                title='" . mysql_real_escape_string($pluslet_title) . "',
                body='" . mysql_real_escape_string($pluslet_body) . "',
                type='" . mysql_real_escape_string($pluslet_type) . "',
                extra = '$pluslet_extra'
                WHERE pluslet_id ='$id'";
            $r = mysql_query($q);
            //print $q;
            if (!$r) {
                print "<p>There was a problem with your insert:</p>";
                print "<p>$q</p>";
                $id = false;
            }
            break;
        case "delete":
            $q = "DELETE FROM pluslets WHERE pluslet_id = '$id'";
            $r = mysql_query($q);
            break;
    }
    return $id;
}

?>

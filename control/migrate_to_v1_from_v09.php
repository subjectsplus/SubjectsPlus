<?php

/*
 * Migrating from version 0.9 to 1.0
 * Don't use if you aren't on version 0.9 already!!!
 */

// Set all of these conditions to TRUE to run script for first time
// If something went wrong, you might want to selectively turn off 
// certain functions

// Staff table
$fixPermissions = FALSE;
$addStaffDeets = FALSE;
$removeOldPermissions = FALSE;
// Location table
$addCallnum = FALSE;
$addCtags = FALSE;
$updateCtags = FALSE;
$removeOldLocations = FALSE;
//Pluslets
$updatePlusletTypes = FALSE;
$addExtraFieldToPluslet = FALSE;
$convertToJson = FALSE;
$updateTypeCol = FALSE;
//Subject
$updateSubjectTable = FALSE;
//TalkBack
$updateTBTable = FALSE;

// Shouldn't have to change anything below here . . .
$subcat = "admin";
$page_title = "Migration script";

include("includes/header.php");



// ALTER TABLE staff ADD ptags VARCHAR( 255 ) NULL DEFAULT NULL, ADD active INT( 1 ) NOT NULL DEFAULT '1', ADD extra VARCHAR( 255 ) NULL DEFAULT NULL, ADD bio TEXT NULL DEFAULT NULL
// UPDATE `ithaca2_live2`.`staff` SET `ptags` = 'talkback|faq|records|eresource_mgr|admin' WHERE `staff`.`staff_id` =9;

/////////////////
// 1. Fix Permissions/Modify Staff table
/////////////////

if ($fixPermissions) {

    $querierUser = new Querier();
    $qUser = "SELECT staff_id, email, talkback, rxs, rxs_eres, admin, faq FROM staff WHERE user_type_id = 1";
    $userArray = $querierUser->query($qUser);

//print_r($userArray);

    foreach ($userArray as $value) {

        //print "<p>" . $value[1] . ": talkback: $value[2]; rxs: $value[3]; rxs_eres: $value[4]; admin: $value[admin]</p>";
        $new_permissions = "";
        if ($value[talkback] == 1) {
            $new_permissions .= "talkback|";
        }
        if ($value[rxs] == 1) {
            $new_permissions .= "records|";
        }
        if ($value[rxs_eres] == 1) {
            $new_permissions .= "eresource_mgr|";
        }
        if ($value[admin] == 1) {
            $new_permissions .= "admin|";
        }
        if ($value[faq] == 1) {
            $new_permissions .= "faq|";
        }
        $new_permissions = substr($new_permissions, 0, -1);


        //$querierUpdate = new sp_Querier();
        $q2 = "UPDATE staff SET ptags = '$new_permissions' WHERE staff_id = '" . $value["staff_id"] . "'";
        $r = $db->query($q2);

        if ($r) {
            print "<p>Permissions fixed for $value[1]";
        } else {
            print "<p>Problem with permissions for $value[1].  Query run:  $q2</p>";
        }
    }
}

//////////////////
// 2. Remove old permission columns
//////////////////
//

if ($removeOldPermissions) {
    $q = "ALTER TABLE `staff` DROP `talkback` ,
    DROP `rxs` ,
    DROP `rxs_eres` ,
    DROP `admin` ,
    DROP `faq`";

    $r = $db->query($q);
    if ($r) {
        print "<p>Obsolete fields removed from staff table.</p>";
    } else {
        print "<p>Problem removing obsolete fields from staff table. Query = $q</p>";
    }
}

/////////////////
// 2b  Move staff details into staff table
/////////////////

if ($addStaffDeets) {
    $querierUser = new Querier();
    $qUser = "SELECT s.staff_id, sd.responsibilities, sd.education, sd.affiliations, sd.since
        FROM staff s, staff_details sd
        WHERE s.staff_id = sd.staff_id
        ORDER BY s.staff_id";
    $userArray = $querierUser->query($qUser);

//print_r($userArray);

    foreach ($userArray as $value) {
        $staff_deets = "";
        // test for each category
        if ($value[1] != '') {
            $staff_deets .= "<h3>Responsibilities</h3>
                $value[1]";
        }

        if ($value[2] != '') {
            $staff_deets .= "<h3>Education</h3>
                $value[2]";
        }

        if ($value[3] != '') {
            $staff_deets .= "<h3>Affiliations</h3>
                $value[3]";
        }

        $q = "UPDATE staff set bio = '" . $staff_deets . "' WHERE staff_id = " . $value[0];
        $r = $db->query($q);
    }
}

/////////////////
// 3b.  All Call number field
/////////////////

if ($addCallnum) {
    $q = "ALTER TABLE `location` ADD `call_number` VARCHAR( 255 ) NULL DEFAULT NULL AFTER `format`";
    $r = $db->query($q);

    if ($r) {
        print "<p>call_number field added to location table.</p>";
    } else {
        print "<p>Problem adding call_number field to location table.</p>";
    }
}

/////////////////
// 4.  Update location attributes (ctags)
// "full_text", "openurl", "images", "video", "audio"
////////////////

if ($updateCtags) {

    // Add ctags column first
    $q = "ALTER TABLE location ADD ctags VARCHAR( 255 ) NULL DEFAULT NULL";
    $r = $db->query($q);

    if ($r) {
        print "<p>ctags field added to location table.</p>";
    } else {
        print "<p>Problem adding ctags field to location table.</p>";
    }

    $querierLoc = new Querier();
    $qLoc = "SELECT location_id, article_linker, image_files, video_files, audio_files, fulltextCol
    FROM location";
    $locArray = $querierLoc->query($qLoc);

//print_r($locArray);

    foreach ($locArray as $value) {

        //print "<p>" . $value[1] . ": talkback: $value[2]; rxs: $value[3]; rxs_eres: $value[4]; admin: $value[admin]</p>";
        $new_permissions = "";
        if ($value[article_linker] == "Y") {
            $new_permissions .= "openurl|";
        }
        if ($value[image_files] == "Y") {
            $new_permissions .= "images|";
        }
        if ($value[audio_files] == "Y") {
            $new_permissions .= "audio|";
        }
        if ($value[fulltextCol] == "Y") {
            $new_permissions .= "full_text|";
        }
        $new_permissions = substr($new_permissions, 0, -1);


        //$querierUpdate = new sp_Querier();
        $q2 = "UPDATE location SET ctags = '$new_permissions' WHERE location_id = '" . $value["location_id"] . "'";
        $r = $db->query($q2);

        if ($r) {
            print "<p>Locations updated for $value[0]";
        } else {
            print "<p>Problem with location updated for $value[0].  Query run:  $q2</p>";
        }
    }
}

//////////////////
// 5. Remove old location columns
// article_linker, image_files, video_files, audio_files, fulltextCol
//////////////////

if ($removeOldLocations) {
    $q = "ALTER TABLE `location` DROP `article_linker` ,
    DROP `image_files` ,
    DROP `video_files` ,
    DROP `audio_files` ,
    DROP `fulltextCol`";

    $r = $db->query($q);
    if ($r) {
        print "<p>Obsolete fields removed from locations table.</p>";
    } else {
        print "<p>Problem removing obsolete fields from locations table. Query = $q</p>";
    }
}

//////////////////
// 6. Update type of Special pluslets (ones with local file)
//////////////////

if ($updatePlusletTypes) {

    $q9 = "UPDATE pluslet SET type = 'Special' WHERE local_file != ''";
    $r9 = $db->query($q9);

    if ($r9) {
        print "<p>Updated files to Special type.</p>";
    } else {
        print "<p>Problem updating files to Special type. Query = $q9</p>";
    }

    $q9a = "UPDATE pluslet SET extra = '' WHERE type = 'Special'";
    $r9a = $db->query($q9a);

    if ($r9a) {
        print "<p>Set extra to empty for type = Special.</p>";
    } else {
        print "<p>Problem updating extra field for Special type. Query = $q9a</p>";
    }

    $q9b = "UPDATE pluslet SET extra = '', body = '' WHERE type = 'Heading'";
    $r9b = $db->query($q9b);

    if ($r9b) {
        print "<p>Empty out extra and body fields for type = Heading.</p>";
    } else {
        print "<p>Problem emptying out extra and body fields for Heading type. Query = $q9b</p>";
    }
}

///////////////////////////
//6b Probably need to remove local_file field
//@todo
//////////////////////////

//////////////////
// 7. Make extra field larger (to 255 chars)
//////////////////

if ($addExtraFieldToPluslet) {
    $q = "ALTER TABLE `pluslet` CHANGE `extra` `extra` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";
    $r = $db->query($q);

    if ($r) {
        print "<p>Made extra field larger (255 chars).</p>";
    } else {
        print "<p>Problem making extra field in pluslet table larger. Query = $q</p>";
    }
}


//////////////////
// 8. convert the old extra data into new json
// {"num_items":10,  "show_desc":1, "show_feed": 1, "feed_type": "Delicious"}
//////////////////

if ($convertToJson) {
    $querierExtra = new Querier();
    $qextra = "SELECT pluslet_id, type, extra
    FROM `pluslet` 
    WHERE  type = 'deliciouslinks' OR type = 'flickr' OR type = 'rss' OR type = 'twitter'";
    $extraArray = $querierExtra->query($qextra);

//print_r($userArray);

    foreach ($extraArray as $value) {

        $our_json = "";
        $new_type = "";
        $this_extra = explode("|", $value[2]);

        //print "$value[1] -- $value[2] -- count = " . count($this_extra) . "<p>";

        if (count($this_extra) == 3) {
            // there should be three items
            // Let's fix the types temporarily
            switch ($value[1]) {
                case "deliciouslinks":
                    $new_type = "Delicious";
                    break;
                case "flickr":
                    $new_type = "Flickr";
                    break;
                case "rss":
                    $new_type = "RSS";
                    break;
                case "twitter":
                    $new_type = "Twitter";
                    break;
            }

            $our_json = "{\"num_items\":$this_extra[0],\"show_desc\": $this_extra[1],\"show_feed\": $this_extra[2], \"feed_type\": \"$new_type\"}";
        } else {
            $our_json = "";
        }

        //print "<p>json = " .$our_json . "<br />";
        //$querierUpdate = new sp_Querier();
        $q2 = "UPDATE pluslet SET extra = '$our_json' WHERE pluslet_id = '" . $value["pluslet_id"] . "'";
        $r5 = $db->query($q2);
        if ($r5) {
            print "<p>success! new extra for " . $value["pluslet_id"] . " = $our_json</p>";
        } else {
            print "<p>failure! new extra for " . $value["pluslet_id"] . "failed.  Json = $our_json // Query = $q2</p>";
        }
    }
}

//////////////////
// 6.  Update the type column
// deliciouslinks = Delicious; heading = Heading; flickr = Flickr; rss = RSS; twitter = Twitter
//////////////////

if ($updateTypeCol) {


    $q4 = "UPDATE pluslet SET type = 'Feed' WHERE type = 'deliciouslinks' OR type = 'flickr' OR type = 'rss' OR type = 'twitter'";
    $r4 = $db->query($q4);

    if ($r4) {
        print "<p>Updated files to Feed type.</p>";
    } else {
        print "<p>Problem updating files to Feed type. Query = $q4</p>";
    }

    $q5 = "UPDATE pluslet SET type = 'Heading' WHERE type = 'heading'";
    $r5 = $db->query($q5);

    if ($r5) {
        print "<p>Updated files to Heading type.</p>";
    } else {
        print "<p>Problem updating files to Heading type. Query = $q5</p>";
    }

    $q6 = "UPDATE `pluslet` SET TYPE = 'Basic' WHERE TYPE = '' OR ISNULL(TYPE)";
    $r6 = $db->query($q6);

    if ($r6) {
        print "<p>Updated all remaining files to Basic type.</p>";
    } else {
        print "<p>Problem updating remaining files to Basic type. Query = $q6</p>";
    }
}

if ($updateSubjectTable) {

    $q7 = "ALTER TABLE `subject` ADD `extra` VARCHAR( 255 ) NULL DEFAULT NULL ";
    $r7 = $db->query($q7);

    if ($r7) {
        print "<p>Added 'extra' column to subject table.</p>";
    } else {
        print "<p>Problem adding extra column to subject table. Query = $q7</p>";
    }

}

if ($updateTBTable) {

    $q8 = "UPDATE `talkback` SET display = 1 WHERE display = 'Yes'";
    $r8 = $db->query($q8);

    $q9 = "UPDATE `talkback` SET display = 0 WHERE display = 'No'";
    $r9 = $db->query($q9);

    if ($r8) {
        print "<p>Modify talkback table.</p>";
    } else {
        print "<p>Problem modifying talkback table. Query = $q8 // $q9</p>";
    }
    

}

include("includes/footer.php");
?>
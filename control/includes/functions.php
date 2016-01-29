<?php

use SubjectsPlus\Control\Querier;

include_once("autoloader.php");

if (file_exists("config.php")) {
	include_once("config.php");
}


//////////////////////////////
// If gettext isn't installed
// just return the string
//////////////////////////////

if (!function_exists("gettext")) {

  function _($string) {
    return $string;
  }

  function gettext($string) {
    return $string;
  }

}

function checkSession() {

  global $salt;

  if (isset($_SESSION['checkit'])) {

    if (md5($_SESSION['email']) . $salt == $_SESSION['checkit']) {
      $result = "ok";
    } else {
      $result = "failure";
    }
  } else {
    $result = "failure";
  }

  return $result;
}


/////////////////////
// Gets info about the user, based on IP or .htaccess, according to your config file
// This is called by control/includes/header.php, and control/login.php
/////////////////////////

function isCool($emailAdd="", $password="", $shibboleth=false) {

  $db = new Querier;
  
  global $subcat;
  global $CpanelPath;
  global $PublicPath;
  global $debugger;
  global $salt;
  

  if($shibboleth == true) {
  	
  	$connection = $db->getConnection();
  	$statement = $connection->prepare("SELECT staff_id, ip, fname, lname, email, user_type_id, ptags, extra
        FROM staff
        WHERE email = :mail");
  	$statement->bindParam(":mail", $emailAdd);
  	$statement->execute();   
  	$result =  $statement->fetchAll();
  	
  } else {

  	$query = "SELECT staff_id, ip, fname, lname, email, user_type_id, ptags, extra
        FROM staff
        WHERE email = '" . scrubData($emailAdd, "email") . "' AND password = '" . scrubData($password) . "'";
  	$db = new Querier;
  	$result = $db->query($query);  	 
 
  }
 
  $numrows = count($result);

  if ($numrows > 0) {

    $user = $result;
    if (is_array($user)) {

 
//set session variables
session_start();
session_regenerate_id();

// Create session vars for the basic types
      $_SESSION['checkit'] = md5($user[0][4]) . $salt;
      $_SESSION['staff_id'] = $user[0][0];
      $_SESSION['ok_ip'] = $user[0][1];
      $_SESSION['fname'] = $user[0][2];
      $_SESSION['lname'] = $user[0][3];
      $_SESSION['email'] = $user[0][4];
      $_SESSION['user_type_id'] = $user[0][5];

// unpack our extra
      if ($user[0][7] != NULL) {
        $jobj = json_decode($user[0][7]);
        $_SESSION['css'] = $jobj->{'css'};
      }

// unpack our ptags
      $current_ptags = explode("|", $user[0][6]);

      foreach ($current_ptags as $value) {
        $_SESSION[$value] = 1;
      }

      $result = "success";
    }
  } else {

    $result = "failure";
  }

  
  
  return $result;
}

///////////////////////////
// A not-awesome way of deciding which jquery to load
// options:  tablesorter, datepicker, filetree, colorbox
///////////////////////////

function generatejQuery($use_jquery) {

  global $AssetPath;

// Always load jQuery core, ui, livequery
  $myjquery = "<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js\"></script>\n
	<script type=\"text/javascript\" src=\"$AssetPath" . "js/jquery.livequery.min.js\"></script>\n";

// If there's not an array of values, send 'er back
  if (!is_array($use_jquery)) {
    return $myjquery;
  }

// Check to see what additional jquery files need to be loaded
  if (in_array("colorbox", $use_jquery)) {
    $myjquery .= "<script type=\"text/javascript\" src=\"$AssetPath" . "jquery/jquery.colorbox-min.js\"></script>\n
	<style type=\"text/css\">@import url($AssetPath" . "css/shared/colorbox.css);</style>\n";
  }
  if (in_array("hover", $use_jquery)) {
    $myjquery .= "<script type=\"text/javascript\" src=\"$AssetPath" . "jquery/jquery.hoverIntent.js\"></script>\n";
  }

  if (in_array("ui", $use_jquery)) {
    $myjquery .= "<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js\"></script>";
  }

  if (in_array("ui_styles", $use_jquery)) {
    $myjquery .= "<link rel=\"stylesheet\" href=\"$AssetPath" . "css/shared/jquery-ui.css\" type=\"text/css\" media=\"all\" />";
  }

  if (in_array("tablesorter", $use_jquery)) {
    $myjquery .= "<script type=\"text/javascript\" src=\"$AssetPath" . "jquery/jquery.tablesorter.js\"></script>\n
		<script type=\"text/javascript\" src=\"$AssetPath" . "jquery/jquery.tablesorter.pager.js\"></script>\n";
  }

  return $myjquery;
}

function noPermission($text) {
  $returnval = "<div id=\"maincontent\">
    <br /><br />
    <div class=\"box\" style=\"width: 50%;\">
    <p>$text</div>
    </div>";

  return $returnval;
}

/// cURL helper function
// used by video ingest module

function curl_get($url) {
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_TIMEOUT, 30);

  //added @ symbol to not display if curlopt_followlocation option cannot be set.
  //This function still works without it.
  @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
  $return = curl_exec($curl);
  curl_close($curl);
  return $return;
}

///////////////
// Some truncation functions
// TruncByWord is for word-based trunc, Truncate for character-based
//////////////
// Found in talkback

function TruncByWord($phrase, $max_words) {
  //remove all tags (if any) so that html is not included
  $phrase = preg_replace( '/<[^>]*>/', '', $phrase );

  $phrase_array = explode(' ', $phrase);
  if (count($phrase_array) > $max_words && $max_words > 0)
    $phrase = implode(' ', array_slice($phrase_array, 0, $max_words)) . '...';
  return $phrase;
}

/*
 * string Truncate ( string str , int length)
 * @param  string  str     string to truncate /abbreviate
 * @param  int     length  length to truncate /abbreviate to
 * @param  string  traling string to use for trailing on truncated strings
 * @return string  abbreviated string
 */

function Truncate($str, $length=10, $trailing=' [more]') {
// take off chars for the trailing
  $length-=strlen($trailing);
  if (strlen($str) > $length) {
// string exceeded length, truncate and add trailing dots
    return substr($str, 0, $length) . $trailing;
  } else {
// string was already short enough, return the string
    $res = $str;
  }

  return $res;
}

function stripP($text) {
///////////////////////////
// Fix FCKeditor madness!
// * check if it begins and ends with a P tag; if so, remove them
// this problem in FCKeditor 2.6.3; perhaps will be fixed in later versions
///////////////////////////

  $matcher = preg_match("/^<p>.*<\/p>$/", trim($text));

  if ($matcher == 1) {
// trim off those p tags!
    $text = preg_replace('/^<p>(.*)<\/p>$/', '$1', $text);
  }
  return $text;
}

function uploader2($temp_path, $target_path) {

  global $upload_whitelist;

  /* Verifiy File extension;  code modified from here:
    http://hungred.com/useful-information/secure-file-upload-check-list-php/ */

  $fileName = strtolower($_FILES['uploadedfile']['name']);

  if (!in_array(end(explode('.', $fileName)), $upload_whitelist)) {
    echo _("Invalid file type: not on whitelist of ok file types");
    exit(0);
  }

  /* Add the original filename to our target path.
    Result is "uploads/filename.extension" */

  $target_path_1 = $temp_path . basename($_FILES['uploadedfile']['name']);
  $_FILES['uploadedfile']['tmp_name'];


  $target_path_2 = $target_path . basename($_FILES['uploadedfile']['name']);

  if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path_2)) {
    return basename($_FILES['uploadedfile']['name']);
  } else {
    return "no";
  }
}

function getSubBoxes($prefix="", $trunc="", $all_subs=0) {

  $subs_option_boxes = "";

  if ($all_subs == "1") {
    $subs_query = "SELECT distinct subject_id, subject, type FROM subject ORDER BY type, subject";
  } else {
    $subs_query = "SELECT distinct s.subject_id, subject, type
            FROM subject s, staff_subject ss
            WHERE s.subject_id = ss.subject_id
            AND ss.staff_id = " . $_SESSION['staff_id'] . "
            ORDER BY type, subject";
  }

  $db = new Querier;
  $subs_result = $db->query($subs_query);

  $num_subs = count($subs_result);

  if ($num_subs > 0) {

// create the option
    $current_type = "";
    $subs_option_boxes = "";

    foreach ($subs_result as $myrow) {
      $subs_id = $myrow[0];
      $subs_name = $myrow[1];
      $subs_type = $myrow[2];

      if ($trunc) {
        $subs_name = Truncate($subs_name, $trunc, '');
      }

      if ($current_type != $subs_type) {

        $subs_option_boxes .= "<option value=\"\" style=\"background-color: #F6E3E7\">~~" . strtoupper($subs_type) . "~~</option>";
      }

      $subs_option_boxes .= "<option value=\"$prefix$subs_id\">$subs_name</option>";

      $current_type = $subs_type;
    }
  }

  return $subs_option_boxes;
}

function getDBbySubBoxes($selected_sub, $additionaltype = "Placeholder") {
  $db = new Querier;
  $subs_option_boxes = "";
  $alphabet = "";
  $morequery = "";

  if ($additionaltype != "") { $morequery = "OR type = '" . $additionaltype . "'";}

  $subs_query = "SELECT distinct subject_id, subject, type FROM `subject` WHERE (type = 'Subject' " . $morequery . ") AND active = '1' ORDER BY subject";
 
  $subs_result = $db->query($subs_query);



  $num_subs = count($subs_result);

  if ($num_subs > 0) {
    foreach ($subs_result as $myrow) {
      $subs_id = $myrow[0];
      $subs_name = $myrow[1];

      $subs_name = Truncate($subs_name, 50, '');

      $subs_option_boxes .= "<option value=\"databases.php?letter=bysub&amp;subject_id=$subs_id\"";
      if ($selected_sub == $subs_id) {
        $subs_option_boxes .= " selected=\"selected\"";
      }
      $subs_option_boxes .= ">" . _( $subs_name ) . "</option>";
    }
  }

  $alphabet .= " <select name=\"browser\" onChange=\"window.location=this.options[selectedIndex].value\">
  <option value=\"\" style=\"color: #ccc;\">- by subject -</option>
        $subs_option_boxes
        </select>";

  return $alphabet;
}

function changeMe($table, $flag, $item_id, $record_title, $staff_id) {
  $db = new Querier;

  global $dbName_SPlus;

  $record_title = TruncByWord($record_title, 15);

// Can be insert, update, delete; only the first creates a new record, so...
  if ($flag == "insert" || $flag == "delete") {
    $q = "insert into chchchanges (staff_id, ourtable, record_id, record_title, message)
        values(" . $staff_id . ", \"$table\", " . $item_id . ", \"" . $record_title . "\", \"$flag\")";

    $r = $db->exec($q);
    if ($r) {
      return true;
    } else {
      return false;
    }
  } else {
// find out person who made last change to this record
    $qtest = "SELECT staff_id, chchchanges_id, message
        FROM `chchchanges`
        WHERE record_id = \"$item_id\" and ourtable = \"$table\" ORDER BY date_added DESC";

    $result = $db->query($qtest);



// If there are no results, we need to insert a record
    if (!$result) {
      $q = "insert into chchchanges (staff_id, ourtable, record_id, record_title, message)
            values(" . $staff_id . ", \"$table\", " . $item_id . ", \"" . $record_title . "\", \"$flag\")";

      $r = $db->exec($q);
      if ($r) {
        return true;
      } else {
        return false;
      }
    } else {
// If the editor is the same as last time & it's not the first record,
// just update the time; Otherwise, add a new entry to the table

      if (($result[0] == $staff_id) && ($result[2] != "insert")) {
// Editor is same as last guide updater, just update the time
        $q = "UPDATE chchchanges SET message = 'update', date_added = NOW() WHERE chchchanges_id = " . $result[1];
      } else {
//Editor is different, add entry to table
        $q = "insert into chchchanges (staff_id, ourtable, record_id, record_title, message)
                    values(" . $staff_id . ", \"$table\", " . $item_id . ", \"" . $record_title . "\", \"update\")";
      }
//print $q;

      $r = $db->exec($q);
      if ($r) {
        return true;
      } else {
        return false;
      }
    }
  }
}

function lastModded($table, $record_id, $zero_message = 1, $show_email = 1) {
  $q = "SELECT email, DATE_FORMAT(date_added, '%b %D %Y') as last_modified
        FROM chchchanges c, staff s
        WHERE c.staff_id = s.staff_id
        AND ourtable = '$table'
        AND record_id = '$record_id'
        ORDER BY date_added DESC";
//print $q;
  $db = new Querier;
  $r = $db->query($q);
  $my_mod = $r;


  if ($my_mod) {
                           $val = $my_mod[0]['last_modified'];
    if ($show_email == 1) {
      $val .= ", " . $my_mod[0]['email'];
    }
  } else {
    if ($zero_message == 1) {
      $val = _("Last modification date unknown.");
    } else {
      $val = "";
    }
  }

  return $val;
//return _("last modified") . " " . $my_mod[1] . " - " . $my_mod[0];
}

/*
 * scrubData
 * @param  mixed 		string	string to scrub
 * @param  string		type		options are: integer, text, ascii, richtext, email
 * @return string  	scrubbed string
 */

function scrubData($string, $type="text") {

  switch ($type) {
    case "text":
// magic quotes test
      if (get_magic_quotes_gpc()) {
        $string = stripslashes($string);
      }
      $string = strip_tags($string);
      $string = htmlspecialchars($string, ENT_QUOTES);
      break;
    case "richtext":
// magic quotes test
      if (get_magic_quotes_gpc()) {
        $string = stripslashes($string);
      }
      break;
    case "email":
// magic quotes test
      if (get_magic_quotes_gpc()) {
        $string = stripslashes($string);
      }
      //removes any tags protecting against javascript injection
      $string = strip_tags($string);

      //checks to see if the email is in valid email format, if not return a blank string
      if (!isValidEmailAddress($string)) {
        $string = '';
      }

      break;
    case "integer":
// this just makes it into a whole number; might not be a good solution...
      $string = round($string);
      break;
  }


  return $string;
}

// just to handle all the error messages
function blunDer($message, $type = 1) {
  global $administrator;

  print "<h2>" . _("Someone Has Blundered!") . "</h2>";
  if ($type == 0) {
    print "<p>" . _("(But probably not you.)") . "</p>";
  }
  print "<p>$message</p>\n";
  print _("<p>Please contact the <a href=\"mailto:$administrator\">Administrator</a></p>");
  include("../includes/footer.php");
  exit();
}

//////////////
// Erstwhile guide_functions.php
////////////////


function findDescOverride($subject_id, $title_id) {
  $db = new Querier();

  $query = "SELECT description_override FROM rank WHERE subject_id = '$subject_id' AND title_id = '$title_id'";
  $override_text = $db->query($query);

  if (count($override_text) != 0) {
    return $override_text[0][0];
  }
}

function showDocIcon($extension) {
  switch ($extension) {
    case "avi":
    case "mpg":
    case "mpeg":
    case "mp4":
    case "mov":
    case "wmv":
      return "film.png";
      break;
    case "doc":
    case "rtf":
    case "docx":
      return "doc.png";
      break;
    case "fla":
      return "flash.png";
      break;
    case "bmp":
    case "gif":
    case "jpg":
    case "jpeg":
    case "png";
    case "tif":
    case "tiff":
      return "picture.png";
      break;
    case "m4p":
    case "mp3":
    case "ogg":
    case "wav":
      return "music.png";
      break;
    case "pdf":
      return "pdf.png";
      break;
    case "ppt":
      return "ppt.png";
      break;
    case "psd":
      return "psd.png";
      break;
    case "swf":
      return "flash.png";
      break;
    case "txt":
      return "txt.png";
      break;
    case "xls":
    case "xlsx":
      return "xls.png";
      break;
    case "xml":
      return "code.png";
      break;
    case "zip":
      return "zip.png";
      break;
  }
}

function showIcons($ctags, $showtext = 0) {
  global $PublicPath;
  global $IconPath;
  global $AssetPath;
  $icons = "";
if ($ctags != "") {
  foreach ($ctags as $value) {
    switch ($value) {
      case "restricted":
        //$icons .= "<img src=\"$IconPath/lock.png\" border=\"0\" alt=\"" . _("Restricted Resource") . "\" title=\"" . _("Restricted Resource") . "\" /> ";
        $icons .= "<img src=\"$IconPath/v2-lock.png\" border=\"0\" alt=\"" . _("Restricted Resource") . "\" title=\"" . _("Restricted Resource") . "\" /> ";

        if ($showtext == 1) {
          $icons .= " = " . _("Restricted resource") . "<br />";
        }
        break;
      case "unrestricted":
        $icons .= "<img src=\"$IconPath/lock_unlock.png\" border=\"0\" alt=\"" . _("Unrestricted Resource") . "\" title=\"" . _("Unrestricted Resource") . "\" /> ";
        if ($showtext == 1) {
          $icons .= " = " . _("Unrestricted resource") . "<br />";
        }
        break;
      case "full_text":
        //$icons.= " <img src=\"$IconPath/document-26.png\" border=\"0\" alt=\"" . _("Some full text available") . "\" title=\"" . _("Some full text available") . "\" />";
        $icons.= " <img src=\"$IconPath/full_text.gif\" border=\"0\" alt=\"" . _("Some full text available") . "\" title=\"" . _("Some full text available") . "\" />";

        if ($showtext == 1) {
          $icons .= " = " . _("Some full text") . "<br />";
        }
        break;
      case "openurl":
        //$icons .= "<img src=\"$IconPath/link-26.png\" border=\"0\" alt=\"openURL\" title=\"openURL\" /> ";
        $icons .= "<img src=\"$IconPath/article_linker.gif\" border=\"0\" alt=\"openURL\" title=\"openURL\" /> ";
        if ($showtext == 1) {
          $icons .= " = " . _("OpenURL enabled") . "<br /><br />";
        }
        break;
      case "images":
        //$icons.= " <img src=\"$IconPath/image_file-26.png\" border=\"0\" alt=\"" . _("Resource contains images") . "\" title=\"" . _("Resource contains images") . "\" />";
        $icons.= " <img src=\"$IconPath/camera.gif\" border=\"0\" alt=\"" . _("Resource contains images") . "\" title=\"" . _("Resource contains images") . "\" />";

        if ($showtext == 1) {
          $icons .= " = " . _("Images") . "<br />";
        }
        break;
      case "video":
        //$icons.= " <img src=\"$IconPath/video_file-26.png\"  border=\"0\" alt=\"" . _("Resource contains video") . "\" title=\"" . _("Resource contains video") . "\" />";
        $icons.= " <img src=\"$IconPath/television.gif\"  border=\"0\" alt=\"" . _("Resource contains video") . "\" title=\"" . _("Resource contains video") . "\" />";

        if ($showtext == 1) {
          $icons .= " = " . _("Video files") . "<br />";
        }
        break;
      case "audio":
        //$icons.= " <img src=\"$IconPath/audio_file-26.png\" border=\"0\" alt=\"" . _("Resource contains audio") . "\" title=\"" . _("Resource contains audio") . "\" />";
        $icons.= " <img src=\"$IconPath/sound.gif\" border=\"0\" alt=\"" . _("Resource contains audio") . "\" title=\"" . _("Resource contains audio") . "\" />";

        if ($showtext == 1) {
          $icons .= " = " . _("Audio files") . "<br />";
        }
        break;
    }
  }
}

  return $icons;
}

function seeRecentChanges($staff_id, $limit=10) {

  global $IconPath;
  global $CpanelPath;
  $recent_activity = "";

  if ($staff_id) {

    $sq2 = "SELECT ourtable, record_id, record_title, message, date_added
        FROM chchchanges
        WHERE staff_id = '" . $staff_id . "'
        GROUP BY record_title, message, ourtable
        ORDER BY date_added DESC
        LIMIT 0, $limit";
  } else {
    $sq2 = "SELECT ourtable, record_id, record_title, message, date_added, CONCAT( fname, ' ', lname ) AS fullname
        FROM chchchanges, staff
        WHERE chchchanges.staff_id = staff.staff_id
        GROUP BY record_title, message, ourtable
        ORDER BY date_added DESC
        LIMIT 0 , $limit";
  }


  //print $sq2;
  $db = new Querier;
  $sr2 = $db->query($sq2);

  $num_rows = count($sr2);

  $row_count = 0;
  $colour1 = "oddrow";
  $colour2 = "evenrow";

  if ($num_rows != 0) {

    foreach ($sr2 as $myrow2) {

      $row_colour = ($row_count % 2) ? $colour1 : $colour2;

      $intro = "";
      $message = $myrow2["3"];

      switch ($myrow2["0"]) {
        case "guide":
          $intro = _("Research Guides") . " $message";
          $linkit = $CpanelPath . "guides/guide.php?subject_id=$myrow2[1]";
          break;
        case "faq":
          $intro = _("FAQ") . " $message";
          $linkit = $CpanelPath . "faq/faq.php?faq_id=$myrow2[1]";
          break;
        case "talkback":
          $intro = _("Talk Back") . " $message";
          $linkit = $CpanelPath . "talkback/talkback.php?talkback_id=$myrow2[1]";
          break;
        case "subject":
          switch ($message) {
            case "insert":
              $intro = _("Research Guide created");
              break;
            case "update":
              $intro = _("Research Guide metadata");
              break;
            case "delete":
              $intro = _("Research Guide deleted");
              break;
          }

          $linkit = $CpanelPath . "guides/guide.php?subject_id=$myrow2[1]";
          break;
        case "record":
          $intro = _("Record") . " $message";
          $linkit = $CpanelPath . "records/record.php?record_id=$myrow2[1]";
          break;
        case "staff_details":
          $intro = _("Staff Details updated.");
          break;
        case "staff":
          switch ($message) {
            case "insert":
              $intro = _("New User Added");
              $linkit = $CpanelPath . "admin/user.php?staff_id=$myrow2[1]";
              break;
            case "update":
              $intro = _("User Information updated");
              $linkit = $CpanelPath . "admin/user.php?staff_id=$myrow2[1]";
              break;
            case "delete":
              $intro = _("User deleted");
              break;
          }

          break;
        case "video":
          $intro = _("Video") . " $message";
          $linkit = $CpanelPath . "videos/video.php?video_id=$myrow2[1]";
          break;
      }

      $recent_activity .= "<div class=\"recent-activity $row_colour\"> <img src=\"$IconPath/required.png\"  /></img> $intro";
      if ($myrow2["2"] != "") {
        $recent_activity .= ": <a href=\"$linkit\" classs=\"recent-activity-link\" title=\"Took place: $myrow2[4]\">$myrow2[2]</a>";
      }

      if (!$staff_id) {
        $recent_activity .= " <span class=\"recent-activity-span\">$myrow2[5]</span>";
      }
      $recent_activity .= "</div>";
      $row_count++;
    }
  }
  return $recent_activity;
}

/* This function is only for the University of Miami; on UM site, rename this to getHeadshot and comment out the other one */
function getHeadshot($email, $pic_size="medium", $class="staff_photo") {

  
   $name_id = explode("@", $email);
  $lib_image = "_" . $name_id[0];
  global $AssetPath;
  // Get the real file path for the headshot image 
  $headshot_path  =  dirname(dirname(dirname(__FILE__))) . "/assets/users/$lib_image/headshot.jpg";

  if(file_exists($headshot_path)) {

      // Check if the image is the UM logo
      $image_hash = md5_file($headshot_path);
      $um_logo = "91b8c9ec083c5abc898a5c482aac959e";

      if($image_hash == $um_logo) {} else {

              $headshot = "<img src=\"" . $AssetPath . "" . "users/$lib_image/headshot.jpg\" alt=\"$email\" title=\"$email\"";

  switch ($pic_size) {
    case "small":
      $headshot .= " width=\"50\"";
      break;
    case "medium":
      $headshot .= " width=\"70\"";
      break;
  }

  $headshot .= " class=\"staff_photo\"  align=\"left\" />";
  // If the image exists and isn't the UM logo return the img html
  return $headshot;
     }
   }
}


/* This function is only for the University of Miami; on UM site, rename this to getHeadshot and comment out the other one */
function getHeadshotUM($email, $pic_size="medium", $class="staff_photo") {

  
   $name_id = explode("@", $email);
  $lib_image = "_" . $name_id[0];
  global $AssetPath;
  // Get the real file path for the headshot image 
  $headshot_path  =  dirname(dirname(dirname(__FILE__))) . "/assets/users/$lib_image/headshot.jpg";

  if(file_exists($headshot_path)) {

      // Check if the image is the UM logo
      $image_hash = md5_file($headshot_path);
      $um_logo = "91b8c9ec083c5abc898a5c482aac959e";

      if($image_hash == $um_logo) {} else {

              $headshot = "<img src=\"" . $AssetPath . "" . "users/$lib_image/headshot.jpg\" alt=\"$email\" title=\"$email\"";

  switch ($pic_size) {
    case "small":
      $headshot .= " width=\"50\"";
      break;
    case "medium":
      $headshot .= " width=\"70\"";
      break;
  }

  $headshot .= " class=\"staff_photo\"  align=\"left\" />";
  // If the image exists and isn't the UM logo return the img html
  return $headshot;
     }
   }
}

function getHeadshotFull($email, $pic_size="full", $class="staff_photo_full") {

  $name_id = explode("@", $email);
  $lib_image = "_" . $name_id[0];
  global $AssetPath;
  
  $headshot_large = "<img src=\"" . $AssetPath . "" . "/users/$lib_image/headshot_large.jpg\" alt=\"$email\" title=\"$email\"  width=\"225\" class=\"$class\" />";
  
  return $headshot_large;
}




// Display staff images
function showStaff($email, $picture=1, $pic_size="medium", $link_name = 0) {
  global $tel_prefix;
  global $mod_rewrite;

  $q = "SELECT fname, lname, title, tel, email FROM staff WHERE email = '$email'";

  $db = new Querier;
  $r = $db->query($q);

  $row_count = count($r);

  if ($row_count == 0) {
    return;
  }

  foreach ($r as $myrow) {

    if ($link_name == 1) {
      $email = $myrow["email"];
      $name_id = explode("@", $email);

      if ($mod_rewrite == 1) {
        //$linky = "/subjects/profile/" . $name_id[0]; // um custom
        $linky = $linky = "staff/" . $name_id[0];
      } else {
        $linky = "staff_details.php?name=" . $name_id[0];
      }
      $full_name = "<a href=\"$linky\">" . $myrow[0] . " " . $myrow[1] . "</a>";
    } else {
      $full_name = $myrow[0] . " " . $myrow[1];
    }


    $staffer = "<td class=\"staffpic\">";
    $staffer .= getHeadshot($email, $pic_size);
    $staffer .= "</td><td><strong>$full_name</strong><br />$myrow[2]<br />$tel_prefix $myrow[3]<br /><a href=\"mailto:$myrow[4]\">$myrow[4]</a></td>";
  }

  return $staffer;
}

function getDBbyTypeBoxes($selected_type = "", $show_formats = TRUE) {

  $types_option_boxes = "";
  $alphabet = "";
  global $all_ctags;

  sort($all_ctags);

    foreach ($all_ctags as $tag) {

      $new_tag = ucwords(preg_replace('/_/', ' ', $tag));

      $types_option_boxes .= "<option value=\"databases.php?letter=bytype&amp;type=$tag\"";

      if ($selected_type == $tag) {
        $types_option_boxes .= " selected=\"selected\"";
      }
      $types_option_boxes .= ">" . _( $new_tag ) . "</option>";
    }

    if ($show_formats == TRUE) {
      $alphabet .= " <select name=\"browser\" onChange=\"window.location=this.options[selectedIndex].value\">
      <option value=\"\">- by format -</option>
      <option value=\"databases.php?letter=bytype\">" . _("List All Format Types") . "</option>
      $types_option_boxes
      </select>";
    }

  return $alphabet;
}

function getLetters($table, $selected = "A", $numbers = 1, $show_formats = TRUE) {

  $selected = scrubData($selected);

  $selected_subject = "";
  if (isset($_GET["subject_id"])) {
    $selected_subject = intval($_GET["subject_id"]);
  }

  $selected_type = "";
  if (isset($_GET["type"])) {
    $selected_type = $_GET["type"];
  }

  $showsearch = 0;
  $abc_link = "";

// If it's an array, just plunk that stuff in //

  if (is_array($table)) {
    $letterz = $table;
    $showsearch = 0;
    $azRange = $table;
  } else {

    $shownew = 1;
    $extras = "";

    switch ($table) {
      case "databases":
        $lq = "SELECT distinct UCASE(left(title,1)) AS initial
                    FROM location l, location_title lt, title t
                    WHERE l.location_id = lt.location_id AND lt.title_id = t.title_id
                    AND eres_display = 'Y'
                    AND left(title,1) REGEXP '[A-Z]'
                    ORDER BY initial";
        $abc_link = "databases.php";
        $shownew = 0;
        break;
    }

//print $lq;
    $db = new Querier;
    $lr = $db->query($lq);

    foreach ($lr as $mylets) {
      $letterz[] = $mylets[0][0];
    }

    // let's init an array of all letters
    $azRange = range('A', 'Z');

    if ($numbers == 1) {
      $letterz[] = "Num";
      $azRange[] = "Num";
    }

    $letterz[] = "All";
    $azRange[] = "All";

    if (!$selected) {
      $selected = "ALL";
    }
  }


  $alphabet = "<div id=\"letterhead\" align=\"center\">";

  foreach ($azRange as $char) {
    if (in_array($char, $letterz)) {
          if ($char == $selected) {
      $alphabet .= "<span id=\"selected_letter\">$char</span> ";
    } else {
      $alphabet .= "<a href=\"$abc_link?letter=$char\">$char</a> ";
    }
    } else {
      $alphabet .= "<span class=\"inactive\">$char</span> ";
    }
}

/*
  foreach ($letterz as $value) {
    if ($value == $selected) {
      $alphabet .= "<span id=\"selected_letter\">$value</span> ";
    } else {
      $alphabet .= "<a href=\"$abc_link?letter=$value\">$value</a>";
    }
  }
*/
  if ($table == "databases") {
    $alphabet .= getDBbyTypeBoxes($selected_type, $show_formats);
    $alphabet .= getDBbySubBoxes($selected_subject);
  }

  if ($showsearch != 0) {
    $alphabet .= "<input type=\"text\" id=\"letterhead_suggest\" size=\"30\"  />";
  }


  $alphabet .= "</div>";

  return $alphabet;
}

function prepareTH($array) {

  $th = "
<table width=\"100%\" class=\"item_listing\">
<tr class=\"pure-g staff-heading\">";

  foreach ($array as $key => $value) {
    $th .= "<th>$value</th>";
  }

  $th .= "</tr>";

  return $th;
}

function prepareTHUM($array) {
   $th = "
    <table class=\"footable foo1\" data-filter=\"#filter\">
      <thead>
        <tr class=\"staff-heading\">";
    
        foreach ($array as $header) {

          if($header["hide"] == true) {
                if($header["nosort"]== true) {
                    $th .= "<th data-hide=\"phone,mid\" data-sort-ignore=\"true\">"
                        . $header['label'] . "</th>";
                } 
                else {
                  $th .= "<th data-hide=\"phone,mid\">"
                      . $header['label'] . "</th>";
                } 
          
          }
          else {
                if($header["nosort"]== true){
                  $th .= "<th data-sort-ignore=\"true\">" .$header['label'] ."</th>";
                } 

                else {
                  $th .= "<th>" .$header['label'] ."</th>";
                }            
          }

        }

    $th .= "</tr></thead>";

  return $th;
}

function isValidEmailAddress($lstrPotentialEmail) {
  $lstrExpression = '/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/i';

  $lintMatch = preg_match($lstrExpression, $lstrPotentialEmail);

  if ($lintMatch > 0) {
    return true;
  }

  return false;
}

/**
 * displayLogoOnlyHeader() - this function displays a logo only header
 *
 * @return void
 */
function displayLogoOnlyHeader()
{
	//find where control folder is and get assest URL to display logo
	$lstrURL = $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];

	$lobjSplit = explode( '/', $lstrURL );

	for( $i=(count($lobjSplit) - 1); $i >=0; $i-- )
	{
		if($lobjSplit[$i] == 'control')
		{
			unset($lobjSplit[$i]);
			$lstrURL = implode( '/' , $lobjSplit );
			break;
		}else
		{
			unset($lobjSplit[$i]);
		}
	}

	//display logo only header
	?>
		<header id="header">

		  <img class="login-only-logo" src="<?php echo '//' .$lstrURL . '/assets/'; ?>images/admin/logo_small.png" />

		</header>
		<?php
}

/**
 * isInstalled() - this funtion determines whether or not SujectsPlus is installed
 *
 * @return boolean
 */
function isInstalled()
{
	//does key SubjectsPlus tables exist query
	$lstrQuery = 'SHOW TABLES LIKE \'staff%\'';

    $db = new Querier;
	$rscResults = $db->query( $lstrQuery );
	$lintRowCount = count( $rscResults );

	//no key SubjectsPlus tables exists
	if( $lintRowCount == 0 ) return FALSE;
	return TRUE;
}

/**
 * isUpdated() - this funtion determines whether or not SujectsPlus is updated to 2.0
 *
 * @return boolean
 */
function isUpdated()
{
	//does key SubjectsPlus 2.0 tables exist query
	$lstrQuery = 'SHOW TABLES LIKE \'staff_department\'';
    $db = new Querier;
	$rscResults = $db->query( $lstrQuery );
	$lintRowCount = count( $rscResults );

	//no key SubjectsPlus 2.0 tables exists
	if( $lintRowCount == 0 ) return FALSE;
	return TRUE;
}

/**
 * getAssetPath() - returns path of asset folder. Used for when wanting to get
 * asset path when configuration AssetPath is not set yet (e.g. Installation)
 *
 * @return string
 */
function getAssetPath()
{
	$lstrPath = dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR;

	return $lstrPath;
}

/**
 * getControlPath() - returns path of control folder. Used for when wanting to get
 * control path when configuration CpanelPath is not set yet (e.g. Installation)
 *
 * @return string
 */
function getControlPath()
{
	$lstrPath = dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR;

	return $lstrPath;
}

/**
 * getAssetURL() - returns url of asset folder. SUed for when wanting to get
 * asset url when configuration AssestURL is not set yet (e.g. Installation).
 *
 * @return string
 */
function getAssetURL()
{
	$lstrURL = $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];

	$lobjSplit = explode( '/', $lstrURL );

	for( $i=(count($lobjSplit) - 1); $i >=0; $i-- )
	{
		if($lobjSplit[$i] == 'control' || $lobjSplit[$i] == 'subjects')
		{
			unset($lobjSplit[$i]);
			$lstrURL = implode( '/' , $lobjSplit );
			break;
		}else
		{
			unset($lobjSplit[$i]);
		}
	}


	return '//' . $lstrURL . '/assets/';

}

/**
 * getControlRL() - returns url of control folder. SUed for when wanting to get
 * control url when configuration CPanelURL is not set yet (e.g. Installation).
 *
 * @return string
 */
function getControlURL()
{
	$lstrURL = $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];

	$lobjSplit = explode( '/', $lstrURL );

	for( $i=(count($lobjSplit) - 1); $i >=0; $i-- )
	{
		if($lobjSplit[$i] == 'subjects')
		{
			unset($lobjSplit[$i]);
			$lstrURL = implode( '/' , $lobjSplit );

			$lstrURL = '//' . $lstrURL . '/control/';

			break;
		}elseif($lobjSplit[$i] == 'control')
		{
			$lstrURL = implode( '/' , $lobjSplit );
			$lstrURL = '//' . $lstrURL . '/';

			break;
		}else
		{
			unset($lobjSplit[$i]);
		}
	}

	return $lstrURL;
}


function getSubjectsURL()
{
	$lstrURL = $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];

	$lobjSplit = explode( '/', $lstrURL );

	for( $i=(count($lobjSplit) - 1); $i >=0; $i-- )
	{
		if($lobjSplit[$i] == 'subjects')
		{
			unset($lobjSplit[$i]);
			$lstrURL = implode( '/' , $lobjSplit );

			$lstrURL = '//' . $lstrURL . '/subjects/';

			break;
		}elseif($lobjSplit[$i] == 'control')
		{
			$lstrURL = implode( '/' , $lobjSplit );

			$lstrURL = '//' . $lstrURL . '/';

			break;
		}else
		{
			unset($lobjSplit[$i]);
		}
	}

	return $lstrURL;
}

/**
 * getRewriteBase() - this function will find the base for SubjectsPlus so that
 * a .htaccess file use the rewrite base
 *
 * @return string
 */
function getRewriteBase()
{
	$lstrURI = $_SERVER[ 'REQUEST_URI' ];

	$lobjSplit = explode( '/', $lstrURI );

	for( $i=(count($lobjSplit) - 1); $i >=0; $i-- )
	{
		if($lobjSplit[$i] == 'control')
		{
			unset($lobjSplit[$i]);
			$lstrRewriteBase = implode( '/' , $lobjSplit );
			$lstrRewriteBase = $lstrRewriteBase . '/';
			break;
		}else
		{
			unset($lobjSplit[$i]);
		}
	}

	return $lstrRewriteBase;
}

/**
 * gcd() and reduce() will return us the lowest common denominator fraction.
 * Used to get pure.css-friendly numbers
 *
 * @return array
 */

function gcd( $a, $b)
{
  return $b ? gcd($b, $a%$b) : $a;
}

function reduce($numerator,$denominator) {
  $gcd = gcd($numerator,$denominator);
  return array($numerator/$gcd, $denominator/$gcd); }

/**
 * makePluslet() is just to save time in creating pluslets
 * you pass in title and body, it returns pluslet
 *
 * @return string
 */

function makePluslet ($title = "", $body = "", $bonus_styles = "", $printout = TRUE) {
  $pluslet = "
  <div class=\"pluslet $bonus_styles\">
    <div class=\"titlebar\">
      <div class=\"titlebar_text\">$title</div>
      <div class=\"titlebar_options\"></div>
    </div>
    <div class=\"pluslet_body\">$body
    </div>
  </div>";

  if ($printout == TRUE) {
    print $pluslet;
  } else {
    return $pluslet;
  }
}

/**
 * feedBack() is to wrap the feedback in some styling
 * $display expects either "response" (from guides, which is positioned absolutely)
 * or the default "feedback" (which is relative).  Look at css for these ids in admin_styles.css
 * @return string
 */

function feedBack($message="", $display="feedback") {
  //print "HELLO our message is $message";
  if ($message != "") {
    print "<div class=\"master-feedback\" style=\"display:block;\">$message</div>";
  } else {
    return "";
  }

}

// Mod in_array to work with multidimensional arrays
function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}


// http://stackoverflow.com/questions/2815162/is-there-a-php-function-like-pythons-zip
function zip() {
    $args = func_get_args();
    $zipped = array();
    $n = count($args);
    for ($i=0; $i<$n; ++$i) {
        reset($args[$i]);
    }
    while ($n) {
        $tmp = array();
        for ($i=0; $i<$n; ++$i) {
            if (key($args[$i]) === null) {
                break 2;
            }
            $tmp[] = current($args[$i]);
            next($args[$i]);
        }
        $zipped[] = $tmp;
    }
    return $zipped;
}

// Color console output 

function colorize($text, $status) {
  $out = "";
  switch($status) {
    case "SUCCESS":
      $out = "[42m"; //Green background
      break;
    case "FAILURE":
      $out = "[41m"; //Red background
      break;
    case "WARNING":
      $out = "[43m"; //Yellow background
      break;
    case "NOTE":
      $out = "[44m"; //Blue background
      break;
    default:
      throw new Exception("Invalid status: " . $status);
  }
    return chr(27) . "$out" . "$text" . chr(27) . "[0m";
}

/**
 * tokenizeText() is used to convert tokens created via FCKeditor wysiwyg
 * into something prettily output
 *
 * The original is in the Pluslet class, and these two should probably be made one
 *
 * @return string
 */

function tokenizeText($our_text, $our_subject_id = "") {
        global $proxyURL;
        global $PublicPath;
        global $FAQPath;
        global $UserPath;
        global $IconPath;
        global $open_string;
        global $close_string;
        global $open_string_kw;
        global $close_string_kw;
        global $open_string_cn;
        global $close_string_cn;
        global $open_string_bib;

      $db = new Querier();

        $icons = "";
        //$target = "target=\"_" . $target . "\"";
        $target = "";
        $tokenized = "";

        $parts = preg_split('/<span[^>]*>{{|}}<\/span>/', $our_text);

      if( count($parts) == 1 )
        $parts = preg_split('/{{|}}/', $our_text);

        if (count($parts) > 1) { // there are tokens in $body
            foreach ($parts as $part) {
                if (preg_match('/^dab},\s?{\d+},\s?{.+},\s?{[01]{2}$/', $part) || preg_match('/^faq},\s?{(\d+,)*\d+$/', $part)
                  || preg_match('/^cat},\s?{.+},\s?{.*},\s?{\w+$/', $part) || preg_match('/^fil},\s?{.+},\s?{.+$/', $part)
                  || preg_match('/^sss},\s?{[^}]*/', $part) || preg_match('/^toc},\s?{[^}]*/', $part) ) { // $part is a properly formed token
                    $fields = preg_split('/},\s?{/', $part);
                    $prefix = substr($part, 0, 3);
                    switch ($prefix) {
                        case "faq":
                            $query = "SELECT faq_id, question FROM `faq` WHERE faq_id IN(" . $fields[1] . ") ORDER BY question";
                            $result = $db->query($query);
                            $tokenized.= "<ul>";
                            foreach ($result as $myrow) {
                                $tokenized.= "<li><a href=\"$FAQPath" . "?faq_id=$myrow[0]\" $target>" . stripslashes(htmlspecialchars_decode($myrow[1])) . "</a></li>";
                            }
                            $tokenized.= "</ul>";
                            break;
                        case "fil":
                            $ext = explode(".", $fields[1]);
                            $i = count($ext)-1;
                            $our_icon = showDocIcon($ext[$i]);
                            $file = "$UserPath/$fields[1]";
                            $tokenized.= "<a href=\"$file\" $target>$fields[2]</a> <img style=\"position:relative; top:.3em;\" src=\"$IconPath/$our_icon\" alt=\"$ext[$i]\" />";
                            break;
                        case "cat":
                            $pretext = "";
                            switch ($fields[3]) {
                                case "subject":
                                    $cat_url = $open_string . $fields[1] . $close_string;
                                    $pretext = $fields[2] . " ";
                                    $linktext = $fields[1];
                                    break;
                                case "keywords":
                                    $cat_url = $open_string_kw . $fields[1] . $close_string_kw;
                                    $linktext = $fields[2];
                                    break;
                                case "call_num":
                                    $cat_url = $open_string_cn . $fields[1] . $close_string_cn;
                                    $linktext = $fields[2];
                                    break;
                                case "bib":
                                    $cat_url = $open_string_bib . $fields[1];
                                    $linktext = $fields[2];
                                    break;
                            }
                            $tokenized.= "$pretext<a href=\"$cat_url\" $target>$linktext</a>";
                            break;
                        case "dab":
                            //print_r($fields);
                            $description = "";
                            ///////////////////
                            // Check for icons or descriptions in fields[3]
                            // 00 = neither; 10 = icons no desc; 01 = desc no icons; 11 = both
                            ///////////////////
                            if (isset($fields["3"])) {
                                switch ($fields["3"]) {
                                    case "00":
                                        $show_icons = "";
                                        $show_desc = "";
                                        $show_rank = 0;
                                        break;
                                    case "10":
                                        $show_icons = "yes";
                                        $show_desc = "";
                                        $show_rank = 0;
                                        break;
                                    case "01":
                                        $show_icons = "";
                                        $show_desc = 1;
                                        $icons = "";
                                        break;
                                    case "11":
                                        $show_icons = "yes";
                                        $show_desc = 1;
                                        break;
                                }
                            }
                            $query = "SELECT location, access_restrictions, format, ctags, helpguide, citation_guide, description, call_number, t.title
                                    FROM location l, location_title lt, title t
                                    WHERE l.location_id = lt.location_id
                                    AND lt.title_id = t.title_id
                                    AND t.title_id = $fields[1]";
                            //print $query . "<br /><br />";
                            $result = $db->query($query);

                            foreach ($result as $myrow) {

                                // eliminate final line breaks -- offset fixed 11/15/2011 agd
                                $myrow[6] = preg_replace('/(<br \/>)+/', '', $myrow[6]);
                                // See if it's a web format
                                if ($myrow[2] == 1) {
                                    if ($myrow[1] == 1) {
                                        $url = $myrow[0];
                                        $rest_icons = "unrestricted";
                                    } else {
                                        $url = $proxyURL . $myrow[0];
                                        $rest_icons = "restricted";
                                    }

                                    $current_ctags = explode("|", $myrow[3]);

                                    // add our $rest_icons info to this array at the beginning
                                    array_unshift($current_ctags, $rest_icons);

                                    if ($show_icons == "yes") {
                                        $icons = showIcons($current_ctags);
                                    }

                                    if ($show_desc == 1) {
                                        // if we know the subject_id, good; for public, must look up
                                      $subject_id = '';
                                        if (isset($_GET["subject_id"])) {
                                            $subject_id = $_GET["subject_id"];
                                        } elseif (isset($_GET["subject"])) {
                                            $q1 = "SELECT subject_id FROM subject WHERE shortform = '" . $_GET["subject"] . "'";

                                            $r1 = $db->query($q1);
                                            $subject_id = $db->last_id($r1);
                                            $subject_id = $subject_id[0];
                                        }

                                        $override = findDescOverride($subject_id, $fields[1]);
                                        // if they do want to display the description:
                                        if ($override != "") {
                                            // show the subject-specific "description_override" if it exists
                                            $description = "<br />" . scrubData($override);
                                        } else {
                                            $description = "<br />" . scrubData($myrow[6]);
                                        }
                                        //$description = "<br />$myrow[9]";
                                    }
                                    $tokenized.= "<a href=\"$url\" $target>$myrow[8]</a> $icons $description";
                                } else {
                                    // It's print
                                    $format = "other";
                                    if ($show_icons == "yes") {
                                        $icons = showIcons($current_ctags);
                                    }
                                    if ($show_desc != "") {
                                        $description = "<br />$myrow[6]";
                                    }

                                    // Simple Print (2), or Print with URL (3)
                                    if ($myrow[2] == 3) {
                                        $tokenized.= "<em>$myrow[8]</em><br />" . _("") . "
                                        <a href=\"$myrow[0]\" $target>$myrow[7]</a>
                                        $icons $description";
                                    } else {

                                        // check if it's a url
                                        if (preg_match('/^(https?|www)/', $myrow[0])) {
                                            $tokenized.= "<a href=\"$myrow[0]\" $target>$myrow[8]</a> $icons $description";
                                        } else {
                                            $tokenized.= "$myrow[8] <em>$myrow[0]</em> $icons $description";
                                        }
                                    }
                                }
                            }
                            break;
                      case 'sss':
                        global $tel_prefix;

                        $querier = new Querier();
                        $qs = "SELECT lname, fname, email, tel, title from staff WHERE email IN ('" . str_replace( ',', "','", $fields[1] ) . "') ORDER BY lname, fname";

                        //print $qs;

                        $staffArray = $querier->query($qs);

                        foreach ($staffArray as $value) {

                          // get username from email
                          $truncated_email = explode("@", $value[2]);

                          $staff_picture = $relative_asset_path . "users/_" . $truncated_email[0] . "/headshot.jpg";

                          // Output Picture and Contact Info
                          $tokenized .= "
                          <div class=\"clearboth\"><img src=\"$staff_picture\" alt=\"Picture: $value[1] $value[0]\"  class=\"staff_photo2\" align=\"left\" style=\"margin-bottom: 5px;\" />
                          <p><a href=\"mailto:$value[2]\">$value[1] $value[0]</a><br />$value[4]<br />
                          Tel: $tel_prefix $value[3]</p>\n</div>\n";
                        }
                        break;
                      case 'toc':
                        $lobjTocPluslet = new Pluslet_TOC('', '', $our_subject_id);
                        $lobjTocPluslet->setTickedItems( explode(',', $fields[1]) );
                        $lobjTocPluslet->setHideTitleBar(1);
                        $tokenized .= $lobjTocPluslet->output();
                        break;

                    }
                } elseif (preg_match('/{|}/', $part) && preg_match('/\bdab\b|\bfaq\b|\bcat\b|\bfil\b/', $part)) { // looks kinda like a token
                    $tokenized.= "<span style='background-color:yellow'>BROKEN TOKEN: " . $part . "</span>";
                } else {
                    $tokenized.= $part;
                }
            } // end foreach
        } else {

            $our_text = $our_text;
            return $our_text;
        }
        $our_text = $tokenized;
        return $our_text;
    }

function listGuides($search = "", $type="all", $display="default") {
    $db = new Querier();
    
    $andclause = "";
    global $guide_path;

    if ($search != "") {
        $search = scrubData($search);
        $andclause .= " AND subject LIKE '%" . $db->quote($search) . "%'";
    }

    if ($type != "all") {
        $andclause .= " AND type=" . $db->quote($type) . "";
    }

    $q = "SELECT shortform, subject, type FROM subject WHERE active = '1' " . $andclause . " AND type != 'Placeholder' ORDER BY subject";
   // $r = $db->query($q);
    //print $q;

    $row_count = 0;
    $colour1 = "oddrow";
    $colour2 = "evenrow";

    $db = new Querier;

    switch ($display) {
      case "default":

    $list_guides = "<table class=\"footable foo3\" width=\"98%\" data-filter=#filter-guides>
                    <thead>
                      <tr class=\"staff-heading\">
                        <th data-sort-ignore=\"false\">Name</th>
                        <th data-hide=\"phone,mid\" data-sort-ignore=\"true\">Type</th>
                      </tr>
                    </thead>";
    foreach ($db->query($q) as $myrow) {

        $row_colour = ($row_count % 2) ? $colour1 : $colour2;

        $guide_location = $guide_path . $myrow[0];

        $list_guides .= "<tr class=\"zebra type-$myrow[2]\" style=\"height: 1.5em;\">
     <td><a href=\"$guide_location\">" . htmlspecialchars_decode($myrow[1]) . "</a> 
        <div class=\"list_bonus\"></div></td>
        <td class=\"subject\">{$myrow[2]}</td>
         </tr>\n";
        $row_count++; 
    }
    $list_guides .= "</table>";
      break;
      case "2col":

        $col_1 = "<div class=\"pure-u-1-2\">";
        $col_2 = "<div class=\"pure-u-1-2\">";

        foreach ($db->query($q) as $myrow) {

        $guide_location = $guide_path . $myrow[0];

        $our_item = "<li><a href=\"$guide_location\">" . htmlspecialchars_decode($myrow[1]) . "</a>
        <div class=\"list_bonus\">$myrow[2]</div>
        </li>";

          if ($row_count & 1) {
            // odd
            $col_2 .= $our_item;
          } else {
            // even
            $col_1 .= $our_item;
          }

        $row_count++;
        }

        $col_1 .= "</div>";
        $col_2 .= "</div>";


        $list_guides = "<div class=\"pure-g guide_list\">" . $col_1 . $col_2 . "</div>";

      break;
    }


    return $list_guides;
}

function listCollections($search = "", $display="default") {
    $db = new Querier();
    
    $whereclause = "";
    global $guide_path;

    if ($search != "") {
        $search = scrubData($search);
        $whereclause .= " WHERE subject LIKE '%" . $db->quote($search) . "%'";
    }


    $q = "SELECT collection_id, title, description, shortform FROM $whereclause collection ORDER BY title";
    $r = $db->query($q);
    $num_rows = count($r);
            
    $switch_row = round($num_rows / 2);

    $layout = "";

    //print $q;
    $row_count = 1;
    $colour1 = "oddrow";
    $colour2 = "evenrow";

    if ($num_rows < 1) { return; }

    switch ($display) {
      case "default":

    $list_collections = "<table class=\"item_listing\" width=\"98%\">";

    foreach ($r as $myrow) {

        $row_colour = ($row_count % 2) ? $colour1 : $colour2;

        $guide_location = "collection.php?d=" . $myrow[3];

        $list_collections .= "<tr class=\"zebra $row_colour\" style=\"height: 1.5em;\">
        <td><a href=\"$guide_location\">" . htmlspecialchars_decode($myrow[1]) . "</a>
        <div style=\"font-size: .9em;\">$myrow[2]</div></td></tr>\n";

        $row_count++; 
    }

    $list_collections .= "</table>";
    break;

    case "2col":

    // for 2 col
    $col_1 = "<div class=\"pure-u-1 pure-u-md-1-2\"><ul class=\"guide-listing\">";
    $col_2 = "<div class=\"pure-u-1 pure-u-md-1-2\"><ul class=\"guide-listing\">";    

    foreach ($r as $myrow) {

      $guide_location = "collection.php?d=" . $myrow[3];
      $list_bonus = $myrow[2];

      $our_item = "<li><i class=\"fa fa-plus-square\"></i> <a href=\"$guide_location\">" . htmlspecialchars_decode($myrow[1]) . "</a>
      <div class=\"guide_list_bonus\">$list_bonus</div>
      </li>";  

      if ($row_count <= $switch_row) {
        // first col
        $col_1 .= $our_item;
                
      } else {
        // even
        $col_2 .= $our_item;
      }

      $row_count++;

    } // end foreach

    $col_1 .= "</ul></div>";
    $col_2 .= "</ul></div>";

    $layout .= "<div class=\"pure-g guide_list\"><div class=\"pure-u-1 guide_list_header\"><a name=\"section-Collection\"></a><h3>" . _("Guide Collections") . "</h3></div>" . $col_1 . $col_2 ."</div>";
    $list_collections = $layout;

    break;
  }
    
    return $list_collections;
}

function listGuideCollections($collection_shortform) {

global $guide_path;
global $AssetPath;
global $collection_thumbnail_default;

$db = new Querier();

$collection_shortform = scrubData($collection_shortform, "text");

$qCollection = "SELECT c.collection_id, c.title, c.description, s.subject, s.shortform, s.redirect_url, s.description, s.keywords, s.type, s.last_modified 
FROM collection c, collection_subject cs, subject s
WHERE c.collection_id = cs.collection_id
AND cs.subject_id = s.subject_id
AND c.shortform = '$collection_shortform'
AND s.active = '1'
ORDER BY cs.sort";

$rCollection = $db->query($qCollection);

// prepare striping
$colour1 = "oddrow";
$colour2 = "evenrow";

$list_guides = "<table class=\"item_listing\" width=\"98%\">";

  foreach ($rCollection as $key => $value) {

    $row_colour = ($key % 2) ? $colour1 : $colour2;

    $guide_location = $guide_path . $value[4];
    $thumbnail = $AssetPath . "images/guide_thumbs/$value[4].jpg";
    //$thumbnail_default = "$AssetPath/images/guide_thumbs/chc.jpg"; //um only
    $thumbnail_default = $collection_thumbnail_default;

    //check if appropriate image exists; otherwise use the default one
    if (!@getimagesize($thumbnail)) { $thumbnail = $thumbnail_default; }

    // Stick in the title if it's the first row
    if ($key == 0) {
      $list_guides .= "<tr><td><h3>$value[1]</h3></td></tr>";
    }

    $list_guides .= "<tr class=\"zebra $row_colour\" style=\"height: 1.5em;\">
    <td><img class=\"staff_photo\" align=\"left\" style=\"margin-bottom: 20px;\" title=\"" . $value[3] . "\" alt=\"" . $value[3] . 
     "\" src=\"$thumbnail\" />
     <a href=\"$guide_location\">" . htmlspecialchars_decode($value[3]) . "</a> 
        <div style=\"font-size: .9em;\">{$value[6]}</div></td></tr>";


  }

$list_guides .= "</table>";

return $list_guides;
}


// This just returns whether or not you want an anchor target to open in new window
// made this puppy a function in case people want to use it elsewhere
    
function targetBlanker() {
  global $target_blank;

  if (isset($target_blank) && $target_blank == TRUE) {
     $target = "target=\"_blank\"";
  } else {
     $target = "";
  }  

  return $target;

}

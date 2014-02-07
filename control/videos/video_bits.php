<?php

/**
 *   @file video_bits.php
 *   @brief Ingesting videos via load; called by 
 *
 *   @author adarby
 *   @date 
 *   @todo 
 */
$subsubcat = "";
$subcat = "videos";
$page_title = "Video Bits include";
$header = "noshow";


include("../includes/header.php");

// Connect to database
try {
    $dbc = new DBConnector($uname, $pword, $dbName_SPlus, $hname);
} catch (Exception $e) {
    echo $e;
}

//print_r($_POST);

switch ($_REQUEST["type"]) {

    case "ingest":
      // check if we already have a record like this
      $our_id = scrubData($_REQUEST["foreign_id"]);
      
      $qcheck = "SELECT video_id FROM video WHERE foreign_id = '" . $our_id . "'";
      //print $qcheck;
      $rcheck = MYSQL_QUERY($qcheck);
      
      if (mysql_num_rows($rcheck) == 0) {
        $qinsert = "INSERT INTO video (title, description, source, foreign_id, duration, date, display)
        values(\"" . $_POST["title"] . "\", \""  
        . $_POST["description"] . "\", \"" 
        . $_POST["source"] . "\", \""         
        . $_POST["foreign_id"] . "\", \"" 
        . $_POST["duration"] . "\", \""         
        . $_POST["upload_date"] . "\",
          1                
        )";
        
        $rinsert = MYSQL_QUERY($qinsert);
        $video_id = mysql_insert_id();
        
        
      } else {
        // Do an update
        
        $qupdate = "UPDATE video 
          SET title = '" . mysql_real_escape_string(scrubData($_POST["title"])) . "',
          description = '" . mysql_real_escape_string(scrubData($_POST["description"], "richtext")) . "',
          source = '" . mysql_real_escape_string(scrubData($_POST["source"])) . "',
          foreign_id = '" . mysql_real_escape_string(scrubData($_POST["foreign_id"])) . "',
          duration = '" . mysql_real_escape_string(scrubData($_POST["duration"])) . "',
          date = '" . mysql_real_escape_string(scrubData($_POST["upload_date"])) . "'
          WHERE foreign_id = '" . $our_id . "'";
        
        $rupdate = MYSQL_QUERY($qupdate);
        $video_id = mysql_fetch_row($rcheck);
        $video_id = $video_id[0];
      }
      // insert/update image
      
      // get small thumbnail
      $image = curl_get($_POST["thumbnail_small"]);
      //$image = file_get_contents($_POST["thumbnail_small"]);
      $new_image = "../../assets/images/video_thumbs/" .$video_id . "_small.jpg";
      file_put_contents($new_image, $image);

      // get medium thumbnail (actually the youtube one is pretty large)
      $image = curl_get($_POST["thumbnail_medium"]);
      //$image = file_get_contents($_POST["thumbnail_medium"]);
      $new_image = "../../assets/images/video_thumbs/" .$video_id . "_medium.jpg";
      file_put_contents($new_image, $image);
      
      print "<p><strong>" . _("Modified.") . "</strong>  <a href=\"video.php?video_id=$video_id\">" . _("Check metadata for accuracy.  New videos are now active.") . "</a>.</p>";
      
     

        break;

}
?>
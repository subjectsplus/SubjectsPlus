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
    
use SubjectsPlus\Control\Querier;

include_once(__DIR__ . "/../includes/header.php");


// Connect to database


//print_r($_POST);

switch ($_REQUEST["type"]) {

    case "ingest":
        $db = new Querier;
        
      // check if we already have a record like this
      $our_id = scrubData($_REQUEST["foreign_id"]);
      
      $qcheck = "SELECT video_id FROM video WHERE foreign_id = '" . $our_id . "'";
      //print $qcheck;
      $rcheck = $db->query($qcheck);
      
      if (count($rcheck) == 0) {
        $qinsert = "INSERT INTO video (title, description, source, foreign_id, duration, date, display) VALUES (\"" .
            $_POST["title"] . "\",\""  .
            $_POST["description"] . "\",\"" .
            $_POST["source"] . "\",\"" .
            $_POST["foreign_id"] . "\",\"" .
            $_POST["duration"] . "\",\"" .
            date("Y-m-d", strtotime($_POST["upload_date"])) . "\",\"" .
            1 . "\")";

       //   print_r ($qinsert);
        $rinsert = $db->exec($qinsert);
        $video_id = $db->last_id();
        
        
      } else {
        // Do an update
          $db = new Querier;


          $qupdate = "UPDATE video SET title = " .
              $db->quote(scrubData($_POST['title'])) .
              ",description = " . $db->quote(scrubData($_POST['description'], 'richtext')) .
              ",source = " . $db->quote(scrubData($_POST['source'])) .
              ",foreign_id = " . $db->quote(scrubData($_POST['foreign_id'])) .
              ",duration = " . $db->quote(scrubData($_POST['duration'])) .
              ",date = " . $db->quote(scrubData(date("Y-m-d", strtotime($_POST["upload_date"])))) .
              " WHERE foreign_id = " . $db->quote($our_id);

          //print_r ($qupdate);

          $rupdate = $db->exec($qupdate);

          $video_idq = "SELECT video_id FROM video WHERE foreign_id =" . $db->quote($our_id) . "LIMIT 1";
          $video_q_result = $db->query($video_idq);
          $video_q_result_array = $video_q_result[0];
          $video_id = $video_q_result_array[0];


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

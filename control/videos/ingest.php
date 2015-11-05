<?php

/**
 *   @file 
 *   @brief 
 *
 *   @author adarby, rgilmour
 *   @date mar 2013
 */
    
use SubjectsPlus\Control\Querier;

$subcat = "video";
$page_title = "Video Admin";

include("../includes/header.php");

try {
  } catch (Exception $e) {
  echo $e;
}

$vid_user_name = "";
$ingest_source = "";

if (isset($_POST['username'])) {
  $vid_user_name = scrubData($_POST['username']);
} 

if (isset($_POST['ingest_source'])) {
  $ingest_source = scrubData($_POST['ingest_source']);
}


///////////
// our form //
////////////

$ingest_form = "<p>" . _("Select a source you wish to pull in video metadata from.  Enter the name of the user account, followed by the source.") . "</p><br />";

$ingest_form .= "<form method=\"post\" action=\"\">
  <p><strong>" .
        _("Account username") . "</strong>
  <input type=\"text\" name=\"username\" size=\"30\" value= \"$vid_user_name\" /> 
  
  <select id=\"ingest_source\" name=\"ingest_source\">
    <option value=\"Vimeo\"";
if ($ingest_source == "Vimeo") {
  $ingest_form .= " selected";
}
$ingest_form .= ">Vimeo</option>
    <option value=\"YouTube\"";
if ($ingest_source == "YouTube") {
  $ingest_form .= " selected";
}
$ingest_form .= ">YouTube</option>
  </select>
  <input type=\"submit\" name=\"submit\" value=\"submit\" /></p>
</form>";



if (isset($vid_user_name) && $vid_user_name != "") {
  $content = seekVids($ingest_source, $vid_user_name);
} else {
  $content = "<p>" . _("Enter an account username to begin.") . "</p>";
}

print "<br />
<div style=\"float: left;  width: 70%;\">
  <div id=\"ingest_results\"></div>
    <div class=\"box no_overflow\">
    $ingest_form
  </div>
  <div class=\"box no_overflow\">
    $content
</div>
</div>";

include("../includes/footer.php");
?>


<script type="text/javascript">
  $(document).ready(function(){
    $(".toggle_unanswered").click(function() {
      $("#unanswered .hideme").toggle();
      return false;
    });

    $(".toggle_answered").click(function() {
      $("#answered .hideme").toggle();
      return false;
    });

    $('a[id*=ingest]').click(function() {
      var ingest_id = $(this).attr("id").split("---");
      var data_bits = $("#data-" + ingest_id[1]).html();
      var useful_bits = data_bits.split("||");
      // title || description || thumbnail | username || upload date || duration || source
      // //$this_vid_title||$this_vid_description||$this_vid_thumbnail_small||$this_vid_thumbnail_medium||$this_vid_owner||$this_vid_date||$this_vid_duration||$source
      //alert(useful_bits[0]);
      $('#buttonland-'+ingest_id[1]).load("video_bits.php",
      {type: 'ingest', foreign_id: ingest_id[1], title: useful_bits[0], description: useful_bits[1], thumbnail_small: useful_bits[2], thumbnail_medium: useful_bits[3], username: useful_bits[4], upload_date: useful_bits[5], duration: useful_bits[6], source: useful_bits[7]}, 
      function() {
        
        //$('#buttonland-'+ingest_id[1]).html('<p><strong> Modified.</strong>  This record is NOT displayed until you update the metadata:  <a href="video.php?foreign_id=' +ingest_id[1] + '">' + useful_bits[0] + '</a></p>');
        
        //$(this).parent().html('<p><strong> Modified.</strong>  To display, modify record:  <a href="video.php">' + useful_bits[0] + '</a></p>');
      }).fadeIn(1600);
      //alert(ingest_id[1]);
      
    });
    

    /////////////////
    // Load custom modal window
    ////////////////

    $("a[class*=showcustom]").colorbox({
      iframe: true,
      innerWidth:"80%",
      innerHeight:"90%",
      maxWidth: "960px",
      maxHeight: "800px",

      onClosed:function() {
        location.reload();
      }
    });

  });
</script>
<style>

  a.button {
    color: #6e6e6e;
    font: bold 12px sans-serif;
    text-decoration: none;
    padding: 7px 12px;
    position: relative;
    display: inline-block;
    text-shadow: 0 1px 0 #fff;
    -webkit-transition: border-color .218s;
    -moz-transition: border .218s;
    -o-transition: border-color .218s;
    transition: border-color .218s;
    background: #f3f3f3;
    background: -webkit-gradient(linear,0% 40%,0% 70%,from(#F5F5F5),to(#F1F1F1));
    background: -moz-linear-gradient(linear,0% 40%,0% 70%,from(#F5F5F5),to(#F1F1F1));
    border: solid 1px #dcdcdc;
    border-radius: 2px;
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    margin-right: 10px;
    cursor: pointer;
  }
  a.button:hover {
    color: #333;
    border-color: #999;
    -moz-box-shadow: 0 2px 0 rgba(0, 0, 0, 0.2) -webkit-box-shadow:0 2px 5px rgba(0, 0, 0, 0.2);
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
  }
  a.button:active {
    color: #000;
    border-color: #444;
  }
  a.left {
    -webkit-border-top-right-radius: 0;
    -moz-border-radius-topright: 0;
    border-top-right-radius: 0;
    -webkit-border-bottom-right-radius: 0;
    -moz-border-radius-bottomright: 0;
    border-bottom-right-radius: 0;
    margin: 0;
  }
  a.middle {
    border-radius: 0;
    -webkit-border-radius: 0;
    -moz-border-radius: 0;
    border-left: solid 1px #f3f3f3;
    margin: 0;
    border-left: solid 1px rgba(255, 255, 255, 0);
  }
  a.middle:hover,
  a.right:hover { border-left: solid 1px #999 }
  a.right {
    -webkit-border-top-left-radius: 0;
    -moz-border-radius-topleft: 0;
    border-top-left-radius: 0;
    -webkit-border-bottom-left-radius: 0;
    -moz-border-radius-bottomleft: 0;
    border-bottom-left-radius: 0;
    border-left: solid 1px #f3f3f3;
    border-left: solid 1px rgba(255, 255, 255, 0);
  }
  a.big {
    font-size: 16px;
    padding: 10px 15px;
  }
  a.supersize {
    font-size: 20px;
    padding: 15px 20px;
  }
  a.save {
    background: url(../../assets/images/icons/button_sprite.png) 10px 7px no-repeat #f3f3f3;
    padding-left: 30px;
  }
  a.add {
    background: url(../../assets/images/icons/button_sprite.png)  10px -27px no-repeat #f3f3f3;
    padding-left: 30px;
  }
  a.delete {
    background: url(../../assets/images/icons/button_sprite.png)  10px -61px no-repeat #f3f3f3;
    padding-left: 30px;
  }
  a.flag {
    background: url(../../assets/images/icons/button_sprite.png)  10px -96px no-repeat #f3f3f3;
    padding-left: 30px;
  }
  a.up {
    background: url(../../assets/images/icons/button_sprite.png)  13px -133px no-repeat #f3f3f3;
    width: 18px;
  }
  a.down {
    background: url(../../assets/images/icons/button_sprite.png)  13px -168px no-repeat #f3f3f3;
    width: 18px;
  }
  a.save-big {
    background: url(../../assets/images/icons/button_sprite.png) 15px 11px no-repeat #f3f3f3;
    font-size: 16px;
    padding: 10px 15px 10px 35px;
  }
  a.add-big {
    background: url(../../assets/images/icons/button_sprite.png)  15px -23px no-repeat #f3f3f3;
    font-size: 16px;
    padding: 10px 15px 10px 35px;
  }
</style>



<?php

function seekVids($source, $vid_user_name, $start_index=1, $vid_count=0) {
	libxml_use_internal_errors(true);	
  switch ($source) {
    case "Vimeo":
      // API endpoint
      $api_endpoint = 'http://vimeo.com/api/v2/' . $vid_user_name;
      $vid_data = $api_endpoint . "/videos.xml";
      if ($start_index == 2 || $start_index == 3) {
        $vid_data .= "?page=$start_index";
      }
      $base = "video";
      break;

    case "YouTube":
      // API endpoint
      $api_endpoint = 'https://www.youtube.com/feeds/videos.xml?user=' . $vid_user_name;
      $vid_data = $api_endpoint;
      $base = "entry";
      break;
  }
  
  $videos = simplexml_load_string(curl_get($vid_data));

  if ($videos == NULL) {
  	$message = _("Error loading the video feed. It's possible there is no channel by that name.");
    print "<div class=\"master-feedback\" style=\"display:block;\">$message</div>";
  	exit;
  }
  	
  
// Load the user info and clips
//$user = simplexml_load_string(curl_get($api_endpoint . '/info.xml'));
  /*
    print "<pre>";
    print_r($videos);
    print "</pre>";

   */
  $content = "<h3>Videos from $source for user $vid_user_name</h3>
  <table width=\"98%\" class=\"item_listing\">";

  $row_count = 0;
  $colour1 = "oddrow";
  $colour2 = "evenrow";


  foreach ($videos->$base as $video) {

    $row_colour = ($row_count % 2) ? $colour1 : $colour2;

    switch ($source) {
      case "Vimeo":
        $this_vid_id = $video->id;
        $this_vid_url = $video->url;
        $this_vid_thumbnail_small = $video->thumbnail_small;
        $this_vid_thumbnail_medium = $video->thumbnail_medium;
        $this_vid_title = $video->title;
        $this_vid_description = $video->description;
        $this_vid_owner = $video->user_name;
        $this_vid_date = $video->upload_date;
        $this_vid_duration = $video->duration;
        break;
      case "YouTube":
        // code bits from http://www.ibm.com/developerworks/xml/library/x-youtubeapi/
        // get nodes in media: namespace for media information
    	
   	
        $media = $video->children('http://search.yahoo.com/mrss/');
        $this_vid_title = $video->title;
        $this_vid_description = $media->group->description;
        $this_vid_owner = $video->author->name;
        $this_vid_full_id = $video->id;
        $this_vid_date = $video->published;
        $boom = explode(":", $this_vid_full_id);
        $this_vid_id = $boom[2]; // hopefully this won't change!
        // get url
        $attrs = $media->group->content->attributes();
 
        $this_vid_url = $attrs['url'];
        
        
        // get video thumbnail
        $attrs = $media->group->thumbnail->attributes();     
       // print_r($media->group->thumbnail->attributes());
        
        $this_vid_thumbnail_small = $attrs['url'];
        $attrs2 = $media->group->thumbnail[0]->attributes();
        $this_vid_thumbnail_medium = $attrs['url'];
        
        $this_vid_duration = "";
        break;
    }
  
  $vid_count++;
  
    // check if this video is in place
    $qcheck = "SELECT title FROM video WHERE foreign_id = \"" . $this_vid_id . "\"";
    //print $qcheck;
    $db = new Querier;
                                                               
    $rcheck = $db->query($qcheck);

    if (count($rcheck) == 0) {
      $is_new = "";
      $add_string = "<a class=\"button add\" id=\"ingest---$this_vid_id\">" . _("INSERT Video Metadata") . "</a>";
    } else {
      $is_new = _("Note:  You already have metadata for this video.  Click UPDATE to overwrite.");
      $add_string = "<a class=\"button save\" id=\"ingest---$this_vid_id\">" . _("UPDATE Video Metadata") . "</a>";
    }


    $content .= "<tr class=\"zebra $row_colour\" valign=\"top\">
          <td width=\"250\" id=\"buttonland-$this_vid_id\">$add_string<br /><span class=\"smaller\">$is_new</span></td>
            <td><a href=\"$this_vid_url\"><img src=\"$this_vid_thumbnail_small\" /></a></td>
            <td>$this_vid_title</td>
            <td style=\"display: none;\" id=\"data-$this_vid_id\">$this_vid_title||$this_vid_description||$this_vid_thumbnail_small||$this_vid_thumbnail_medium||$this_vid_owner||$this_vid_date||$this_vid_duration||$source</td>
          </tr>";



    $row_count++;
  }
   switch($source) {

    case "YouTube":
      if ($vid_count % 50) { // we've probably got 'em all
        $content .= "</table>";                          
        return $content;
      } else {               // there are probably more                             
        $start_index += 50;
        $content .= seekVids($source, $vid_user_name, $start_index, $vid_count);
        return $content;
      }
    break;

    case "Vimeo":            // THIS WILL ONLY WORK FOR 60 VIDEOS OR FEWER
      if ($vid_count % 20) { // we've probably got 'em all
        $content .= "</table>";
        return $content;
      } else {              // there are probably more
        $start_index++;
        $content .= seekVids($source, $vid_user_name, $start_index, $vid_count);
        return $content;
      }
    break;

   }

}

?>
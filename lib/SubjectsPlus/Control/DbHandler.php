<?php
   namespace SubjectsPlus\Control;
/**
 *   @file sp_DBHandler
 *   @brief display results of A-Z list
 *
 *   @author adarby
 *   @date
 *   @todo  This just writes out the db results, not a proper object, I suppose.
 */
///////// Databases ///////////

class DbHandler {

  function writeTable($qualifier, $subject_id = '') {

    global $IconPath;
    global $proxyURL;

    // sanitize submission
    $selected = scrubData($qualifier);
    $subject_id = scrubData($subject_id);

    // Prepare conditions
    $condition1 = "";
    $condition2 = "";
    $condition3 = "";
    
    switch ($qualifier) {
      case "Num":
        $condition1 = "WHERE left(title, 1)  REGEXP '[[:digit:]]+'";
        $condition2 = "WHERE left(alternate_title, 1)  REGEXP '[[:digit:]]+'";
        break;
      case "All":
        $condition1 = "WHERE title != ''";
        $condition2 = "WHERE alternate_title != ''";
        break;
      case "bysub":

        if (isset($subject_id)) {
          $condition1 = "WHERE subject_id = $subject_id";
          $condition2 = "WHERE subject_id = $subject_id";
        } else {
          $condition1 = "WHERE title LIKE '%" . mysql_real_escape_string($selected) . "%'";
          $condition2 = "WHERE alternate_title LIKE '%" . mysql_real_escape_string($selected) . "%'";
        }
        break;
      case "bytype":

        if (isset($_GET["type"])) {
          $condition1 = "WHERE ctags LIKE '%" . mysql_real_escape_string(scrubData($_GET["type"])) . "%'";
          $condition2 = "WHERE ctags LIKE '%" . mysql_real_escape_string(scrubData($_GET["type"])) . "%'";
          $condition3 = "and alternate_title NOT NULL";
        }

        break;
      default:
        $condition1 = "WHERE title LIKE '" . mysql_real_escape_string($selected) . "%'";
        $condition2 = "WHERE alternate_title LIKE '" . mysql_real_escape_string($selected) . "%'";
    }


      
    
      $q1 = "CREATE TEMPORARY TABLE merge
      SELECT distinct left(title,1) as initial, title as newtitle, description, location, access_restrictions, title.title_id as this_record,
eres_display, display_note, pre, citation_guide, ctags, helpguide
		FROM title, restrictions, location, location_title, source, rank
		$condition1
		AND title.title_id = location_title.title_id
		AND location.location_id = location_title.location_id
		AND restrictions_id = access_restrictions
		AND eres_display = 'Y'
      AND rank.title_id = title.title_id AND source.source_id = rank.source_id
		ORDER BY newtitle";

      //print $q1 . ";";
     $r1 = MYSQL_QUERY($q1); 
     
     
      $q2 = "INSERT INTO merge 
     SELECT distinct left(alternate_title,1) as initial, alternate_title as newtitle, description, location, access_restrictions, title.title_id as this_record,
eres_display, display_note, pre, citation_guide, ctags, helpguide
		FROM title, restrictions, location, location_title, source, rank 
		$condition2
		AND title.title_id = location_title.title_id
		AND location.location_id = location_title.location_id
		AND restrictions_id = access_restrictions
		AND eres_display = 'Y'
     AND rank.title_id = title.title_id AND source.source_id = rank.source_id
        $condition3
		ORDER BY newtitle";
      
      //print $q2 . ";";
      $r2 = MYSQL_QUERY($q2);
      
      $q = "SELECT * FROM merge WHERE newtitle != '' ORDER BY newtitle";
      
      //print $q;

      
    // check row count for 0 returns
    $r = MYSQL_QUERY($q);
    $num_rows = mysql_num_rows($r);

    if ($num_rows == 0) {
      return "<div class=\"no_results\">" . _("Sorry, there are no results at this time.") . "</div>";
    }

    // prepare 	header
    $items = "<table width=\"98%\" class=\"item_listing\">";

    $row_count = 0;
    $colour1 = "oddrow";
    $colour2 = "evenrow";

    while ($myrow = mysql_fetch_array($r)) {

      $row_colour = ($row_count % 2) ? $colour1 : $colour2;

      $patterns = "/'|\"/";
      $replacements = "";

      $item_title = $myrow["1"];
      if ($myrow["pre"] != "") {
        $item_title = $myrow["pre"] . " " . $item_title;
      }
      
      $safe_title = trim(preg_replace($patterns, $replacements, $item_title));
      $blurb = $myrow["description"];
      $bib_id = $myrow["5"];

      /// CHECK RESTRICTIONS ///

      if (($myrow['4'] == 2) OR ($myrow['4'] == 3)) {
        $url = $proxyURL . $myrow[3];
        $rest_icons = "restricted";
      } elseif ($myrow['4'] == 4) {
        $url = $myrow[3];
        $rest_icons = "restricted";
      } else {
        $url = $myrow[3];
        $rest_icons = ""; // if you want the unlocked icon to show, enter "unrestricted" here
      }

      $current_ctags = explode("|", $myrow["ctags"]);

      // add our $rest_icons info to this array at the beginning
      array_unshift($current_ctags, $rest_icons);

      $icons = showIcons($current_ctags);
      
      /// Check for Help Guide ///
      if ($myrow["helpguide"] != "") {
        $helpguide = " <a href=\"" . $myrow["helpguide"] . "\"><img src=\"$IconPath/help.gif\" border=\"0\" alt=\"" . _("Help Guide") . "\" title=\"" . _("Help Guide") . "\" /></a>";
      } else {
        $helpguide = "";
      }
      
      //Check if there is a display note

      if ($myrow["display_note"] == NULL) {
        $display_note_text = "";
      } else {
        $display_note_text = "<br /><strong>" . _("Note:") . " </strong>$myrow[display_note]";
      }


      $bonus = "$blurb<br />";

      if ($blurb != "") {
        $information = "<img src=\"$IconPath/information.png\" class=\"curse_me\" border=\"0\" alt=\"" . _("more information") . "\" title=\"" . _("more information") . "\"  id=\"bib-$bib_id\" />";
      } else {
        $information = "";
      }

      $items .= "
	<tr class=\"zebra $row_colour\" valign=\"top\">
		<td class=\"toggleLink\" style=\"width: 120px;\">$information $icons</td>
		<td><a href=\"$url\">$item_title</a> $helpguide $display_note_text
                    <div class=\"list_bonus\">$bonus</div></td>
	</tr>";

      $row_count++;
    }

    $items .= "</table>";
    return $items;
  }

  function displaySubjects() {

    $q = "SELECT subject, subject_id FROM subject WHERE active = '1' ORDER BY subject";
    $r = MYSQL_QUERY($q);

    // check row count for 0 returns

    $num_rows = mysql_num_rows($r);

    if ($num_rows == 0) {
      return "<div class=\"no_results\">" . _("Sorry, there are no results at this time.") . "</div>";
    }

    // prepare 	header
    $items = "<table width=\"98%\" class=\"item_listing\">";

    $row_count = 0;
    $colour1 = "oddrow";
    $colour2 = "evenrow";

    while ($myrow = mysql_fetch_array($r)) {

      $row_colour = ($row_count % 2) ? $colour1 : $colour2;

      $items .= "
	<tr class=\"zebra $row_colour\" valign=\"top\">
		<td><a href=\"databases.php?letter=bysub&subject_id=$myrow[1]\">$myrow[0]</a></td>
	</tr>";

      $row_count++;
    }

    $items .= "</table>";
    return $items;
  }

  function displayTypes() {
    global $all_ctags;
    sort($all_ctags);

    // prepare 	header
    $items = "<table width=\"98%\" class=\"item_listing\">";

    foreach ($all_ctags as $value) {
    
      $pretty_type = ucwords(preg_replace('/_/', ' ', $value));
      $items .= "
	<tr class=\"zebra\" valign=\"top\">
		<td><a href=\"databases.php?letter=bytype&type=$value\">" . $pretty_type . "</a></td>
	</tr>";
    }
    $items .= "</table>";
    return $items;
  }

  function searchFor($qualifier) {
    
  }

  function deBug() {
    print "Query:  " . $q;
  }

}

?>
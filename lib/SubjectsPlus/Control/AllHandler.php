<?php
namespace SubjectsPlus\Control;
/**
 *   @file
 *   @brief
 *
 *   @author adarby (adapted by dzydlewski 2/2012 for record searches)
 *   @date
 *   @todo  This just writes out the db results, not a proper object, I suppose.
 */
///////// Databases ///////////

    
class AllHandler {

  function writeTable($qualifier, $subject_id = '') {

    global $IconPath;
    global $proxyURL;

    // sanitize submission
    $selected = scrubData($qualifier);
    $subject_id = scrubData($subject_id);

    // determine submission type
    $selected = getTableOptions($selected, $subject_id);

    if (isset($subject_id) && $subject_id != "") {
      $q = "select distinct left(title,1) as initial, title, description, location, access_restrictions, title.title_id as this_record,
eres_display, display_note, pre, citation_guide, ctags
        FROM title, restrictions, location, location_title, source, rank
        $selected
        AND title.title_id = location_title.title_id
        AND location.location_id = location_title.location_id
        AND restrictions_id = access_restrictions
       
        AND rank.title_id = title.title_id AND source.source_id = rank.source_id
        ORDER BY title ";
    } else {
      $q = "select distinct left(title,1) as initial, title, description, location, access_restrictions, title.title_id as this_record,
eres_display, display_note, pre, citation_guide, ctags
		FROM title, restrictions, location, location_title, source
		$selected
		AND title.title_id = location_title.title_id
		AND location.location_id = location_title.location_id
		AND restrictions_id = access_restrictions

		ORDER BY title";
    }

    //print $q;
    $db = new Querier;
    $r = $db->query($q);

    // check row count for 0 returns

    $num_rows = count($r);

    if ($num_rows == 0) {
      return "<div class=\"no_results\">" . _("Sorry, there are no results at this time.") . "</div>";
    }

    // prepare 	header
    $items = "<table width=\"98%\" class=\"item_listing\">";

    $row_count = 0;
    $colour1 = "oddrow";
    $colour2 = "evenrow";

    foreach ($r as $myrow) {

      $row_colour = ($row_count % 2) ? $colour1 : $colour2;

      $patterns = "/'|\"/";
      $replacements = "";

      $item_title = $myrow["title"];
      $safe_title = trim(preg_replace($patterns, $replacements, $item_title));
      $blurb = $myrow["description"];
      $bib_id = $myrow["5"];

      /// CHECK RESTRICTIONS ///

      if (($myrow['4'] == 2) OR ($myrow['4'] == 3)) {
        $url = $proxyURL . $myrow[3];
        $rest_icons = "restricted";
      } else {
        $url = $myrow[3];
        $rest_icons = ""; // if you want the unlocked icon to show, enter "unrestricted" here
      }

      $current_ctags = explode("|", $myrow["ctags"]);

      // add our $rest_icons info to this array at the beginning
      array_unshift($current_ctags, $rest_icons);

      $icons = showIcons($current_ctags);

      //Check if there is a display note

      if ($myrow["display_note"] == NULL) {
        $display_note_text = "";
      } else {
        $display_note_text = "<strong>" . _("Note:") . " </strong>$myrow[display_note]";
      }


      $bonus = "$blurb";

      if ($blurb != "") {
        $information = "<img src=\"$IconPath/information.png\" border=\"0\" alt=\"" . _("more information") . "\" title=\"" . _("more information") . "\"  id=\"bib-$bib_id\" />";
      } else {
        $information = "";
      }

      $items .= "
	<tr class=\"zebra $row_colour\" valign=\"top\">
		
		<td><a href=\"$url\" target=\"_blank\"><strong>$item_title</strong></a> $icons<br/>$bonus   $display_note_text
                   
	</tr>";

      $row_count++;
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

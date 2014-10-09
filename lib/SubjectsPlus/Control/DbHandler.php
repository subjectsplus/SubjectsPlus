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

    
use SubjectsPlus\Control\Querier;

class DbHandler {
    
  function writeTable($qualifier, $subject_id = '', $description_search = 0) {
      
    global $IconPath;
    global $proxyURL;

    $db = new Querier;
    // sanitize submission
    
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
            //get title ids in pluslets' resource token connected to subject
            $lobjGuide = new Guide($subject_id);
            $lobjTitleIds = $lobjGuide->getRelatedTitles();

          $condition1 = "WHERE (subject_id = $subject_id";
          $condition1 .= count($lobjTitleIds) > 0 ? "\nOR t.title_id IN (" . implode( ',', $lobjTitleIds) . ")" : "";
          $condition1 .= ")";
          $condition2 = "WHERE subject_id = $subject_id";
        } else {
          $condition1 = "WHERE title LIKE " . $db->quote("%" . $qualifier . "%");
          $condition2 = "WHERE alternate_title LIKE " . $db->quote("%" . $qualifier . "%");
        }
        break;
      case "bytype":

        if (isset($_GET["type"])) {
          $condition1 = "WHERE ctags LIKE " . $db->quote(scrubData($_GET["type"]));
          $condition2 = "WHERE ctags LIKE " . $db->quote(scrubData($_GET["type"]));
          $condition3 = "and alternate_title NOT NULL";
        }

        break;
        case "search":
        $condition1 = "WHERE title LIKE " . $db->quote("%" . $qualifier . "%");
        // If you uncomment the next line, it will search description field
        $condition1 = "WHERE (title LIKE " . $db->quote("%" . $qualifier . "%") . " OR description LIKE " . $db->quote("%" . $qualifier . "%");
        $condition2 = "WHERE alternate_title LIKE " . $db->quote("%" + $qualifier + "%");

        break;
            
      default:
      // This is the simple output by letter and also the search
            
            if (strlen($qualifier) == 1) {
            
                // Is like the first letter
                $condition1 = "WHERE title LIKE " . $db->quote($qualifier . "%");
                
            } else {
                      $condition1 = "WHERE title LIKE " . $db->quote("%" . $qualifier . "%");
            }
            
            if ($description_search == 1) {
          // If you uncomment the next line, it will search description field
          $condition1 = "WHERE (title LIKE " . $db->quote("%" . $qualifier . "%") . " OR description LIKE " . $db->quote("%" . $qualifier . "%") . ")";
        }

        $condition2 = "WHERE alternate_title LIKE " . $db->quote("%" + $qualifier + "%");
    }

      $q1 = "SELECT distinct left(t.title,1) as initial, t.title as newtitle, t.description, location, access_restrictions, t.title_id as this_record,eres_display, display_note, pre, citation_guide, ctags, helpguide
            FROM title as t
            INNER JOIN location_title as lt
            ON t.title_id = lt.title_id
            INNER JOIN location as l
            ON lt.location_id = l.location_id
            INNER JOIN restrictions as r
            ON l.access_restrictions = r.restrictions_id
            INNER JOIN rank as rk
            ON rk.title_id = t.title_id
            INNER JOIN source as s
            ON rk.source_id = s.source_id
            $condition1
            AND eres_display = 'Y'
            ORDER BY newtitle";

      $q2 = "SELECT distinct left(t.alternate_title,1) as initial, t.alternate_title as newtitle, t.description, location, access_restrictions, t.title_id as this_record,eres_display, display_note, pre, citation_guide, ctags, helpguide
            FROM title as t
            INNER JOIN location_title as lt
            ON t.title_id = lt.title_id
            INNER JOIN location as l
            ON lt.location_id = l.location_id
            INNER JOIN restrictions as r
            ON l.access_restrictions = r.restrictions_id
            INNER JOIN rank as rk
            ON rk.title_id = t.title_id
            INNER JOIN source as s
            ON rk.source_id = s.source_id
            $condition2
		        AND eres_display = 'Y'
            $condition3

		        ORDER BY newtitle";

   
      
    $r = $db->query($q1);
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
        
      
        
      $item_title = $myrow[1];
      if ($myrow["pre"] != "") {
        $item_title = $myrow["pre"] . " " . $item_title;
      }
      
      $safe_title = trim(preg_replace($patterns, $replacements, $item_title));
      $blurb = $myrow["description"];
      $bib_id = $myrow[5];

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
        $display_note_text = "<br /><strong>" . _("Note:") . " </strong>" . $myrow['display_note'];
      }


      $bonus = "$blurb<br />";

      if ($blurb != "") {
        $information1 = "<span id=\"bib-$bib_id\" class=\"toggleLink curse_me\"><img src=\"$IconPath/information.png\" border=\"0\" alt=\"" . _("more information") . "\" title=\"" . _("more information") . "\" /></span>";
        // This is new details link; you can use the one above if you prefer
        $information = "<span id=\"bib-$bib_id\" class=\"toggleLink curse_me\">" . _("about") . "</span>";
        
      } else {
        $information = "";
      }

      $target = targetBlanker();    

      $items .= self::generateLayout($row_colour,$url,$target,$item_title,$information,$information1,$icons,$helpguide,$display_note_text,$bonus);

      $row_count++;
    }

    $items .= "</table>";
    return $items;
  }

  function generateLayout($row_colour,$url,$target,$item_title,$information,$information1,$icons,$helpguide,$display_note_text,$bonus) {
    $onerow = "<tr class=\"zebra $row_colour\" valign=\"top\">
      <td><a href=\"$url\" $target>$item_title</a> $information <span class=\"db_icons\">$icons</span> $helpguide $display_note_text
        <div class=\"list_bonus\">$icons $bonus</div>
      </td>  
    </tr>";
    $onerow = "<tr class=\"zebra $row_colour\" valign=\"top\">
      <td style=\"width: 120px\">$information1 <span class=\"db_icons\">$icons</span></td><td><a href=\"$url\" $target>$item_title</a>  $helpguide $display_note_text
        <div class=\"list_bonus\">$bonus</div>
      </td>  
    </tr>";

    return $onerow;

  }

  function displaySubjects() {

    $q = "SELECT subject, subject_id FROM subject WHERE active = '1' ORDER BY subject";
    $r = $db->query($q);

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


    foreach ($r as $myrow) {
     
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

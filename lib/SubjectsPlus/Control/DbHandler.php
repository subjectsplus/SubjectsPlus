<?php

namespace SubjectsPlus\Control;

/**
 * @file sp_DBHandler
 * @brief display results of A-Z list
 *
 * @author adarby
 *         @date
 * @todo This just writes out the db results, not a proper object, I suppose.
 */
// /////// Databases ///////////
use SubjectsPlus\Control\Querier;

class DbHandler {
	private $_connection;
	function writeTable($qualifier, $subject_id = '', $description_search = 0) {
		global $IconPath;
		global $proxyURL;

		$db = new Querier ();
		// sanitize submission

		switch ($qualifier) {
            case "Free" :

                $connection = $db->getConnection ();
                $statement = $connection->prepare ( "SELECT DISTINCT LEFT(t.title,1) AS initial, t.title AS newtitle, t.description, location, access_restrictions, t.title_id AS this_record,eres_display, display_note, pre, citation_guide, ctags, helpguide, alternate_title
        FROM title AS t
        INNER JOIN location_title AS lt
        ON t.title_id = lt.title_id
        INNER JOIN location AS l
        ON lt.location_id = l.location_id
        INNER JOIN restrictions AS r
        ON l.access_restrictions = r.restrictions_id
        INNER JOIN rank AS rk
        ON rk.title_id = t.title_id
        INNER JOIN source AS s
        ON rk.source_id = s.source_id
        WHERE title != ''
        AND l.`access_restrictions` = '1'
        AND eres_display = 'Y'
                ORDER BY newtitle" );

                $statement->execute ();
                $results = $statement->fetchAll ();

            break;
			case "Num" :

				$connection = $db->getConnection ();
				$statement = $connection->prepare ( "SELECT distinct left(t.title,1) as initial, t.title as newtitle, t.description, location, access_restrictions, t.title_id as this_record,eres_display, display_note, pre, citation_guide, ctags, helpguide
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
      	WHERE left(title, 1)  REGEXP '[[:digit:]]+'
      	AND eres_display = 'Y'
      	ORDER BY newtitle" );

				$statement->execute ();
				$results = $statement->fetchAll ();

				break;
			case "All" :
				$connection = $db->getConnection ();
				$statement = $connection->prepare ( "
        SELECT distinct left(t.title,1) as initial, t.title as newtitle, t.description, location, access_restrictions, t.title_id as this_record,eres_display, display_note, pre, citation_guide, ctags, helpguide, alternate_title
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
        WHERE title != ''
        AND eres_display = 'Y'
        		ORDER BY newtitle" );

				$statement->execute ();
				$results = $statement->fetchAll ();

				break;
			case "bysub" :

				if (isset ( $subject_id )) {
					// get title ids in pluslets' resource token connected to subject
					// I don't know the purpose of this, so I zeroed it out below by resetting the $lobjTitleIds array --agd
					
					$lobjGuide = new Guide ( $subject_id );
					$lobjTitleIds = $lobjGuide->getRelatedTitles ();

					$lobjTitleIds = array();
					if (count($lobjTitleIds) > 0) {

						$connection = $db->getConnection ();
						$statement = $connection->prepare ( "
        	SELECT distinct left(t.title,1) as initial, t.title as newtitle, t.description, location, access_restrictions, t.title_id as this_record,eres_display, display_note, pre, citation_guide, ctags, helpguide, alternate_title
        	From title as t
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
        WHERE title LIKE :qualifer
        	AND eres_display = 'Y'
        	AND dbbysub_active = 1
        ORDER BY newtitle" );

						/*
						$condition1 = "WHERE (subject_id = $subject_id";
						$condition1 .= count ( $lobjTitleIds ) > 0 ? "\nOR t.title_id IN (" . implode ( ',', $lobjTitleIds ) . ")" : "";
						$condition1 .= ")";
						*/
						$letter = "%" . $qualifier . "%";

						$statement->bindParam ( ":qualifer", $letter );
						$statement->execute ();
						$results = $statement->fetchAll ();



					} else {

						$connection = $db->getConnection ();
						$statement = $connection->prepare ( "
        	SELECT distinct left(t.title,1) as initial, t.title as newtitle, t.description, location, access_restrictions, t.title_id as this_record,eres_display, display_note, pre, citation_guide, ctags, helpguide, alternate_title
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
        WHERE subject_id = :subject_id
        	AND eres_display = 'Y'
        	AND dbbysub_active = 1
        ORDER BY newtitle" );

						$statement->bindParam ( ":subject_id", $subject_id );
						$statement->execute ();
						$results = $statement->fetchAll();

					}

				} else {


					$connection = $db->getConnection ();
					$statement = $connection->prepare ( "
        	SELECT distinct left(t.title,1) as initial, t.title as newtitle, t.description, location, access_restrictions, t.title_id as this_record,eres_display, display_note, pre, citation_guide, ctags, helpguide
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
        WHERE title LIKE :qualifer
        	AND eres_display = 'Y'
        	AND dbbysub_active = 1
        ORDER BY newtitle" );

					$letter = "%" . $qualifier . "%";

					$statement->bindParam ( ":qualifer", $letter );
					$statement->execute ();
					$results = $statement->fetchAll ();




				}
				break;
			case "bytype" :

				if (isset ( $_GET ["type"] )) {

					$type = scrubData ( "%" . $_GET ["type"] . "%" );

					$connection = $db->getConnection ();
					$statement = $connection->prepare ( "
        		SELECT distinct left(t.title,1) as initial, t.title as newtitle, t.description, location, access_restrictions, t.title_id as this_record,eres_display, display_note, pre, citation_guide, ctags, helpguide
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
            WHERE ctags LIKE :type
            AND eres_display = 'Y'
            ORDER BY newtitle" );

					$statement->bindParam ( ":type", $type );
					$statement->execute ();
					$results = $statement->fetchAll ();
				}

				break;
			case "search" :
				// If you uncomment the next line, it will search description field
				$connection = $db->getConnection ();
				$statement = $db->prepare ( "
        	SELECT distinct left(t.title,1) as initial, t.title as newtitle, t.description, location, access_restrictions, t.title_id as this_record,eres_display, display_note, pre, citation_guide, ctags, helpguide
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
        WHERE (title LIKE :qualifier OR description LIKE :qualifier)
        	AND eres_display = 'Y'
        ORDER BY newtitle" );

				$qualifier = "%" . $qualifier . "%";
				$statement->execute ();
				$results = $statement->fetchAll ();

				break;

			default :
				// This is the simple output by letter and also the search

				if (strlen ( $qualifier ) == 1) {

					// Is like the first letter

					$connection = $db->getConnection ();
					$statement = $connection->prepare ( "
	SELECT DISTINCT LEFT(t.title,1) as initial, t.title as newtitle, t.description, location, access_restrictions, t.title_id as this_record,eres_display, display_note, pre, citation_guide, ctags, helpguide,alternate_title
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
        WHERE left(t.title, 1) LIKE :qualifer
        	AND eres_display = 'Y'

        UNION
        	SELECT DISTINCT LEFT(alternate_title,1) as initial, alternate_title as newtitle, t.description, location, access_restrictions, t.title_id as this_record,eres_display, display_note, pre, citation_guide, ctags, helpguide,alternate_title
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
        WHERE left(alternate_title, 1) LIKE :qualifer
        	AND eres_display = 'Y'
ORDER BY newtitle

" );

					$letter = $qualifier . "%";

					$statement->bindParam ( ":qualifer", $letter );
					$statement->execute ();
					$results = $statement->fetchAll ();
				} else {

					$connection = $db->getConnection ();
					$statement = $connection->prepare ( "
        	SELECT distinct left(t.title,1) as initial, t.title as newtitle, t.description, location, access_restrictions, t.title_id as this_record,eres_display, display_note, pre, citation_guide, ctags, helpguide
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
        WHERE title LIKE :qualifer
        	AND eres_display = 'Y'
        ORDER BY newtitle" );

					$letter = "%" . $qualifier . "%";

					$statement->bindParam ( ":qualifer", $letter );
					$statement->execute ();
					$results = $statement->fetchAll ();

					$condition1 = "WHERE title LIKE " . $db->quote ( "%" . $qualifier . "%" );
				}

				if ($description_search == 1) {

					$connection = $db->getConnection ();
					$statement = $connection->prepare ( "
        	SELECT distinct left(t.title,1) as initial, t.title as newtitle, t.description, location, access_restrictions, t.title_id as this_record,eres_display, display_note, pre, citation_guide, ctags, helpguide
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
        WHERE (title LIKE :qualifer OR description LIKE :qualifer1)
        	AND eres_display = 'Y'        	
        ORDER BY newtitle" );

					$qualifier = "%" . $qualifier . "%";
					$statement->bindParam ( ":qualifer", $qualifier );
					$statement->bindParam ( ":qualifer1", $qualifier );
					$statement->execute();
					$results = $statement->fetchAll ();
				}
		}

		$num_rows = count ( $results );

		if ($num_rows == 0) {
			return "<div class=\"no_results\">" . _ ( "Sorry, there are no results at this time." ) . "</div>";
		}

		// prepare header
		$items = "<table width=\"98%\" class=\"item_listing trackContainer\">";

		$row_count = 0;
		$colour1 = "oddrow";
		$colour2 = "evenrow";

		foreach ( $results as $myrow ) {

			$row_colour = ($row_count % 2) ? $colour1 : $colour2;

			$patterns = "/'|\"/";
			$replacements = "";

			$item_title = $myrow [1];
			if ($myrow ["pre"] != "") {
				$item_title = $myrow ["pre"] . " " . $item_title;
			}

			$safe_title = trim ( preg_replace ( $patterns, $replacements, $item_title ) );
			$blurb = $myrow ["description"];
			$bib_id = $myrow [5];

			// / CHECK RESTRICTIONS ///

			if (($myrow ['4'] == 2) or ($myrow ['4'] == 3)) {
				$url = $proxyURL . $myrow [3];
				$rest_icons = "restricted";
			} elseif ($myrow ['4'] == 4) {
				$url = $myrow [3];
				$rest_icons = "restricted";
			} else {
				$url = $myrow [3];
				$rest_icons = ""; // if you want the unlocked icon to show, enter "unrestricted" here
			}

			$current_ctags = explode ( "|", $myrow ["ctags"] );

			// add our $rest_icons info to this array at the beginning
			array_unshift ( $current_ctags, $rest_icons );

			$icons = showIcons ( $current_ctags );

			// / Check for Help Guide ///
			if ($myrow ["helpguide"] != "") {
				$helpguide = " <a href=\"" . $myrow ["helpguide"] . "\"><img src=\"$IconPath/help.gif\" border=\"0\" alt=\"" . _ ( "Help Guide" ) . "\" title=\"" . _ ( "Help Guide" ) . "\" /></a>";
			} else {
				$helpguide = "";
			}

			// Check if there is a display note

			if ($myrow ["display_note"] == NULL) {
				$display_note_text = "";
			} else {
				$display_note_text = "<div class=\"db-note\"><strong>" . _ ( "Note:" ) . " </strong>" . $myrow ['display_note'] . "</div>";
			}

			$bonus = "$blurb<br />";
			$favorite_link_rand_id = time() . rand();

			if ($blurb != "") {
				$information1 = "<span class=\"fas fa fa-heart uml-quick-links favorite-item-icon inactive-favorite\" data-favorite-link-rand-id=\"$favorite_link_rand_id\" tabindex=\"0\" role=\"button\" data-type=\"favorite-item-icon\" data-item-type=\"Databases\" alt=\"Add to My Favorites\" title=\"Add to My Favorites\"></span><span id=\"bib-$bib_id\" class=\"toggleLink curse_me\"><i class=\"fas fa fa-info-circle\" title=\"" . _ ( "more information" ) . "\"></i></span>";
				// This is new details link; you can use the one above if you prefer
				$information = "<span class=\"fas fa fa-heart uml-quick-links favorite-item-icon inactive-favorite\" data-favorite-link-rand-id=\"$favorite_link_rand_id\" tabindex=\"0\" role=\"button\" data-type=\"favorite-item-icon\" data-item-type=\"Databases\" alt=\"Add to My Favorites\" title=\"Add to My Favorites\"></span><span id=\"bib-$bib_id\" class=\"toggleLink curse_me\"><i class=\"fas fa fa-info-circle\" title=\"" . _ ( "about" ) . "\"></i></span>";
			} else {
				$information = "";
				$information1 = "";
			}

			$target = targetBlanker ();


          $items .= self::generateLayout ( $row_colour, $url, $target, $item_title, $information, $information1, $icons, $helpguide, $display_note_text, $bonus, $favorite_link_rand_id);



			$row_count ++;
		}

		$items .= "</table>";
		return $items;
	}
	function generateLayout($row_colour, $url, $target, $item_title, $information, $information1, $icons, $helpguide, $display_note_text, $bonus, $favorite_link_rand_id) {
		$onerow = "<tr class=\"zebra $row_colour\" valign=\"top\">
      <td><a href=\"$url\" $target>$item_title</a> $information <span class=\"db_icons\">$icons</span> $helpguide $display_note_text
        <div class=\"list_bonus\">$icons $bonus</div>
      </td>
    </tr>";
		$onerow = "<tr class=\"zebra $row_colour\" valign=\"top\">
      <td width=\"70px\">$information1</td>
      <td><a href=\"$url\" $target data-favorite-link-rand-id=\"$favorite_link_rand_id\" class=\"no-decoration default \">$item_title</a>  $helpguide $display_note_text
        <div class=\"list_bonus\"><span class=\"db_icons\">$icons</span> $bonus</div>
      </td>
    </tr>";

		return $onerow;
	}





	function displaySubjects() {

		$db = new Querier;
		$connection = $db->getConnection();
		$statement = $connection->prepare ( "SELECT s.subject, s.subject_id, s.type
FROM subject as s WHERE exists(
SELECT t.title, l.record_status, r.title_id, r.rank_id, r.description_override
FROM rank r, location_title lt, location l, title t
    WHERE subject_id = s.subject_id
    AND lt.title_id = r.title_id
    AND l.location_id = lt.location_id
    AND t.title_id = lt.title_id
    AND l.eres_display = 'Y'
    AND l.record_status = 'Active'
    AND r.dbbysub_active = 1)
AND s.active = 1
ORDER BY s.subject");

		$statement->bindParam ( ":qualifer", $letter );
		$statement->execute ();
		$r = $statement->fetchAll();

		// check row count for 0 returns

		$num_rows = count ( $r );

		if ($num_rows == 0) {
			return "<div class=\"no_results\">" . _ ( "Sorry, there are no results at this time." ) . "</div>";
		}

		// prepare header
		$items = "<table width=\"98%\" class=\"item_listing\">";

		$row_count = 0;
		$colour1 = "oddrow";
		$colour2 = "evenrow";

		foreach ( $r as $myrow ) {

			$row_colour = ($row_count % 2) ? $colour1 : $colour2;

			$items .= "
	<tr class=\"zebra $row_colour\" valign=\"top\">
		<td><a href=\"databases.php?letter=bysub&subject_id=$myrow[1]\">$myrow[0]</a></td>
	</tr>";

			$row_count ++;
		}

		$items .= "</table>";
		return $items;
	}
	function displayTypes() {
		global $all_ctags;
		sort ( $all_ctags );

		// prepare header
		$items = "<table width=\"98%\" class=\"item_listing\">";

		foreach ( $all_ctags as $value ) {

			$pretty_type = ucwords ( preg_replace ( '/_/', ' ', $value ) );
			$items .= "
	<tr class=\"zebra\" valign=\"top\">
		<td><a href=\"databases.php?letter=bytype&type=$value\">" . $pretty_type . "</a></td>
	</tr>";
		}
		$items .= "</table>";
		return $items;
	}
	function deBug() {
		print "Query:  " . $q;
	}
}

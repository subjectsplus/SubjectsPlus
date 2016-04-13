<?php
/**
 *   @file SaveGuide.php
 *   @brief This class takes a JSON encoded guide and saves it to the database.
 *
 *   @author little9 (Jamie Little)
 *   @date Auguest 2015
 */
namespace SubjectsPlus\Control\Guide;

use SubjectsPlus\Control\Querier;

class SaveGuide {
	
	private $_input;
	
	public function __construct($input) {
		$this->_input = $input;
	}
	
	
	public function save() {
		
		$db = new Querier;
		$lobjTabs = json_decode ( $this->_input['tabs'], true );
		
		// Remove all existing entries for that guide from intervening table
		$subject_id = $this->_input["this_subject_id"];
		
		$qs = "SELECT tab_id FROM tab WHERE subject_id = '$subject_id'";
		$drs = $db->query ( $qs );
		
		// list all pluslets associated with guide before save that aren't special
		$qp = "SELECT p.pluslet_id
FROM pluslet p
INNER JOIN pluslet_section ps
ON p.pluslet_id = ps.pluslet_id
INNER JOIN section sec
ON ps.section_id = sec.section_id
INNER JOIN tab t
ON sec.tab_id = t.tab_id
INNER JOIN subject s
ON t.subject_id = s.subject_id
WHERE s.subject_id = $subject_id
AND p.type != 'Special'";
		
		$lobjBeforePluslets = $db->query ( $qp );
		$lobjAfterPluslets = array (); // initiate list currently saving pluslets
		
		foreach ( $drs as $row ) {
			$qd = "DELETE ps, sec FROM pluslet_section ps
	INNER JOIN section sec
	ON ps.section_id = sec.section_id
	WHERE sec.tab_id = '{$row[0]}'";
			$db->exec ( $qd );
			
			$qd = "DELETE FROM tab WHERE tab_id = '{$row[0]}'";
			$db->exec ( $qd );
		}
		
		$lintTabIndex = 0;
		
		foreach ( $lobjTabs as $lobjTab ) {
			if (isset ( $lobjTab ['external'] )) {
			} else {
				$lobjTab ['external'] = NULL;
			}
			
			$qi = "INSERT INTO tab (subject_id, label, tab_index, external_url, visibility) VALUES ('$subject_id', '{$lobjTab['name']}', $lintTabIndex, '{$lobjTab['external']}', {$lobjTab['visibility']})";
			// print $qi . "<br />";
			$db->exec ( $qi );
			
			$lintTabId = $db->last_id ();
			
			$lintSectionIndex = 0;
			
			// insert sections
			foreach ( $lobjTab ['sections'] as $lobjSection ) {
				// insert section, as of now only one per tab
				$qi = "INSERT INTO section (section_index, layout, tab_id) VALUES ('$lintSectionIndex', '{$lobjSection['layout']}', '$lintTabId')";
				// print $qi . "<br />";
				$db->exec ( $qi );
				
				$lintSecId = $db->last_id ();
				
				$left_col = $lobjSection ["left_data"];
				$center_col = $lobjSection ["center_data"];
				$sidebar = $lobjSection ["sidebar_data"];
				
				// added by dgonzalez in order to separate by '&pluslet[]=' even if dropspot-left doesn't exist
				$left_col = "&" . $left_col;
				$center_col = "&" . $center_col;
				$sidebar = "&" . $sidebar;
				
				// remove the "drop here" non-content & get all our "real" contents into array
				$left_col = str_replace ( "dropspot-left[]=1", "", $left_col );
				$leftconts = explode ( "&pluslet[]=", $left_col );
				
				$center_col = str_replace ( "dropspot-center[]=1", "", $center_col );
				$centerconts = explode ( "&pluslet[]=", $center_col );
				
				$sidebar = str_replace ( "dropspot-sidebar[]=1", "", $sidebar );
				$sidebarconts = explode ( "&pluslet[]=", $sidebar );
				
				// CHECK IF THERE IS CONTENT
				
				// Now insert the appropriate entries
				
				foreach ( $leftconts as $key => $value ) {
					if ($key != 0) {
						$qi = "INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES ('$value', '$lintSecId', 0, '$key')";
						// print $qi . "<br />";
						$db->exec($qi);
						
						array_push ( $lobjAfterPluslets, $value );
					}
				}
				
				foreach ( $centerconts as $key => $value ) {
					if ($key != 0) {
						$qi = "INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES ('$value', '$lintSecId', 1, '$key')";
						// print $qi . "<br />";
						$db->exec($qi);
						
						array_push ( $lobjAfterPluslets, $value );
					}
				}
				
				foreach ( $sidebarconts as $key => $value ) {
					if ($key != 0) {
						$qi = "INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES ('$value', '$lintSecId', 2, '$key')";
						// print $qi . "<br />";
						$db->exec($qi);
						
						array_push ( $lobjAfterPluslets, $value );
					}
				}
				
				$lintSectionIndex ++;
			}
			
			$lintTabIndex ++;
		}
		
		// delete all pluslets that are not being used anymore to avoid orphans
		foreach ( $lobjBeforePluslets as $lobjPluslet ) {
			if (! in_array ( $lobjPluslet ['pluslet_id'], $lobjAfterPluslets )) {
				$q = "DELETE FROM pluslet WHERE pluslet_id = {$lobjPluslet['pluslet_id']}";
				
				if ($db->exec ( $q ) === FALSE) {
					print "Error could not remove pluslet orphans!";
					exit ();
				}
			}
		}
		
		// ///////////////////
		// Alter chchchanges table
		// table, flag, item_id, title, staff_id
		// //////////////////
		
		// $updateChangeTable = changeMe("guide", "update", $_COOKIE["our_guide_id"], $_COOKIE["our_guide"], $_SESSION['staff_id']);
		
		print  _( "Thy Will Be Done:  Guide Updated." ) ;
	}
}
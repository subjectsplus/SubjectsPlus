<?php

namespace SubjectsPlus\Control\Guide;
use SubjectsPlus\Control\Guide;

class SectionData {
	
	private $_input;
	
	
	public function __construct($input) {
		$this->_input = $input;
	}
	public function processSection() {
		global $IconPath;
		
		$lobjGuide = new Guide ();
		
		if (isset ( $this->_input ['action'] )) {
			switch ($this->_input ['action']) {
				case 'create' :
					// print section and slider div
					$new_id = rand ( 1, 100000 );
					
					print "<div id=\"section_new_$new_id\" class=\"sp_section\" data-layout=\"4-4-4\">";
					print "<div class=\"sp_section_controls\">
				<img src=\"$IconPath/hand_cursor-26.png\" class=\"section_sort\" title=\"Move Section\"/>
				<i class=\"fa fa-trash-o section_remove\" title=\"Delete Section\"></i>
				<div id=\"slider_section_new_$new_id\" class=\"sp_section_slider\"></div>
				</div>";
					
					print $lobjGuide->dropBoxes ( 0, 'left', "" );
					print $lobjGuide->dropBoxes ( 1, 'center', "" );
					print $lobjGuide->dropBoxes ( 2, 'sidebar', "" );
					print '<div id="clearblock" style="clear:both;"></div> <!-- this just seems to allow the space to grow to fit dropbox areas -->';
					print '</div>';
					print '<script type="text/javascript">';
					
					print "makeSectionSlider('div[id=\"" . "slider_section_new_{$new_id}" . "\"]');";
					print "</script>";
					break;
				case 'delete' :
					if (isset ( $_POST ['section_id'] )) {
						$db = new Querier ();
						
						$q = "DELETE p, s
							FROM pluslet p
							INNER JOIN pluslet_section ps
							ON p.pluslet_id = ps.pluslet_id
							INNER JOIN section s
							ON ps.section_id = s.section_id
							WHERE p.type != 'Special'
							AND s.section_id = " . $_POST ['section_id'];
						
						if ($db->exec ( $q ) === FALSE) {
							print "Query Error! Did not delete";
						} else {
							print "Thy will be done!";
						}
					} else {
						print "Error: No section ID";
					}
					break;
				default :
					print 'No action.';
					break;
			}
		} else {
			print 'No action.';
		}
	}
}
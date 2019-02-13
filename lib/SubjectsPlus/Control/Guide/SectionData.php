<?php
/**
 * @file SaveGuide.php
 * @brief This class is used to write a section to the database.
 *
 * @author little9 (Jamie Little)
 * @date Auguest 2015
 */

namespace SubjectsPlus\Control\Guide;

use SubjectsPlus\Control\Guide;
use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Interfaces\OutputInterface;


class SectionData implements OutputInterface {

	private $_input;
	public $section_ids;


	public function __construct( $input ) {
		$this->_input = $input;
	}

	public function processSection() {
		global $IconPath;

		$lobjGuide = new Guide ();

		if ( isset ( $this->_input ['action'] ) ) {
			switch ( $this->_input ['action'] ) {
				case 'create' :
					// print section and slider div
					$new_id = rand( 1, 100000 );

					print "<div id=\"section_$new_id\" class=\"sp_section pure-g\" data-layout='4-4-4'>";
					print "<div class=\"sp_section_controls\">";
					print "<i class=\"fa fa-arrows section_sort\" title=\"Move Section\"></i>
					<i class=\"fa fa-trash-o section_remove\" title=\"Delete Section\"></i>
					</div>";


					print $lobjGuide->dropBoxes( 0, 'left', "" );
					print $lobjGuide->dropBoxes( 1, 'center', "" );
					print $lobjGuide->dropBoxes( 2, 'sidebar', "" );
					print '<div id="clearblock" style="clear:both;"></div> <!-- this just seems to allow the space to grow to fit dropbox areas -->';
					print '</div>';

					break;
				case 'delete' :
					if ( isset ( $_POST ['section_id'] ) ) {
						$db = new Querier ();

						$q = "DELETE p, s
							FROM pluslet p
							INNER JOIN pluslet_section ps
							ON p.pluslet_id = ps.pluslet_id
							INNER JOIN section s
							ON ps.section_id = s.section_id
							WHERE p.type != 'Special'
							AND s.section_id = " . $_POST ['section_id'];

						if ( $db->exec( $q ) === false ) {
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


	public function fetchSectionIdsBySubjectId( $subject_id ) {

		$connection = $this->db->getConnection();

		$statement = $connection->prepare( "SELECT * FROM section
    											INNER JOIN tab on section.tab_id = tab.tab_id
											    INNER JOIN subject on tab.subject_id = subject.subject_id
												WHERE tab.subject_id = :subject_id" );
		$statement->bindParam( ":subject_id", $subject_id );
		$statement->execute();
		$section_ids        = $statement->fetchAll();
		$this->sections_ids = $section_ids;

	}


	public function toArray() {
		return get_object_vars( $this );
	}

	public function toJSON() {
		return json_encode( get_object_vars( $this ) );
	}
}
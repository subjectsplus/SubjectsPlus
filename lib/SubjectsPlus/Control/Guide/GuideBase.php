<?php
/**
 *   @file GuideBase.php
 *   @brief This class allows you to load a guide as an object and save it as an object. It could be used to make the Guide more object-oriented. 
 *
 *   @author little9 (Jamie Little)
 *   @date Auguest 2015
 */
namespace SubjectsPlus\Control\Guide;

use SubjectsPlus\Control\Querier;

class GuideBase {
	private $_subject_id;
	private $_subject;
	private $_staff;
	private $_shortform;
	private $_description;
	private $_keywords;
	private $_redirect_url;
	private $_active;
	private $_type;
	private $_extra;
	private $_message;
	private $_all_tabs;
	private $_departments;
	private $_parents;
	private $_header;
	private $_sections = array ();
	private $_pluslets = array ();
	private $_tabs = array ();
	
	public function __construct($subject_id, $staff, Querier $db) {
		$this->_subject_id = $subject_id;
		$this->_staff = $staff;
		$this->db = $db;
	}
	public function loadGuide() {
		// This method loads a guide from the database
		$connection = $this->db->getConnection ();
		$statement = $connection->prepare ( "SELECT * FROM subject WHERE subject_id = :subject_id" );
		$statement->bindParam ( ":subject_id", $this->_subject_id );
		$statement->execute ();
		$results = $statement->fetchAll ();
		
		// Get Tabs
		$tabs_statement = $connection->prepare ( "SELECT * FROM subject 
                            INNER JOIN tab on tab.subject_id = subject.subject_id 
                            WHERE subject.subject_id = :subject_id" );
		$tabs_statement->bindParam ( ":subject_id", $this->_subject_id );
		$tabs_statement->execute ();
		$this->_tabs = $tabs_statement->fetchAll ();
		
		// Get Sections
		$sections_statement = $connection->prepare ( "SELECT * FROM subject 
                                INNER JOIN tab on tab.subject_id = subject.subject_id 
                                INNER JOIN section on tab.tab_id = section.tab_id
                                WHERE subject.subject_id = :subject_id" );
		$sections_statement->bindParam ( ":subject_id", $this->_subject_id );
		$sections_statement->execute ();
		$this->_sections = $sections_statement->fetchAll ();
		
		// Get Pluslets
		$pluslets_statement = $connection->prepare ( "SELECT * FROM subject 
                                INNER JOIN tab on tab.subject_id = subject.subject_id 
                                INNER JOIN section on tab.tab_id = section.tab_id
                                INNER JOIN pluslet_section on section.section_id = pluslet_section.section_id
                                INNER JOIN pluslet on pluslet_section.pluslet_id = pluslet.pluslet_id
                            WHERE subject.subject_id = :subject_id" );
		$pluslets_statement->bindParam ( ":subject_id", $this->_subject_id );
		$pluslets_statement->execute ();
		$this->_pluslets = $pluslets_statement->fetchAll ();
		
		foreach ( $results as $result ) {
			$this->_subject = $result ['subject'] . " – " .date('Y-m-d H:i:s');
			$this->_shortform = $result ['shortform'] . date('Y-m-dH:i:s');
			$this->_description = $result ['description'];
			$this->_keyswords = $result ['keywords'];
			//$this->_redirect_url = $result ['active'];
			$this->_type = $result ['type'];
			$this->_extra = $result ['extra'];
			$this->_active = $result ['active'];
			$this->_header = $result ['header'];
		}
	}
	public function saveGuide() {
		$connection = $this->db->getConnection ();
		$statement = $connection->prepare("SELECT staff_id FROM staff WHERE email = :email");
		$statement->bindParam(":email", $this->_staff);
		$statement->execute();
	    $staff_id = $statement->fetchAll(); 
		
		$statement = $connection->prepare ( "INSERT INTO subject (`subject`, `active`, `shortform`,`header`, `description`, `keywords`, `type`, `extra`  ) VALUES (:subject, :active, :shortform, :header, :description, :keywords, :type, :extra)" );
		
		$statement->bindParam ( ':subject', $this->_subject );
		$statement->bindParam ( ':active', $this->_active );
		$statement->bindParam ( ':shortform', $this->_shortform );
		//$statement->bindParam ( ':redirect_url', $this->_redirect_url );
		$statement->bindParam ( ':header', $this->_header );
		$statement->bindParam ( ':description', $this->_description );
		$statement->bindParam ( ':keywords', $this->_keywords );
		$statement->bindParam ( ':type', $this->_type );
		$statement->bindParam ( ':extra', $this->_extra );
		
		$statement->execute ();
		$sds = array ();
		$subject_insert_id = $this->db->last_id ();

		$tabs_ids = array ();
		foreach ( $this->_tabs as $subject_guide ) {
			// Insert tabs
			
			$statement = $connection->prepare ( "INSERT INTO tab (`subject_id`, `label`, `tab_index`, `external_url`, `visibility`) VALUES (:subject_id, :label, :tab_index, :external_url, :visibility )" );
			$statement->bindParam ( ":subject_id", $subject_insert_id );
			$statement->bindParam ( ":label", $subject_guide ['label'] );
			$statement->bindParam ( ":tab_index", $subject_guide ['tab_index'] );
			$statement->bindParam ( ":external_url", $subject_guide ['external_url'] );
			$statement->bindParam ( ":visibility", $subject_guide ['visibility'] );
			$statement->execute ();
			
			$tab_insert_id = $this->db->last_id ();
			$tabs_ids [] = array (
					$subject_guide ['tab_id'] => $tab_insert_id 
			);
		}
		
		$index = 0;
		$index_tab = 0;
		foreach ( $this->_sections as $subject_guide ) {
			$index ++;
			$tab_id_key = ( int ) $subject_guide ['tab_id'];


			foreach ( $tabs_ids as $tds ) {

				if (isset ( $tds [$tab_id_key] ) && $tds [$tab_id_key] != NULL) {
										
					$statement = $connection->prepare ( "INSERT INTO section (`tab_id`, `layout`, `section_index`) VALUES (:tab_id, :layout, :section_index)" );
					$statement->bindParam ( ":tab_id", $tds [$tab_id_key] );
					$statement->bindParam ( ":layout", $subject_guide ['layout'] );
					$statement->bindParam ( ":section_index", $subject_guide ['section_index'] );
					$statement->execute ();
					
					$section_insert_id = $this->db->last_id ();
					
					array_shift ( $this->_sections );
					
					$section_id_key = ( int ) $subject_guide ['section_id'];
					$sds [] = array (
							$subject_guide ['section_id'] => $section_insert_id 
					);

					if($index_tab < 1) {
						$index_tab = 1;
						foreach ( $this->_pluslets as $subject_guide ) {

							// Insert pluslets
							$pluslet_statement = $connection->prepare ( "
			    		INSERT INTO pluslet (`title`, `body`, `type`, `extra`, `hide_titlebar`,`collapse_body`, `titlebar_styling`, `favorite_box`)
			    		VALUES (:title, :body, :type, :extra, :hide_titlebar, :collapse_body, :titlebar_styling, :favorite_box) " );

							$pluslet_statement->bindParam ( ':title', $subject_guide ['title'] );
							$pluslet_statement->bindParam ( ':body', $subject_guide ['body'] );
							$pluslet_statement->bindParam ( ':type', $subject_guide ['type'] );
							$pluslet_statement->bindParam ( ':extra', $subject_guide ['extra'] );
							$pluslet_statement->bindParam ( ':hide_titlebar', $subject_guide ['hide_titlebar'] );
							$pluslet_statement->bindParam ( ':collapse_body', $subject_guide ['collapse_body'] );
							$pluslet_statement->bindParam ( ':titlebar_styling', $subject_guide ['titlebar_styling'] );
							$pluslet_statement->bindParam ( ':favorite_box', $subject_guide ['favorite_box'] );
							$pluslet_statement->execute ();

							$pluslet_last_id = $this->db->last_id ();
							foreach ( $sds as $section_id ) {

								if (isset ( $section_id [$section_id_key] ) && $section_id [$section_id_key] != NULL && $subject_guide ['section_id'] == $section_id_key) {

									// Insert pluslet sections
									$statement = $connection->prepare ( "INSERT INTO pluslet_section (`section_id`, `pluslet_id`, `pcolumn`, `prow` ) VALUES (:section_id, :pluslet_id, :pcolumn, :prow) " );
									$statement->bindParam ( ":section_id", $section_id [$section_id_key] );
									$statement->bindParam ( ":pluslet_id", $pluslet_last_id );
									$statement->bindParam ( ":pcolumn", $subject_guide ['pcolumn'] );
									$statement->bindParam ( ":prow", $subject_guide ['prow'] );
									$statement->execute ();
								}
							}
						}


					}

				}
			}
		}
		
	$statement = $connection->prepare("INSERT INTO staff_subject (`staff_id`, `subject_id`) VALUES (:staff_id, :subject_id)");
    $statement->bindParam(":staff_id", $staff_id[0]['staff_id']);
    $statement->bindParam(":subject_id", $subject_insert_id);
    $statement->execute();

		return $subject_insert_id;
	}
	public function toArray() {
		return get_object_vars ( $this );
	}
	public function toJSON() {
		return json_encode ( get_object_vars ( $this ) );
	}
}
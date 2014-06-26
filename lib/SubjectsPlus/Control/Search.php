<?php
namespace SubjectsPlus\Control;

/**
 *   @file Search.php
 *   @brief Searches across the different content types in SubjectPlus (guides, pluslets, etc)
 *
 *   @author little9
 *   @date May Day 2014
 *   @todo
 */

use SubjectsPlus\Control\Querier;

class Search {



  private $_search;

  public function setSearch($_search) {
    $this->_search = $_search;
  }

  public function getSearch() {
    $db = new Querier;
    $quoted_search = $db->quote('%' . $this->_search . '%');
    return $quoted_search;
    
  }

  public function getResults() {

    $sql = "SELECT subject_id AS 'id', shortform AS 'shortform',  subject AS 'matching_text', description as 'additional_text', '' as 'tab_index', '' AS 'parent_id', 'Subject Guide' as 'content_type' FROM subject 
WHERE description LIKE" . $this->getSearch() . "
OR subject LIKE " . $this->getSearch() . "
OR keywords LIKE " . $this->getSearch() . "
OR shortform LIKE " . $this->getSearch() . "
OR type LIKE " . $this->getSearch() . "
UNION 
SELECT p.pluslet_id AS 'id', su.shortform as 'shortform', p.title AS 'matching_text',p.body AS 'additional_text', t.tab_index AS 'additional_text', su.subject_id AS 'parent_id', 'Pluslet' AS 'content_type' FROM pluslet AS p 
	INNER JOIN pluslet_section AS ps 
	ON ps.pluslet_id = p.pluslet_id
	INNER JOIN section AS s 
	ON ps.section_id = s.section_id
	INNER JOIN tab AS t
	ON s.tab_id = t.tab_id
	INNER JOIN subject AS su 
	ON su.subject_id = t.subject_id
WHERE p.body LIKE "  . $this->getSearch() . "
OR p.title LIKE "  . $this->getSearch() ."
UNION
SELECT title_id AS 'id', '' as 'shortform', title AS 'matching_text' , description as 'additional_text','' as 'tab_index',  '' AS 'parent_id', 'Record' as 'content_type' FROM title 
WHERE title LIKE " . $this->getSearch() . "
OR description LIKE " . $this->getSearch() . "
UNION
SELECT faq_id AS 'id', '' as 'shortform' ,  question AS 'matching_text', answer as 'additional_text','' as 'tab_index', '' AS 'parent_id', 'FAQ' as 'content_type' FROM faq 
WHERE question LIKE " . $this->getSearch() . "
OR answer LIKE " . $this->getSearch() . "
OR keywords LIKE " . $this->getSearch() . "
UNION
SELECT talkback_id AS 'id', '' as 'shortform',  question AS 'matching_text' , answer as 'additional_text','' as 'tab_index',  '' AS 'parent_id', 'Talkback' as 'content_type' FROM talkback 
WHERE question LIKE " . $this->getSearch() . "
OR answer LIKE " . $this->getSearch() . "
UNION
SELECT staff_id AS 'id',  '' as 'shortform', email AS 'matching_text' , fname as 'additional_text','' as 'tab_index', '' AS 'parent_id', 'Staff' as 'content_type' FROM staff 
WHERE fname LIKE " . $this->getSearch() . "
OR lname LIKE " . $this->getSearch() . "
OR email LIKE " . $this->getSearch() . "
OR tel LIKE " . $this->getSearch() . "
UNION
SELECT department_id AS 'id', '' as 'shortform', name AS 'matching_text' , telephone as 'additional_text','' as 'tab_index',  '' AS 'parent_id', 'Department' as 'content_type' FROM department 
WHERE name LIKE " . $this->getSearch() . "
OR telephone LIKE " . $this->getSearch() . "
UNION
SELECT video_id AS 'id',  '' as 'shortform', title AS 'matching_text' , description as 'additional_text','' as 'tab_index', '' AS 'parent_id', 'Video' as 'content_type' FROM video 
WHERE title LIKE " . $this->getSearch() . "
OR description LIKE " . $this->getSearch() . "
OR vtags LIKE " . $this->getSearch();

$db = new Querier;
$results = $db->query($sql);

return $results;

}




}




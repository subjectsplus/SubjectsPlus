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

    $sql = "SELECT subject_id AS 'id', subject AS 'matching_text', 'subject' as 'content_type' FROM subject 
WHERE description LIKE" . $this->getSearch() ."
OR subject LIKE" . $this->getSearch() ."
UNION 
SELECT pluslet_id AS 'id', title AS 'matching_text', 'pluslet' AS 'content_type' FROM pluslet 
WHERE title LIKE " . $this->getSearch() ."
OR body LIKE " . $this->getSearch() ."
UNION
SELECT faq_id AS 'id', question AS 'matching_text','faq' as 'content_type' FROM faq 
WHERE question LIKE " . $this->getSearch() ."
OR answer LIKE " . $this->getSearch() ."
UNION
SELECT talkback_id AS 'id', question AS 'matching_text' , 'talkback' as 'content_type' FROM talkback 
WHERE question LIKE " . $this->getSearch() ."
OR answer LIKE " . $this->getSearch() ;

$db = new Querier;
$results = $db->query($sql);

return $results;

}




}




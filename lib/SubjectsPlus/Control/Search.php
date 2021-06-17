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
  private $patterned_search;
  private $db;
  private $connection;

  /**
   * Construction of the Search object.
   * Initializes and stores Database Querier and Connection.
   */
  public function __construct() {
    $this->db = new Querier;
    $this->connection = $this->db->getConnection();
  }

  /**
   * Sets the search term to find in the database.
   *
   * @param string $_search
   * @return void
   */
  public function setSearch($_search) {
    $this->_search = $_search;
    $this->patterned_search = ('%' . $_search . '%');
  }

  /**
   * Generates a search for records in the database.
   *
   * @param string $order Sort order. Default is "ASC" for ascending, otherwise "DESC" for descending.
   * @return array $results Results of record search in array form.
   */
  public function getRecordSearch($order="ASC") {
    // Order can only be in ascending or descending order
    if ($order != "ASC")
      $order = "DESC";

    // Query for Records search results
    // Query will prioritize the best match in the title
    $query = "SELECT title_id AS 'id', '' AS 'shortform', title AS 'matching_text', 
    description AS 'additional_text', '' AS 'tab_index', '' AS 'parent_id', 'Record' AS 'content_type', LOCATE(:search, t.title)
    FROM title AS t
    WHERE 
      t.title LIKE :patterned
    OR 
      t.description LIKE :patterned
    ORDER BY
      CASE 
        WHEN LOCATE(:search, t.title) > 0 THEN 0
        ELSE 1
      END ASC,
      LOCATE(:search, t.title) {$order}";

    $statement = $this->connection->prepare($query);
    $statement->bindParam(":search", $this->_search);
    $statement->bindParam(":patterned", $this->patterned_search);
    $statement->execute();
    $results = $statement->fetchAll();
    
    return $results;
  }

  /**
   * Generates a search for subject guides in the database.
   *
   * @param string $order Sort order. Default is "ASC" for ascending, otherwise "DESC" for descending.
   * @return array $results Results of subject guides search in array form.
   */
  public function getSubjectGuideSearch($order="ASC") {
    // Order can only be in ascending or descending order
    if ($order != "ASC")
      $order = "DESC";

    // Query for Subject Guide search results
    // Query will prioritize best match for the subject
    $query = "SELECT subject_id AS 'id', shortform AS 'shortform',  subject AS 'matching_text', 
    description AS 'additional_text', '' AS 'tab_index', '' AS 'parent_id', 'Subject Guide' AS 'content_type',
    LOCATE(:search, s.subject)
    FROM subject AS s
    WHERE s.description LIKE :patterned
    OR s.subject LIKE :patterned
    OR s.keywords LIKE :patterned
    OR s.shortform LIKE :patterned
    OR s.type LIKE :patterned
    ORDER BY 
      CASE
        WHEN LOCATE(:search, s.subject) > 0 THEN 0
        ELSE 1
      END ASC,
      LOCATE(:search, s.subject) {$order}";
    
    $statement = $this->connection->prepare($query);
    $statement->bindParam(":search", $this->_search);
    $statement->bindParam(":patterned", $this->patterned_search);
    $statement->execute();
    $results = $statement->fetchAll();
    
    return $results;
  }
  
  /**
   * Generates a search for pluslets in the database.
   *
   * @param string $order Sort order. Default is "ASC" for ascending, otherwise "DESC" for descending.
   * @return array $results Results of pluslet search in array form.
   */
  public function getPlusletSearch($order="ASC") {
    // Order can only be in ascending or descending order
    if ($order != "ASC")
      $order = "DESC";

    // Query for Pluslet search results
    // Query will prioritize best match for the title
    $query = "SELECT p.pluslet_id AS 'id', su.shortform AS 'shortform', p.title AS 'matching_text',p.body AS 'additional_text', 
    t.tab_index AS 'tab_index', su.subject_id AS 'parent_id', 'Pluslet' AS 'content_type', LOCATE(:search, p.title)
    FROM pluslet AS p 
    INNER JOIN pluslet_section AS ps 
    ON ps.pluslet_id = p.pluslet_id
    INNER JOIN section AS s 
    ON ps.section_id = s.section_id
    INNER JOIN tab AS t
    ON s.tab_id = t.tab_id
    INNER JOIN subject AS su 
    ON su.subject_id = t.subject_id
    WHERE p.body LIKE :patterned
    OR p.title LIKE :patterned
    ORDER BY
      CASE
        WHEN LOCATE(:search, p.title) > 0 THEN 0
          ELSE 1
      END ASC,
      LOCATE(:search, p.title) {$order}";
    
    $statement = $this->connection->prepare($query);
    $statement->bindParam(":search", $this->_search);
    $statement->bindParam(":patterned", $this->patterned_search);
    $statement->execute();
    $results = $statement->fetchAll();
    
    return $results;
  }

  /**
   * Generates a search for faq in the database.
   *
   * @param string $order Sort order. Default is "ASC" for ascending, otherwise "DESC" for descending.
   * @return array $results Results of faq search in array form.
   */
  public function getFAQSearch($order="ASC") {
    // Order can only be in ascending or descending order
    if ($order != "ASC")
      $order = "DESC";

    // Query for FAQ search results
    // Query will prioritize best match for the question
    $query = "SELECT faq_id AS 'id', '' AS 'shortform', question AS 'matching_text', answer AS 'additional_text', '' AS 'tab_index', 
    '' AS 'parent_id', 'FAQ' AS 'content_type', LOCATE(:search, question)
    FROM faq 
    WHERE question LIKE :patterned
    OR answer LIKE :patterned
    OR keywords LIKE :patterned
    ORDER BY
      CASE
          WHEN LOCATE(:search, question) > 0 THEN 0
            ELSE 1
        END ASC,
      LOCATE(:search, question) {$order}";
    
    $statement = $this->connection->prepare($query);
    $statement->bindParam(":search", $this->_search);
    $statement->bindParam(":patterned", $this->patterned_search);
    $statement->execute();
    $results = $statement->fetchAll();
    
    return $results;
  }
  
  /**
   * Generates a search for talkback in the database.
   *
   * @param string $order Sort order. Default is "ASC" for ascending, otherwise "DESC" for descending.
   * @return array $results Results of talkback search in array form.
   */
  public function getTalkbackSearch($order="ASC") {
    // Order can only be in ascending or descending order
    if ($order != "ASC")
      $order = "DESC";

    // Query for Talkback search results
    // Query will prioritize best match for the question
    $query = "SELECT talkback_id AS 'id', '' AS 'shortform',  question AS 'matching_text' , answer AS 'additional_text', 
    '' AS 'tab_index',  '' AS 'parent_id', 'Talkback' AS 'content_type', LOCATE(:search, question)
    FROM talkback 
    WHERE question LIKE :patterned
    OR answer LIKE :patterned
    ORDER BY
      CASE
        WHEN LOCATE(:search, question) > 0 THEN 0
          ELSE 1
        END ASC,
      LOCATE(:search, question) {$order}";
    
    $statement = $this->connection->prepare($query);
    $statement->bindParam(":search", $this->_search);
    $statement->bindParam(":patterned", $this->patterned_search);
    $statement->execute();
    $results = $statement->fetchAll();
    
    return $results;
  }

  /**
   * Generates a search for departments in the database.
   *
   * @param string $order Sort order. Default is "ASC" for ascending, otherwise "DESC" for descending.
   * @return array $results Results of department search in array form.
   */
  public function getDepartmentSearch($order="ASC") {
    // Order can only be in ascending or descending order
    if ($order != "ASC")
      $order = "DESC";

    // Query for Department search results
    // Query will prioritize best match for the name
    $query = "SELECT department_id AS 'id', '' AS 'shortform', name AS 'matching_text' , telephone AS 'additional_text',
    '' AS 'tab_index',  '' AS 'parent_id', 'Department' AS 'content_type', LOCATE(:search, name)
    FROM department 
    WHERE name LIKE :patterned
    OR telephone LIKE :patterned
    ORDER BY
      CASE
          WHEN LOCATE(:search, name) > 0 THEN 0
            ELSE 1
          END ASC,
      LOCATE(:search, name) {$order}";
    
    $statement = $this->connection->prepare($query);
    $statement->bindParam(":search", $this->_search);
    $statement->bindParam(":patterned", $this->patterned_search);
    $statement->execute();
    $results = $statement->fetchAll();
    
    return $results;
  }

  /**
   * Generates a search for staff in the database.
   *
   * @param string $order Sort order. Default is "ASC" for ascending, otherwise "DESC" for descending.
   * @return array $results Results of staff search in array form.
   */
  public function getStaffSearch($order="ASC") {
    // Order can only be in ascending or descending order
    if ($order != "ASC")
      $order = "DESC";

    // Query for Staff search results
    // Query will prioritize best match for the first name and last name
    $query = "SELECT staff_id AS 'id', '' AS 'shortform', email AS 'matching_text' , fname AS 'additional_text', '' AS 'tab_index', '' AS 'parent_id', 'Staff' AS 'content_type' 
    FROM staff
    WHERE CONCAT(fname, lname) LIKE REPLACE(:patterned, ' ', '')
    OR CONCAT(lname, fname) LIKE REPLACE(:patterned, ' ', '')
    OR email LIKE :patterned
    OR tel LIKE :patterned
    ORDER BY 
    CASE
      WHEN LOCATE(:search, fname) > 0 THEN 0
      ELSE 1
    END ASC,
    CASE
      WHEN LOCATE(:search, lname) > 0 THEN 0
      ELSE 1
    END ASC,
      LOCATE(:search, fname) {$order},
      LOCATE(:search, lname) {$order}";
    
    $statement = $this->connection->prepare($query);
    $statement->bindParam(":search", $this->patterned_search);
    $statement->bindParam(":patterned", $this->patterned_search);
    $statement->execute();
    $results = $statement->fetchAll();
    
    return $results;
  }

  /**
   * Generates a search for videos in the database.
   *
   * @param string $order Sort order. Default is "ASC" for ascending, otherwise "DESC" for descending.
   * @return array $results Results of video search in array form.
   */
  public function getVideoSearch($order="ASC") {
    // Order can only be in ascending or descending order
    if ($order != "ASC")
      $order = "DESC";

    // Query for Staff search results
    $query = "SELECT video_id AS 'id',  '' AS 'shortform', title AS 'matching_text' , description AS 'additional_text',
    '' AS 'tab_index', '' AS 'parent_id', 'Video' AS 'content_type', LOCATE(:search, title)
    FROM video 
    WHERE title LIKE :patterned
    OR description LIKE :patterned
    OR vtags LIKE :patterned
    ORDER BY 
      CASE
        WHEN LOCATE(:search, title) > 0 THEN 0
        ELSE 1
      END ASC,
      LOCATE(:search, title) {$order}";
    
    $statement = $this->connection->prepare($query);
    $statement->bindParam(":search", $this->_search);
    $statement->bindParam(":patterned", $this->patterned_search);

    $statement->execute();
    $results = $statement->fetchAll();
    
    return $results;
  }

  /**
   * Generates a search for records, subject guides, pluslets, faq, talkback, departments, staff, and videos in the database.
   *
   * @param string $order Sort order. Default is "ASC" for ascending, otherwise "DESC" for descending.
   * @return array $results Results of records, subject guides, pluslets, faq, talkback, departments, staff, and videos in array form.
   */
  public function getResults($order="ASC") {
    if ($order != "ASC")
      $order = "DESC";

    $results = array(); // Compilation of all search results

    // Individual search results from different categories
    $record_results = $this->getRecordSearch($order);
    $subject_results = $this->getSubjectGuideSearch($order);
    $pluslet_results = $this->getPlusletSearch($order);
    $faq_results = $this->getFAQSearch($order);
    $talkback_results = $this->getTalkbackSearch($order);
    $dept_results = $this->getDepartmentSearch($order);
    $staff_results = $this->getStaffSearch($order);
    $video_results = $this->getVideoSearch($order);

    // Merge the different categorized search results
    $results = array_merge($results, $record_results);
    $results = array_merge($results, $subject_results);
    $results = array_merge($results, $pluslet_results);
    $results = array_merge($results, $faq_results);
    $results = array_merge($results, $talkback_results);
    $results = array_merge($results, $dept_results);
    $results = array_merge($results, $staff_results);
    $results = array_merge($results, $video_results);

    return $results;
  }
}




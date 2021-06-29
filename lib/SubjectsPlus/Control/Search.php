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
   * @param string $sortby Sort order by "relevance", "alphabetical_ascending", or "alphabetical_descending". 
   *                       Default is "relevance".
   * @param bool $atoz Determines whether the AtoZ list is used for records.
   *                   Default is true.
   * @return array $results Results of record search in array form.
   */
  public function getRecordSearch($sortby="relevance", $atoz=true) {
    // Set the order and query
    $order = NULL;
    $query = NULL;
    
    // conditional for eres_display in query
    // when atoz is true, the query will only display those in the AtoZ list
    $eres_display_query = ($atoz ? "AND location.eres_display = 'Y'" : "");

    switch($sortby) {
      case "alphabetical_ascending":
        // Query will order by title alphabetically from A to Z
        $order = "ASC";
        $query = "SELECT t.title_id AS 'id', '' AS 'shortform', t.title AS 'matching_text', 
        t.description AS 'additional_text', '' AS 'tab_index', '' AS 'parent_id', 'Record' AS 'content_type'
        FROM title AS t, location, location_title
        WHERE
          t.title_id = location_title.title_id
        AND
          location.location_id = location_title.location_id 
        {$eres_display_query} 
        AND 
          (t.title LIKE :patterned OR t.description LIKE :patterned)
        ORDER BY LOWER(t.title) {$order}";
        break;
      
      case "alphabetical_descending":
        // Query will order by title alphabetically from Z to A
        $order = "DESC";
        $query = "SELECT t.title_id AS 'id', '' AS 'shortform', t.title AS 'matching_text', 
        t.description AS 'additional_text', '' AS 'tab_index', '' AS 'parent_id', 'Record' AS 'content_type'
        FROM title AS t, location, location_title
        WHERE
          t.title_id = location_title.title_id
        AND
          location.location_id = location_title.location_id 
        {$eres_display_query} 
        AND
          (t.title LIKE :patterned OR t.description LIKE :patterned)
        ORDER BY LOWER(t.title) {$order}";
        break;

      case "relevance":
      default:
        // Query will prioritize best match/most relevant for the title
        $order = "ASC";
        $query = "SELECT t.title_id AS 'id', '' AS 'shortform', t.title AS 'matching_text', 
        t.description AS 'additional_text', '' AS 'tab_index', '' AS 'parent_id', 'Record' AS 'content_type', LOCATE(:search, t.title)
        FROM title AS t, location, location_title
        WHERE
          t.title_id = location_title.title_id
        AND
          location.location_id = location_title.location_id 
        {$eres_display_query} 
        AND
          (t.title LIKE :patterned OR t.description LIKE :patterned)
        ORDER BY
          CASE 
            WHEN LOCATE(:search, t.title) > 0 THEN 0
            ELSE 1
          END ASC,
          LOCATE(:search, t.title) {$order}";
        break;
    }

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
   * @param string $sortby Sort order by "relevance", "alphabetical_ascending", or "alphabetical_descending". 
   *                       Default is "relevance".
   * @param bool $active Determines whether to only show guides marked as active.
   * @return array $results Results of subject guides search in array form.
   */
  public function getSubjectGuideSearch($sortby="relevance", $active=true) {
    // Set the order and query
    $order = NULL;
    $query = NULL;
    
    // conditional for active in query
    // when active is true, the query will only display guides that are marked active
    $active_query = ($active ? "s.active = 1 AND" : "");

    switch($sortby) {
      case "alphabetical_ascending":
        // Query will order by subject alphabetically from A to Z
        $order = "ASC";
        $query = "SELECT subject_id AS 'id', shortform AS 'shortform',  subject AS 'matching_text', 
        description AS 'additional_text', '' AS 'tab_index', '' AS 'parent_id', 'Subject Guide' AS 'content_type'
        FROM subject AS s
        WHERE {$active_query} (s.description LIKE :patterned
        OR s.subject LIKE :patterned
        OR s.keywords LIKE :patterned
        OR s.shortform LIKE :patterned
        OR s.type LIKE :patterned)
        ORDER BY LOWER(s.subject) {$order}";
        break;
      
      case "alphabetical_descending":
        // Query will order by subject alphabetically from Z to A
        $order = "DESC";
        $query = "SELECT subject_id AS 'id', shortform AS 'shortform',  subject AS 'matching_text', 
        description AS 'additional_text', '' AS 'tab_index', '' AS 'parent_id', 'Subject Guide' AS 'content_type'
        FROM subject AS s
        WHERE {$active_query} (s.description LIKE :patterned
        OR s.subject LIKE :patterned
        OR s.keywords LIKE :patterned
        OR s.shortform LIKE :patterned
        OR s.type LIKE :patterned)
        ORDER BY LOWER(s.subject) {$order}";
        break;

      case "relevance":
      default:
        // Query will prioritize best match/most relevant for the subject
        $order = "ASC";
        $query = "SELECT subject_id AS 'id', shortform AS 'shortform',  subject AS 'matching_text', 
        description AS 'additional_text', '' AS 'tab_index', '' AS 'parent_id', 'Subject Guide' AS 'content_type',
        LOCATE(:search, s.subject)
        FROM subject AS s
        WHERE {$active_query} (s.description LIKE :patterned
        OR s.subject LIKE :patterned
        OR s.keywords LIKE :patterned
        OR s.shortform LIKE :patterned
        OR s.type LIKE :patterned)
        ORDER BY 
          CASE
            WHEN LOCATE(:search, s.subject) > 0 THEN 0
            ELSE 1
          END ASC,
          LOCATE(:search, s.subject) {$order}";
        break;
    }
    
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
  public function getPlusletSearch($sortby="relevance") {
    // Set the order and query
    $order = NULL;
    $query = NULL;
    
    switch($sortby) {
      case "alphabetical_ascending":
        // Query will order by pluslet title alphabetically from A to Z
        $order = "ASC";
        $query = "SELECT p.pluslet_id AS 'id', su.shortform AS 'shortform', p.title AS 'matching_text',p.body AS 'additional_text', 
        t.tab_index AS 'tab_index', su.subject_id AS 'parent_id', 'Pluslet' AS 'content_type'
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
        ORDER BY LOWER(p.title) {$order}";
        break;
      
      case "alphabetical_descending":
        // Query will order by pluslet title alphabetically from Z to A
        $order = "DESC";
        $query = "SELECT p.pluslet_id AS 'id', su.shortform AS 'shortform', p.title AS 'matching_text',p.body AS 'additional_text', 
        t.tab_index AS 'tab_index', su.subject_id AS 'parent_id', 'Pluslet' AS 'content_type'
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
        ORDER BY LOWER(p.title) {$order}";
        break;

      case "relevance":
      default:
        // Query will prioritize best match/most relevant for the pluslet title
        $order = "ASC";
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
        break;
    }
    
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
   * @param string $sortby Sort order by "relevance", "alphabetical_ascending", or "alphabetical_descending". 
   *                       Default is "relevance".
   * @return array $results Results of faq search in array form.
   */
  public function getFAQSearch($sortby="relevance") {
    // Set the order and query
    $order = NULL;
    $query = NULL;
    
    switch($sortby) {
      case "alphabetical_ascending":
        // Query will order by question alphabetically from A to Z
        $order = "ASC";
        $query = "SELECT faq_id AS 'id', '' AS 'shortform', question AS 'matching_text', answer AS 'additional_text', '' AS 'tab_index', 
        '' AS 'parent_id', 'FAQ' AS 'content_type'
        FROM faq 
        WHERE question LIKE :patterned
        OR answer LIKE :patterned
        OR keywords LIKE :patterned
        ORDER BY LOWER(question) {$order}";
        break;
      
      case "alphabetical_descending":
        // Query will order by question alphabetically from Z to A
        $order = "DESC";
        $query = "SELECT faq_id AS 'id', '' AS 'shortform', question AS 'matching_text', answer AS 'additional_text', '' AS 'tab_index', 
        '' AS 'parent_id', 'FAQ' AS 'content_type'
        FROM faq 
        WHERE question LIKE :patterned
        OR answer LIKE :patterned
        OR keywords LIKE :patterned
        ORDER BY LOWER(question) {$order}";
        break;

      case "relevance":
      default:
        // Query will prioritize best match/most relevant for the question
        $order = "ASC";
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
        break;
    }
    
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
   * @param string $sortby Sort order by "relevance", "alphabetical_ascending", or "alphabetical_descending". 
   *                       Default is "relevance".
   * @return array $results Results of talkback search in array form.
   */
  public function getTalkbackSearch($sortby="relevance") {
    // Set the order and query
    $order = NULL;
    $query = NULL;
    
    switch($sortby) {
      case "alphabetical_ascending":
        // Query will order by talkback question alphabetically from A to Z
        $order = "ASC";
        $query = "SELECT talkback_id AS 'id', '' AS 'shortform',  question AS 'matching_text' , answer AS 'additional_text', 
        '' AS 'tab_index',  '' AS 'parent_id', 'Talkback' AS 'content_type'
        FROM talkback 
        WHERE question LIKE :patterned
        OR answer LIKE :patterned
        ORDER BY LOWER(question) {$order}";
        break;
      
      case "alphabetical_descending":
        // Query will order by talkback question alphabetically from Z to A
        $order = "DESC";
        $query = "SELECT talkback_id AS 'id', '' AS 'shortform',  question AS 'matching_text' , answer AS 'additional_text', 
        '' AS 'tab_index',  '' AS 'parent_id', 'Talkback' AS 'content_type'
        FROM talkback 
        WHERE question LIKE :patterned
        OR answer LIKE :patterned
        ORDER BY LOWER(question) {$order}";
        break;

      case "relevance":
      default:
        // Query will prioritize best match/most relevant for the talkback question
        $order = "ASC";
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
        break;
    }
    
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
   * @param string $sortby Sort order by "relevance", "alphabetical_ascending", or "alphabetical_descending". 
   *                       Default is "relevance".
   * @return array $results Results of department search in array form.
   */
  public function getDepartmentSearch($sortby="relevance") {
    // Set the order and query
    $order = NULL;
    $query = NULL;
    
    switch($sortby) {
      case "alphabetical_ascending":
        // Query will order by department name alphabetically from A to Z
        $order = "ASC";
        $query = "SELECT department_id AS 'id', '' AS 'shortform', name AS 'matching_text' , telephone AS 'additional_text',
        '' AS 'tab_index',  '' AS 'parent_id', 'Department' AS 'content_type'
        FROM department 
        WHERE name LIKE :patterned
        OR telephone LIKE :patterned
        ORDER BY LOWER(name) {$order}";
        break;
      
      case "alphabetical_descending":
        // Query will order by department name alphabetically from Z to A
        $order = "DESC";
        $query = "SELECT department_id AS 'id', '' AS 'shortform', name AS 'matching_text' , telephone AS 'additional_text',
        '' AS 'tab_index',  '' AS 'parent_id', 'Department' AS 'content_type'
        FROM department 
        WHERE name LIKE :patterned
        OR telephone LIKE :patterned
        ORDER BY LOWER(name) {$order}";
        break;

      case "relevance":
      default:
        // Query will prioritize best match/most relevant for the department name
        $order = "ASC";
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
        break;
    }
    
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
   * @param string $sortby Sort order by "relevance", "alphabetical_ascending", or "alphabetical_descending". 
   *                       Default is "relevance".
   * @param bool $active Determines whether to only show staff marked as active.
   * @return array $results Results of staff search in array form.
   */
  public function getStaffSearch($sortby="relevance", $active=true) {
    // Set the order and query
    $order = NULL;
    $query = NULL;
    
    // conditional for active in query
    // when active is true, the query will only display staff that are marked active
    $active_query = ($active ? "active = 1 AND" : "");

    switch($sortby) {
      case "alphabetical_ascending":
        // Query will order by first and last name alphabetically from A to Z
        $order = "ASC";
        $query = "SELECT staff_id AS 'id', '' AS 'shortform', email AS 'matching_text' , fname AS 'additional_text', '' AS 'tab_index', '' AS 'parent_id', 'Staff' AS 'content_type' 
        FROM staff
        WHERE {$active_query} (CONCAT(fname, lname) LIKE REPLACE(:patterned, ' ', '')
        OR CONCAT(lname, fname) LIKE REPLACE(:patterned, ' ', '')
        OR email LIKE :patterned
        OR tel LIKE :patterned)
        ORDER BY LOWER(fname) {$order}, LOWER(lname) {$order}";
        break;
      
      case "alphabetical_descending":
        // Query will order by first and last name alphabetically from Z to A
        $order = "DESC";
        $query = "SELECT staff_id AS 'id', '' AS 'shortform', email AS 'matching_text' , fname AS 'additional_text', '' AS 'tab_index', '' AS 'parent_id', 'Staff' AS 'content_type' 
        FROM staff
        WHERE {$active_query} (CONCAT(fname, lname) LIKE REPLACE(:patterned, ' ', '')
        OR CONCAT(lname, fname) LIKE REPLACE(:patterned, ' ', '')
        OR email LIKE :patterned
        OR tel LIKE :patterned)
        ORDER BY LOWER(fname) {$order}, LOWER(lname) {$order}";
        break;

      case "relevance":
      default:
        // Query will prioritize best match/most relevant for the first name and last name
        $order = "ASC";
        $query = "SELECT staff_id AS 'id', '' AS 'shortform', email AS 'matching_text' , fname AS 'additional_text', '' AS 'tab_index', '' AS 'parent_id', 'Staff' AS 'content_type' 
        FROM staff
        WHERE {$active_query} (CONCAT(fname, lname) LIKE REPLACE(:patterned, ' ', '')
        OR CONCAT(lname, fname) LIKE REPLACE(:patterned, ' ', '')
        OR email LIKE :patterned
        OR tel LIKE :patterned)
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
        break;
    }
    
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
   * @param string $sortby Sort order by "relevance", "alphabetical_ascending", or "alphabetical_descending". 
   *                       Default is "relevance".
   * @return array $results Results of video search in array form.
   */
  public function getVideoSearch($sortby="relevance") {
    // Set the order and query
    $order = NULL;
    $query = NULL;
    
    switch($sortby) {
      case "alphabetical_ascending":
        // Query will order by title alphabetically from A to Z
        $order = "ASC";
        $query = "SELECT video_id AS 'id',  '' AS 'shortform', title AS 'matching_text' , description AS 'additional_text',
        '' AS 'tab_index', '' AS 'parent_id', 'Video' AS 'content_type'
        FROM video 
        WHERE title LIKE :patterned
        OR description LIKE :patterned
        OR vtags LIKE :patterned
        ORDER BY LOWER(title) {$order}";
        break;
      
      case "alphabetical_descending":
        // Query will order by title alphabetically from Z to A
        $order = "DESC";
        $query = "SELECT video_id AS 'id',  '' AS 'shortform', title AS 'matching_text' , description AS 'additional_text',
        '' AS 'tab_index', '' AS 'parent_id', 'Video' AS 'content_type'
        FROM video 
        WHERE title LIKE :patterned
        OR description LIKE :patterned
        OR vtags LIKE :patterned
        ORDER BY LOWER(title) {$order}";
        break;

      case "relevance":
      default:
        // Query will prioritize best match/most relevant for the title
        $order = "ASC";
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
        break;
    }
    
    $statement = $this->connection->prepare($query);
    $statement->bindParam(":search", $this->_search);
    $statement->bindParam(":patterned", $this->patterned_search);

    $statement->execute();
    $results = $statement->fetchAll();
    
    return $results;
  }

  /**
   * Generates a search for records, subject guides, pluslets, faq, talkback, departments, staff, 
   * and videos in the database.
   *
   * @param string $sortby Sort order by "relevance", "alphabetical_ascending", or "alphabetical_descending". 
   *                       Default is "relevance".
   * @param bool $display_only_public Determines whether to display only public results.
   *                                  Default is true.
   * 
   * @return array $results Results of records, subject guides, pluslets, faq, talkback, departments, staff, 
   *                        and videos in array form.
   */
  public function getResults($sortby="relevance", $display_only_public=true) {
    
    switch($sortby) {
      case "relevance":
        break;
      case "alphabetical_ascending":
        break;
      case "alphabetical_descending":
        break;
      default:
        $sortby = "relevance";
        break;
    }

    $results = array(); // Compilation of all search results

    // Individual search results from different categories
    $record_results = $this->getRecordSearch($sortby, $display_only_public);
    $subject_results = $this->getSubjectGuideSearch($sortby, $display_only_public);
    $pluslet_results = $this->getPlusletSearch($sortby);
    $faq_results = $this->getFAQSearch($sortby);
    $talkback_results = $this->getTalkbackSearch($sortby);
    $dept_results = $this->getDepartmentSearch($sortby);
    $staff_results = $this->getStaffSearch($sortby, $display_only_public);
    $video_results = $this->getVideoSearch($sortby);

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




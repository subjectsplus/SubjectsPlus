<?php
/**
 *   @file Autocomplete.php
 *   @brief
 *
 *   @author Jamie Little (little9)
 *   @date may 2014
 *   @todo
 */

namespace SubjectsPlus\Control;

use SubjectsPlus\Control\Querier;

include('../../control/includes/functions.php');

class Autocomplete {
  private $param;
  private $collection;
  private $json;
  private $subject_id;
  private $search_page;

  public function setSubjectId($subject_id) {
    $this->subject_id = $subject_id;
  }

  public function getSubjectId() {
    return $this->subject_id;

  }

  public function setSearchPage($search_page) {
    $this->search_page = $search_page;
  }

  public function getSearchPage() {
    return $this->search_page;

  }

  public function setParam($param) {
    $this->param = $param;
  }

  public function getParam() {
    return $this->param;

  }

  public function setCollection($collection) {
    $this->collection = $collection;
  }

  public function getCollection() {
    return $this->collection;
  }

  public function setJSON($json) {
    $this->$json = $json;
  }

  public function getJSON() {
    return $this->json;

  }

  public function search() {

    $db = new Querier;
    $search_param = $db->quote("%" . $this->param . "%");
    $subject_id = $db->quote( $this->subject_id );

    switch ($this->collection) {
      case "home":
        $q = "SELECT subject_id AS 'id', subject AS 'matching_text',subject AS 'label', description as 'additional_text', shortform AS 'short_form', 'Subject Guide' as 'content_type', '' as 'additional_id', '' as 'parent' FROM subject
                    WHERE description LIKE "  . $search_param . "
                    OR subject LIKE "  . $search_param . "
                    OR keywords LIKE "  . $search_param . "
                    UNION
                    SELECT p.pluslet_id, p.title,p.title AS 'label', su.subject_id AS 'parent_id', su.shortform, 'Pluslet' AS 'content_type', t.tab_index as 'additional_id',su.subject as 'parent' FROM pluslet AS p
                    INNER JOIN pluslet_section AS ps
                    ON ps.pluslet_id = p.pluslet_id
                    INNER JOIN section AS s
                    ON ps.section_id = s.section_id
                    INNER JOIN tab AS t
                    ON s.tab_id = t.tab_id
                    INNER JOIN subject AS su
                    ON su.subject_id = t.subject_id
                    WHERE p.body LIKE "  . $search_param . "
                    OR p.title LIKE "  . $search_param . "

                    UNION
                    SELECT faq_id AS 'id', question AS 'matching_text',question AS 'label',  answer as 'additional_text','' AS 'short_form','FAQ' as 'content_type', '' as 'additional_id', '' as 'parent' FROM faq
                    WHERE question LIKE "  . $search_param . "
                    OR answer LIKE "  . $search_param . "
                    OR keywords LIKE "  . $search_param . "
                    UNION
                    SELECT talkback_id AS 'id', question AS 'matching_text' ,question AS 'label', answer as 'additional_text','' AS 'short_form', 'Talkback' as 'content_type', '' as 'additional_id', '' as 'parent' FROM talkback
                    WHERE question LIKE "  . $search_param . "
                    OR answer LIKE "  . $search_param . "
                    UNION
                    SELECT staff_id AS 'id', email AS 'matching_text' ,email AS 'label', fname as 'additional_text','' AS 'short_form', 'Staff' as 'content_type', '' as 'additional_id', '' as 'parent' FROM staff
                    WHERE fname LIKE " .$search_param  . "
                    OR lname LIKE "  . $search_param . "
                    OR email LIKE " . $search_param . "
                    OR tel LIKE " . $search_param . "
                    UNION
                    SELECT department_id AS 'id', name AS 'matching_text' , name AS 'label', telephone as 'additional_text','' AS 'short_form', 'Department' as 'content_type', '' as 'additional_id','' as 'parent' FROM department
                    WHERE name LIKE " . $search_param ."
                    OR telephone LIKE  " . $search_param . "
                    UNION
                    SELECT video_id AS 'id', title AS 'matching_text' ,title AS 'label', description as 'additional_text','' AS 'short_form', 'Video' as 'content_type', '' as 'additional_id', '' as 'parent' FROM video
                    WHERE title LIKE " .  $search_param . "
                    OR description LIKE " . $search_param . "
                    OR vtags LIKE " .  $search_param;



        break;
      case "guides":
        $q = "SELECT subject_id as 'id', subject,'Subject Guide' as 'content_type', subject AS 'label',shortform AS 'short_form' FROM subject WHERE subject LIKE " . $search_param
           . "OR shortform LIKE " . $search_param
           . "OR description LIKE " . $search_param
           . "OR keywords LIKE " . $search_param
           . "OR type LIKE " . $search_param ;
        break;


      case "guide":
        $q = "SELECT p.pluslet_id as 'id',su.shortform as 'short_form','Pluslet' as 'content_type', p.title, p.title AS 'label', ps.section_id, t.tab_index AS 'additional_id', t.subject_id, su.subject FROM pluslet AS p
                    INNER JOIN pluslet_section AS ps
                    ON ps.pluslet_id = p.pluslet_id
                    INNER JOIN section AS s
                    ON ps.section_id = s.section_id
                    INNER JOIN tab AS t
                    ON s.tab_id = t.tab_id
                    INNER JOIN subject AS su
                    ON su.subject_id = t.subject_id
                    WHERE p.body LIKE " . $search_param   .
             " AND t.subject_id = " . $subject_id ;


        break;
      case "records":
        $q = "SELECT title_id AS 'id', 'Record' as 'content_type',title AS 'label', title FROM title WHERE title LIKE " . $search_param ;
        break;
      case "faq":
        $q = "SELECT faq_id AS 'id',question AS 'label', LEFT(question, 55), 'FAQ' as 'content_type'  FROM faq WHERE question LIKE " . $search_param ;
        break;
      case "talkback":
        $q = "SELECT talkback_id AS 'id',question AS 'label','Talkback' as content_type, LEFT(question, 55) FROM talkback WHERE question LIKE " . $search_param ;
        break;
      case "admin":
        $q = "SELECT staff_id AS 'id',email AS 'label','Staff' as 'content_type', CONCAT(fname, ' ', lname, ' (', email, ')') as fullname FROM staff WHERE (fname LIKE " . $search_param . ") OR (lname LIKE " . $search_param . ")";
        break;

    }

    //print_r ($q);
    $result = $db->query($q);
    $arr = array();
    $i = 0;

    // This takes the results and creates an array that will be turned into JSON

    foreach ($result as $myrow)  {

      //add no title label if empty
      $myrow['label'] = empty($myrow['label']) ? '[no title]' : $myrow['label'];

      $arr[$i]['label'] = $myrow['label'];

      if(isset($myrow['content_type'])) {

        $arr[$i]['id'] = $myrow['id'];

        if (isset( $myrow['short_form'])) {
          $arr[$i]['shortform'] =  $myrow['short_form'];
        }


        if (isset($myrow['matching_text'])) {
          $arr[$i]['value'] = $myrow['matching_text'];
        }


        if (isset($myrow['content_type'])) {
          $arr[$i]['content_type'] = $myrow['content_type'];

        }

        if (isset( $myrow['parent'])) {
          $arr[$i]['parent'] = $myrow['parent'];
        }

        if (isset( $myrow['additional_id'])) {
          $arr[$i]['parent_id'] = $myrow['additional_id'];


        }



        switch($myrow['content_type']) {

          case "Record":
            $arr[$i]['label'] = $myrow['label'];

            if ($this->getSearchPage() == "control") {
              $arr[$i]['url'] = 'record.php?record_id=' . $myrow['id'];
          }   else {

              $db = new Querier();
              $record_url_sql = "SELECT location, title
				FROM location l, title t, location_title lt 
				WHERE  t.title_id = lt.title_id
				AND l.location_id = lt.location_id AND t.title_id = " . $db->quote($myrow['id']) . " ";
            

              $record_url_result = $db->query($record_url_sql);

              if (isset($record_url_result[0]['location'])) {

                $arr[$i]['url'] = $record_url_result[0]['location'];
            } else {

                $arr[$i]['url'] = '';


            }

          }

            break;

          case "Subject Guide":
            if ($this->getSearchPage() == "control") {
              $arr[$i]['url'] = getControlURL() . 'guides/guide.php?subject_id=' . $myrow['id'];

          }   else {

              $arr[$i]['url'] = 'guide.php?subject=' . $myrow['short_form'];
          }

            break;


          case "FAQ":
              $arr[$i]['label']  = $myrow['label'];
              $arr[$i]['url'] = 'faq.php?faq_id=' . $myrow['id'];

            break;

          case "Pluslet":
            if ($this->getSearchPage() == "control") {
              $arr[$i]['url'] = getControlURL() . 'guides/guide.php?subject_id=' . $myrow['short_form'] . '#box-' . $myrow['additional_id'] . '-' . $myrow['id'];
              $arr[$i]['hash'] = '#box-' . $myrow['additional_id'] . '-' . $myrow['id'];

              $arr[$i]['label'] = $myrow['label'];
          } else {

              $arr[$i]['url'] = 'guide.php?subject=' . $myrow['short_form'] . '#box-' . $myrow['additional_id'] . '-' . $myrow['id'];
          	  $arr[$i]['hash'] = '#box-' . $myrow['additional_id'] . '-' . $myrow['id'];
              $arr[$i]['tab_index'] = $myrow['additional_id'];

          }
            break;



          case "Talkback":
            $arr[$i]['label'] = $myrow['label'];
            if ($this->getSearchPage() == "control") {
              $arr[$i]['url'] = 'talkback.php?talkback_id=' . $myrow['id'];
          } else {
              $arr[$i]['url'] = 'talkback.php';
          }
            break;

          case "Staff":
            $arr[$i]['label'] = $myrow['fullname'];

            if ($this->getSearchPage() == "control") {

              $arr[$i]['url'] = 'user.php?staff_id=' . $myrow['id'];

          } else {

	      $name = explode('@',$myrow['label']);
              $arr[$i]['url'] = 'staff_details.php?name=' . $name[0];

          }
            break;
        }

      } else {
        $arr[$i]['value'] = $myrow[0];
      }


      $i++;
    }

    $response = json_encode($arr);


    return $response;
  }

}

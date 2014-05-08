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
    /*
    SELECT pluslet_id AS 'id', title AS 'matching_text', body as 'additional_text','' AS 'short_form', 'Pluslet' AS 'content_type' FROM pluslet 
    WHERE title LIKE "  . $db->quote("%" . $this->param . "%") . "
    OR body LIKE "  . $db->quote("%" . $this->param . "%") . "
     */
    $db = new Querier;

    switch ($this->collection) {
      case "home":
	$q = "SELECT subject_id AS 'id', subject AS 'matching_text', description as 'additional_text', shortform AS 'short_form', 'Subject Guide' as 'content_type' FROM subject 
WHERE description LIKE"  . $db->quote("%" . $this->param . "%") . "
OR subject LIKE "  . $db->quote("%" . $this->param . "%") . "
OR keywords LIKE "  . $db->quote("%" . $this->param . "%"). "
UNION 
SELECT p.pluslet_id, p.title, su.subject_id, su.shortform, 'Pluslet' AS 'content_type' FROM pluslet AS p 
	INNER JOIN pluslet_section AS ps 
	ON ps.pluslet_id = p.pluslet_id
	INNER JOIN section AS s 
	ON ps.section_id = s.section_id
	INNER JOIN tab AS t
	ON s.tab_id = t.tab_id
	INNER JOIN subject AS su 
	ON su.subject_id = t.subject_id
WHERE p.body LIKE "  . $db->quote("%" . $this->param . "%") . "
OR p.title LIKE "  . $db->quote("%" . $this->param . "%") . "

UNION
SELECT faq_id AS 'id', question AS 'matching_text', answer as 'additional_text','' AS 'short_form','FAQ' as 'content_type' FROM faq 
WHERE question LIKE "  . $db->quote("%" . $this->param . "%") . "
OR answer LIKE "  . $db->quote("%" . $this->param . "%") . "
OR keywords LIKE "  . $db->quote("%" . $this->param . "%") . "
UNION
SELECT talkback_id AS 'id', question AS 'matching_text' , answer as 'additional_text','' AS 'short_form', 'Talkback' as 'content_type' FROM talkback 
WHERE question LIKE "  . $db->quote("%" . $this->param . "%") . "
OR answer LIKE "  . $db->quote("%" . $this->param . "%") . "
UNION
SELECT staff_id AS 'id', email AS 'matching_text' , fname as 'additional_text','' AS 'short_form', 'Staff' as 'content_type' FROM staff 
WHERE fname LIKE " .$db->quote("%" . $this->param . "%")  . "
OR lname LIKE "  . $db->quote("%" . $this->param . "%") . "
OR email LIKE " . $db->quote("%" . $this->param . "%") . "
OR tel LIKE " . $db->quote("%" . $this->param . "%") . "
UNION
SELECT department_id AS 'id', name AS 'matching_text' , telephone as 'additional_text','' AS 'short_form', 'Department' as 'content_type' FROM department 
WHERE name LIKE " . $db->quote("%" . $this->param . "%") ."
OR telephone LIKE  " . $db->quote("%" . $this->param . "%") . "
UNION
SELECT video_id AS 'id', title AS 'matching_text' , description as 'additional_text','' AS 'short_form', 'Video' as 'content_type' FROM video 
WHERE title LIKE " .  $db->quote("%" . $this->param . "%") . "
OR description LIKE " . $db->quote("%" . $this->param . "%") . "
OR vtags LIKE " .  $db->quote("%" . $this->param . "%");



break;
case "guides":
$q = "SELECT subject_id, subject, shortform FROM subject WHERE subject LIKE " . $db->quote("%" . $this->param . "%") 
											  . "OR shortform LIKE " . $db->quote("%" . $this->param . "%") 
															     . "OR description LIKE " . $db->quote("%" . $this->param . "%") 
																				  . "OR keywords LIKE " . $db->quote("%" . $this->param . "%") 
																								    . "OR type LIKE " . $db->quote("%" . $this->param . "%") ;
break;	


case "guide":
$q = "SELECT p.pluslet_id, p.title, ps.section_id, s.tab_id, t.subject_id, su.subject FROM pluslet AS p 
	INNER JOIN pluslet_section AS ps 
	ON ps.pluslet_id = p.pluslet_id
	INNER JOIN section AS s 
	ON ps.section_id = s.section_id
	INNER JOIN tab AS t
	ON s.tab_id = t.tab_id
	INNER JOIN subject AS su 
	ON su.subject_id = t.subject_id
        WHERE p.body LIKE " . $db->quote("%" . $this->param . "%")   . 
					 " AND t.subject_id = " . $db->quote( $this->subject_id );

break;
case "records":
$q = "SELECT title_id, title FROM title WHERE title LIKE " . $db->quote("%" . $this->param . "%") ;
break;		
case "faq":
$q = "SELECT faq_id, LEFT(question, 55), 'faq' as 'type'  FROM faq WHERE question LIKE " . $db->quote("%" . $this->param . "%") ;
break;
case "talkback":
$q = "SELECT talkback_id, LEFT(question, 55) FROM talkback WHERE question LIKE " . $db->quote("%" . $this->param . "%") ;
break;	
case "admin":
$q = "SELECT staff_id, CONCAT(fname, ' ', lname) as fullname FROM staff WHERE (fname LIKE " . $db->quote("%" . $this->param . "%") . ") OR (lname LIKE " . $db->quote("%" . $this->param . "%") . ")";
break;

}


$result = $db->query($q);
$arr = array();
$i = 0;

// This takes the results and creates an array that will be turned into JSON

foreach ($result as $myrow)  {

  $arr[$i]['label'] = $myrow[1];
  if(isset($myrow[3])) {
    $arr[$i]['shortform'] = $myrow[3];
    $arr[$i]['id'] = $myrow[0];
    $arr[$i]['value'] = $myrow[3];
    $arr[$i]['category'] = $myrow[4];

    switch($myrow[4]) {
      
      case "Subject Guide":
        if ($this->getSearchPage() == "control") {
	  $arr[$i]['url'] = 'guides/guide.php?subject_id=' . $myrow[0];
      }   else {
          $arr[$i]['url'] = 'guide.php?subject=' . $myrow[3];   
      }
        
	break;
      
      
      case "FAQ":
	if ($this->getSearchPage() == "control") {
          $arr[$i]['url'] = 'faq/faq.php?faq_id=' . $myrow[0];
      } else {
          $arr[$i]['url'] = 'faq.php?page=all#faq-' .$myrow[0];    
      }
	break;
      
      case "Pluslet":
	if ($this->getSearchPage() == "control") {
	  $arr[$i]['url'] = 'guides/guide.php?subject_id=' . $myrow[0];
      } else {
	  $arr[$i]['url'] = 'guide.php?subject=' . $myrow[3];
	  
      }
	break;

      case "Talkback":
        if ($this->getSearchPage() == "control") {
	  $arr[$i]['url'] = 'talkback/talkback.php?talkback_id=' . $myrow[0];
      } else {
          $arr[$i]['url'] = 'talkback.php';    
      }
	break;
      
      case "Staff":
	if ($this->getSearchPage() == "control") {
	  $arr[$i]['url'] = 'admin/user.php?staff_id=' . $myrow[0];
	  
      } else {
	  $arr[$i]['url'] = 'staff.php';
	  
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

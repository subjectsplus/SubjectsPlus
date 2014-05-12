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
      
    $db = new Querier;
    $search_param = $db->quote("%" . $this->param . "%");

    switch ($this->collection) {
      case "home":
	$q = "SELECT subject_id AS 'id', subject AS 'matching_text', description as 'additional_text', shortform AS 'short_form', 'Subject Guide' as 'content_type', '' as 'additional_id', '' as 'parent' FROM subject 
WHERE description LIKE "  . $search_param . "
OR subject LIKE "  . $search_param . "
OR keywords LIKE "  . $search_param . "
UNION 
SELECT p.pluslet_id, p.title, su.subject_id AS 'parent_id', su.shortform, 'Pluslet' AS 'content_type', t.tab_index as 'additional_id',su.subject as 'parent' FROM pluslet AS p 
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
SELECT faq_id AS 'id', question AS 'matching_text', answer as 'additional_text','' AS 'short_form','FAQ' as 'content_type', '' as 'additional_id', '' as 'parent' FROM faq 
WHERE question LIKE "  . $search_param . "
OR answer LIKE "  . $search_param . "
OR keywords LIKE "  . $search_param . "
UNION
SELECT talkback_id AS 'id', question AS 'matching_text' , answer as 'additional_text','' AS 'short_form', 'Talkback' as 'content_type', '' as 'additional_id', '' as 'parent' FROM talkback 
WHERE question LIKE "  . $search_param . "
OR answer LIKE "  . $search_param . "
UNION
SELECT staff_id AS 'id', email AS 'matching_text' , fname as 'additional_text','' AS 'short_form', 'Staff' as 'content_type', '' as 'additional_id', '' as 'parent' FROM staff 
WHERE fname LIKE " .$search_param  . "
OR lname LIKE "  . $search_param . "
OR email LIKE " . $search_param . "
OR tel LIKE " . $search_param . "
UNION
SELECT department_id AS 'id', name AS 'matching_text' , telephone as 'additional_text','' AS 'short_form', 'Department' as 'content_type', '' as 'additional_id','' as 'parent' FROM department 
WHERE name LIKE " . $search_param ."
OR telephone LIKE  " . $search_param . "
UNION
SELECT video_id AS 'id', title AS 'matching_text' , description as 'additional_text','' AS 'short_form', 'Video' as 'content_type', '' as 'additional_id', '' as 'parent' FROM video 
WHERE title LIKE " .  $search_param . "
OR description LIKE " . $search_param . "
OR vtags LIKE " .  $search_param;



break;
case "guides":
$q = "SELECT subject_id, subject, shortform FROM subject WHERE subject LIKE " . $search_param 
   . "OR shortform LIKE " . $search_param 
   . "OR description LIKE " . $search_param 
   . "OR keywords LIKE " . $search_param 
   . "OR type LIKE " . $search_param ;
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
        WHERE p.body LIKE " . $search_param   . 
     " AND t.subject_id = " . $db->quote( $this->subject_id );

break;
case "records":
$q = "SELECT title_id AS 'id', title FROM title WHERE title LIKE " . $search_param ;
break;		
case "faq":
$q = "SELECT faq_id AS 'id', LEFT(question, 55), 'FAQ' as 'content_type'  FROM faq WHERE question LIKE " . $search_param ;
break;
case "talkback":
$q = "SELECT talkback_id AS 'id','Talkback' as content_type, LEFT(question, 55) FROM talkback WHERE question LIKE " . $search_param ;
break;	
case "admin":
$q = "SELECT staff_id AS 'id','Staff' as content_type, CONCAT(fname, ' ', lname) as fullname FROM staff WHERE (fname LIKE " . $search_param . ") OR (lname LIKE " . $search_param . ")";
break;

}


$result = $db->query($q);
$arr = array();
$i = 0;

// This takes the results and creates an array that will be turned into JSON

//print_r($result);
print_r ($result);
foreach ($result as $myrow)  {


  $arr[$i]['label'] = $myrow[1];

  if(isset($myrow['content_type'])) {
    $arr[$i]['shortform'] = $myrow['short_form'];
    $arr[$i]['id'] = $myrow['id'];
    $arr[$i]['value'] = $myrow[1];
    $arr[$i]['content_type'] = $myrow['content_type'];
    $arr[$i]['parent'] = $myrow['parent'];
    $arr[$i]['parent_id'] = $myrow[2];
    
    switch($myrow[2]) {
      
      case "Subject Guide":
        if ($this->getSearchPage() == "control") {
	  $arr[$i]['url'] = 'guides/guide.php?subject_id=' . $myrow['id'];
      }   else {
          $arr[$i]['url'] = 'guide.php?subject=' . $myrow['short_form'];   
      }
        
	break;
      
      
      case "FAQ":
	if ($this->getSearchPage() == "control") {
          $arr[$i]['url'] = 'faq.php?faq_id=' . $myrow[0];
      } else {
          $arr[$i]['url'] = 'faq.php?page=all#faq-' .$myrow[0];    
      }
	break;
      
      case "Pluslet":
	if ($this->getSearchPage() == "control") {
	  $arr[$i]['url'] = 'guides/guide.php?subject_id=' . $myrow['additional_text'] . '#box-' . $myrow['additional_id'] . '-' . $myrow['id'];
      } else {
	  $arr[$i]['url'] = 'guide.php?subject=' . $myrow[3] . '#box-' . $myrow['additional_id'] . '-' . $myrow['id'];
	  $arr[$i]['tab_index'] = $myrow['additional_id'];
	  
      }
	break;

      case "Talkback":
        if ($this->getSearchPage() == "control") {
	  $arr[$i]['url'] = 'talkback/talkback.php?talkback_id=' . $myrow['id'];
      } else {
          $arr[$i]['url'] = 'talkback.php';    
      }
	break;
      
      case "Staff":
	if ($this->getSearchPage() == "control") {
	
	  $arr[$i]['url'] = 'admin/user.php?staff_id=' . $myrow['staff_id'];
	    print_r($myrow);
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

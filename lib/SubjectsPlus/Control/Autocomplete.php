<?php

namespace SubjectsPlus\Control;

use SubjectsPlus\Control\Querier;


class Autocomplete {
  private $param;
  private $collection;
  private $json;
  private $subject_id;

  
 public function setSubjectId($subject_id) {
    $this->subject_id = $subject_id;
  }

  public function getSubjectId() {
    return $this->subject_id;
    
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

    switch ($this->collection) {
      case "home":
	$q = "SELECT subject_id, subject, shortform FROM subject WHERE subject LIKE " . $db->quote("%" . $this->param . "%") ;
	break;
      case "guides":
	$q = "SELECT subject_id, subject, shortform FROM subject WHERE subject LIKE " . $db->quote("%" . $this->param . "%") ;
	
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
	$q = "SELECT faq_id, LEFT(question, 55) FROM faq WHERE question LIKE " . $db->quote("%" . $this->param . "%") ;
	break;
      case "talkback":
	$q = "SELECT talkback_id, LEFT(question, 55) FROM talkback WHERE question LIKE " . $db->quote("%" . $this->param . "%") ;
	break;	
      case "admin":
	$q = "SELECT staff_id, CONCAT(fname, ' ', lname) as fullname FROM staff WHERE (fname LIKE " . $db->quote("%" . $this->param . "%") . ") OR (lname LIKE " . $db->quote("%" . $param . "%") . ")";
	break;
	
    }


    $result = $db->query($q);
    $arr = array();
    $i = 0;

    foreach ($result as $myrow){
      $arr[$i]['value'] = $myrow[0];
      $arr[$i]['label'] = $myrow[1];
      $i++;
    }

    $response = json_encode($arr);
    
    //$this->setJSON($response);
    return $response;
  }

}

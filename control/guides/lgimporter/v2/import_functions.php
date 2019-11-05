<?php
namespace SubjectsPlus\Control;
include_once('../../../../lib/SubjectsPlus/Control/Guide.php');
include_once('../../../../lib/SubjectsPlus/Control/Querier.php');
include_once('../../../includes/config.php');
include_once('../../../includes/functions.php');


class LGImport2 {
  function __construct() {
    if($this->lg = simplexml_load_file("./lg2.xml")) { // if the libguides xml loads
    } else {
      echo "failed to load file";
    }
    $this->db = new Querier;

  }

  public function loadAllSubjectTerms($staff_id) {
      foreach ($this->lg->xpath('//subject') as $sub) { // for each subject in original XML
        //echo "Importing subject: " . $sub->name;
        $this->setSubjectPost($sub, $staff_id); //sets _POST fields with appropriate values from XML
        $newsub = new Guide("", "post"); //create new Guide object with _POST values
        $newsub->insertRecord(); //insert guide into subject, rely on this method for dupe handling
      }
  }

  public function loadAllRecords($staff_id) {
      foreach ($this->lg->guides->guide as $guide) { // for each subject in original XML
        foreach ($guide->xpath(".//asset[type/text() = 'Link' or type/text() = 'Database']") as $asset) { // for each subject in original XML
          if ($this->fetchId('location', 'location', $asset->url, 'location_id')) { //dupe check
            //echo "Not importing duplicate asset: " . $asset->name . "<br/>";
          } else {
            //echo "Importing asset: " . $asset->name . "<br/>";
            $_POST['title'] = $asset->name;
            $_POST['description'] = $asset->description;
            $_POST['location'] = Array($asset->url);
            $_POST['access_restrictions'] = ($asset->enable_proxy && 1 == $asset->enable_proxy) ? Array(2) : Array(1);

            $_POST['subjects'] = Array();
            foreach ($guide->subjects->subject as $sub) {
              $_POST['subjects'][] = $this->fetchSubjectID($this->getShortForm($sub->name));
            }
            if (empty($_POST['subjects'])) {
              $_POST['subjects'] = Array(1);
            }
            $_POST['format'] = Array(1); // In a stock install, this will be Web
            $_POST['rank'] = Array(rand(0,32767));
            $_POST['location_id'] = Array('');
  	    $_POST['title_id'] = $_POST['prefix'] = $_POST['alternate_title'] = $_POST['internal_notes'] = '';
            $_POST['eres_display'] = ('Database' == $asset->type) ? Array('Y') : Array('N');
  	    $_POST['call_number'] = $_POST['display_note'] = $_POST['ctags'] = $_POST['helpguide'] = $_POST['source'] = $_POST['description_override'] = $_POST['dbbysub_active'] = Array();
            $newrecord = new Record("", "post"); //create new Record object with _POST values
            $newrecord->insertRecord(); //insert guide into subject, rely on this method for dupe handling
          }
        }
      }
  }

  public function loadAllLibGuides($staff_id) {
      foreach ($this->lg->guides->guide as $gc) { // for each guide in the original xml

        $this->setSubjectPost($gc, $staff_id); //sets _POST fields with appropriate values from XML
        $_POST["subject_id"] = $this->fetchSubjectID($this->getShortForm($gc->name));
        if ($gc->subjects) {
          foreach ($gc->subjects->subject as $parent) {
            $_POST['parent_id'][] = $this->fetchSubjectID($this->getShortForm($parent->name));
          }
        }

        $newgui = new Guide("", "post"); //create new Guide object with _POST values
        $_POST["subject_id"] ? $newgui->updateRecord() : $newgui->insertRecord(); //insert or update guide into subject
        $subid = $this->fetchSubjectID($this->getShortform($gc->name)); //retrieve subject_id of guide that was just inserted/updated

        foreach ($gc->pages->page as $page) { // for each page in the current guide

          $this->setTab($page, $subid);  // create or update Tab for the page
          $tabid = $this->fetchTabId($subid, ($page->position - 1)); // fetch the tab_id associated with the page
          $box_count = 0; // initialize number of boxes to zero

          foreach ($page->boxes->box as $box) { // for each box in the current page

            if ($this->setSection($box_count, $tabid)) { // if a new section is created successfully
              //echo "<br>Section insert success!<br>";

              $sectid = $this->fetchSectionId($box_count, $tabid);

              foreach ($box->assets->asset as $asset) {
                $this->setPluslet($asset, $sectid, $box);
              }//end of asset loop
            }
            else {
              echo "<br>Something went wrong in setSection()<br>";
            }
            $box_count += 1; //increment box count
          }//end of box loop

        }//end of page loop
        //break; //this break is for testing purposes and ensures only one guide is imported
      }//end of guide loop
  }

  /*
  * getShortform() takes a string and returns a checksum
  *   this is a temporary mesaure to get a unique shortform
  *   for any imported guides and will likely be removed
  *   or changed later on
  */
  private function getShortform($str) {
    $newstr = crc32($str);
    if(ord($newstr) == 45){
      $newstr = substr($newstr, 1);
    }
    return $newstr;
  }

  /*
  * fetchId() returns the id of an entry in a table
  *   associated with an identifying value
  */
  private function fetchId($table, $column, $value, $id) {
    $sql = "SELECT * FROM {$table} WHERE {$column} = '{$value}'";
    $result = $this->db->query($sql);
    if ($result) {
      return $result[0]["{$id}"];
    }
    else {
      return NULL;
    }
  }

  /*
  * fetchSectionId() returns the id of an entry
  *   in the 'section' table that matches a
  *   section_index and tab_id
  */
  private function fetchSectionId($section_index, $tab_id) {
    $sql = "SELECT * FROM section WHERE section_index = '{$section_index}' AND tab_id = '{$tab_id}'";
    $result = $this->db->query($sql);
    if ($result) {
      return $result[0]['section_id'];
    }
    else {
      return NULL;
    }
  }

  /*
  * fetchTabId() returns the id of an entry
  *   in the 'tab' table that matches a
  *   subject_id and tab_index
  */
  private function fetchTabId($subject_id, $tab_index) {
    $sql = "SELECT * FROM tab WHERE subject_id = '{$subject_id}' AND tab_index = '{$tab_index}'";
    $result = $this->db->query($sql);
    if ($result) {
      return $result[0]['tab_id'];
    }
    else {
      return NULL;
    }
  }

  /*
  * fetchStaffId() returns the staff id of the user
  *   associated with an email address($email)
  */
  private function fetchStaffId($email) {
    $result = $this->fetchId('staff', 'email', $email, 'staff_id');
    if ($result) {
      return $result;
    }
    else {
      return NULL;
    }
  }

  /*
  * fetchSubjectID() returns the subject_id of the 'subject'
  *   table entry that matches the provided shortform
  */
  private function fetchSubjectID($shortform) {
    $result = $this->fetchId('subject', 'shortform', $shortform, 'subject_id');
    if ($result) {
      return $result;
    }
    else {
      return NULL;
    }
  }

  /*
  * setSubjectPost() prepares _POST fields with the approriate
  *   values from the libguides xml
  */
  private function setSubjectPost($gc, $staff_id = 1) {
    $_POST["subject_id"]=NULL;
    $_POST["subject"]=$gc->name;
    $_POST["shortform"]=$this->getShortform($gc->name);
    $_POST["description"]=$gc->description;
    if ($gc->tags) {
      foreach ($gc->tags->tag as $ti => $tc){
        $_POST["keywords"][$ti] = $tc->name;
      }
    } else {
      $_POST["keywords"] = Array();
    }
    $_POST["redirect_url"]=$gc->redirect;
    $_POST["active"]='1';
    $_POST["type"]='Subject';
    $_POST['extra']=Array('maincol'=>'');
    $_POST['header']='default';
    $_POST['staff_id']=Array($staff_id);
    $_POST['parent_id']=[];  //same
    //$_POST['discipline_id'];
  }

  /*
  * setTab() takes a single xml page object from
  *   the libguides2_export, extracts the appropriate
  *   values and either updates an existing tab or
  *   creates a new one
  */
  private function setTab($page, $subid) {
    //set values from page
    $tab_label = $page->name;
    $tab_index = ($page->position - 1);
    if($page->hidden == 1){
      $tab_visibility = 0;
    }
    else{
      $tab_visibility = 1;
    }

    if ($tab_index == 0) {
      $sql = "UPDATE tab SET label='{$tab_label}', visibility={$tab_visibility} WHERE subject_id={$subid} AND tab_index={$tab_index}";
    }
    else {
      if ($this->fetchTabId($subid, $tab_index) == NULL){
        $sql = "INSERT INTO tab (subject_id, label, tab_index, visibility)
                VALUES ('{$subid}', '{$tab_label}', '{$tab_index}', '{$tab_visibility}')";
      }
      else {
        return;
      }
    }
    $result = $this->db->exec($sql);
    return $result;
  }

  /*
  *setSection() takes a section_index and
  *   a tab_id and creates a section entry.
  *   layout is currently set to one box per
  *   section until we can figure out how
  *   to convert the libguides layouts.
  */
  private function setSection($section_index, $tabid) {
    $layout = '0-12-0';

    $sql = "INSERT INTO section (section_index, layout, tab_id)
            VALUES ('{$section_index}', '{$layout}', '{$tabid}')";
    if ($this->fetchSectionId($section_index, $tabid) == NULL) {
      $result = $this->db->exec($sql);
      return $result;
    }
    else {
      return;
    }
  }

  /*
  * setPluslet() takes an asset from
  *   the libguides xml and a section_id
  *   to create a basic pluslet
  */
  private function setPluslet($asset, $sectid, $box){
    $title = ('' == $asset->name) ? $box->name : $asset->name;
    $body = $extra = '';
    switch ($asset->type) {
      case 'Book from the Catalog':
        if (!$asset->isbn || 0 === strlen($asset->isbn)) { return; }
        $type = 'BookList';
        $extra = '{"listDescription":"Book from the Catalog","isbn":"' . $asset->isbn . '"}';
        break;
      case 'Database':
        $id = $this->fetchTitleIdFromURL($asset->url);
        if(!$id) { return; }
        $body = '<span class="token-list-item subsplus_resource" contenteditable="false">{{dab},{' . $id . '}, {Sample Record},{110}}</span>';
        $type = 'Basic';
        break;
      case 'Google Search':
        $type = 'GoogleSearch';
        break;
      case 'Link':
        $id = $this->fetchTitleIdFromURL($asset->url);
        if(!$id) { return; }
        $body = '<span class="token-list-item subsplus_resource" contenteditable="false">{{dab},{' . $id . '}, {Sample Record},{100}}</span>';
        $type = 'Basic';
        break;
      case 'Rich Text/HTML':
        $body = $asset->description;
        $type = 'Basic';
        break;
      case 'RSS Feed':
        $body = $asset->url;
        $type = 'Feed';
        $extra = '{"num_items":10,  "show_desc": 1, "show_feed": 1, "feed_type": "RSS"}';
        break;
    }

    if ($type) {
      $sql = "INSERT INTO pluslet (title, body, extra, clone, type)
            VALUES ('{$title}', '{$body}', '{$extra}', 0, '{$type}')";

      $result = $this->db->exec($sql);
      if ($result) {
        // echo "New pluslet created";
        $pluslet_section = 'INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow)
        VALUES (' . $this->db->last_id() . ', ' . $sectid . ', 1, ' . rand(0,20) . ')';
        $ps_result = $this->db->exec($pluslet_section);
      }
    }
  }

  /*
  * fetchTitleIdFromURL() returns the id of an entry
  *   in the 'location' table that matches a
  *   URL
  */

  private function fetchTitleIdFromURL($url) {
    $sql = "SELECT title_id FROM location_title INNER JOIN location ON location.location_id=location_title.location_id WHERE location='" . $url . "' LIMIT 1";
    $result = $this->db->query($sql);
    if ($result) {
      return $result[0]['title_id'];
    }
    else {
      return NULL;
    }

  }
}


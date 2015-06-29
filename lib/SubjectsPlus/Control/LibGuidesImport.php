<?php
namespace SubjectsPlus\Control;

class LibGuidesImport {

  private $_guide_id;
  private $_libguides_xml;
  private $_guide_owner;
  private $_row = 0;
  private $_column = 0;
  
  
  
  public function __construct($lib_guides_xml_path) {
  	$libguides_xml= new \SimpleXMLElement(file_get_contents($lib_guides_xml_path,'r'));
  	
  	$this->libguidesxml = $libguides_xml;
  	
  	$db = new Querier;
  	$this->db = $db;
  	
  }
  
  public function importLog($log_text) {
    
  	
  	$formatted_text = "<p>" . $log_text . "</p>";
  	
    file_put_contents("../../../import_log.html", $formatted_text, FILE_APPEND);
    
  }

  public function setGuideOwner($guide_owner) {
    $this->_guide_owner = $guide_owner;
  }
  public function getGuideOwner() {

    return $this->_guide_owner;

  }
 
  public function setGuideID($guide_id) {
    $this->_guide_id = $guide_id;
  }
  public function getGuideID() {
    return $this->_guide_id;
  }


  public function setRow($row) {
  	$this->_row = $row;
  }
  
  public function getRow() {
  	$this->_row++;
  	if ($this->_row > 2) {
  		$this->_row = 0;
  	}
  	return $this->_row;
  	
  }
  
  
  
  public function setColumn($column) {
  	$this->_column = $column;
  }
  
  public function getColumn() {
  	$this->_column++;
  	if ($this->_column > 2) {
  		$this->_column = 0;
  	}
  	return $this->_column; 
  }
  
  
  public function insertBasicPluslet($box, $section_id, $description) {
	
	
  	$row = $this->getRow();
  	$column = $this->getColumn();

	$description_clean = $this->db->quote($description);
	
	if($this->db->exec("INSERT INTO pluslet (pluslet_id, title, body, type) VALUES ($box->BOX_ID, '$box->NAME', $description_clean, 'Basic')")) {
	
		$this->importLog("Inserted pluslet '$box->NAME'");
		$clean_description = null;
	
	} else {
	
	
		$this->importLog("Error inserting pluslet:");
		ob_start();
		var_dump($this->db->errorInfo(ob_get_clean()));
		
		$this->importLog();
	
	}
	
	
	if($this->db->exec("INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES ('$box->BOX_ID', '$section_id', $column, $row)")) {
		$this->importLog("Inserted pluslet section relationship");
	
	
		// This sticks the newly created pluslet into a section
	} else {
	
	
		$this->importLog("Error inserting pluslet_section:");
		$this->importLog( $this->db->errorInfo());
		$this->importLog("INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES ('$box->BOX_ID', '$section_id', $column, $row)");
	}
}



public function insertSubjectSpecialist($box, $section_id) {
	
	$row = $this->getRow();
	$column = $this->getColumn();
	
	if($this->db->exec("INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES (3, $section_id, $column, $row)")) {
		$this->importLog("Inserted pluslet section subject specialist");
		$this->importLog("INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES (3, $section_id, $column, $row)");
		// This sticks the newly created pluslet into a section
	} else {
	
		$this->importLog("Couldn't insert pluslet section subject specialist");
		$this->importLog("INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES (3, $section_id, $column, $row)");
		
	}

}

public function insertRSSPluslet($box, $section_id, $feed_url) {
	
	$row = $this->getRow();
	$column = $this->getColumn();
	
	if($this->db->exec("INSERT INTO pluslet (pluslet_id, title, body, type, extra) VALUES ($box->BOX_ID, '$box->NAME', '$feed_url', 'Feed', '{\"num_items\":5,  \"show_desc\":1, \"show_feed\": 1, \"feed_type\": \"RSS\"}' )")) {
	
		$this->importLog("Inserted RSS pluslet '$box->NAME'");
		$clean_description = null;
	
	} else {
	
		$this->importLog("INSERT INTO pluslet (pluslet_id, title, body, type) VALUES ('$box->BOX_ID', '$box->NAME', '$feed_url', 'Feed', '' )");
		
		$this->importLog("RSS RSSS RSS Error inserting pluslet:");
		$this->importLog($this->db->errorInfo());
	
	}
	
	
	if($this->db->exec("INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES ('$box->BOX_ID', '$section_id', $column, $row)")) {
		$this->importLog("Inserted pluslet section relationship");
	
	
		// This sticks the newly created pluslet into a section
	} else {
	
	
		$this->importLog("RSS Error inserting pluslet_section:");
		$this->importLog( $this->db->errorInfo());
		$this->importLog("RSS INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES ('$box->BOX_ID', '$section_id', $column, $row)");
	}
	
	
	

}


public function insertLinkedBox($box, $section_id) {
	

    $row = $this->getRow();
	$column = $this->getColumn();
	
	if($this->db->exec("INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES ('$box->LINKED_BOX_ID', '$section_id', $column, $row)")) {
		$this->importLog("Inserted linked box");


		// This sticks the newly created pluslet into a section
	} else {


		$this->importLog("Error inserting pluslet_section:");
		$this->importLog( $this->db->errorInfo());
		$this->importLog("INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES ('$box->LINKED_BOX_ID', '$section_id', $column, $row)");
	}
	
}

  

  public function importBoxLinks($box) {

  	$description = "";	
    
    foreach ( $box->LINKS->LINK as $link )  {
      
      
      
      $record = $this->db->query("SELECT * FROM location WHERE location = " .  $this->db->quote($link->URL),NULL,TRUE);


      if ($record[0]['location_id']) {

	$record_title = $this->db->query("SELECT title.title,title.title_id, location.location  FROM 
location_title 
JOIN title ON title.title_id = location_title.title_id
JOIN location on location.location_id = location_title.location_id
WHERE location.location_id = " . $record[0]['location_id']);

} else {

}

if ($record_title[0]["title"] == "") {
  
  $description .=    "<div class=\"links\">" . 
		     "<span class=\"link_title\"><a href=\"$link->URL\">$link->NAME</a></span>" .
                     "<div class=\"link-description\">$link->DESCRIPTION_SHORT</div>" .
                     "</div>";

} 

if ($record_title[0][title]) {
  
  $description .= 
  "<div class=\"links\">" . 
                  "<span class=\"link_title\">{{dab},{" . $record_title[0]['title_id'] . "}," . "{" . $record_title[0]["title"] . "},{01}}</span>" . 
                  "</div>";
  
}

$this->importLog ("Insert record:");
$this->importLog($record_title); 
$this->importLog("SELECT * FROM location WHERE location = " .  $this->db->quote($link->URL));

    }
    
    return $description;

}

public function importBox($box, $section_id) {
	
  $row = $this->getRow();
  $column = $this->getColumn();	
		
  $wc = new WordCleaner();
  
  $this->db->exec("SET NAMES utf-8" );
  	
  $description = null;
  
  //$description .= "<div class=\"Box Type\">$box->BOX_TYPE</div>";       

  // Import images and replace the old urls with new urls
  $doc = new \DOMDocument();

  $doc->loadHTML(mb_convert_encoding($box->DESCRIPTION, 'HTML-ENTITIES', 'UTF-8'));
  
  
  // Download images
  
  $nodes = $doc->getElementsByTagName("img");
  
  foreach( $nodes as $node ) {
  
  	foreach ($node->attributes as $attr) {
  		$test = strpos($attr->value, "http://");
  
  		if ($test !== false) {
  	  $this->importLog( $attr->value);
  	   
  	  $attr->value = $this->downloadImages($attr->value);
  	   
  	  $this->importLog($attr->value);
  
  
  		}
  	}
  }
  
  
  

  
  // Create html for the description

  $raw_description = $doc->saveHTML();
  $clean_description = $wc->strip_word_html($raw_description);
  
  $description .= "<div class=\"description\">".  $wc->strip_word_html($clean_description)  . "</div>";

  
  switch ($box->BOX_TYPE) {      

  case "Text Box":
    
   $this->insertBasicPluslet($box, $section_id, $description); 	
    break;
  case "Basic Links":

    $description .= $this->importBoxLinks($box);
    $this->insertBasicPluslet($box, $section_id, $description);
    break;
    
  case "Complex Links":

   $description .= $this->importBoxLinks($box);
   $this->insertBasicPluslet($box, $section_id, $description);
    break;

  case "Embedded Media & Widgets":
    //Box type: Media & Widgets

    foreach ( $box->EMBEDDED_MEDIA_AND_WIDGETS as $media )  {
      
      $description .= 
      "<div class=\"embedded-media-widgets\">" . 
		      "<div class=\"embedded-media-description\">$media->URL</div>" .
                      "</div>";
  }

  	$this->insertBasicPluslet($box, $section_id, $description);
  break;
 
  case "Linked Box":
  	
    		         $this->insertLinkedBox($box, $section_id);
    		          
    	break;	         
    	
  case "RSS Feeds & Podcasts":
  	$feed_url = $box->RSS_FEED->URL;
  	$this->insertRSSPluslet($box, $section_id, $feed_url);
  	
  	break;

  case "Books":
    // Box type: Books

    foreach ( $box->BOOKS->BOOK as $book )  {
      
    	 
    	 
    	
      $description .= 
      "<div class=\"book\">" . 
      		"<div class=\"book-cover-art\"><img class=\"cover-image\" src=\"$book->COVER_ART\"></div>" .
		      "<a class=\"book-title\" href=\"$book->URL\">$book->TITLE</a>" . 
		      "<div class=\"book-author\">$book->AUTHOR</div>" .
		      "<div class=\"book-call-number\">$book->CALL_NUMBER</div>" .
		      "<div class=\"book-description\">$book->DESCRIPTION</div>" .
		      "</div>";
  }

  $this->insertBasicPluslet($box, $section_id, $description);
  
  break;
 
  case "User Submitted Links":
  
  case "Files":
  
  	foreach ( $box->FILES as $files )  {
  	
  		foreach ($files->FILE as $file) {
  			$description .= "<div class=\"file\">" .
    		    "<div class=\"file-title\">" . $file->NAME . "</div>"
    		    . "<div class=\"file-description\">" . $file->DESCRIPTION . "</div>" 
    		    . "<div class=\"file-path-name\">" .$file->FILE_NAME . "</div>"
   		    . "</div>";
  		}
  	}
  	
  	$this->insertBasicPluslet($box, $section_id, $description);
  
  case "User Feedback":
    
  case "Google Search":
   
  case "Poll":
   
  case "Google Books":
 
  case "Events":
   
  case "Guide Links":
    
  case "User Profile":
   $this->insertSubjectSpecialist($box, $section_id);
   
  case "Google Scholar":
   

}



}


public function insertChildTab($tab) {
	 
	
	 
}


public function downloadImages($url) {
  // This method creates a folder for a guide image in assets, downloads the image , and then returns the new URL for that image

  global $AssetPath;

  // Download the image with CURL

  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  $data = curl_exec($ch);
  curl_close($ch);

  // Create a path for the iamge
  $dir_name =  dirname(dirname(dirname(dirname(__FILE__)))) . "/assets/images/" . $this->_guide_id . "/";

  // Make the guide's asset directory if needed
  if (!is_dir($dir_name))
  {
    mkdir($dir_name, 0777, true);
  }


  // Write the file
  $file_name = substr( $url, strrpos( $url, '/' )+1 );
  $file = fopen( $dir_name .  $file_name, 'w+');
  fwrite($file, $data);
  fclose($file);


  // Return the new URL
  $img_path = $AssetPath . "/images/" . $this->_guide_id . "/" . $file_name;

  $this->importLog($img_path);
  
  return $img_path;

}

public function getStaffID ($email_address) {
  // This method takes an email address and returns the subjectsplus staff id for the user

  
  $staff_id = $this->db->query("SELECT staff_id FROM staff WHERE email = '$email_address'");

  return $staff_id[0][0];

}

public function outputOwners() {

	$libguides_xml= $this->libguidesxml;
	
	$owners = $libguides_xml->xpath("//OWNER");
	$owner_names = array();
	$owner_email = array();
	$owner_profile = array();
	
	foreach ($owners as $owner) {
		if(!in_array((string) $owner->NAME, $owner_names)) {
	
			array_push($owner_names, (string) $owner->NAME);
		}
	}
	
	
	foreach ($owners as $owner) {
		if(!in_array((string) $owner->EMAIL_ADDRESS, $owner_email)) {
	
			array_push($owner_email, (string) $owner->EMAIL_ADDRESS);
		}
	}
	
	
	$owners_combined = zip($owner_names, $owner_email);
    $owners_sorted = array_multisort($owners_combined);
	echo "<select name=\"email\" class=\"owners\" >";
	
	foreach ($owners_combined as $owner) {
	
		
			echo "<option value=\"$owner[1]\">$owner[0]</option>";
		
			
	}
	echo "<select>";
	
	
}

public function outputGuides($email_address) {
  // Outputs a select box for guides 

  $libguides_xml= $this->libguidesxml;
  
  
  $owners = $libguides_xml->xpath("//OWNER/EMAIL_ADDRESS[.='$email_address']/..");
  $owner_names = array();
  $owner_email = array();
  $owner_profile = array();

  foreach ($owners as $owner) {
    if(!in_array((string) $owner->NAME, $owner_names)) {

      array_push($owner_names, (string) $owner->NAME);
    }
  }


  foreach ($owners as $owner) {
    if(!in_array((string) $owner->EMAIL_ADDRESS, $owner_email)) {

      array_push($owner_email, (string) $owner->EMAIL_ADDRESS);
    }
  }

  
  $owners_combined = zip($owner_names, $owner_email);
 $previously_imported = array();
  

  foreach ($owners_combined as $owner) {
  //  echo "<section class=\"import-block\">";
  //  echo "<h3>Please select a guide below.</h3>";

    
    
 //   echo "<p>If you are looking for a specific guide enter it's name after clicking below.</p>";
    echo "<h3>" . $owner[0] . "'s Guides</h3>";
     
  	$guide_names = $libguides_xml->xpath("//OWNER/NAME[text() = '$owner[0]']/ancestor::GUIDE");
  
  	echo "<select class=\"guides\" >";
  
  	foreach ($guide_names as $guide) {
  	
  	    $prexisting_guide = $this->db->query("SELECT * FROM subject WHERE subject_id = $guide->GUIDE_ID");
  	    var_dump ($prexisting_guide);
  	    
  		if (!$prexisting_guide) {
  		
  		echo "<option value=\"$guide->GUIDE_ID\">$guide->NAME - $guide->STATUS</option>";

  		
  		
  		
  		} else {
  			
  			
  		}
  	}
  	
  
  
  	echo "</select>";
  
  	foreach ($guide_names as $guide) {
  	
  		$guide_imported_count = $this->db->query("SELECT COUNT(*) FROM subject WHERE subject_id = '{$guide->GUIDE_ID}'");
  		
  		
  		if ($guide_imported_count[0][0]  == "1") {
  			array_push($previously_imported,array("guide_name" => (string) $guide->NAME, "guide_id" => (string) $guide->GUIDE_ID));
  		}
  	
  	}
  	
  	echo "<script>";
  	echo "var previously_imported = " . json_encode($previously_imported) . ";";
  	echo "</script>";
  	echo "</section>";
  }
  
  
  
  
  return $owners_combined;

}


public function guideImported() {

  $guide_id = $this->getGuideID();


  



  $guide = $this->db->query("SELECT COUNT(*) FROM subject WHERE subject_id = '$guide_id'");

  return $guide;

}



public function guideDupe($guide_url) {
	
  
  $guide = $this->db->query("SELECT COUNT(*) FROM location WHERE location = $guide_url");


  
  return $guide[0][0];

}

public function loadLibGuidesXML() {
		
  $section_index = 0;

  $libguides_xml= $this->libguidesxml;

  // zip combines arrays in fancy way
  // From the python docs: "This function returns a list of tuples, where the i-th tuple contains the i-th element from each of the argument sequences or iterables. The returned list is truncated in length to the length of the shortest argument sequence."

  $guide_id = $this->getGuideID();

  //Get the guide owner's email address
  $guide_owner_id = $libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/following-sibling::OWNER_ACCOUNT_ID");
  $guide_owner_email = $libguides_xml->xpath("//ACCOUNT_ID[.=\"$guide_owner_id[0]\"]/following-sibling::EMAIL");
  $this->setGuideOwner($guide_owner_email);

  $subject_values = zip($libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/following-sibling::NAME"),
			$libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/parent::GUIDE/GUIDE_ID"),
			$libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/parent::GUIDE/LAST_UPDATE"),
			$libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/parent::GUIDE/DESCRIPTION"),
			$libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/parent::GUIDE/PAGES"),
  		    $libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/parent::GUIDE/STATUS"),	
			$libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/parent::GUIDE/PAGES//LINKS"),
			$libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/parent::GUIDE/OWNER_ACCOUNT_ID"),
  		    $libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/parent::GUIDE/OWNER/EMAIL_ADDRESS"),
			$libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/parent::GUIDE/TAGS")
			);

  $test = $libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/following-sibling::NAME");
  $this->importLog($test);
 
  
  
  return $subject_values;
  
}

public function insertChildren() {
	
	$tabs = $this->db->query("SELECT * FROM tab");
	
	foreach ($tabs as $tab) {
		$tab_id = $tab['tab_id'];
		
		if ($tab['parent'] == '') {
			
			$child_ids = array();
			
			$children =	$this->db->query("SELECT * FROM tab WHERE parent = $tab_id");
					
			foreach ($children as $child) {
			
				array_push($child_ids, array( "child" => $child['tab_id']));
			
			}
			
			$children_json = json_encode($child_ids);
			$this->db->exec("UPDATE tab SET children='$children_json' WHERE tab_id='$tab_id'");
		}
	}
	
	
	
}

public function loadLibGuidesLinksXML() {
	
  $all_titles = array();
  $titles = array();
  $dupes = array();
  $link_status = array();
  $urls = array();
  
  $guide_id = $this->getGuideID();
    
  $libguides_xml= $this->libguidesxml;
  
  $link_values = $libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/..//LINKS/LINK");
   
  foreach (  $link_values as $link ) {
 	
    // Remove the proxy url from the link URL
    $noproxy_url = str_replace("https://iiiprxy.library.miami.edu/login?url=", "",$link->URL); 
    $noproxy_url = $this->db->quote($noproxy_url);
    
    $clean_url =  str_replace("'","", $noproxy_url);
    
    array_push($urls, array("url" => $clean_url));
    
    /*
    $ch = @curl_init($clean_url);
    @curl_setopt($ch, CURLOPT_HEADER, TRUE);
    @curl_setopt($ch, CURLOPT_NOBODY, TRUE);
    @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
    @curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $status = array();
    */
    //preg_match('/HTTP\/.* ([0-9]+) .*/', @curl_exec($ch) , $status);
    
    		
    /*
    if ($status[1] == 200) {
    	array_push($link_status, array("working_link" => "true"));
    	
    } else {
    	array_push($link_status, array("working_link" => "false"));
    	
    }
    */
   
    		
    $title =  $this->db->quote($link->NAME);
	array_push($titles, array("title" => $title));
	
	
    $record_check = $this->db->query("SELECT COUNT(*) FROM location WHERE location = $noproxy_url ");
    $title_check = $this->db->query("SELECT COUNT(*) FROM title WHERE title = $title");
    
    
    $this->importLog ( $record_check) ;
    $this->importLog ("RECORD CHECK!!!!!!!!!!!!!!!!!!!!!!");
    $this->importLog($record_check[0][0]);

    if ($record_check[0][0] == 0 && $title_check[0][0] == 0) {

      if ($this->db->exec("INSERT INTO location (location, format, access_restrictions, eres_display) VALUES (" . $this->db->quote($link->URL) . " , 1, 1, 'N' )" )) {
	
      	array_push($dupes, array("status" => "New Record Created"));
      	 
      	
	$this->importLog("Inserted location");
	$location_id = $this->db->last_id(); 
		
      } else {

	$this->importLog ("Error inserting location:");
      }
      
      // When inserting the titles into the databases, articles (a, an, the) should be removed and then stored in the prefix field 
      
      $matches = array();
      preg_match("/^\b(the|a|an|la|les|el|las|los)\b/i", $link->NAME, $matches);

      
      // If there isn't an article in the title
      if (empty($maches[0])) {
	
	if( $this->db->exec("INSERT INTO title (title, description) VALUES (" . $this->db->quote($link->NAME) . ","  . $this->db->quote($link->DESCRIPTION_SHORT)  . ")") ) {
	  $this->importLog( "Inserted title");
	  $title_id = $this->db->last_id();

	} else {
	  $this->importLog("Error inserting title:" );
	  $this->importLog(  $this->db->errorInfo() );
	}
	
      }
      
      // If there is an article in the title
      
      if(isset($matches[0])) {
	
	$clean_link_name = preg_replace("/^\b(the|a|an|la|les|el|las|los)/i", " ", $link->NAME);
	
	if( $this->db->exec("INSERT INTO title (title, description, pre) VALUES (" . $this->db->quote($clean_link_name) . ","  . $this->db->quote($link->DESCRIPTION_SHORT) . "," . $this->db->quote($matches[0]) . ")") ) {
	  $this->importLog( "Inserted title");
	  $title_id = $this->db->last_id();

	} else {
	  $this->importLog("Error inserting title:" );
	  $this->importLog(  $this->db->errorInfo() );
	}
	
      }
      
      
      if( $this->db->exec("INSERT INTO location_title (title_id, location_id) VALUES ($title_id, $location_id )") ) {
	$this->importLog( "Inserted location_title"); 
	

      } else {
	$this->importLog( "Error inserting location_title:");
	$this->importLog(  $this->db->errorInfo()  );

	$this->importLog( "INSERT INTO location_title (title_id, location_id) VALUES ($title_id, $location_id)");
      }

      
    } else {
    	array_push($dupes, array("status" => "Link Already Imported Into Records" ));
    	
    }

    

    
  }
  
  $all_titles['titles'] = zip($titles, $dupes, $urls);
  
  $return_titles = json_encode($all_titles);
  
  return $return_titles;
}


public function importLibGuides() {
  $subject_values = $this->loadLibGuidesXML();
 
  
  $subject_id = (string) $subject_values[0][1];
  $response = array();


  if ($this->guideImported() != 0) {

    //exit;
  }
  
  
  foreach($subject_values as $subject) { 

    // Remove the apostrophes and spaces from the shortform 

    $shortform = preg_replace('/\s+/','_', str_replace("'", "", $subject[0] ));
    
    // Escape the apostrophes in the guide name 

    $guide_name = str_replace("'", "''",$subject[0]);
    

    if ($subject[0] != null) {
      


      if($this->db->exec("INSERT INTO subject (subject, subject_id, shortform, description, keywords) VALUES ('$guide_name', '$subject[1]', '$shortform' , '$subject[3]', '$subject[7]')")) {

      
      	$response = array("imported_guide" => $subject[1] );
      	
       
	

      } else {
       // echo $subject[1][0];
       
      	$response = array("imported_guide" => $subject[1][0] );
      	 
	
	$query = "INSERT INTO subject (subject, subject_id, shortform, last_modified, description, keywords) VALUES ('$guide_name', '$subject[1]', '$shortform' , '$subject[2]', '$subject[3]', '$subject[7]')";
        
	$this->importLog( "Error inserting subject:");
	$this->importLog ($query);
        $this->importLog ( $this->db->errorInfo() ); 
	
      }

      if ($this->getGuideOwner() != null) {
      	
      	$guide_owner = 
      	
	$staff_id = $this->getStaffID( $subject[8] );
	
	$this->importLog ("Staff ID: " . $staff_id );
	
	if($this->db->exec("INSERT INTO staff_subject (subject_id, staff_id) VALUES ($subject[1], $staff_id)")) {
	  $this->importLog ("Inserted staff: '$staff_id'");
	  
	} else {

	  $this->importLog("Error inserting staff. ");
	  
	}
	
      }


    }
    
    else {
      
    }

    $subject_page = $subject[4];

    $tab_index = 0; 
    
    $tab_children = array();
    
    foreach ($subject_page->PAGE as $tab) {


    	try {
    		
    		$this->db->exec("ALTER TABLE `tab` ADD COLUMN `parent` VARCHAR(500) NULL AFTER `visibility`");
    		$this->db->exec("ALTER TABLE `tab` ADD COLUMN `children` VARCHAR(500) NULL AFTER `parent`");
    		
    	} catch(Exception $e) {
    	
    		$this->importLog($e);	
    		
    	}
    	
      // LibGuide's pages are tabs so make a new tab

      $tab_index++; 
      $visibility = 1;
      $current_parent;
      
      $clean_tab_name = $this->db->quote($tab->NAME);
      


        $parent_id =  $tab->PARENT_PAGE_ID;
        $tab_id = $tab->PAGE_ID;
	    $external_url = $tab->EXTERNAL_LINK;
	    
     
      
      if($this->db->exec("INSERT INTO tab (tab_id, subject_id, label, tab_index,visibility, parent,children,extra,external_url) VALUES ('$tab->PAGE_ID', '$subject[1]', $clean_tab_name, $tab_index - 1, $visibility, '','','','$external_url')")) {
      	
      	if ($parent_id != '') {
      	    $this->db->exec("UPDATE tab SET parent='$parent_id' WHERE tab_id='$tab_id'");
      	} else {
      		
      		
      		
      	}
      		
         
      	
      	
	$this->importLog ("Inserted tab '$tab->NAME'");

      } else {

        $this->importLog( "Problem inserting the tab, '$tab->NAME'. This tab may already exist in the database." );
	
        
	$this->importLog ("Error inserting tab:");
	$this->importLog ($this->db->errorInfo());

      }
     
      
      
     
      
      
      
      $section_index = null;
      
      
      foreach ($tab->BOXES as $section) {

        // LibGuide's box parents into sections
        
        $section_uniqid = $section_index . rand();
        
        $section_index++;


        if($this->db->exec("INSERT INTO section (tab_id, section_id, section_index) VALUES ('$tab->PAGE_ID', $section_uniqid ,   $section_index)")) {
          $this->importLog("Inserted section");
        } else { 
          $this->importLog("Problem inserting this section. This section  may already exist in the database.");
          
	  $this->importLog("Error inserting section:");
	  $this->importLog($this->db->errorInfo() );
          
        }
        
      }

      foreach ($tab->BOXES->BOX as $pluslet) {
        // This imports each LibGuide's boxes as pluslets 

      		$this->importLog("\n");
      	$this->importLog((string) $pluslet);
      		$this->importLog("\n");
 	
	$this->importBox($pluslet, $section_uniqid);
	

	$box_names['box_name'] = $pluslet->NAME;
	$box_types['box_type'] = $pluslet->BOX_TYPE;
	$boxes = array($box_names, $boy_types);
	array_push($response, array("box" => $boxes ));
	
	

	
      }
    }
    
    
    
    
  }
  $this->insertChildren();
  
  return json_encode($response);
  
 } 
 
}

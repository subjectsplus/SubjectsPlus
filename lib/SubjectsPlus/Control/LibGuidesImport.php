<?php
namespace SubjectsPlus\Control;

class LibGuidesImport {

  private $_guide_id;
  private $_libguides_xml;
  private $_guide_owner;
  
  
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
  public function setLibGuidesXML($libguides_xml) {
    $this->_libguides_xml = $libguides_xml;
  }
  public function getLibGuidesXML() {
    $this->_libguides_xml;
  }
  public function setGuideID($guide_id) {
    $this->_guide_id = $guide_id;
  }
  public function getGuideID() {
    return $this->_guide_id;
  }

 
  public function insert_basic_pluslet($box, $section_id, $description) {
	$db = new Querier;
	
	$row = 0;
	$column = 0;
		
	if($db->exec("INSERT INTO pluslet (pluslet_id, title, body, type) VALUES ($box->BOX_ID, '$box->NAME', '$description', 'Basic')")) {
	
		$this->importLog("Inserted pluslet '$box->NAME'");
		$clean_description = null;
	
	} else {
	
	
		$this->importLog("Error inserting pluslet:");
		$this->importLog($db->errorInfo());
	
	}
	
	
	if($db->exec("INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES ('$box->BOX_ID', '$section_id', $column, $row)")) {
		$this->importLog("Inserted pluslet section relationship");
	
	
		// This sticks the newly created pluslet into a section
	} else {
	
	
		$this->importLog("Error inserting pluslet_section:");
		$this->importLog( $db->errorInfo());
		$this->importLog("INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES ('$box->BOX_ID', '$section_id', $column, $row)");
	}
}

public function insert_subject_specialist($box, $section_id) {
	
	$db = new Querier;
	$row = 0;
	$column = 0;
	
	if($db->exec("INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES ('3', '$section_id', $column, $row)")) {
		$this->importLog("Inserted pluslet section subject specialist");
	
		// This sticks the newly created pluslet into a section
	} else {
	
		$this->importLog("Couldn't insert pluslet section subject specialist");
		
	}

}

public function insert_rss_pluslet($box, $section_id, $feed_url) {
	$db = new Querier;
	$row = 0;
	$column = 0;
	
	if($db->exec("INSERT INTO pluslet (pluslet_id, title, body, type, extra) VALUES ($box->BOX_ID, '$box->NAME', '$feed_url', 'Feed', '{\"num_items\":5,  \"show_desc\":1, \"show_feed\": 1, \"feed_type\": \"RSS\"}' )")) {
	
		$this->importLog("Inserted RSS pluslet '$box->NAME'");
		$clean_description = null;
	
	} else {
	
		$this->importLog("INSERT INTO pluslet (pluslet_id, title, body, type) VALUES ('$box->BOX_ID', '$box->NAME', '$feed_url', 'Feed', '' )");
		
		$this->importLog("RSS RSSS RSS Error inserting pluslet:");
		$this->importLog($db->errorInfo());
	
	}
	
	
	if($db->exec("INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES ('$box->BOX_ID', '$section_id', $column, $row)")) {
		$this->importLog("Inserted pluslet section relationship");
	
	
		// This sticks the newly created pluslet into a section
	} else {
	
	
		$this->importLog("RSS Error inserting pluslet_section:");
		$this->importLog( $db->errorInfo());
		$this->importLog("RSS INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES ('$box->BOX_ID', '$section_id', $column, $row)");
	}
	
	
	

}


public function insert_linked_box($box, $section_id) {
	$db = new Querier;

	$row = 0;
	$column = 0;
	
	if($db->exec("INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES ('$box->LINKED_BOX_ID', '$section_id', $column, $row)")) {
		$this->importLog("Inserted linked box");


		// This sticks the newly created pluslet into a section
	} else {


		$this->importLog("Error inserting pluslet_section:");
		$this->importLog( $db->errorInfo());
		$this->importLog("INSERT INTO pluslet_section (pluslet_id, section_id, pcolumn, prow) VALUES ('$box->LINKED_BOX_ID', '$section_id', $column, $row)");
	}




}

  

  public function import_box_links($box) {

  	$description = "";	
    
    foreach ( $box->LINKS->LINK as $link )  {
      
      
      $db = new Querier;
      $record = $db->query("SELECT * FROM location WHERE location = " .  $db->quote($link->URL),NULL,TRUE);


      if ($record[0]['location_id']) {

	$record_title = $db->query("SELECT title.title,title.title_id, location.location  FROM 
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
                  "{{dab},{" . $record[0]['location_id'] . "}," . "{" . $record_title[0]["title"] . "},{01}}" . 
                  "<div class=\"link-description\">$link->DESCRIPTION_SHORT</div>" .
                  "</div>";
  
}

$this->importLog ("Insert record:");
$this->importLog($record_title); 
$this->importLog("SELECT * FROM location WHERE location = " .  $db->quote($link->URL));

    }
    
    return $description;

}

public function import_box($box, $section_id) {
	
  $row = 0;
  $column = 0;		
	
  $db = new Querier;	
  $wc = new WordCleaner();
  
  $db->exec("SET NAMES utf-8" );
  	
  $description = null;
  
  $description .= "<div class=\"Box Type\">$box->BOX_TYPE</div>";       

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
  	   
  	  $attr->value = $this->download_images($attr->value);
  	   
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
    
   $this->insert_basic_pluslet($box, $section_id, $description); 	
    break;
  case "Basic Links":

    $description .= $this->import_box_links($box);
    $this->insert_basic_pluslet($box, $section_id, $description);
    break;
    
  case "Complex Links":

   $description .= $this->import_box_links($box);
   $this->insert_basic_pluslet($box, $section_id, $description);
    break;

  case "Embedded Media & Widgets":
    //Box type: Media & Widgets

    foreach ( $box->EMBEDDED_MEDIA_AND_WIDGETS as $media )  {
      
      $description .= 
      "<div class=\"embedded-media-widgets\">" . 
		      "<div class=\"embedded-media-description\">$media->URL</div>" .
                      "</div>";
  }

  	$this->insert_basic_pluslet($box, $section_id, $description);
  break;
 
  case "Linked Box":
  	
    		         $this->insert_linked_box($box, $section_id);
    		          
    	break;	         
    	
  case "RSS Feeds & Podcasts":
  	$feed_url = $box->RSS_FEED->URL;
  	$this->insert_rss_pluslet($box, $section_id, $feed_url);
  	
  	break;

  case "Books":
    // Box type: Books

    foreach ( $box->BOOKS->BOOK as $book )  {
      
      $description .= 
      "<div class=\"books\">" . 
		      "<a href=\"$book->URL\">$book->TITLE</a>" . 
		      "<div class=\"book-description\">$book->DESCRIPTION</div>" .
		      "</div>";
  }

  $this->insert_basic_pluslet($box, $section_id, $description);
  
  break;
 
  case "User Submitted Links":
    
  case "User Feedback":
    
  case "Google Search":
   
  case "Poll":
   
  case "Google Books":
 
  case "Events":
   
  case "Guide Links":
    
  case "User Profile":
   $this->insert_subject_specialist($box, $section_id);
   
  case "Google Scholar":
   

}



}

public function download_images($url) {
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

  $db = new Querier;
  $staff_id = $db->query("SELECT staff_id FROM staff WHERE email = '$email_address'");

  return $staff_id[0][0];

}

public function output_guides($lib_guides_xml_path) {
  // Outputs a select box for guides 

  $libguides_xml= new \SimpleXMLElement(file_get_contents($lib_guides_xml_path,'r'));
  
  
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

  foreach ($owners_combined as $owner) {
    echo "<section class=\"import-block\">";
    echo "<h1>" . $owner[0] . "</h1>";
    
    
    $guide_names = $libguides_xml->xpath("//OWNER/NAME[text() = '$owner[0]']/ancestor::GUIDE");
    
    echo "<select class=\"guides $owner_email[0]\" >";

    foreach ($guide_names as $guide) {

      echo "<option value=\"$guide->GUIDE_ID\">$guide->NAME - $guide->STATUS</option>";

    }

    echo "<select>";
    
    echo "<div class=\"import-controls\">";
    echo "<h2>First import your links:</h2>";
    echo  "<button class='import_links pure-button pure-button-primary'>Import Links</button>";
    echo "<h2>Then import your guides:</h2>";
    echo  "<button class='import_guide pure-button pure-button-primary'>Import Guide</button>";
    echo "</div>";
  	
	echo "</section>";	
  }

  

}


public function guide_imported() {

  $guide_id = $this->getGuideID();


  $db = new Querier;



  $guide = $db->query("SELECT COUNT(*) FROM subject WHERE subject_id = '$guide_id'");

  return $guide;

}



public function guide_dupe($guide_name) {

  $db = new Querier;
  $guide = $db->query("SELECT COUNT(*) FROM subject WHERE subject = '$guide_name'");

  return $guide;

}

public function load_libguides_xml($lib_guides_xml_path) {
		
  $section_index = 0;

  $libguides_xml= new \SimpleXMLElement(file_get_contents($lib_guides_xml_path,'r'));

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
			$libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/parent::GUIDE/TAGS")
			);

  $test = $libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/following-sibling::NAME");
  $this->importLog($test);
 
  
  
  return $subject_values;
  
}


public function load_libguides_links_xml($lib_guides_xml_path) {

  $db = new Querier;

  $all_titles = array();
  $titles = array();
  $dupes = array();
  $link_status = array();
  $urls = array();
  
  $guide_id = $this->getGuideID();
    
  $libguides_xml= new \SimpleXMLElement(file_get_contents($lib_guides_xml_path,'r'));
  
  $link_values = $libguides_xml->xpath("//GUIDE/GUIDE_ID[.=$guide_id]/..//LINKS/LINK");
   
  foreach (  $link_values as $link ) {
  	error_reporting('E_ALL');
  	ini_set('display_errors',1);
  	
    // Remove the proxy url from the link URL
    $noproxy_url = str_replace("https://iiiprxy.library.miami.edu/login?url=", "",$link->URL); 
    $noproxy_url = $db->quote($noproxy_url);
    
    $clean_url =  str_replace("'","", $noproxy_url);
    
    array_push($urls, array("url" => $clean_url));
    
    $ch = @curl_init($clean_url);
    @curl_setopt($ch, CURLOPT_HEADER, TRUE);
    @curl_setopt($ch, CURLOPT_NOBODY, TRUE);
    @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
    @curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $status = array();
    preg_match('/HTTP\/.* ([0-9]+) .*/', @curl_exec($ch) , $status);
    
    
    if ($status[1] == 200) {
    	array_push($link_status, array("working_link" => "true"));
    	
    } else {
    	array_push($link_status, array("working_link" => "false"));
    	
    }
    
    $title =  $db->quote($link->NAME);
	array_push($titles, array("title" => $title));
	
	
    $record_check = $db->query("SELECT COUNT(*) FROM location WHERE location = $noproxy_url ");
    $title_check = $db->query("SELECT COUNT(*) FROM title WHERE title = $title");
    $this->importLog ( $record_check) ;
    $this->importLog ("RECORD CHECK!!!!!!!!!!!!!!!!!!!!!!");
    $this->importLog($record_check[0][0]);

    if ($record_check[0][0] == 0 && $title_check[0][0] == 0) {

      if ($db->exec("INSERT INTO location (location, format, access_restrictions, eres_display) VALUES (" . $db->quote($link->URL) . " , 1, 1, 'N' )" )) {
	
	$this->importLog("Inserted location");
	$location_id = $db->last_id(); 
		
      } else {

	$this->importLog ("Error inserting location:");
      }
      
      // When inserting the titles into the databases, articles (a, an, the) should be removed and then stored in the prefix field 
      
      $matches = array();
      preg_match("/^\b(the|a|an|la|les|el|las|los)\b/i", $link->NAME, $matches);

      
      // If there isn't an article in the title
      if (empty($maches[0])) {
	
	if( $db->exec("INSERT INTO title (title, description) VALUES (" . $db->quote($link->NAME) . ","  . $db->quote($link->DESCRIPTION_SHORT)  . ")") ) {
	  $this->importLog( "Inserted title");
	  $title_id = $db->last_id();

	} else {
	  $this->importLog("Error inserting title:" );
	  $this->importLog(  $db->errorInfo() );
	}
	
      }
      
      // If there is an article in the title
      
      if(isset($matches[0])) {
	
	$clean_link_name = preg_replace("/^\b(the|a|an|la|les|el|las|los)/i", " ", $link->NAME);
	
	if( $db->exec("INSERT INTO title (title, description, pre) VALUES (" . $db->quote($clean_link_name) . ","  . $db->quote($link->DESCRIPTION_SHORT) . "," . $db->quote($matches[0]) . ")") ) {
	  $this->importLog( "Inserted title");
	  $title_id = $db->last_id();

	} else {
	  $this->importLog("Error inserting title:" );
	  $this->importLog(  $db->errorInfo() );
	}
	
      }
      
      
      if( $db->exec("INSERT INTO location_title (title_id, location_id) VALUES ($title_id, $location_id )") ) {
	$this->importLog( "Inserted location_title"); 
	

      } else {
	$this->importLog( "Error inserting location_title:");
	$this->importLog(  $db->errorInfo()  );

	$this->importLog( "INSERT INTO location_title (title_id, location_id) VALUES ($title_id, $location_id)");
      }

      
    } else {
    
    	array_push($dupes, array("status" => "Duplicate entry" ));
    	
    }

  }
  
  $all_titles['titles'] = zip($titles, $dupes, $urls, $link_status);
  
  $return_titles = json_encode($all_titles);
  
  return $return_titles;
}


public function import_libguides($subject_values) {
  
 
  $db = new Querier;
  $subject_id = (string) $subject_values[0][1];



  if ($this->guide_imported() != 0) {

    //exit;
  }
  
  
  foreach($subject_values as $subject) { 

    // Remove the apostrophes and spaces from the shortform 

    $shortform = preg_replace('/\s+/','_', str_replace("'", "", $subject[0] ));
    
    // Escape the apostrophes in the guide name 

    $guide_name = str_replace("'", "''",$subject[0]);
    

    if ($subject[0] != null) {
      


      if($db->exec("INSERT INTO subject (subject, subject_id, shortform, description, keywords) VALUES ('$guide_name', '$subject[1]', '$shortform' , '$subject[3]', '$subject[7]')")) {

      
      	
        echo $subject[1];

	

      } else {
        echo $subject[1][0];
	
	$query = "INSERT INTO subject (subject, subject_id, shortform, last_modified, description, keywords) VALUES ('$guide_name', '$subject[1]', '$shortform' , '$subject[2]', '$subject[3]', '$subject[7]')";
        
	$this->importLog( "Error inserting subject:");
	$this->importLog ($query);
        $this->importLog ( $db->errorInfo() ); 
	
      }

      if ($this->getGuideOwner() != null) {
	$staff_id = $this->getStaffID( $this->getGuideOwner());
	
	$this->importLog ("Staff ID: " . $staff_id );
	
	if($db->exec("INSERT INTO staff_subject (subject_id, staff_id) VALUES ($subject[1], $staff_id)")) {
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
    
    
    foreach ($subject_page->PAGE as $tab) {


      // LibGuide's pages are tabs so make a new tab

      
      $tab_index++; 
      
      $clean_tab_name = $db->quote($tab->NAME);
      
      if($db->exec("INSERT INTO tab (tab_id, subject_id, label, tab_index) VALUES ('$tab->PAGE_ID', '$subject[1]', $clean_tab_name, $tab_index - 1)")) {
	
	$this->importLog ("Inserted tab '$tab->NAME'");

      } else {

        $this->importLog( "Problem inserting the tab, '$tab->NAME'. This tab may already exist in the database." );
	
        
	$this->importLog ("Error inserting tab:");
	$this->importLog ($db->errorInfo());

      }
     
      $section_index = null;
      foreach ($tab->BOXES as $section) {

        // LibGuide's box parents into sections
        
        $section_uniqid = $section_index . rand();
        
        $section_index++;


        if($db->exec("INSERT INTO section (tab_id, section_id, section_index) VALUES ('$tab->PAGE_ID', $section_uniqid ,   $section_index)")) {
          $this->importLog("Inserted section");
        } else { 
          $this->importLog("Problem inserting this section. This section  may already exist in the database.");
          
	  $this->importLog("Error inserting section:");
	  $this->importLog($db->errorInfo() );
          
        }
        
      }

      foreach ($tab->BOXES->BOX as $pluslet) {
        // This imports each LibGuide's boxes as pluslets 

      		$this->importLog("\n");
      	$this->importLog((string) $pluslet);
      		$this->importLog("\n");
 	
	$this->import_box($pluslet, $section_uniqid);
	

	// Go throught the links in the box and check to see if they are OK

	// Links in Boxes

	
      }
    }
  }
}
}

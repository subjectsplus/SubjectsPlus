<?php
include("includes/header.php");
use SubjectsPlus\Control\Search;
$search = new Search;
$search->setSearch($_POST['searchterm']);
?>
<div id="main-content">
  <div class="pure-g-r">
    <div class="pure-u-1">
      <div class="box">
	<h2 class="bw_header">Search Results</h2>
      </div>
      <?php

      $results = $search->getResults();

      foreach ($results as $result) {
	
	echo "<div class='box'>";
	

	
	switch($result['content_type']) {
	  
 	  case 'Talkback':
	    echo "<p>";
	    echo "<a href='talkback/talkback.php?talkback_id=" . $result['id'] . "'/>" . $result['matching_text'] .  "</a>";
	    echo "</p>";
	    
	    break;

	  case 'Subject Guide':
	    echo "<p>";
	    echo "<a href='guides/guide.php?subject_id=" . $result['id'] . "'/>". $result['matching_text'] ."</a>";
	    echo "</p>";
	    break;

	  case 'FAQ':
	    echo "<p>";
	    echo "<a href='faq/faq.php?faq_id=" . $result['id'] . "'/>". $result['matching_text'] ."</a>";
	    echo "</p>";
	    break;

	  case 'Pluslet':
	    echo "<p>";
	    echo "<a href='faq/faq.php?subject=" . $result['id'] . "'/>". $result['matching_text'] ."</a>";
	    echo "</p>";
	    
	    break;
	  case 'Staff':
	    echo "<p>";
	    echo "<a href='staff/staff.php?staff_id=" . $result['id'] . "'/>". $result['matching_text'] ."</a>";
	    echo "</p>";
	    
	    break;
	}
    
    	echo "<p>";
	echo $result['content_type'];
	echo "</p>";  



	echo "<p>";
	echo $result['additional_text'];
	echo "</p>";
    
    
	echo "</div>";
      }
      ?>      
    </div>
  </div>
</div>


<?php
/**
 *   @file search.php
 *   @brief Search results page
 *
 *   @author Jamie Little (little9)
 *   @date May 2014
 */

use SubjectsPlus\Control\AllHandler;
use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Search;
    

$page_title = "Search Results";

include("../control/includes/config.php");
include("../control/includes/functions.php");
include("../control/includes/autoloader.php");
include("includes/header.php");



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
	
	echo "<p>";
	echo $result['content_type'];
	echo "</p>";  

	echo "<p>";
	echo $result['matching_text'];
	echo "</p>";

	echo "<p>";
	echo $result['additional_text'];
	echo "</p>";
	
	switch($result['content_type']) {
	  
 	  case 'Talkback':
	    echo "<p>";
	    echo "<a href='talkback/talkback.php?talkback_id=" . $result['id'] . "'/>View</a>";;
	    echo "</p>";
	    
	    break;

	  case 'Subject Guide':
	    echo "<p>";
	    echo "<a href='guide.php?subject=" . $result['id'] . "'/>View</a>";;
	    echo "</p>";
	    break;

	  case 'FAQ':
	    echo "<p>";
	    echo "<a href='faq/faq.php?faq_id=" . $result['id'] . "'/>View</a>";;
	    echo "</p>";
	    break;

	  case 'Pluslet':
	    echo "<p>";
	    echo "<a href='faq/faq.php?faq_id=" . $result['id'] . "'/>View</a>";;
	    echo "</p>";
	    
	    break;
	  case 'Staff':
	    echo "<p>";
	    echo "<a href='staff/staff.php?staff_id=" . $result['id'] . "'/>View</a>";;
	    echo "</p>";
	    
	    break;
	}
	echo "</div>";
      }
      ?>      
    </div>
  </div>
</div>

<?php
include("includes/footer.php");
?>

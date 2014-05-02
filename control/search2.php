<?php

include("includes/header.php");

use SubjectsPlus\Control\Search;
$search = new Search;
$search->setSearch($_GET['q']);

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
	
	  switch($result['content_type']) {
	  
 	  case 'talkback':
	      echo "<p>";
	      echo "<a href='talkback/talkback.php?talkback_id=" . $result['id'] . "'/>View</a>";;
	      echo "</p>";
	      echo "</div>";
	      break;

	  case 'subject':
	      echo "<p>";
	      echo "<a href='guides/guide.php?subject_id=" . $result['id'] . "'/>View</a>";;
	      echo "</p>";
	      echo "</div>";
	      break;

	  case 'faq':
	      echo "<p>";
	      echo "<a href='faq/faq.php?faq_id=" . $result['id'] . "'/>View</a>";;
	      echo "</p>";
	      echo "</div>";
	      break;




	  }
	  
	}
	?>
    
    </div>
  </div>
</div>


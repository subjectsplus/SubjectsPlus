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
	
	
	  echo "<p>";
	  echo "<a href='" . $result['id'] . "'/>View</a>";;
	  echo "</p>";

	  echo "</div>";
	}
	?>
    
    </div>
  </div>
</div>


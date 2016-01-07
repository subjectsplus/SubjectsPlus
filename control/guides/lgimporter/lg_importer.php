<?php
/**
 *   @file libguides_importer.php
 *   @brief The main LGImporter page. This is where users will choose which guide to import and see import results. 
 *   @author little9 (Jamie Little)
 *   @date June 2014
 */

// For instruction on how to use this please see the follwing page on the wiki:
// http://www.subjectsplus.com/wiki/index.php?title=Libguides_Importer
header('Content-Type:text/html; charset=UTF-8');

$subcat = "guides";
$page_title = "LibGuides Importer Stage 2";

include ('../../includes/header.php');

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\LGImport;
use SubjectsPlus\Control\Logger;

?>


<style>
.link_results {
	display: none;
}

.guide_results {
	display: none;
}
</style>
<!-- 
<div class="pluslet"> 
  <div class="titlebar">
  <div class="titlebar_text">Setup Instructions</div>
  </div>
  <div class="pluslet_body"> 
    For instructions on how to set this up please view the <a href="http://www.subjectsplus.com/wiki/index.php?title=Libguides_Importer">documentation</a> on the SubjectsPlus wiki.
  </div>
 
</div>
-->


<?php

$db = new Querier ();
$log = new Logger ();

$libguides_importer = new LGImport ( 'libguides.xml', $log, $db );
?>

<div class="pure-g">
	<div class="pure-u-1-2">

		<div class="pluslet">
			<div class="titlebar">
				<div class="titlebar_text">Select a Guide to Import</div>
			</div>
			<div class="pluslet_body"> 



<?php

$owners_combined = $libguides_importer->OutputGuides ( $_GET ["email"] );

// var_dump($owners_combined);

?>

    <div class="import-controls">
					<h2 class="import-your-links">First import your links.</h2>
					<p>After importing your links you can view a report about the links
						that were imported.
					
					
					<p>
					
					
					<p>
						You can also view the links in the <a href="../records">Records</a>
						section of SubjectsPlus.
					</p>
					<button class='import_links pure-button pure-button-primary'>①
						Import Links</button>
					<button class='view-links-results pure-button pure-button-primary'
						disabled>View/Hide Link Import Results</button>
					<div class="loading"></div>
					<h2 class="import-your-guides">Then import your guides:</h2>
					<button class='import_guide pure-button pure-button-primary'>② 
						Import Guide</button>
				</div>







			</div>
		</div>
	</div>


	<div class="pure-u-1-2">
		<div class="pluslet">
			<div class="titlebar">
				<div class="titlebar_text">Previously Imported Guides</div>
			</div>
			<div class="pluslet_body">
				<p>If you have imported guides they will appear below:
				
				
				<p>
				
				
				<h3 class="imported-guides"></h3>

				<ul class="previously-imported"></ul>

				<script>
console.log(previously_imported);
  
 if (previously_imported) { 
	 for (var i = 0; i < previously_imported.length; i++) { 
	    console.log(previously_imported[i]); 
	    $('.previously-imported').append("<li><a target=\"_blank\" href=../guide.php?subject_id=" + previously_imported[i].guide_id + ">" + previously_imported[i].guide_name + "</li>"); 
	    
	 }
 }
  </script>
			</div>
		</div>
	</div>
</div>



<div class="pluslet">
	<div class="titlebar">
		<div class="titlebar_text">Import Output</div>
	</div>
	<div class="pluslet_body import-output">
		<div class="import-message">
			<p>After you import a guide feedback about the process will appear
			here.</p>
		</div>


		<table class="link_results pure-table">
			<caption>Link Import Results</caption>
			<tr>
				<th>Title</th>
				<th>Status</th>
				<th>URL</th>
			</tr>
			<tbody class="link-results-body"></tbody>
		</table>
	</div>

	<table class="guide_results pure-table">
		<caption>Guide Import Results</caption>
		<tr>
			<th>Box Name</th>
			<th>Box Type</th>
		</tr>
	</table>
</div>

<link rel="stylesheet"
	href="<?php echo $AssetPath; ?>js/select2/select2.css" type="text/css"
	media="all" />
<script type="text/javascript"
	src="<?php echo $AssetPath; ?>/js/select2/select2.min.js"></script>
<style>
.select2-container {
	width: 20%;
	margin-right: 3%;
}
</style>


<span class="staff-id"
	data-staff_id="<?php echo $_SESSION['staff_id']; ?>" />
<script src="lg_importer.js"></script>
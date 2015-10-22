<?php
/**
 *   @file libguides_importer_user.php
 *   @brief On this page the user chooses who's guides they would like to import. 
 *   @author little9 (Jamie Little)
 *   @date June 2014
 */
header("Content-Type: text/html");

$subcat = "guides";
$page_title = "LibGuides Importer Stage 1";


include('../../includes/header.php');

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\LGImport;
use SubjectsPlus\Control\Logger;
?>

<link rel="stylesheet" href="<?php echo $AssetPath; ?>js/select2/select2.css" type="text/css" media="all" />

<script type="text/javascript" src="<?php echo $AssetPath; ?>/js/select2/select2.min.js"></script>



<div class="pluslet"> 
  <div class="titlebar">
  <div class="titlebar_text">Select Your Name</div>
  </div>
  <div class="pluslet_body"> 
<p>Please select your name below to being importing your guides.</p>
<p>After selecting your name, you'll be able to choose which guide you want to import.</p>
<form action="lg_importer.php" method="GET">
<?php 

$db = new Querier;
$log = new Logger;

$libguides_importer = new LGImport('libguides.xml',$log,$db);
$libguides_importer->OutputOwners();

?>
<p></p>
<button type="submit" class="pure-button pure-button-primary">View Your Guides</button>
</form>


</div>
</div>
<script type="text/javascript">
$('select').select2({'width':'500px'});
</script>
<?php
header("Content-Type: text/html");
error_reporting(1);
ini_set('display_errors', 1);
include('../includes/autoloader.php');
include('../includes/config.php');
include('../includes/functions.php');
include('../includes/header.php');

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\LibGuidesImport;



?>

<link rel="stylesheet" href="<?php echo $AssetPath; ?>js/select2/select2.css" type="text/css" media="all" />

<script type="text/javascript" src="<?php echo $AssetPath; ?>/js/select2/select2.min.js"></script>



<div class="pluslet"> 
  <div class="titlebar">
  <div class="titlebar_text">Select Your Name</div>
  </div>
  <div class="pluslet_body"> 

<form action="libguides_importer.php" method="GET">
<?php 
$libguides_importer = new LibGuidesImport('libguides.xml');
$libguides_with_owners = $libguides_importer->OutputOwners();
?>
<p></p>
<button type="submit" class="pure-button pure-button-primary">View Your Guides</button>
</form>


</div>
</div>
<script type="text/javascript">
$('select').select2();
</script>
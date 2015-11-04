<?php
/**
 *   @file guide_copy.php
 *   @brief This page takes a subject id and creates a copy of the that guide. It includes a form for selecting which guide to copy. 
 *   @author little9 (Jamie Little)
 *   @date July 2015
 */
global $AssetPath;

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Guide\GuideBase;
use SubjectsPlus\Control\Guide\GuideList;

include ("../includes/autoloader.php");
include ("../includes/config.php");



if (isset($_POST['subject_id'])) {
session_start();
$db = new Querier;
$subject_id = (int) $_POST['subject_id'];
$guide_base = new GuideBase($subject_id, $_SESSION['email'], $db);
$guide_base->loadGuide();
echo $guide_base->saveGuide();

exit;
} else {
	include("../includes/header.php");
	
}



$db = new Querier;
$guide_list = new GuideList($db);
$guide_list_output = $guide_list->toArray();

?>
<style>
.metadata-url {
display:none;
}
</style>
<div class="pure-g">
<div class="pure-u-1-3">
<div class="pluslet">
<div class="titlebar">
        <div class="titlebar_text">Copy Guide</div>
        <div class="titlebar_options"></div>
      </div>
      <div class="pluslet_body">
      <select id="guides" class="guide-select"> 
<?php 
foreach ($guide_list_output as $guide) {
	$subject_id = $guide["subject_id"];
	$subject = $guide["subject"];
	$status = $guide["active"];
	
	if ($status == 0) {
		$status_text = " - Hidden";
	}
	if ($status == 1) {
		$status_text = " - Published";
	}
	if ($status == 2) {
		$status_text = " - Suppressed";
	}
	
	echo "<option class='guide-option' value='{$subject_id}'>{$subject}  $status_text</option>";
	
}
?>
</select>
<div class="copy-output">
<a class="metadata-url">Click here to edit your metadata</a>
</div>


<button class="button pure-button pure-button-primary create-guide"> <?php echo _('Copy Guide'); ?></button>

</div>
</div>
</div>
</div>
<script>
$('.create-guide').on('click', function() {

	var selected_guide = $('.guide-option:selected').val();
	$.post("guide_copy.php", {subject_id : selected_guide } , function (data) {

		console.log(data);
		$('.metadata-url').show();
		$('.metadata-url').attr('href', "metadata.php?subject_id=" + data);

		window.location.href = "<?php echo getControlURL(); ?>" + "guides/metadata.php?subject_id=" + data;
		
	});
	
});
</script>


<link rel="stylesheet" href="<?php echo $AssetPath; ?>js/select2/select2.css" type="text/css" media="all" />

<script type="text/javascript" src="<?php echo $AssetPath; ?>/js/select2/select2.min.js"></script>
<style>
.select2-container {
width: 65%;
margin-right: 3%;
}

.create-guide {
margin-top: 1.5%;
margin-left: 0.5%;
}
</style>

<script>
$(document).ready(function() {

$('#guides').select2();

});
</script>
<?php

namespace SubjectsPlus\Control;

require_once('../../../includes/upload/class.upload.php');
require_once('./import_functions.php');


$subcat = "guides";
$page_title = "LibGuides v.2 Importer";
include ('../../../includes/header.php');

if (!isset($_POST['Upload']) && file_exists('lg2.xml')) {
?>
<div class="master-feedback" style="display:block;">Warning: you have already uploaded an XML file. Running this process again can result in duplicates.</div>

<?php

}

?>

<div class="pure-g">

<?php

$handle = new \upload($_FILES['xml_file']);

if ($handle->uploaded) {
	$handle->file_new_name_body = 'lg2';
	$handle->file_new_name_ext = 'xml';
        $handle->file_overwrite = true;
	$handle->Process('.');
	$handle->Clean;
}
?>
  <form enctype="multipart/form-data" name="libguides2" method="post" action="importer.php" class="pure-u-1">
    <div class="pluslet">
      <div class="titlebar"><span class="titlebar_text">Import settings</span></div>
      <fieldset class="pluslet_body"
	<?php if (isset($_POST['Upload'])) {print " disabled";} ?>
      >
	<input type="checkbox" name="actions" value="subjects" id="import-subjects" checked disabled />
        <label for="import-subjects">Import subject terms</label>
        <br />
	<input type="checkbox" name="actions" value="databases" id="import-databases" checked disabled />
        <label for="import-databases">Import databases and links</label>
        <br />
	<input type="checkbox" name="actions" value="guides" id="import-guides" />
        <label for="import-guides">Import guides</label>
        <br />
        <label>Choose an owner for guides and databases 
<?php
        $qStaff = "select staff_id, CONCAT(fname, ' ', lname) as fullname FROM staff WHERE ptags LIKE '%records%' AND active = '1' ORDER BY lname, fname";
        $querierStaff = new Querier();
        $staffArray = $querierStaff->query($qStaff);
        $staffMe = new Dropdown("staff_id", $staffArray, "", "50");
        echo $staffMe->display();
?>
        </label>
        <br />
        <label for="import-file">Libguides 2.0 XML file</label>
	<input type="file" name="xml_file" id="import-file" required />
        <br />
        <input class="pure-button pure-button-primary" type="submit" name="Upload" value="Upload" />
      </fieldset>
    </div>
  </form>
<?php
if (isset($_POST['Upload'])) {
?>
  <div class="pure-u-1">
    <div class="pluslet">
      <div class="titlebar"><span class="titlebar_text">Log</span></div>
      <div class="pluslet_body">
<?php
$importer = new LGImport2;
$importer->loadAllSubjectTerms($_POST['staff_id']) ;
$importer->loadAllRecords($_POST['staff_id']) ;
if (isset($_POST['actions']) && 'guides' === $_POST['actions']) {
	$importer->loadAllLibGuides($_POST['staff_id']) ;
}
?>
      </div>
    </div>
  </div>
</div>
<?php
}

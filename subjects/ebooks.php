<?php
/**
 *   @file ebooks.php
 *   @brief Display ebooks
 */
use SubjectsPlus\Control\CompleteMe;
use SubjectsPlus\Control\Querier;

include("../control/includes/config.php");
include("../control/includes/functions.php");
include("../control/includes/autoloader.php");

$this_fname = "ebooks.php";
$that_fname = theme_file($this_fname, $subjects_theme);
if ( $this_fname != $that_fname ) { include($that_fname); exit;}

// Add header now

include(theme_file("includes/header.php", $subjects_theme));
?>

    <p>eBooks</p>

<?php
///////////////////////////
// Load footer file
///////////////////////////
include(theme_file("includes/footer.php", $subjects_theme));
?>
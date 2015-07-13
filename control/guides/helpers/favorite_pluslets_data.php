<?php
header("Content-Type: application/json");
header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require_once("../../includes/autoloader.php");
require_once("../../includes/config.php");


use SubjectsPlus\Control\FavoritePluslet;

$objFavPluslets = new FavoritePluslet();
$staff_id = $objFavPluslets->setStaffId($_GET['staff_id']);
$favorites = $objFavPluslets->getFavoritePluslets($staff_id);


echo json_encode($favorites);


?>




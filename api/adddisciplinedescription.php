<?php

use SubjectsPlus\Control\Querier;
$db = new Querier;
include_once("../control/includes/config.php");
include_once("../control/includes/functions.php");


$lobjDB = new DBConnector($uname, $pword, $dbName_SPlus, $hname);

$lstrQuery = "SELECT * FROM subject";

$lobjQuerier = new Querier();

$lobjResults = $lobjQuerier->query($lstrQuery, true);

foreach($lobjResults as $lobjRow)
{
	$lobjExtra = json_decode($lobjRow['extra']);

	if(isset($lobjExtra) || isset($lobjExtra->description))
	{
		unset($lobjExtra->description);
	}

	if(!isset($lobjExtra) || !isset($lobjExtra->disciplines))
	{
		if(!isset($lobjExtra))
		{
			$lobjExtra = new stdClass();
		}

		$lobjExtra->disciplines = 'Science,Audiobooks';
	}

	$lstrExtra = json_encode($lobjExtra);

	$lstrQuery = "UPDATE subject SET description = 'This is a default description', extra = '$lstrExtra' WHERE subject_id = {$lobjRow['subject_id']}";

	$db->exec($lstrQuery);
}

$lstrQuery = "SELECT * FROM staff";

$lobjResults = $lobjQuerier->query($lstrQuery, true);

foreach($lobjResults as $lobjRow)
{
	$lobjExtra = json_decode($lobjRow['extra']);

	if(!isset($lobjExtra) || !isset($lobjExtra->disciplines))
	{
		if(!isset($lobjExtra))
		{
			$lobjExtra = new stdClass();
		}

		$lobjExtra->disciplines = 'Humanities,Music';
	}

	$lstrExtra = json_encode($lobjExtra);

	$lstrQuery = "UPDATE staff SET extra = '$lstrExtra' WHERE staff_id = {$lobjRow['staff_id']}";

	$db->exec($lstrQuery);
}

echo "done updating staff and subject tables to include descriptions and disciplines";
?>
<?php

//include subjectsplus config and functions files
include_once('../../../../control/includes/config.php');
include_once('../../../../control/includes/functions.php');
include_once('../../../../control/includes/autoloader.php');

/**
 * getUserPath() - checks to see if session exists and then extracts username
 * 					and returns user path.
 *
 * @return array
 */
function getUserPath()
{
	// start our session
	session_start();

	//check to see if user is logged in
	if(isset($_SESSION['email']))
	{
		//extract user name from email
		$lobjTemp = explode( '@', $_SESSION['email']);
		$lstrUsername = $lobjTemp[0];

		//check to see if the user has their assets folder
		$lstrPath = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . DIRECTORY_SEPARATOR . 'assets'
		. DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . '_' . $lstrUsername;

		if(!file_exists($lstrPath))
		{
			//folder does not exists, so lets try to create it
			if(!mkdir($lstrPath, 0755))
			{
				//exit on  error
				echo "<strong style=\"color:red\">" . _( "User assets folder does not exist and cannot create it." ) . "</strong>";
				exit;
			}
		}

		//return the path of the user's assets folder
		return $lstrPath;
	}else
	{
		//exit on error
		echo "<strong style=\"color:red\">" . _( "User not logged in." ) . "<strong>";
		exit;
	}

}

/**
 * getRadioButtons() - generate the radio buttons list from the existing files in the path
 *
 * @param string $lstrPath
 * @return string
 */
function getRadioButtons($lstrPath)
{
	//$lstrHTML =  "<input  name=\"but\" type=\"radio\" value=\"\" checked=\"checked\" />" . _("Never mind") . "<br />";
	$lstrHTML = '';

	$lobjAllFiles = array();

	//open directory and store all file names in an array
	$lrscDirectory  = opendir($lstrPath);
	while (false !== ($lstrFile = readdir($lrscDirectory)))
	{
		if ( $lstrFile != ".." && $lstrFile != "." )
		{
			$lobjAllFiles[] = $lstrFile;
		}
	}

	//sort the array
	sort($lobjAllFiles);

	foreach ($lobjAllFiles as $lstrFile)
	{
		$lobjTemp = explode(DIRECTORY_SEPARATOR, $lstrPath);

		$lstrToken = "{{fil},{" . $lobjTemp[ count( $lobjTemp ) - 1 ] . "/" . $lstrFile . "}, {" . $lstrFile . "}}";
		$lstrChanged = date( "F d Y H:i:s.",filectime( $lstrPath . "/" . $lstrFile ) );

		$lstrHTML .= "<input name=\"but\" type=\"radio\" value=\"$lstrToken\" ><strong> $lstrFile</strong> ($lstrChanged)<br />";
	}

	return $lstrHTML;
}

//get user path
$lstrUserPath = getUserPath();

//based on the user's path, generate radio buttons list
$lstrRadioButtonsHTML = getRadioButtons($lstrUserPath);

//echo out radio buttons list
echo $lstrRadioButtonsHTML;
?>
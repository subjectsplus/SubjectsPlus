<?php
/**
 *   @file /control/includes/configErrorPage.php
 *   @brief Error page when something goes wrong with config
 *   @description this file displays all error messages where there is something
 *   wrong with the configurations of SubjectsPlus
 *
 *   @author dgonzalez
 *   @date Jan 2013
 */

use SubjectsPlus\Control\Config;

//set asset path
$lstrURL = $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];

$lobjSplit = explode( '/', $lstrURL );

for( $i=(count($lobjSplit) - 1); $i >=0; $i-- )
{
	if($lobjSplit[$i] == 'control')
	{
		unset( $lobjSplit[$i] );
		$AssetPath = 'http://' . implode( '/' , $lobjSplit ) . '/assets/';
		break;
	}else
	{
		unset($lobjSplit[$i]);
	}
}

//begin HTML doc
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style type="text/css" media="all">@import "<?php print $AssetPath; ?>css/admin/admin_styles.css";</style>
        <title>Error Page</title>
    </head>

    <body>
<?php

include("autoloader.php");
include("functions.php");

//create new config instance
$lobjConfig = new Config();

//display only if error variable exists
if( isset($_GET['error'] ) )
{
	//based on error passed
	switch( $_GET['error'] )
	{
		//no configurations file
		case 'nofile':
			$lstrTitle = _( "Configuration File Missing!" );
			$lstrMessage = _( "The SubjectsPlus Configurations file does not exist. Before we continue we need to setup a connection to your database." )
				. "<br /><a href=\"../setup-config.php?steps\">" . _( "Let's make it!" ) . "</a>";

			$lobjConfig->displayErrorPage( $lstrTitle, $lstrMessage, FALSE );
			break;
		//no bse file to make config file
		case 'nobasefile':
			$lstrTitle = _( "Configuration File Error!" );
			$lstrMessage = _( "Sorry, I need a config-default.php file to work from. Please re-upload this file from your SubjectsPlus installation." );

			$lobjConfig->displayErrorPage( $lstrTitle, $lstrMessage );
			break;
		//could not connect to database
		case 'database':
			$lstrTitle = _( "DB Error!" );
			$lstrMessage = _( "Could not choose database.<br />The database name does not exist. Make sure that database exists and change configurations manually in file config.php.<br />You can also delete the config file and go through initial config setup." );

			$lobjConfig->displayErrorPage( $lstrTitle, $lstrMessage );
			break;
		//could not connect to mysql server
		default:
			$lstrTitle = _( "DB Error!" );
			$lstrMessage = _( "Could not establish mySQL connection.<br />Make sure user name exists and password is correct and change configurations manually in file config.php.<br />You can also delete the config file and go through initial config setup." );

			$lobjConfig->displayErrorPage( $lstrTitle, $lstrMessage );
			break;
	}

	exit;
}

?>
</body>
</html>
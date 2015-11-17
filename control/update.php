<?php

/**
 *   @file control/update.php
 *   @brief help updating to SubjectsPlus 3.0
 *   @description This file will help a user walk through basic steps to update
 * 	 to SubjectsPlus 3.0
 *
 *   @author dgonzalez
 *   @date Feb 2013
 *   @todo
 */

use SubjectsPlus\Control\Updater;

ini_set('display_errors',1);
error_reporting(E_ALL);


//set varirables needed in header
$subcat = "update";
$page_title = "Update";
$sessionCheck = 'no';
$no_header = "yes";
$updateCheck = 'no';

include("includes/header.php");

//logo only header
displayLogoOnlyHeader();

//find what step we are in
$lintStep = ( isset( $_GET['step'] ) ? (int) $_GET['step'] : 0 );

//if at SubjectsPlus 3.0 already, display message and discontinue
if( isUpdated() )
{
	?>
	<div id="maincontent" class="update-main">
<div class="box required_field">
		<h2 class="bw_head"><?php echo _( "Already Updated" ); ?></h2>

			<p><?php echo _( 'Already at SubjectsPlus 3.0. No need to run updater.' ) ?></p>
			<p><a href="login.php"><?php echo _( 'Log In.' ) ?></a></p>
		</div>
	</div>
	<?php
}else
{
	//new of Updater
	$lobjUpdater = new Updater();

	//depending on step, display content
	switch( $lintStep )
	{
		case 0:
			//first show updater message
			$lobjUpdater->displayStartingUpdaterPage();
			break;
		case 1:
			//update and on success show complete page
			if( $lobjUpdater->update( ) )
			{
				$lobjUpdater->displayUpdaterCompletePage();
				$_SESSION['firstUpdate'] = 1;
			}
			break;
	}
}

include("includes/footer.php");

?>
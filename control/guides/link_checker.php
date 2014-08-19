<?php
/**
 *   @file link_checker.php
 *   @brief check links in a given guide
 *
 *   @author rgilmour, gspomer, dgonzalez
 *   @date Apr 2013
 */

use SubjectsPlus\Control\LinkChecker;
    
//set up variable whether to include head nav or not
if( isset($_GET['wintype']) && $_GET['wintype'] == 'pop') $no_header = "yes"; //if popup, do not display header

//depending on type, show and run link checker form
if( isset($_GET['type']) && strtolower( $_GET['type'] ) == 'records' )
{
	//set header variables and include header file
	$subcat = "records";
	$page_title = "Records Link Checker";
	$use_jquery = array("ui_styles");
	include("../includes/header.php");

	//create new link checker object
	$lobjLinkChecker = new LinkChecker($proxyURL);

	$lobjLinkChecker->displayHTMLForm('record');
	if(isset($_POST['LinkCheckRecords']))
	{
		$lobjLinkChecker->checkRecordsLinks();
	}

}else
{
	//set header variables and include header file
	$subcat = "guides";
	$page_title = "Guide Link Checker";
	$use_jquery = array("ui_styles");
	include("../includes/header.php");

	//create new link checker object
	$lobjLinkChecker = new LinkChecker($proxyURL);

	$lobjLinkChecker->displayHTMLForm('subject');
	if(isset($_POST['subject_id']))
	{
		$lobjLinkChecker->checkSubjectLinks($_POST['subject_id']);
	}

}

include('../includes/footer.php'); ?>
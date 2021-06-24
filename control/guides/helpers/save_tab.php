<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 2/27/19
 * Time: 10:27 AM
 */

header( 'Content-Type: application/json' );
header( "Expires: on, 01 Jan 1970 00:00:00 GMT" );
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-store, no-cache, must-revalidate" );
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

require_once(__DIR__ . "/../../includes/autoloader.php" );
require_once(__DIR__ . "/../../includes/config.php" );
require_once(__DIR__ . "/../../includes/functions.php");

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Guide\TabData;

$db      = new Querier;
$tab = new TabData( $db );

$subject_id   = scrubData( $_REQUEST['subject_id'], "integer" );
$label        = scrubData( $_REQUEST['label'] );
$tab_index    = scrubData( $_REQUEST['tab_index'], "integer" );

if ( isset($_REQUEST['external_url']) ) {
	$external_url = scrubData( $_REQUEST['external_url'] );
} else {
	$external_url = null;
}


$visibility   = scrubData( $_REQUEST['visibility'], "integer" );

$tab->create( $subject_id, $label, $tab_index, $external_url, $visibility);

echo $tab->toJSON();
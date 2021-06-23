<?php
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
use SubjectsPlus\Control\Guide\SectionService;

$db      = new Querier;
$section = new SectionService( $db );

$section_id = scrubData( $_REQUEST['section_id'], "integer" );
$layout     = scrubData( $_REQUEST['layout'] );

$result = $section->updateSectionLayout($section_id, $layout);
var_dump($result);
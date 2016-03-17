<?php

/**
 *   @file export_contacts.php
 *   @brief View and update emergency contact info
 *
 *   @author adarby
 *   @date mar 2012
 *   @todo 
 */
use SubjectsPlus\Control\Querier;
$subcat = "";
$header = "noshow";

include("../includes/header.php");

$db = new Querier;

switch ($_GET["type"]) {
  case "all_staff":
  	//depending on permissions user has, set credential as true or false
	$check_credentials = (isset($_SESSION["view_map"]) && $_SESSION["view_map"] == 1) ? TRUE : FALSE;
    break;
  case "direct":
  	//depending on permissions user has, set credential as true or false
  	$check_credentials = (isset($_SESSION["supervisor"]) && $_SESSION["supervisor"] == 1) ? TRUE : FALSE;

    // get only those reporting DIRECTLY to this person
    $and = "AND supervisor_id = " . $_SESSION["staff_id"];
   
    break;
  
  case "all_reports":
     // Get the whole chain of folks reporting to this person
  	//depending on permissions user has, set credential as true or false
  	$check_credentials = (isset($_SESSION["supervisor"]) && $_SESSION["supervisor"] == 1) ? TRUE : FALSE;

     //$and = "AND supervisor_id IN (" . $_GET["ids"] . ")";
    $and = "AND supervisor_id IN (" . $_GET["ids"] . ")";
    break;
  default:
  	//depending on permissions user has, set credential as true or false
  	$check_credentials = (isset($_SESSION["view_map"]) && $_SESSION["view_map"] == 1) ? TRUE : FALSE;

    $and = "AND user_type_id = '1' ";
}

// Boot them out if they shouldn't be viewing this file 
if ($check_credentials == FALSE) {

  echo "<p style=\"background-color: red; color: white;\">You probably should not be here.  Please use the back button.  If you think you should be able to access this part of the site, please contact an administrator";
  include("../includes/footer.php");
  
  exit;
}

$header = "";
$data = "";

$select = "SELECT lname AS 'Last Name', fname AS 'First Name', tel AS 'Work Phone #', cell_phone AS 'Cell Phone #', home_phone as 'Home Phone',  staff.email AS 'Email',
emergency_contact_name AS 'Contact Name', emergency_contact_phone AS 'Contact Phone #', emergency_contact_relation AS 'Relationship', name AS 'Department',
  street_address AS 'Street Address', city as 'City', state AS 'State', zip as 'Zip Code', supervisor_id AS Super_ID, (SELECT lname from staff where staff.staff_id = Super_ID) AS 'Supervisor LName', (SELECT 
  fname from staff where staff.staff_id = Super_ID) AS 'Supervisor FName'
  FROM staff, department
  WHERE active = '1'
  AND staff.department_id = department.department_id
  $and
  ORDER BY lname";

$db = new Querier;
$export = $db->query($select,PDO::FETCH_NUM);

$fields = count( $export );

// yes, i cheated.  couldn't figure out how to get this with pdo from $select query
$header_fields = array("Last Name", "First Name", "Work Phone #", "Cell Phone #", "Home Phone", "Contact Name", "Contact Phone #", "Relationship", "Department", "Street Address", "City", "State", "Zip", "Super_ID", "Supervisor Lname", "Supervisor Fname");

foreach ($header_fields as $key => $value) {
  $header .= $value  . "\t";
}


    foreach( $export as $row )
{

    $line = '';
    foreach( $row as $value )

    {                                            
        if ( ( !isset( $value ) ) || ( $value == "" ) )
        {
            $value = "\t";
        }
        else
        {
            $value = str_replace( '"' , '""' , $value );
            $value = '"' . $value . '"' . "\t";
        }
        $line .= $value;
    }
    $data .= trim( $line ) . "\n";
}
$data = str_replace( "\r" , "" , $data );

if ( $data == "" )
{
    $data = "\n(0) Records Found!\n";                        
}
/*
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Content-type:text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=contacts.csv");
header("Pragma: no-cache");
header("Expires: 0");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
*/

header("Content-type: .xls application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=contacts.xls");
header("Pragma: no-cache");
header("Expires: 0");

print "$header\n$data";

//print "$header\n$data";

    

exit; 

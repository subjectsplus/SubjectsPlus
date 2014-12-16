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


$subsubcat = "";
$subcat = "";
$page_title = "View/Export Contact Information";

include("../includes/header.php");

$reporting_id = $_SESSION["staff_id"];

$all_supers = array(); // we init this; used as global in the function checkReports()

$table_init = "<table width=\"98%\" class=\"contact_table\">
      <tr><td class=\"colhead\">Employee Name</td><td class=\"colhead\">Email</td><td class=\"colhead\">Address</td><td class=\"colhead\">Home Phone</td><td class=\"colhead\">Cell Phone</td><td class=\"colhead\">Emergency Contact</td><td class=\"colhead\">Relationship</td><td class=\"colhead\">Contact Phone</td></tr>";
$table_close = "</table>";

// If this user is an admin, allow them to download all contacts
if (isset($_SESSION["view_map"]) && $_SESSION["view_map"] == 1) {
  $intro = "    <ul>
      <li><a href=\"staff_map.php\">View all Contacts on Map</a></li>
      <li><a href=\"export_contacts.php?type=all\">Download All Contacts</a></li>
    </ul>";
} else {
  $intro = "";
}

// Check if there are any reports for this user
$show_reports = checkReports($reporting_id);
if (isset($show_reports) && $show_reports != "") {
  $show_reports = $table_init . $show_reports . $table_close;
  $download_reports = "<a href=\"export_contacts.php?type=direct\">Download to Excel</a>";
} else {
  $show_reports = "<div class=\"pluslet-body\">Sorry, you do not have anyone registered as reporting to you.  If this is an error, please contact the library administration.</div>";
  $download_reports = "";
}

// Check for subreports
$show_reports_hierarchical = checkReports($reporting_id, '', 1);
if (isset($show_reports_hierarchical) && $show_reports_hierarchical != "") {
  $show_reports_hierarchical = $table_init . $show_reports_hierarchical . $table_close;
  $my_supers = implode(",", $all_supers);
  $download_reports_hierarchical = "<a href=\"export_contacts.php?type=all_reports&ids=$my_supers\">Download to Excel</a>";
} else {
  $show_reports = "<div class=\"pluslet-body\">Sorry, you do not have anyone registered as reporting to you.  If this is an error, please contact the library administration.</div>";
  $download_reports_hierarchical = "";  
}
// print_r($all_supers)

?>

<div style="float: left;  width: 100%;margin-top: 2em;">
  <div class="pluslet">
    <div class="titlebar">Emergency Contacts</div>
      <div class="pluslet_body">
    <p>Use this page to view Emergency Contact information.  Unless you are an administrator, you should only be able to see information for people who report to you.</p>
    <?php print $intro; ?>
    </div>
  </div>
  <div class="pluslet">
    <div class="titlebar">Direct Reports for <?php print $_SESSION["fname"] . " " . $_SESSION["lname"] . " " . $download_reports; ?></div>
    <div class="pluslet_body">
      <?php print $show_reports; ?>
    </div>  
  </div>
  <div class="pluslet">
    <div class="titlebar">All Reports for <?php print $_SESSION["fname"] . " " . $_SESSION["lname"] . " " . $download_reports_hierarchical; ?></div>

    <div class="pluslet_body">
      <?php print $show_reports_hierarchical; ?>
    </div>
  </div>
</div>

<?php
include("../includes/footer.php");



function checkReports($staff_id, $super_chain = "", $recursion = 0) {
  global $all_supers;

  $indent = "";
  $data = "";
  
  $q = "SELECT staff_id, CONCAT( fname, ' ', lname ) AS fullname, email, CONCAT( street_address, ' ', city, ' ', state, ' ', zip) as full_address
  , home_phone, cell_phone,
  emergency_contact_name, emergency_contact_relation,emergency_contact_phone, supervisor_id, lname, fname
  FROM staff
  WHERE supervisor_id = '" . $staff_id . "'
  AND active = 1
  ORDER BY lname, fname";
  //print $q . "<br /><br />";
  
  $db = new Querier;
  $r = $db->query($q);
  
  if( !$r ) return $data;

  $row_count = count($r);

  foreach ($r as $myrow) {
    
    
    if ($recursion == 1) {
      $q2 = "select lname, staff_id from staff where staff_id = " . $myrow[9] . " ORDER BY lname, fname";
      $supername = $db->query($q2);

      $superbits = explode("-", $super_chain);

      if (!in_array($supername[1], $superbits)) {
        $super_chain = $super_chain . "-" . $supername[1];
        array_push($all_supers, $supername[1]);
      }

      $superbits = explode("-", $super_chain); //  need to reset this after the alteration
      $num_supervisors = count($superbits);

      //$row_colour = ($row_count % 2) ? $colour1 : $colour2;
      if ($num_supervisors > 2) {
        $indent = "margin-left:" . 1 * ($num_supervisors * 1) . "em";
      } else {
        $indent = "font-weight: bold;";
      }
    }

    //$data .= makeExcelData($myrow);
    //$data = str_replace( "\r" , "" , $data );
    
    $data .= makeTR($myrow, $indent);
    
    if ($recursion == 1) {
      $data .= checkReports($myrow[0], $super_chain, 1);
    }
  }
  //print "<pre>";
  // print_r($typeArray);
  //return $typeArray;
  return $data;
  
}

function makeTR ($myrow, $indent) {
  $data = "<tr>
  <td><span style=\"$indent\">$myrow[1]</span></td>
  <td>$myrow[2]</td>
  <td>$myrow[3]</td>
  <td>$myrow[4]</td>
  <td>$myrow[5]</td>
  <td>$myrow[6]</td>
  <td>$myrow[7]</td>
  <td>$myrow[8]</td>
  </tr>";
  return $data;

}

function makeExcelData ($row) {
  $data = '';
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
  return $data;
}

exit; 

for ( $i = 0; $i < $fields; $i++ )
{
  $header .= mysql_field_name( $export , $i ) . "\t";
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
?>

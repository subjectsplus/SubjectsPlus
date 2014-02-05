<?php
//============================================================+
// File name   : example_048.php
// Begin       : 2009-03-20
// Last Update : 2010-08-08
//
// Description : Example 048 for TCPDF class
//               HTML tables and table headers
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com s.r.l.
//               Via Della Pace, 11
//               09044 Quartucciu (CA)
//               ITALY
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: HTML tables and table headers
 * @author Nicola Asuni
 * @since 2009-03-20
 */

$page_title = "Library Staff";
$subfolder = "services";
$subcat = "";

$header = "noshow";
include("../includes/header.php");

require_once('../../tcpdf/config/lang/eng.php');
require_once('../../tcpdf/tcpdf.php');

$css = "<style>
  
table {
width: 700px;
}

tr { width: 700px; }

table.item_listing th {
    background-color:  #CCCCCC;
    color: #333333;
    font-weight: bold;

    text-align: left;
}

.zebra {
    border-bottom: 1px solid #CCCCCC;
    
}

.evenrow, .even {
    background-color: #EFEFEF;
}

.oddrow, .odd {
    background-color: #FFFFFF;
}

.no_link { text-decoration: none; }

</style>";

switch ($_GET["report"]) {
  case "staff-az":
    $display_title = "Staff List A - Z";
    $staff_data = new sp_StaffDisplay();
    $out = $css . $staff_data->writeTable('A-Z',0);
    $doc_name = $_GET["report"];
    break;
  case "staff-dept":
    $display_title = "Staff List by Department";
    $staff_data = new sp_StaffDisplay();
    $out = $css . $staff_data->writeTable('By Department',0);  
    $doc_name = $_GET["report"];
    break;
  
  case "staff-subject-libs":
    $display_title = "Staff List: Subject Librarians A-Z";
    $staff_data = new sp_StaffDisplay();
    $out = $css . $staff_data->writeTable('Subject Librarians A-Z');      
    $doc_name = $_GET["report"];
    
    break;
}
        


//$out = $items;
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('University of Miami Libraries');
$pdf->SetTitle($display_title);
$pdf->SetSubject('A list of UM Libraries personnel.');
$pdf->SetKeywords('staff, list, contacts');

// set default header data
$pdf->SetHeaderData('logo.png', 25, $display_title, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
//$pdf->SetFont('helvetica', 'B', 20);

// add a page
$pdf->AddPage();

//$pdf->Write(0, 'Example of HTML tables', '', 0, 'L', true, 0, false, false, 0);

$pdf->SetFont('helvetica', '', 9);

$pdf->setFontSubsetting(false);

// -----------------------------------------------------------------------------

$pdf->writeHTML($out, true, false, false, false, '');

// -----------------------------------------------------------------------------

//Close and output PDF document
$our_doc = "../../subjects/pdf/" . $doc_name . ".pdf";
$pdf->Output($our_doc, 'FD');

//============================================================+
// END OF FILE                                                
//============================================================+
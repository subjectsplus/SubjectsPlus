<?php
/**
 *   @file index.php
 *   @brief Display the subject guides splash page
 *
 *   @author adarby
 *   @date nov 2015
 */
use SubjectsPlus\Control\CompleteMe;
use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Guide\GuideList;
    
$use_jquery = array("ui");

$page_title = "Research Guides";
$description = "The best stuff for your research.  No kidding.";
$keywords = "research, databases, subjects, search, find";
$noheadersearch = TRUE;

$db = new Querier;
$connection = $db->getConnection();

// let's use our Pretty URLs if mod_rewrite = TRUE or 1
if ($mod_rewrite == 1) {
   $guide_path = "";
} else {
   $guide_path = "guide.php?subject=";
}

//print_r ($_REQUEST);

// Get the subjects for jquery autocomplete
$suggestibles = "";  // init

$q = "select subject, shortform from subject where active = '1' AND type != 'Placeholder' order by subject";

$statement = $connection->prepare($q);
$statement->execute();
$r = $statement->fetchAll();

//initialize $suggestibles
$suggestibles = '';

foreach ($r as $myrow) {
    $item_title = trim($myrow[0][0]);


	if(!isset($link))
	{
		$link = '';
	}

    $suggestibles .= "{text:\"" . htmlspecialchars($item_title) . "\", url:\"$link$myrow[1][0]\"}, ";

}

$suggestibles = trim($suggestibles, ', ');


// Get our newest guides

$q2 = "select subject, subject_id, shortform from subject where active = '1' AND type != 'Placeholder' order by subject_id DESC limit 0,5";

$statement = $connection->prepare($q2);
$statement->execute();
$r2 = $statement->fetchAll();

$newest_guides = "<ul>\n";

foreach ($r2 as $myrow2 ) {
    $guide_location = $guide_path . $myrow2[2];
    $newest_guides .= "<li><a href=\"$guide_location\">" . trim($myrow2[0]) . "</a></li>\n";
}

$newest_guides .= "</ul>\n";


// Get our newest databases

$qnew = "SELECT title, location, access_restrictions FROM title t, location_title lt, location l WHERE t.title_id = lt.title_id AND l.location_id = lt.location_id AND eres_display = 'Y' order by t.title_id DESC limit 0,5";


$statement = $connection->prepare($qnew);
$statement->execute();
$rnew = $statement->fetchAll();

$newlist = "<ul>\n";
    foreach ($rnew as $myrow) {
    $db_url = "";

    // add proxy string if necessary
    if ($myrow[2] != 1) {
        $db_url = $proxyURL;
    }

    $newlist .= "<li><a href=\"$db_url$myrow[1]\">$myrow[0]</a></li>\n";
}
$newlist .= "</ul>\n";

// Add header now, because we need a value ($v2styles) from it
include("includes/header_um.php");


// put together our main result display
//**************************************

$pills = ""; //init
$layout = ""; //init
$collection_results = ""; //init

// Is this a search?
if (isset($_POST["searchterm"]) && $_POST["searchterm"] != "") {
    $searchterm = scrubData($_POST["searchterm"]);
    $search_param = "%" . $searchterm . "%";

    $pills = "<div class=\"pills-label\">" . _("Start over:") ."</div><div class=\"pills-container\"><a href=\"index.php\">See All Research Guides</a></div>"; 

    $q_search = "select * from subject 
    WHERE active = '1' 
    AND type != 'Placeholder' 
    AND subject LIKE '$search_param'
    ORDER BY subject";


    $statement = $connection->prepare($q_search);
    $statement->execute();
    $r_search = $statement->fetchAll();

    $col_1 = "<div class=\"pure-u-1 pure-u-md-1-2\"><ul class=\"guide-listing\">";

    foreach ($r_search as $key => $value) {

        $guide_location = $guide_path . $value['shortform'];
        $list_bonus = "";

        if ($value[6] != "") {$list_bonus .= $value[6] . "<br /><br />";} // add description
        if ($value[7] != "") {$list_bonus .= "<strong>Keywords:</strong> " . $value[7]; } // add keywords
            
        $col_1 .= "<li><i class=\"fa fa-plus-square\"></i> <a href=\"$guide_location\">" . htmlspecialchars_decode($value[1]) . "</a>
            <div class=\"guide_list_bonus\">$list_bonus</div>
            </li>";
    }

    $col_1 .= "</ul></div>";  // close 'er up

    $layout .= "<div class=\"pure-g guide_list\"><div class=\"pure-u-1 guide_list_header\"><h3>" . _("Search Results for ") . $searchterm . "</h3></div>" . $col_1 . "</div>";
} else {

    // ANCHOR buttons for guide types
    //**************************************
    $guide_type_btns = "<ul>";

    // We don't want our placeholder
    if (in_array('Placeholder', $guide_types)) { unset($guide_types[array_search('Placeholder',$guide_types)]); }

    foreach ($guide_types as $key) {
        $guide_type_btns .= "<li><a id=\"show-" . ucfirst($key) . "\" name=\"show$key\" href=\"#section-" . ucfirst($key) . "\">";
        
        $guide_type_btns .= ucfirst($key) . " Guides</a></li>\n";
    }

    $guide_type_btns .= "<li><a id=\"show-Collection\" name=\"showCollection\" href=\"#section-Collection\">Collections</a></li></ul>";

    $pills = "<div class=\"pills-label\">" . _("Select:") ."</div><div class=\"pills-container\">" . $guide_type_btns . "</div>"; 

    // let's grab our collections
    $collection_results = listCollections("","2col");

    // We don't want our placeholder
    if (in_array('Placeholder', $guide_types)) { unset($guide_types[array_search('Placeholder',$guide_types)]); }

    // loop through our source types
    foreach ($guide_types as $key => $value) {

            $guide_list = new GuideList($db,$value, 1);
            
            $all_guides = $guide_list->toArray(); // get our full listing of guides as an array
            
            $total_rows = count($all_guides); // total number of guides
            
            $switch_row = round($total_rows / 2);

        if ($total_rows > 0) {

            $col_1 = "<div class=\"pure-u-1 pure-u-md-1-2\"><ul class=\"guide-listing\">";
            $col_2 = "<div class=\"pure-u-1 pure-u-md-1-2\"><ul class=\"guide-listing\">";

            $row_count = 1;

            foreach ($all_guides as $myrow) {

                $guide_location = $guide_path . $myrow['shortform'];
                $list_bonus = "";

                if ($myrow[6] != "") {$list_bonus .= $myrow[6] . "<br /><br />";} // add description
                if ($myrow[7] != "") {$list_bonus .= "<strong>Keywords:</strong> " . $myrow[7]; } // add keywords
            
            $our_item = "<li><i class=\"fa fa-plus-square\"></i> <a href=\"$guide_location\">" . htmlspecialchars_decode($myrow[1]) . "</a>
            <div class=\"guide_list_bonus\">$list_bonus</div>
            </li>";

              if ($row_count <= $switch_row) {
                // first col
                $col_1 .= $our_item;
                
              } else {
                // even
                $col_2 .= $our_item;
              }

                $row_count++;

            } //end foreach

            $col_1 .= "</ul></div>";
            $col_2 .= "</ul></div>";

            $layout .= "<div class=\"pure-g guide_list\"><div class=\"pure-u-1 guide_list_header\"><a name=\"section-$value\"></a><h3>$value</h3></div>" . $col_1 . $col_2 ."</div>";
            
        } //end if

    }//end foreach

} // end search term check


    //EXPERTS
    //**************************************
    // get all of our librarian experts into an array
    $qexperts = "SELECT DISTINCT (s.staff_id), CONCAT(s.fname, ' ', s.lname) AS fullname, s.email, s.tel, s.title, sub.subject  FROM staff s, staff_subject ss, subject sub
          WHERE s.staff_id = ss.staff_id
          AND ss.subject_id = sub.subject_id
          AND s.active = 1
          AND sub.active = 1
          AND ptags LIKE '%librarian%'
          GROUP BY s.staff_id
          ORDER BY RAND()
          LIMIT 0,4";

    $statement = $connection->prepare($qexperts);
    $statement->execute();
    $expertArray = $statement->fetchAll();
    

    // init list item
    $expert_item = "";   
    
    // additional text 
    $bonus_text = _("Need help? Ask an expert!");

    // additional text 
    $button_text = _("See all experts");     

    foreach ($expertArray as $key => $value) {

      $exp_image = getHeadshotFull($value['email']);
      //$exp_profile = "<div class=\"expert-img\">" . $exp_image . "</div><div class=\"expert-label\">" . $value['fullname'] . "</div><div class=\"expert-title\">" . $value['title'] ."</div><div class=\"expert-subjects\">" . $value['subject'] ."</div>";

      $librarian_email = $value['email'];
      $name_id = explode("@", $librarian_email);

      $exp_profile = "<li><div class=\"expert-img\">" . $exp_image . "</div><div class=\"expert-label\"><a href=\"" . PATH_TO_SP . "subjects/staff_details.php?name=" . $name_id[0] . "\">" . $value['fullname'] . "</a><br /><div class=\"expert-subject-min\">" . $value['subject'] . "</div></div><div class=\"expert-tooltip\" id=\"tooltip-" . $name_id[0] . "\"><div class=\"expert-title\">" . $value['title'] ."</div><div class=\"expert-subjects\"><strong>Subjects:</strong> " . $value['subject'] ." ...</div></div></li>";

      $expert_item .= $exp_profile;    
    }

    $guide_experts = "$expert_item";

    








////////////////////////////
// Now we are finally read to display the page
////////////////////////////

?>
<div class="panel-container">
<div class="pure-g" id="guidesplash">
    
    <div class="pure-u-1 pure-u-lg-3-4 panel-adj" id="listguides">
        <div class="breather">
             <div class="index-search-area">        
                <?php 
                $input_box = new CompleteMe("quick_search_b", "index.php", $proxyURL, "Find Guides", "guides");
                $input_box->displayBox();
                print $pills;
                print $layout;   
                print $collection_results; 
                ?>
            </div>


        </div> <!-- end breather -->
    </div><!--end 3/4 main area-->

    <div class="pure-u-1 pure-u-lg-1-4">

        <div class="find-expert-area-circ">
                <h3>Find an Expert</h3>
                <p><?php print $bonus_text; ?></p>
                <ul class="expert-list-circ">                   
                    <?php print $guide_experts; ?>
                </ul>
                <div class="expert-btn-area"><a href="<?php print PATH_TO_SP; ?>subjects/staff.php?letter=Subject Librarians A-Z" class="expert-button"><?php print $button_text; ?></a></div>
        </div>

          <!-- start tip -->
            <div class="tip">
                <h2><?php print _("Newest Guides"); ?></h2>
                <?php print $newest_guides; ?>
            </div>
            <!-- end tip -->
            <div class="tipend"> </div>

        <!-- start tip -->
        <div class="tip">
            <h2><?php print _("Newest Databases"); ?></h2>
            <?php print $newlist; ?>
        </div>
        <!-- end tip -->
        <div class="tipend"> </div>
        

    </div><!--end 1/4 sidebar-->

</div> <!--end pure-g-->
</div> <!--end panel-container-->


<?php
///////////////////////////
// Load footer file
///////////////////////////

include("includes/footer_um.php");

?>

<script type="text/javascript" language="javascript">
    $(document).ready(function(){


        $("[id*=show]").on("change", function() {

            var showtype_id = $(this).attr("id").split("-");
            //alert("u clicked: " + showtype_id[1]);
            unStripeR();
            $(".type-" + showtype_id[1]).toggle();
            stripeR();


        });

        // Toggle details for each guide list item 
          $( ".fa-plus-square" ).click(function() {
             $(this).toggleClass('fa-plus-square fa-minus-square');
             $(this).parent().find('.guide_list_bonus').toggle();            
          });

        //add class to ui-autocomplete dropdown
        $( ".ui-autocomplete" ).addClass( "index-search-dd" );

              


    });

    
</script>

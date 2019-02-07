<?php
/**
 *   @file guide.php
 *   @brief Create and arrange the contents of a research guide
 *   @description This is where the magic happens.  Process like this:
 *   1. Determine if it's a cloned guide or not --REMOVED THIS
 *   2. Load guide's metadata (query $q)
 *   3. Load guide's pluslets and arrange intro appropriate column (query $qc)
 *   4. Pull in local css (guide.css) and javascript (guide.js)
 *   5. Put together page
 *
 *   @author adarby
 *   @date Dec 2012
 *   @todo Help popups not pointing to correct files
 *   @todo Edit history not present
 *   @todo Make sure user is allowed to modify this guide (NOFUN not set)
 */
use SubjectsPlus\Control\Guide;
use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\FavoritePluslet;

if (! isset ( $_GET ["subject_id"] )) {
	header ( "location:index.php" );
}

// necessary for jquery slider
$use_jquery = array ("ui_styles" );

// clear out existing cookies

ini_set ( 'display_errors', 1 );
error_reporting ( E_ALL | E_STRICT );
ini_set ( 'log_errors', 'On' );

setcookie ( "our_guide", "", 0, '/', $_SERVER ['HTTP_HOST'] );
setcookie ( "our_guide_id", "", 0, '/', $_SERVER ['HTTP_HOST'] );
setcookie ( "our_shortform", "", 0, '/', $_SERVER ['HTTP_HOST'] );

$subcat = "guides";
$page_title = "Modify Guide";
$tertiary_nav = "yes";

ob_start ();

include ("../includes/header.php");

$postvar_subject_id = scrubData ( $_GET ['subject_id'] );
$this_id = $_GET ["subject_id"];

// Editable if either a) they have admin, or b) they have allguides
$canedit = 0;

if (isset ( $_SESSION ["admin"] ) && $_SESSION ["admin"] == 1) { $canedit = 1; }
if (isset ( $_SESSION ["allguides"] ) && $_SESSION ["allguides"] == 1) { $canedit = 1; }

// See if they have permission to edit this guide
if ($canedit == 0) {
	$q = "SELECT staff_id from staff_subject WHERE subject_id = '$this_id'
    AND staff_id = '" . $_SESSION ["staff_id"] . "'";
	$db = new Querier ();
	$r = $db->query ( $q );
	$num_rows = count ( $r );
	
	if ($num_rows < 1) {
		$no_permission = _ ( "You do not have permission to edit this guide.  Ask the guide's creator to add you as a co-editor." );
		
		print noPermission ( $no_permission );
		
		include ("../includes/footer.php");
		exit ();
	}
}

// See if anything has been added through the Find button

if (isset ( $_GET ["insert_pluslet"] )) {
	$qa = "SELECT p.pluslet_id, p.title, p.body, ps.pcolumn, p.type, p.extra
    FROM pluslet p WHERE p.pluslet_id = '" . $_GET ["insert_pluslet"] . "'";
        $db = new Querier; 	
	$ra = $db->query ( $qa );
}

if (isset ( $this_id )) {
	$subject_id = $_GET ["subject_id"];
	// get name of quide
	$q = "SELECT subject, shortform, active, extra from subject where subject_id = '$subject_id'";
	$db = new Querier;
	$r = $db->query ( $q );
	
	// If this guide doesn't exist, send them away
	if (count ( $r ) == 0) {
		header ( "location:index.php" );
	}
	
	$subject_name = $r [0] [0];
	$shortform = $r [0] [1];
	
	// Is there a selected tab?
	if (isset ( $_GET ["t"] ) && $_GET ["t"] != "") {
		$selected_tab = scrubData ( $_GET ["t"] );
	} else {
		$selected_tab = 0;
	}
	
	// create new guide object and set admin view to true
	$lobjGuide = new Guide ( $this_id );
	$lobjGuide->_isAdmin = TRUE;
	
	$all_tabs = $lobjGuide->getTabs ();
} else {
	print "no guide";
}

// //////////////////////////
// Now, get our pluslets //
// /////////////////////////
global $pluslets_activated;

// get related guides
$related_guides = $lobjGuide->getRelatedGuides ();

$related_guides = array_filter ( $related_guides );
// if none exist then do not display related guide pluslet icon
if (empty ( $related_guides )) {
	if (($key = array_search ( 'Related', $pluslets_activated )) !== false) {
		unset ( $pluslets_activated [$key] );
	}
}

$all_boxes = "<p>" . _ ( "Drag box selection, then drop it to the right" ) . "</p>
<div class=\"box_options_container\">
<ul id=\"box_options\">";

foreach ( $pluslets_activated as $lstrPluslet ) {
	if (file_exists ( dirname ( dirname ( __DIR__ ) ) . "/lib/SubjectsPlus/Control/Pluslet/$lstrPluslet.php" )) {
		$lstrObj = "SubjectsPlus\Control\Pluslet_" . $lstrPluslet;
		
		if (method_exists ( $lstrObj, 'getMenuIcon' )) {
			$all_boxes .= "<li class=\"box-item draggable\" id=\"pluslet-id-$lstrPluslet\" ckclass='" . call_user_func ( array (
					$lstrObj,
					'getCkPluginName' 
			) ) . "'>" . call_user_func ( array (
					$lstrObj,
					'getMenuIcon' 
			) ) . "</li>";
		} else {
			$all_boxes .= "<li class=\"box-item draggable\" id=\"pluslet-id-$lstrPluslet\" ckclass='" . call_user_func ( array (
					$lstrObj,
					'getCkPluginName' 
			) ) . "'>" . $lstrPluslet . "</li>";
		}
	}
}

// Now get Special ones
// make sure: a) there are some linked resources (to show All Items by Source)

$conditions = "";

$q1 = "SELECT rank_id FROM rank WHERE subject_id = '$this_id'";
$db = new Querier;
$r1 = $db->query ( $q1 );

$num_resources = count ( $r1 );

if ($num_resources == 0) {
	$conditions = "AND pluslet_id != '1'";
}

// $q = "SELECT distinct pluslet_id, title, body
// FROM pluslet
// WHERE type = 'Special'
// ";

// $r = $db->query($q);

// foreach ($r as $myrow) {
// $lstrObj = "SubjectsPlus\Control\Pluslet_" . $myrow[0];
// $all_boxes .= "<li class=\"box-item draggable\" id=\"pluslet-id-$lstrPluslet\" ckclass='" . call_user_func(array( $lstrObj, 'getCkPluginName' )) . "'>" . call_user_func(array( $lstrObj, 'getMenuIcon' )) . "</li>";
// }

$all_boxes .= "</div></ul>";

// END DRAGGABLE //
// print_r($_SESSION);
// Let's set some cookies to be used by ckeditor
setcookie ( "our_guide", $subject_name, 0, '/', $_SERVER ['HTTP_HOST'] );
setcookie ( "our_guide_id", $postvar_subject_id, 0, '/', $_SERVER ['HTTP_HOST'] );
setcookie ( "our_shortform", $shortform, 0, '/', $_SERVER ['HTTP_HOST'] );
ob_end_flush ();

?>


<!-- ///////////////////////////////////
   // Structure for Guide Backend - PV
   ///////////////////////////////////-->

<style>
.guidewrapper, #main-options {
	display: none;
}
</style>

<div class="guide-parent-wrap" id="guide-parent-wrap" data-staff-id="<?php echo $_SESSION['staff_id']; ?>" data-subject-id="<?php echo $_GET['subject_id']; ?>">

	<div class="panel-wrap">
		<div id="hide_header">
			<img src="<?php print $AssetPath; ?>images/icons/menu-26.png"
				title="<?php print _("Show/Hide Header"); ?>"
				alt="<?php print _("Show/Hide Header"); ?>" />
		</div>
	</div>
	<!--end .panel-wrap-->


	<div class="guide-wrap">

		<!--GUIDE HEADER CONTAINER-->
		<div id="guide_header">
			<div class="pure-g">
				<div
					class="pure-u-2-5 pure-u-md-1-3 pure-u-lg-1-2 pure-u-xl-5-8 guide-title-area">
					<h2><?php print "<a target=\"_blank\" href=\"$PublicPath" . "guide.php?subject=$shortform\">$subject_name</a>"; ?></h2>
				</div>
				<!-- end pure 5-8-->

				<div
					class="pure-u-2-5 pure-u-md-1-2 pure-u-lg-3-8 pure-u-xl-1-4 guide-commands-area">
					<!-- Save Button -->
					<div id="savour">
						<button class="button pure-button pure-button-primary"
							id="save_guide"><?php print _("SAVE CHANGES"); ?></button>
					</div>
					<!--<div id="savour2"><button class="button pure-button pure-button-primary" id="save_template"><?php //print _("SAVE TEMPLATE"); ?></button></div>-->
				</div>
				<!-- end pure 1-4-->

				<div
					class="pure-u-1-5 pure-u-md-1-6 pure-u-lg-1-8 pure-u-xl-1-8 guide-options-area">

					<ul id="guide_nav">
						<li><a
							href="<?php print $PublicPath . "guide.php?subject=$shortform"; ?>"
							target="_blank"><i class="fa fa-eye"
								title="<?php print _("View Guide"); ?>"></i></a></li>
						<li><a class="showmeta"
							href="<?php print $CpanelPath . "guides/metadata.php?iframe=true&subject_id=$subject_id" . "&amp;wintype=pop"; ?>"><i
								class="fa fa-cog"
								title="<?php print _("Edit Guide Metadata"); ?>"></i></a></li>
						<li><a href="#" id="find-trigger"><i class="fa fa-search"
								title="<?php print _("Find in Guide"); ?>"></i></a></li>
					</ul>
				</div>
				<!-- end pure 1-8-->
			</div>
			<!-- end pure -->
		</div>
		<!-- end guide header-->

		
		
		<input id="extra" type="hidden" size="1"
			value="<?php
			
			if (isset ( $lobj )) {
				print $jobj->{'maincol'};
			}
			
			?>"
			name="extra" />		

	</div>
	<!--end .guide-wrap-->

	
	<!--In guide search-->
	<div id="find-in-guide-container">
		<div class="pure-g">
			<div class="pure-u-5-6 pure-u-lg-7-8">&nbsp;</div>
			<div class="pure-u-1-6 pure-u-lg-1-8 find-guide-parent">
				<form class="pure-form" id="guide_search">
					<input class="find-guide-input" type="text"
						placeholder="<?php print _("Find in Guide"); ?>"></input>
				</form>
			</div>
		</div>
	</div>
	<!-- end #find-in-guide-container-->

	<!-- Feedback -->
	<div id="response"></div>


	<!-- new tab form (suppressed until tab gears clicked) -->
	<div id="dialog" title="Tab data">
		<form class="pure-form pure-form-aligned">
			<fieldset class="ui-helper-reset">
				<div class="pure-control-group">
					<label for="tab_title"><?php print _("Title"); ?></label> <input
						type="text" name="tab_title" id="tab_title" value=""
						class="ui-widget-content ui-corner-all" />
				</div>
				<div class="pure-control-group">
					<label for="tab_external_link"><?php print _("Redirect URL"); ?></label>
					<input type="text" name="tab_external_link" id="tab_external_link" />
				</div>
				<div class="pure-control-group">
					<label><?php print _("Visibility"); ?></label> <select
						name="new-tab-visibility">
						<option value="1">Public</option>
						<option value="0">Hidden</option>
					</select>
				</div>
			</fieldset>
		</form>
	</div>

	<!-- edit tab form (suppressed until tab gears clicked) -->
	<div id="dialog_edit" title="Tab edit">
		<form class="pure-form pure-form-aligned">
			<fieldset class="ui-helper-reset">
				<div class="pure-control-group">
					<label for="tab_title"><?php print _("New Title"); ?></label> <input
						type="text" name="rename_tab_title" id="tab_title" value=""
						class="ui-widget-content ui-corner-all" />
				</div>
				<div class="pure-control-group">
					<label for="tab_external_url"><?php print _("Redirect URL"); ?></label>
					<input type="text" name="tab_external_url" id="tab_external_url" />
				</div>
				<div class="pure-control-group">
					<label><?php print _("Visibility"); ?></label> <select
						name="visibility">
						<option value="1">Public</option>
						<option value="0">Hidden</option>
					</select>
				</div>
			</fieldset>
		</form>
	</div>

</div> <!--end #guide-parent-wrap-->

<!--GUIDE BUILDER CONTAINER-->
		<div class="guidewrapper">

			<div id="guide-container-width">

				<?php global $guide_container_width; ?>
				<script>
					$('#guide-container-width').css('width', '<?php echo $guide_container_width[0]; ?>');

				</script>

				<div id="tabs" data-tab-count="<?php echo ( count($all_tabs) );  ?>">

	                 <?php $lobjGuide->outputNavTabs(); ?>
	                 <?php $lobjGuide->outputTabs (); ?>

	            </div> <!--end #tabs-->

			</div><!--end #guide-container-width-->

		</div><!--end #guidewrapper-->


<!-- FLYOUT PANEL-->
<div id="main-options">

	<!--Flyout trigger-->
	<div class="trigger-main-options">
		<i id="trigger-pointer" class="fa fa-chevron-right"></i>
	</div>

	<!-- Top level -->
	<div class="top-panel-options">
		<ul class="top-panel-options-list">

			<li id="show_box_options" class="top-panel-option-item active-item"><a
				href="#"><img
					src="<?php print $AssetPath; ?>images/icons/boxes1.svg"
					title="<?php print _("Boxes"); ?>" class="custom-icon" /><br /><?php print _("Boxes");?></a></li>

			<li id="show_findbox_options" class="top-panel-option-item"><a
				href="#"><i class="fa fa-search"
					title="<?php print _("Find Boxes"); ?>" /></i><br /><?php print _("Find Boxes"); ?></a></li>

			<li id="show_layout_options" class="top-panel-option-item"><a
				href="#"><i class="fa fa-columns"
					title="<?php print _("Layouts"); ?>" /></i><br /><?php print _("Layouts"); ?></a></li>

			<li id="show_tabs" class="top-panel-option-item"><a href="#"><img
							src="<?php print $AssetPath; ?>images/icons/tabs-white.svg"
							title="<?php print _("Tabs"); ?>" class="custom-icon" /><br /><?php print _("Tabs"); ?></a></li>


			<li id="show_analytics_options" class="top-panel-option-item"><a
				href="#"><i class="fa fa-pie-chart"
					title="<?php print _("Analytics"); ?>" /></i><br /><?php print _("Analytics"); ?></a></li>

			<li id="show_my_guides" class="top-panel-option-item"><a href="#"><img
					src="<?php print $AssetPath; ?>images/icons/myguides.svg"
					title="<?php print _("My Guides"); ?>" class="custom-icon" /><br /><?php print _("My Guides"); ?></a></li>



<!--		<li id="show_asset_manager" class="top-panel-option-item"><a href="#"><img
					src="<?php print $AssetPath; ?>images/icons/myguides.svg"
					title="<?php print _("Image Gallery"); ?>" class="custom-icon" /><br /><?php print _("Assets"); ?></a></li>
					
					-->

			<li><a href="#" id="main-options-close"><?php print _("Close"); ?></a></li>
		</ul>

	</div>

	<!-- Second-level-->
	<div class="second-level-options">

		<div class="second-level-container">

			<!--boxes-->
			<div id="box_options_content" class="second-level-content">
				<h3><?php print _("Add Boxes"); ?></h3>
              <?php print $all_boxes; ?>
              
              <h3><?php print _("Favorite Boxes"); ?></h3>
				<div class="fav-boxes-content">
					<ul class="fav-boxes-list"></ul>
				</div>

				<h3><?php print _("Remove Boxes"); ?></h3>
				<div class="remove_boxes_content">
					<a class="remove_pluslets" href="#"><?php print _("Remove Boxes from Current Tab"); ?></a>
				</div>

			</div>

			<!--find boxes-->
	<?php include_once('flyouts/find_boxes.php'); ?>


			<!-- reorder tabs -->
			<?php include_once('flyouts/tabs.php'); ?>
          
          <!--layout-->
			<div id="layout_options_content" class="second-level-content"
				style="display: none;">
				<h3><?php print _("Choose Layout"); ?></h3>

				<ul class="layout_options">
					<li class="layout-icon" id="col-single"
						title="<?php print _("1 Column"); ?>"></li>
					<li class="layout-icon" id="col-double"
						title="<?php print _("2 Columns"); ?>"></li>
					<li class="layout-icon" id="col-48"
						title="<?php print _("Sidebar + Column"); ?>"></li>
					<li class="layout-icon" id="col-84"
						title="<?php print _("Column + Sidebar"); ?>"></li>
					<li class="layout-icon" id="col-triple"
						title="<?php print _("3 Columns"); ?>"></li>
					<li class="layout-icon" id="col-363"
						title="<?php print _("2 Sidebars"); ?>"></li>
				</ul>

				<h3><?php print _("Add New Section"); ?></h3>
				<ul class="layout_options">
					<li class="top-panel-option-item"><a id="add_section" href="#"><img
							src="<?php print $AssetPath; ?>images/icons/section2.svg"
							title="<?php print _("New Section"); ?>" class="custom-icon" /></a></li>
				</ul>
			</div>
			
          <!--analytics-->
          <?php include_once('flyouts/analytics.php'); ?>



          </div>

		<!--my_guides_list-->
		<?php include_once('flyouts/my_guides_list.php'); ?>



		<!--  Image Gallery -->
		<?php include_once('flyouts/asset_manager.php')?>




  </div>
</div>

</div>
<!--end #main-options-->



<?php
// Get the shortform
$postvar_subject_id = scrubData ( $_GET ['subject_id'] );
$db = new Querier;
$sform = $db->query ( "SELECT shortform FROM subject WHERE subject_id = '$postvar_subject_id'" );
$short_form = $sform [0];
echo "<span id=\"shortform\" data-shortform=\"{$sform[0][0]}\" />";
?>

</div>
<!--end .guide-parent-wrap-->

<script src="../../ckeditor/ckeditor.js"></script>

<script src="<?php echo getControlURL(); ?>includes/js_custom.php" type="text/javascript"></script>

<script>

    $(document).ready(function() {

        // Initialize the guide interface
        var myGuideSetup = guideSetup();
        myGuideSetup.init();

        var ss = subjectSpecialist();
        ss.init();

	    <?php include('../../assets/js/guides/bookList.js'); ?>

        var containers = $(".booklist-content");
        $.each(containers, function() {
            var container = this;
            if ($(container).parent().parent().attr('name') == 'Clone'){
                container = $("#"+$(container).parent().parent().attr('id')).find('.booklist-content')[0];
                $(container).attr('rendered', '0');
            }

            if ($(container).attr('rendered') == '0') {
                var b = bookList();
                b.init(container);
                $(container).attr('rendered', '1');
                setTimer();
            }

            function setTimer() {
                setTimeout(showContainer, 1000);
            }

            function showContainer() {
                var loader = $(container).prev();
                $(loader).hide();
                $(container).show();
            }
        });

    });

</script>


<?php include("../includes/guide_footer.php"); ?>
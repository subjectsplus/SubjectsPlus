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

// END DRAGGABLE //
// print_r($_SESSION);
// Let's set some cookies to be used by ckeditor
setcookie ( "our_guide", $subject_name, 0, '/', $_SERVER ['HTTP_HOST'] );
setcookie ( "our_guide_id", $postvar_subject_id, 0, '/', $_SERVER ['HTTP_HOST'] );
setcookie ( "our_shortform", $shortform, 0, '/', $_SERVER ['HTTP_HOST'] );
ob_end_flush ();

?>
	<div class="guide-parent-wrap" id="guide-parent-wrap" data-staff-id="<?php echo $_SESSION['staff_id']; ?>" data-subject-id="<?php echo $_GET['subject_id']; ?>">

		<div id="data-bind-wrapper">

			<div id="tab-list-container">
				<h1>Tab list</h1>
				<ul id="tab-list"></ul>
			</div>
		</div>

	</div>
<script>
	$(document).ready(function () {
		var myGuideData = guideData();

		var tabData = myGuideData.fetchGuideData();

		tabData.then(function (data) {
            var output = myGuideData.handleGuideData(tabData);
            console.log(JSON.stringify(output));
        });


    })
</script>


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
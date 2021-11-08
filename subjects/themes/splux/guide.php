<?php
/**
 *   @file guide.php
 *   @brief Display the subject guides
 *
 *   @author adarby
 *   @date rev aug 2014, june 13 2018 pv
 */


use SubjectsPlus\Control\Guide;
use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\SubjectsPlus\Control;

$use_jquery = array("ui", "ui_styles", "colorbox");  // don't want the UI styles?  remove ui_styles from array
//$use_jquery = array('sp_legacy');

include_once(__DIR__ . "/../control/includes/autoloader.php"); // need to use this if header not loaded yet
//include_once(__DIR__ . "/../control/includes/config.php");
//include_once(__DIR__ . "/../control/includes/functions.php");

$db = new Querier;

// special image path because of mod_rewrite issues when source types are included in URL
$img_path = $PublicPath . "images";

if( isset( $_GET['subject'] ) ) {
	$check_this = $_GET['subject'];

} elseif( isset($_GET['id']) ) {

    $id = $_GET['id'];
    $connection = $db->getConnection();
    $statement = $connection->prepare("SELECT shortform FROM subject WHERE subject_id = :value");
    $statement->bindParam(':value', $id);
    $statement->execute();
    $res = $statement->fetchAll();

    $check_this = $res[0]["shortform"];

} else {
	$check_this = FALSE;
}

$page_description = _("The best stuff for your research.  No kidding.");
$page_keywords = _("library, research, databases, subjects, search, find");

///////////////////////
// Add This + Search //
// Add This is turned off by default :)

$addthis = "";

$social_and_search = '
<div id="guide_nav_tools">
<form id="guide_search" class="pure-form"> ' .
$addthis . 
'<input id="sp_search" class="find-guide-input ui-autocomplete-input" type="text" placeholder="' . _("Find in Guide") . '" autocomplete="off"/></form>
</div>
';

if ($check_this) {

	$connection = $db->getConnection();
	$statement = $connection->prepare("SELECT subject, subject_id, extra, description, keywords, redirect_url, header FROM subject where shortform = :value");
	$statement->bindParam(':value', $check_this);
	$statement->execute();
	$r = $statement->fetchAll();

    // If this guide doesn't exist, send them away
    if (count($r) == 0) {
        header("location:index.php");
    }

	$redirect_url = $r[0]["redirect_url"];

	if( !is_null($redirect_url) && !empty($redirect_url)  )
	{
		header("Location:$redirect_url");
	}

    $subject_name = $r[0]["subject"];
    $this_id = $r[0]["subject_id"];
	$header_type = $r[0]["header"];

    // check for description and keywords, which may be blank since they were added v2
    if ($r[0]["description"] != "") {
        $description = $r[0]["description"];
    }
    if ($r[0]["keywords"] != "") {
        $keywords = $r[0]["keywords"];
    }

    $jobj = json_decode($r[0]["extra"]);

    // In this section, we get the widths of the three columns, which add up to 12
    // We then do a little bit of math to get them into columns that add up to a bit under 100
    // In order to convert to css %.  If this page were bootstrapped, this wouldn't be necessary.
    if (isset($lobj->{'maincol'})) {
    $col_widths = explode("-", $jobj->{'maincol'});
    }
    //print_r($col_widths);
    if (isset($col_widths[0]) && $col_widths[0] > 0) {
        $left_width = $col_widths[0] * 8;
    } else {
        $left_width = 0;
    }

    if (isset($col_widths[1])) {
        $main_width = $col_widths[1] * 8;
    } else {
        $main_width = 0;
    }

    if (isset($col_widths[2]) && $col_widths[2] > 0) {
        $side_width = ($col_widths[2] * 8) - 3; // we make this a squidgen narrower so it doesn't wrap nastily
    } else {
        $side_width = 0;
    }

    // Is there a selected tab?
    if (isset($_GET["t"]) && $_GET["t"] != "") {
        $selected_tab = scrubData($_GET["t"]);
    } else {
        $selected_tab = 0;
    }

    //create new guide object and set admin view to false
    $lobjGuide = new Guide($this_id);
    $lobjGuide->_isAdmin = FALSE;

	//processVisibility
	if( !$lobjGuide->checkVisibility() )
	{
		exit();
	}

    $all_tabs = $lobjGuide->getTabs('public');
    
} else {
    header("location:index.php");
}

$page_title = $subject_name;

// Do we have an alternate header?
if (isset ($header_type) && $header_type != 'default') {
    if( file_exists("includes/header_$header_type.php") )
    {
        include_once(__DIR__ . "/includes/header_$header_type.php");
    }
    else
    {
        include_once(__DIR__ . "/includes/header.php");
    }
} else {
    include_once(__DIR__ . "/includes/header.php");
}

/*if (in_array($_REQUEST["subject"], $chcGuides)) {
    include_once(__DIR__ . "/includes/header_chc.php");
    $our_site="chc";
} else {
    include_once(__DIR__ . "/includes/header_um.php");
    $our_site="um";
}*/

// do we have more than one tab?
if (count($all_tabs) > 1) {
    $multi_tab = TRUE;
    $printOption = "<div class=\"printer_tabs\"><i class=\"fas fa-print\" title=\"Print this guide\"></i></div>";
} else {
    $multi_tab = FALSE;
    $printOption = "<div class=\"printer_no_tabs\"><i class=\"fas fa-print\" title=\"Print this guide\"></i></div>";
}

// Add tracking image
$tracking_image = "<img style=\"display: none;\" src=\"" . $PublicPath . "track.php?subject=" . scrubData($_GET['subject']) . "&page_title=" . $page_title . "\" />";

print $tracking_image;
print $social_and_search;
?>

<!--Minimal header if splux theme is used-->
<?php
if (isset ($header_type) && $header_type == 'splux') {

    $guide_min_header = "<div class=\"section-minimal-nosearch guide-header\">
        <div class=\"container text-center minimal-header\">
            <h5 class=\"mt-3 mt-lg-0 mb-1\"><a href=\"index.php\" class=\"no-decoration default\">" . _("Research Guides") . "</a></h5>
            <h1>" . $page_title . "</h1>" . $printOption ."</div>
    </div>
    <section class=\"section section-half-top\">
        <div class=\"container\">
            <div class=\"row\">
                <div class=\"col-12\"";

    print $guide_min_header;
}
?>

<!-- Guide content display-->
<div id="tabs" class="hide-tabs-fouc">
	<div id="main-content" data-subject="<?php echo scrubData($_GET['subject']); ?>" data-url="<?php echo getSubjectsURL(); ?>" data-subject-id="<?php echo $this_id; ?>">

		<div id="tab-container" style="visibility: hidden;">
            <?php
			$printer_tabs ='<div class="printer_tabs"><div class="pure-button pure-button-topsearch print-img-tabs"><img src="../assets/images/printer.png" alt="Print" title="Print"></div></div>';

            $printer_no_tabs ='<div class="printer_no_tabs"><div class="pure-button pure-button-topsearch print-img-no-tabs"><img src="../assets/images/printer.png" alt="Print" title="Print"></div></div>';
			
            // Only show tabs if there is more than one tab
            if ($multi_tab == TRUE) {

                if (isset ($header_type) && $header_type == 'splux'){

                    //desktop view
                    $container_md_open = "<div class=\"d-none d-md-inline-block\">";
                    print $container_md_open;

                    $lobjGuide->outputNavTabs('public');

                    $container_md_end = "</div>";
                    print $container_md_end;

                    //mobile view
                    $container_mobile_open = "<div class=\"d-md-none text-center\"><link href=\"". $AssetPath . "js/select2/select2.css\" rel=\"stylesheet\"/><script src=\"". $AssetPath ."js/select2/select2.js\"></script>";
                    print $container_mobile_open;

                    $lobjGuide->outputMobile('public');

                    $container_mobile_end = "</div>";
                    print $container_mobile_end;
                }
                else {
                    $lobjGuide->outputNavTabs('public');
                }

                $bonus_class= "yes-tabs";

                if (isset ($header_type) && $header_type != 'splux'){
                    print $printer_tabs;
                }

            } else {
                $bonus_class = "no-tabs";

                if (isset ($header_type) && $header_type != 'splux'){
                    print $printer_no_tabs;
                }
            }
            ?>
        </div>
		<!-- end tab-container -->

		<div id="tab-body" class="<?php print $bonus_class; ?>">
            <?php
            $lobjGuide->outputTabs('public');

            ?>
        </div>
		<!-- end tab-body -->

	</div> <!-- end main-content -->
</div> <!-- end tabs -->

<?php
if (isset ($header_type) && $header_type == 'splux') {

    $um_new_section_closing = "</div>
            </div>
        </div>
    </section>";

    print $um_new_section_closing;
}
?>


<script type="text/javascript" language="javascript">

    $(document).ready(function(){

        // .togglebody makes the body of a pluslet show or disappear
        $('body').on('click','.titlebar_text', function(event) {

            $(this).parent().next('.pluslet_body').toggle('slow');
        });

        var new_left_width = "<?php print $left_width; ?>%";
        var new_main_width = "<?php print $main_width; ?>%";
        var new_sidebar_width = "<?php print $side_width; ?>%";
        //alert(new_left_width + "-" + new_main_width + "-" + new_sidebar_width);
        if (new_main_width.length > 0) {
            $('#leftcol').width(new_left_width);
            $('#maincol').width(new_main_width);
            $('#rightcol').width(new_sidebar_width);
        }

        // Toggle details for each guide list item 
          $( ".fa-plus-square" ).click(function() {
             $(this).toggleClass('fa-plus-square fa-minus-square');
             $(this).parent().find('.guide_list_bonus').toggle();            
          });
    });

    $(window).load(function(){
        // $ functions to initialize after the page has loaded.

        function findStuff() {
            $(".find_feed").each(function(n) {
                var feed = $(this).attr("name").split("|");
                $(this).load("includes/feedme.php", {type: feed[4], feed: feed[0], count: feed[1], show_desc: feed[2], show_feed: feed[3]});
            });

        }

        findStuff();
    });

<?php
// this messes stuff up if it displays for tabless page

if ($multi_tab == TRUE) { ?>

    //setup $ UI tabs and dialogs
$(function() {

   var tabTitle = $( "#tab_title" ),
   tabContent = $( "#tab_content" ),
   tabTemplate = "<li class=\"dropspotty\"><a href='#{href}'>#{label}</a> <span class='alter_tab' role='presentation'><i class=\"fa fa-cog\"></i></span></li>",
   tabCounter = <?php echo ( count($all_tabs) ); ?>;

   // initialize tabs
   var tabs = $( "#tabs" ).tabs();

   //add click event for external url tabs
   $('li[data-external-link]').each(function()
					 {
       if($(this).attr('data-external-link') != "") {

           $(this).children('a[href^="#tabs-"]').on('click', function(evt)
                               {
                window.open($(this).parent('li').attr('data-external-link'), '_blank');
                //evt.stopImmediatePropagation
           });

         $(this).children('a[href^="#tabs-"]').each(function() {
           var elementData = $._data(this),
           events = elementData.events;

           var onClickHandlers = events['click'];

           // Only one handler. Nothing to change.
                     if (onClickHandlers.length == 1) {
             return;
           }

           onClickHandlers.splice(0, 0, onClickHandlers.pop());
         });
       }
   });

});

<?php
if (isset ($header_type) && $header_type == 'splux') { ?>

    // Select2 for Guide Tabs
    $('#select_tabs').select2({
        width: "80%",
        containerCssClass: "tabs-select",
        dropdownCssClass: "tabs-select-dropdown",
        placeholder: "In this guide..."
    });

    $("#select_tabs").change(function() {

        // open external link on tab-select
        var option_external_link = $(this).find('option:selected').attr('data-external-link');
        console.log(option_external_link);
        if (option_external_link != "") {
            window.open(option_external_link, '_blank');
        } else {
            var option_tab_link = $(this).find('option:selected').val();
            console.log(option_tab_link);
            var trim = option_tab_link.substr(6);
            console.log(trim);
            $("#tabs").tabs("select", trim);
        }
    });

<?php   }
} ?>
</script>

<?php
if (isset ($header_type) && $header_type == 'med') { ?>
<script>
    $("#tab-container").css("visibility", "visible");
</script>
<?php   }
 ?>


<!--[if IE]>
<style>
    #tabs .pluslet ul {
    margin-bottom: .5em;
    list-style-position: inside;
}
</style>
<![endif]-->

<style>
.ui-tabs-vertical {
	width: 55em;
}

.ui-tabs-vertical .ui-tabs-nav {
	padding: .2em .1em .2em .2em;
	float: left;
	width: 12em;
}

.ui-tabs-vertical .ui-tabs-nav li {
	clear: left;
	width: 100%;
	border-bottom-width: 1px !important;
	border-right-width: 0 !important;
	margin: 0 -1px .2em 0;
}

.ui-tabs-vertical .ui-tabs-nav li a {
	display: block;
}

.ui-tabs-vertical .ui-tabs-nav li.ui-tabs-active {
	padding-bottom: 0;
	padding-right: .1em;
	border-right-width: 1px;
}

.ui-tabs-vertical .ui-tabs-panel {
	padding: 1em;
	float: right;
	width: 40em;
}
</style>

<script>

var $target_blank_links = $(".target_blank_links");
$target_blank_links.each(function() {
    $(this).find('a').attr('target', '_blank');

});

$("div[name='Clone']").find('.pluslet_body:eq(0)').children().removeAttr('class');
$("div[name='Clone']").find('.pluslet_body:eq(1)').removeAttr('class');

///////////////////////////////
// Draw attention to TOC linked item
///////////////////////////////
$(document.body).on('click','a[id*=boxid-]', function(event) {
    var tab_id = $(this).attr('id').split('-')[1];
    var box_id = $(this).attr('id').split('-')[2];
    var selected_box = '.pluslet-' + box_id;

    if(!$('#tabs').data('ui-tabs')) {

        $(selected_box).effect('pulsate', {
            times:1
        }, 2000);

    } else {

        $('#tabs').tabs('select', tab_id);

        $(selected_box).effect('pulsate', {
            times:1
        }, 2000);
    }

});
</script>


<script>
<?php include('./includes/js/hash.js'); ?>
<?php include('./includes/js/track.js'); ?>
<?php include('./includes/js/tabDropdown.js'); ?>
<?php include('./includes/js/jquery.scrollTo.js'); ?>
<?php include('./includes/js/autoComplete.js'); ?>
<?php include('./includes/js/cloneView.js'); ?>
<?php include('../assets/js/guides/SubjectSpecialist.js'); ?>
<?php include('../assets/js/guides/Catalog.js'); ?>
<?php include('../assets/js/guides/ArticlesPlus.js'); ?>
<?php include('../assets/js/guides/PrimoSearchBox.js'); ?>
<?php include('../assets/js/guides/bookList.js'); ?>

hash.init();
track.init();
tabDropdown.init();
autoComplete.init();
cloneView.init();

    var ss = subjectSpecialist();
    ss.init();

    var primoCatalog = primoCatalog();
    primoCatalog.init();

    var articlesPlusSearch = articlesPlus();
    articlesPlusSearch.init();

    var primoSearchBox = primoSearchBox();
    primoSearchBox.init();

    var containers = $(".booklist-content");
    $.each(containers, function() {
        var container = this;
        if ($(container).parent().parent().attr('name') == 'Clone'){
            container = $("#"+$(container).parent().parent().attr('id')).find('.booklist-content')[0];
        }

        if ($(container).attr('rendered') == '0') {
            var b = bookList();
            b.init(container);
            $(container).attr('rendered', '1');
            setTimer();
        }

        function setTimer() {
            setTimeout(showContainer, 600);
        }

        function showContainer() {
            var loader = $(container).prev();
            $(loader).hide();
            $(container).show();
        }
    });

</script>

<?php
///////////////////////////
// Load footer file
///////////////////////////

// Do we have an alternate footer?
if (isset ($header_type) && $header_type != 'default') {
    if( file_exists("includes/footer_$header_type.php") )
    {
        include_once(__DIR__ . "/includes/footer_$header_type.php");
    }
    else
    {
        include_once(__DIR__ . "/includes/footer.php");
    }
} else {
    include_once(__DIR__ . "/includes/footer.php");
}
<?php
/**
 *   @file faq.php
 *   @brief Display faqs -- UM theme
 *
 *   @author adarby
 *   @date Sep 28, 2009
 *   @todo The interface for this is pretty lacklustre.  Make it better!
 */

use SubjectsPlus\Control\Dropdown;
use SubjectsPlus\Control\CompleteMe;
use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\TextTokenizer;

$description = "A searchable, sortable list of Frequently Asked Questions";
$keywords = "FAQ, FAQs, help, questions";

$use_jquery = array("ui");

//initialized passed variables
$postvar_coll_id = '';
$postvar_subject_id = '';
$postvar_faq_id = '';

if(isset($_REQUEST['coll_id']))
{
    $postvar_coll_id = $_REQUEST['coll_id'];
}

if(isset($_REQUEST['subject_id']))
{
    $postvar_subject_id = $_REQUEST['subject_id'];
}

if(isset($_REQUEST['faq_id']))
{
    $postvar_faq_id = $_REQUEST['faq_id'];
}

// This is the id of the collection that will appear by default
$default_faqpage_id = "1";

// This is the introductory text on the landing page.  If someone is doing a search, a browse by subject
// or looking at a FAQ collection, this text is overwritten in the if ($displaytype == "search") section
// at around line 125 below.  Put in the different intro text there, if you want.

$intro = _("<p>You can <strong>search</strong> the FAQs, <strong>browse</strong> them <strong>by subject</strong>, see <strong>collections</strong> of FAQs (i.e., different groupings of FAQs for different purposes/audiences), or browse the Basic FAQs, below.</p>");


// init our faq result.
$faq_result = "";


// Make sure the GET and POST data is clean/appropriate
if (isset($_REQUEST["coll_id"])) {
    $postvar_coll_id = scrubData($_REQUEST["coll_id"], "integer");
} else {
    $postvar_coll_id = "";
}

if (isset($_POST['subject_id'])) {
    $postvar_subject_id = scrubData($_POST["subject_id"], "integer");
} else {
    $postvar_subject_id = "";
}

if (isset($_REQUEST['faq_id'])) {
    $postvar_faq_id = scrubData($_REQUEST["faq_id"], "integer");
} else {
    $postvar_faq_id = "";
}


if(isset($_POST['searchterm']))
{
    $search_clause = scrubData($_POST['searchterm']);
}else
{
    $search_clause = '';
}

////////////////
// Get list of subjects for sidebar
///////////////

$db= new Querier();
$connection = $db->getConnection();

$q2 = "select distinct s.subject_id, s.subject
    from faq f, faq_subject fs, subject s 
    WHERE f.faq_id = fs.faq_id 
    AND fs.subject_id = s.subject_id
    AND active = '1'
    ORDER BY subject";

$statement = $connection->prepare($q2);
$statement->execute();
$oursubs = $statement->fetchAll();



if ($oursubs) {
    $guideMe = new Dropdown("subject_id", $oursubs, $postvar_subject_id, "40");
    $guide_string = $guideMe->display();
}


/* Set local variables */
$suggestion_text = '';


if (isset($_REQUEST['searchterm']) && $_REQUEST['searchterm'] && $_REQUEST['searchterm'] != $suggestion_text) {

    $displaytype = "search";
    $page_title = "FAQs: Search Results";
} elseif (isset($_GET['page']) && $_GET['page'] == "all") {
    $displaytype = "all";
    $page_title = "Show All FAQs";
} elseif ($postvar_subject_id != "") {
    $displaytype = "bysubject";
    $page_title = "FAQs by Subject";
} elseif ($postvar_coll_id != "") {
    $displaytype = "collection";

    // Get the name of the collection
    $db = new Querier;
    $connection = $db->getConnection();
    $statement = $connection->prepare("SELECT name, description FROM faqpage WHERE faqpage_id = :postvar_coll_id");
    $statement->bindParam(":postvar_coll_id", $postvar_coll_id);
    $statement->execute();
    $name = $statement->fetchAll();

    $page_title = "FAQS: {$name[0][0]}";
    $intro = stripslashes(htmlspecialchars_decode($name[0][1]));
} elseif ($postvar_faq_id != "") {
    $displaytype = "single";
    $page_title = "FAQs";
} else {
    $displaytype = "splashpage";
    $page_title = "FAQs";
}

include_once(__DIR__ . "/includes/header_splux.php" );

if ($displaytype == "search") {

    $statement = $connection->prepare("SELECT faq_id, question, answer, keywords
	FROM faq
	WHERE (question like ? OR answer like ? OR keywords like ?)
	Group BY question");

    $search_clause = "%" . $search_clause . "%";

    $statement->bindParam(":search_clause", $search_clause);

    $statement->execute();

    $intro = "<p>Search for <strong>$search_clause</strong>.</p>";
} elseif ($displaytype == "all") {

    $statement = $connection->prepare("SELECT distinct faq_id, question, answer, keywords
	FROM faq
	ORDER BY question");

    $statement->execute();

    $intro = "";
} elseif ($displaytype == "bysubject") {

    $statement = $connection->prepare("SELECT f.faq_id, question, answer, f.keywords, subject
	FROM faq f, faq_subject fs, subject s
	WHERE f.faq_id = fs.faq_id
	AND fs.subject_id = s.subject_id
	AND s.subject_id = :postvar_subject_id
	ORDER BY question");

    $statement->bindParam(":postvar_subject_id", $postvar_subject_id);
    $statement->execute();

    $intro = "";
} elseif ($displaytype == "single") {

    $statement = $connection->prepare("SELECT faq_id, question, answer, keywords
	FROM faq
	WHERE faq_id = :postvar_faq_id");

    $statement->bindParam(":postvar_faq_id", $postvar_faq_id);
    $statement->execute();

    $intro = "";
} elseif ($displaytype == "collection") {

    $statement = $connection->prepare("SELECT f.faq_id, question, answer, keywords
	FROM faq f, faq_faqpage ff, faqpage fp
	WHERE f.faq_id = ff.faq_id
	AND fp.faqpage_id = ff.faqpage_id
	AND fp.faqpage_id = :postvar_coll_id
	ORDER BY fp.name, question");

    $statement->bindParam(":postvar_coll_id", $postvar_coll_id);
    $statement->execute();

    $intro = "";
} else {

    // This is the default
    $statement = $connection->prepare("SELECT f.faq_id, question, answer, keywords
	FROM faq f, faq_faqpage ff, faqpage fp
	WHERE f.faq_id = ff.faq_id
	AND fp.faqpage_id = ff.faqpage_id
	AND fp.faqpage_id = :default_faqpage_id
	ORDER BY f.question");

    $statement->bindParam(":default_faqpage_id", $default_faqpage_id);
    $statement->execute();

}

if (isset($debugger) && $debugger == "yes") {
    print "<p class=\"debugger\">$full_query<br /><strong>from</strong> this file</p>";
}

//fetch results
$full_result = $statement->fetchAll();
$result_count = count($full_result);

if ($result_count != 0) {

    $index = "";
    $results = "";
    $row_count = 1;
    $index = '';
    $results = '';

    foreach ($full_result as $myrow) {

        $show_row_count = "";
        $faq_id = $myrow[0];
        $question = stripslashes(htmlspecialchars_decode($myrow[1]));
        $answer = stripslashes(htmlspecialchars_decode($myrow[2]));
        $answer = preg_replace('/<\/?div.*?>/ ', '', $answer);

        $keywords = $myrow["3"];

        if ($result_count > 1) {
            $index .= "<div class=\"faq-listing\"><a href=\"#faq-$row_count\" class=\"no-decoration\">$question</a></div>\n";
            $show_row_count = $row_count . ". ";
        }

        $tokenizer = new TextTokenizer($answer);
        $tokenizer->tokenizeText();
        $answer = $tokenizer->getTokenizedText();

        $results .= "<a name=\"faq-$row_count\"></a>
		<div class=\"faq\">
		<h2>$show_row_count$question</h2>
        <p>$answer</p>
		</div>
";

        // Add 1 to the row count, for the "even/odd" row striping
        $row_count++;
    }
} else {

    $results = "<h2>" . _("No Results") . "</h2><p>". _("There were no FAQs found for this query.") . "</p></div>";
}


$collections_query = "SELECT f.faqpage_id, name
FROM faqpage f, faq_faqpage ff
WHERE f.faqpage_id = ff.faqpage_id
GROUP BY name";

$statement = $connection->prepare($collections_query);
$statement->execute();
$collections_result = $statement->fetchAll();

// create the option
$coll_items = "<li><a href=\"faq.php?page=all\">All</a></li>";

foreach ($collections_result as $myrow1) {
    $coll_id = $myrow1[0];
    $coll_name = $myrow1[1];

    $coll_items .= "<li><a href=\"faq.php?coll_id=$coll_id\">$coll_name</a></li>";
}

?>

<div class="section-minimal-nosearch">
    <div class="container text-center minimal-header">
        <h1><?php print $page_title; ?></h1>
        <p class="mb-0">
            <?php
            if (isset($_GET["page"]) && $_GET["page"] == "all") {
                print _("All FAQs displayed, search or browse to limit results.");
            } else {
                print _("Not all FAQs displayed, search or browse for more.");
            }
            ?>
        </p>
    </div>
</div>

<div class="section section-half">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-lg-8 offset-sm-1 offset-lg-2">
                <!-- Search Area -->
                <div class="default-search">
                    <div class="index-search-area">
                        <?php
                        $input_box = new CompleteMe("quick_search", "faq.php", $proxyURL, "Find FAQs", "faq");
                        $input_box->displayBox();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="section section-half-top">
    <div class="container">
        <div id="backtotop">
            <a href="#" class="default no-decoration">
                <i class="fas fa-arrow-alt-circle-up" title="Back to top"></i>
                <span>Top</span>
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <?php
                if (isset($index) && $index != "") {
                    print "$index <hr class=\"mb-4\">";
                }

                print $results; ?>
            </div>
            <div class="col-lg-4">
                <div class="feature-light popular-list">
                    <h4><?php print _("Browse FAQs by Collection"); ?></h4>
                    <ul>
                        <?php print $coll_items; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(function () {
        var pxShow = 400;//height on which the button will show
        var fadeInTime = 1000;//how slow/fast you want the button to show
        var fadeOutTime = 1000;//how slow/fast you want the button to hide
        var scrollSpeed = 1000;//how slow/fast you want the button to scroll to top. can be a value, 'slow', 'normal' or 'fast'

        $(window).scroll(function(){
            if($(window).scrollTop() >= pxShow){
                $('#backtotop').fadeIn(fadeInTime);
            }else{
                $('#backtotop').fadeOut(fadeOutTime);
            }
        });

        // back to top
        $('#backtotop a').click(function () {
            $('html, body').animate({scrollTop:0}, scrollSpeed);
        });

        //add class to ui-autocomplete dropdown
        $('.index-search-area #quick_search').addClass("index-search-dd");
        $('.index-search-area .pure-button').addClass("btn-small");

    });
</script>

<?php
// footer
include_once(__DIR__ . "/includes/footer_splux.php" ); ?>
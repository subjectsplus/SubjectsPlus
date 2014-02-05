<?php

/**
 *   @file delish_feed.php
 *   @brief Display a delicious feed inline
 *   @description Users can generate delicious pages using the "Generate Delicious Page" link
 *   on the Records tab
 *
 *   @author adarby
 *   @date Sep 22, 2009
 */

include("../control/includes/config.php");
include("../control/includes/functions.php");
include("../control/includes/autoloader.php");

// clean up user-submitted content
$label = scrubData($_GET["label"]);
$tag = scrubData($_GET["tag"]);
$folder = scrubData($_GET["folder"]);

    if ($folder == "") {
        $folder = $DefaultDelishFolder;
    }

    if ($_GET["num"] == 1) {
        $openlist = "<ol>";
        $closelist = "</ol>";
    } else {
        $openlist = "<ul>";
        $closelist = "</ul>";
    }
/* Set local variables */

    if ($label) {
        $page_title = $label;
    } else {
        $page_title = _("Results");
    }


$subfolder = "research";
$description = "";

include("includes/header.php");

// parse the tag

$tags = explode(",",$tag);

print "
<div style=\"float: left; width: 60%;\">
<div class=\"pluslet\">\n
<div class=\"titlebar\"><div class=\"titlebar_text\">$page_title</div></div>\n
<div class=\"pluslet_body\">\n";

?>

<script type="text/javascript" language="javascript">
// init variables
var fullcount = 0;
var titleArray=new Array();
var titleArray0=new Array();
var titleArray1=new Array();
var titleArray2=new Array();
var titleArray3=new Array();
</script>

<?php

// start the loop
	foreach ($tags as $key => $value) {

	$src = "http://del.icio.us/feeds/json/$folder/$value" . "?count=100";
	//print $src;
	?>


	<script type="text/javascript" src="<?php print $src; ?>"></script>

	<script type="text/javascript" language="javascript">

	function stCap(strObj){
	return(strObj.charAt(0).toUpperCase()+strObj.substr(1));
	}


	var key = <?php print $key; ?>;

		for (var i=fullcount, post; post = Delicious.posts[i]; i++) {

			//substring(0,1).toUpperCase()
			var donuts = stCap(post.d);

				if (key == 0) {

				titleArray0[i] = new Array(donuts, post.u ,post.n)
				} else if (key == 1) {

				titleArray1[i] = new Array(donuts, post.u, post.n)
				} else if (key == 2) {

				titleArray2[i] = new Array(donuts, post.u, post.n)
				} else if (key == 3) {

				titleArray3[i] = new Array(donuts, post.u, post.n)
				}

			//titleArray[i] = new Array(donuts, post.u)
			//urlArray[i] = post.u;

		}


	</script>

	<?php

	}

?>

<script type="text/javascript" language="javascript">

	// Create the list type
	var openlist = "<?php print $openlist; ?>";
	var closelist = "<?php print $closelist; ?>";

	// Sort the array
	titleArray = titleArray0.concat(titleArray1).concat(titleArray2).concat(titleArray3);
	titleArray = titleArray.sort();



	document.write(openlist);
	for (var ii=0, item; item = titleArray[ii]; ii++) {

	document.write("<li style=\"margin-top: 5px;\"><a href=\"" + titleArray[ii][1] + "\">" + titleArray[ii][0] + "</a>");

		<?php if ($_GET["notes"] == "yes") { ?>

			if (titleArray[ii][2] != null) {
			document.write("<br /><blockquote style=\"margin-top:0;margin-bottom:0; margin-left: 20px;\">" + titleArray[ii][2] + "</blockquote>");
			}
		<?php } ?>
	document.write("</li>");

	}

	document.write(closelist);


</script>

<?php

print "
</div>\n
</div>\n
</div>\n
<div style=\"float: left; width: 30%; margin-left: 3%;\">
<div class=\"pluslet_simple\">
<img src=\"../assets/images/delish_wordle.gif\" alt=\"wordle based on OED example of 'movie'\" />
<br /><br />
<p>Page fed by <a href=\"http://www.delicious.com/\">delicious</a>, " . _("image created with") . " <a href=\"http://www.wordle.net/\">Wordle</a></p>
</div>
</div>";

include("includes/footer.php");

?>
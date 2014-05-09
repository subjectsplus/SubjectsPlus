<?php

/**
 *   @file delish_url.php
 *   @brief Create a URL for page of delicious results
 *
 *   @author adarby
 *   @date april 2011
 */

$subsubcat = "";
$subcat = "guides";
$page_title = "Generate delicious Display Page";

include("../includes/header.php");

print "<br />
    <div class=\"generate-delicious\"><div class=\"box\">
    <h2>" . _("Generate Delicious Display Page.  Yum.") . "</h2>

<p>" . _("Use this tool to generate a page that display results from delicious.") . "</p>
    <br />
<form>
    <p><strong>" . _("Folder") . "</strong> <input type=\"text\" value=\"$DefaultDelishFolder\" id=\"del_folder\" name=\"folder\" /><br />
    " . _("The delicious account name, e.g., thislibrary ") . "</p>
    <br />
    <p><strong>" . _("Tag") . "</strong> <input type=\"text\" value=\"\" id=\"del_tag\" name=\"tag\" /><br />
    " . _("Any subfolders you want displayed, can be more than one comma-separated") . " </p>
    <br />
    <p><strong>" . _("Label") . "</strong> <input type=\"text\" value=\"\" id=\"del_label\" name=\"label\" /><br />
    " . _("The title you want to appear at the top of your list") . " </p>
    <br />

    <p><strong>" . _("Notes") . "</strong>
    <select name=\"notes\" id=\"del_notes\">
        <option value=\"yes\">" . _("Yes") . "</option>
        <option value=\"no\">" . _("No") . "</option>
    </select>
    </p>
    <br />
    <p><input type=\"submit\" id=\"submit\" name=\"submitit\" value=\"" . _("Generate Delicious URL") . "\"></p>

</form>
<br /><br />
<div id=\"test_url\"></div>
</div>
</div>
<div class=\"huh\">
    <div class=\"box\">
<h2>Huh?</h2>

<p>" . _("This is a way to generate a URL that displays data from your delicious account. You can point users to a
    page of results that looks like a regular page of your website.  Or your SubjectsPlus installation, anyway.") . "</p>
<br /><p>" . _("The public delish feed page is located at sp/subjects/delish_feed.php .") . "<p></div>
</div>
";

include("../includes/footer.php");

?>
<script type="text/javascript">

    $(function (){

        ///////////////////////

        $('#submit').livequery('click', function() {
            
            // generate new url
// http://www.ithacalibrary.com/research/delish_feed.php?label=Art%20History:%20New%20Books%202010-2011&tag=arthis1011,arthis1011plus&notes=yes&num=1
            var url_start = '<?php print $DelishPath; ?>';
            var label = $('#del_label').val();
            var tag = $('#del_tag').val();
            var folder = $('#del_folder').val();
            var notes = $('#del_notes').val();
            var completed_url = url_start + "?folder=' + folder";

                if (tag.length != 0) {
                    completed_url += '&tag=' + tag;
                }
                if (label.length != 0) {
                    completed_url += '&label=' + label;
                }

                if (notes == 'yes') {
                    completed_url += '&notes=yes';
                } else {
                    completed_url += '&notes=no';
                }

                var final_string = '<a target="_blank" href="' + completed_url + '">' + completed_url + '</a>';
                $('#test_url').html(final_string);

return false;
        });



    });


</script>
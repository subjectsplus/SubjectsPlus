<style type="text/css">
    body {margin:0; padding:0; font-family:sans-serif; font-size:11pt;}
</style>
<?php
/**
 *   @file popup_help.php
 *   @brief Okay, this is popup help.  Called by guide.js. 
 *
 *   @author adarby
 *   @date Sep 21, 2009; started to update 2013, plenty of work to do
 */

global $IconPath;

switch ($_GET["type"]) {

    case "Feed":

        $title = _("Help:  Feed box");

        $text = _("
<p>This box allows you to insert an RSS feed into the page.  It could be a normal RSS feed, or else something that
uses the RSS technology, like Delicious or Twitter feeds.</p>

<p>Click the edit icon (pencil) to enter or edit a feed.  In the first box, enter the feed's URL.  Then decide how many items you want to display, and whether or not you want the description (i.e., the body of the feed) to appear.  Note that showing the description could use up a lot of screen real estate.</p>

<h2>Locating a Web Feed</h2>
<p>In order to add a web feed to a SubjectsPlus page, you must provide a URL that points to the feed. Feed URLs are different from general website URLs. They will often, but not necessarily, end in .xml, .rss, or .rdf.</p>
<p>For instance, you may want to feature a feed from Arts & Letters Daily on your subject page. The URL for this site is <a href=\"http://www.aldaily.com\" target=\"blank\">http://www.aldaily.com</a>, but that is not the feed URL. To find the feed URL, click on the web feed icon in the address bar of your browser (the icon may be either blue or orange):</p>

<p><img src=\"../images/admin/rss_example_icons.gif\" alt=\"example feed icons\" border=\"0\" style=\"float: left; margin-right: 10px; \"/> You will probably see a drop-down menu of choices.  Choose \"Subscribe to RSS.\"
You should now see the feed URL in the address bar.  In the above case, it is
<a href=\"http://www.aldaily.com/rss/rss.xml\" target=\"blank\">http://www.aldaily.com/rss/rss.xml</a>.
This is the URL that may be added to SubjectsPlus
to create a feed.</p>
");

        break;
    case "twitter":

        $title = _("Help:  Twitter box");

        $text = "
<p>This box allows you to insert a Twitter feed into the page.  Click the edit icon (pencil) to enter or edit a feed.  In the first box, enter the feed's URL.  Then decide how many items you want to display.  Ignore the description box.</p>

<img src=\"../images/admin/twitter_logo.png\" alt=\"Twitter Logo\" />

<p>In order to add a Twitter feed to a SubjectsPlus page, you must provide the URL that points to the feed itself, not the one that points to the Twitter page that displays the feed.</p>

<p>For instance, you may want to feature a feed from the Office of the British Prime Minister.  Downing Street's Twitter site is <a href=\"http://twitter.com/DowningStreet\" target=\"blank\">http://twitter.com/DowningStreet</a>, but this is not the feed URL.  To find the feed URL, click on the web feed icon in the address bar of your browser (the icon may be either blue or orange):</p>

<img src=\"../images/admin/rss_example_icons.gif\" alt=\"example feed icons\" border=\"0\" \"/>

<p>You will see a drop-down menu of choices.  Choose \"subscribe to DowningStreet's Updates.\"  You should now see the feed URL in the address bar:</p>

<p><a href=\"http://twitter.com/statuses/user_timeline/14224719.rss\" target=\"blank\">http://twitter.com/statuses/user_timeline/14224719.rss</a></p>

<p>When you click on the feed icon, there is also a choice to \"subscribe to DowningStreet's Favorites.\"  This will give you a feed to the posts of users followed by DowningStreet.  This could be useful if you wanted to do a mash-up feed (e.g., subscribe to a number of world news feeds and display content from all of them.)</p>
";

        break;

    case "deliciouslinks":

        $title = "Help:  Delicious box";

        $text = "<p>This box allows you to insert a Delicious feed into the page.  Click the edit icon (pencil) to enter or edit a feed.  In the first box, enter the feed's URL.  Then decide how many items you want to display, and whether or not you want the description (i.e., any note you might have added) to appear.</p>

<img src=\"../images/admin/delicious_logo.png\" alt=\"Delicious Logo\" />

<p>In order to add a delicious feed to a SubjectsPlus page, you must enter the name of the delicious account, i.e., iclibref.  If you want to show a subset of this account, add the tag after, i.e., iclibref/chemistry.  You can show multiple tags by separating them with a + symbol, i.e., iclibref/chemistry+biology.</p>";

        break;

    case "flickr":

        $title = "Help:  Flickr box";

        $text = "<p>This box allows you to insert a Flickr image feed into the page.  Click the edit icon (pencil) to enter or edit a feed.  In the first box, enter the feed's URL.  Then decide how many items you want to display.</p>

<img src=\"../images/admin/flickr_logo.gif\" alt=\"Flickr Logo\" />

<p>In order to add a feed of photos from Flickr, you need to provide the URL that points to the feed itself, not the one that points to the Flickr page that displays the images.</p>

<p>For instance, if you wanted to feature pictures from Cornell's Birdshare group, you would go to their Flickr page at:<p>

<p><a href=\"http://www.flickr.com/groups/birdshare/\" target=\"blank\">http://www.flickr.com/groups/birdshare/</a></p>

<p>To find the feed URL, click on the web feed icon in the address bar of your browser (the icon may be either blue or orange):</p>

 <img src=\"../images/admin/rss_example_icons.gif\" alt=\"example feed icons\" border=\"0\" \"/>

<p>You will see a drop-down menu of choices.  Choose \"subscribe to Flickr: Birdshare RSS feed.\"  You should now see the feed URL in the address bar:</p>

<p><a href=\"http://api.flickr.com/services/feeds/groups_discuss.gne?id=878749@N23&lang=en-us&format=rss_200\" target=\"blank\">http://api.flickr.com/services/feeds/groups_discuss.gne?id=878749@N23&lang=en-us&format=rss_200</a></p>

<p>Not only can one construct Flickr feeds for a given user or group, but feeds can be further limited to a particular set or tag (e.g., all the images in your \"chinchilla\" set or all the images that you have tagged \"eczema\").</p>";


        break;

    case "Special":
        $title = _("'Fixed' Box");
        $text = _("<p>Fixed box types (such as the Key to Icons or the Subject Specialist) can only be customized by the SubjectsPlus administrator.</p>
    <p>You may drop them, move them around and delete them.</p> 
    <p>The All Items by Source box has a little pencil; you may use it to rearrange the records that are associated with this guide.");
        break;

    case "heading":

        $title = _("Heading");

        $text = _("<p>This type just provides an organizational header showing only the title text.  It functions like an HTML H2, say.</p>");

        break;

    default:
        $title = _("'Normal' Box");

        $text = _("<p>You are looking at a normal box.  There are two types:  Editable and non-editable.  If there's a little pencil, that means you can open up the box and add or edit content.  If there's no pencil icon, then it means that the content is fixed.  If you want this changed, you will need to talk to your admin.<p>

<h2>Editable Boxes</h2>
<p><img src=\"$IconPath/page_edit.gif\" alt=\"edit icon\" border=\"0\" style=\"float: left; margin-right: 10px; \" width=\"32\"/> Click on the pencil icon to edit a box.  You will then see a field to enter the title of your box, and another larger box to enter the contents of your box.  This large box has some icons at the top that let you style your text, i.e., make some text bold or italic, create a list, add a hyperlink, etc.  The second line of icons allow you to embed content from elsewhere in SubjectsPlus in your box.  Clicking on these will pop up a window which gives you further options.</p>

<p><strong>Insert Resource Token</strong>:  Use this when you want to insert a reference to an item saved in SubjectsPlus (through the Record tab).  In the search box, enter (part of) the name of the resource you want to embed, say, \"history,\" and hit go.  From any results, tick the radio button of the item that you want to insert, say, \"America, history and life,\" and then click okay.  This will embed a token into your page:</p>

<p>{{dab},{8}, {America, history and life}} </p>

<p>When you save your guide, you will see that you have the most up-to-date information about this resource, pulled directly from the database.  If you want a different name for this link, change the text in between the appropriate curly braces.</p>

<p><strong>Insert or Upload Document</strong>:  This is a way to embed a link to a document you have created.  You will have an option to upload a file, or to select an existing file (that, perhaps, you uploaded for a different guide).  Follow the instructions on this page, and a token will be entered.<p>

<p><strong>Insert FAQs</strong>:  This is a way to display FAQs that have been entered in the FAQ module of SubjectsPlus.  When you click this icon, it will show you any FAQs that are already associated with your subject (there might be none).  Click any that you are interested in.  You can also find other FAQs by clicking one of the links at the top, Browse by Subject or Browse by Collection.  When you've selected the FAQs you want to insert, click OK.  Another token will be added to your box.  This will show the FAQs you've selected in a bulleted list.</p>

");
        break;
}
?>

<div style="background: #C03957; float:left; width: 96%; color: #fff; padding: 2px 2%;">
    <div style="float: left"><strong><?php print $title; ?></strong></div>
</div>

<br style="clear: both;" />

<div class="help_content" style="position: relative; text-align: left; line-height: 1.1em;float: left; margin: 0 10px;color: #333; overflow: auto;">
<?php print $text; ?>
</div>


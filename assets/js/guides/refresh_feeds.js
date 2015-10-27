/////////////////////
// refreshFeeds
// --loads the various feeds after the page has loaded
/////////////////////

function refreshFeeds() {

    $(".find_feed").each(function(n) {
        var feed = $(this).attr("name").split("|");
        $(this).load("../../subjects/includes/feedme.php", {
            type: feed[4],
            feed: feed[0],
            count: feed[1],
            show_desc: feed[2],
            show_feed: feed[3]
        });
    });

}
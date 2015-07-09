function setupAllColorboxes()
{

    /////////////////
    // Load metadata window in modal window
    // maybe they need to have saved all changes before loading?
    ////////////////

    $(".showmeta").colorbox({
        iframe: true,
        innerWidth:960,
        innerHeight:600,

        onClosed:function() {
            //reload window to show changes

            //window.location.href = window.location.href;

        }
    });


    /////////////////
    // Load new Record window in modal window
    // maybe they need to have saved all changes before loading?
    ////////////////

    $(".showrecord").colorbox({
        iframe: true,
        innerWidth:"80%",
        innerHeight:"90%",

        onClosed:function() {
            //change title potentially & shortform for link


        }
    });


    /////////////////
    // Load metadata.php in modal window--to organize All Items by Source
    // called by ticking the pencil button
    ////////////////

    $(".arrange_records").colorbox({
        iframe: true,
        innerWidth:"80%",
        innerHeight:"90%",

        onClosed:function() {
            //reload window to show changes
            //window.location.href = window.location.href;
        }
    });
}

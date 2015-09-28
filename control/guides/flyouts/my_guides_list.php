<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 9/22/15
 * Time: 12:59 PM
 */

?>
<!--my guides-->
<div id="my_guides_content" class="second-level-content" style="display:none;">
    <h3><?php print _("My Guides"); ?></h3>

    <div class="user_guides_display">
        <ul class="user-guides panel-list">
        </ul>
    </div>
    <a class="pure-button pure-button-primary myguide-button" href="./metadata.php">Create New Guide</a>

<script>

    $("#show_my_guides").on('click', function() {
        get_user_guides();
    });

    function get_user_guides() {

        $(".user-guides").empty();
        var request_guides = jQuery.ajax({
            url: "./helpers/user_guides.php?staff_id=<?php echo scrubData($_SESSION['staff_id']); ?>",
            type: "GET",
            dataType: "json",
            success: function(data) {

                if(!data.guides.length) {
                    //no results
                    $(".user-guides").append( "<li  class='panel-list-item'>You have not created a guide.</li>");

                }

                $.each(data.guides, function(idx, obj) {
                    $(".user-guides").append( "<li class='panel-list-item' title='" + obj.subject + "'><a href='./guide.php?subject_id=" + obj.subject_id + "'>" +obj.subject + "</li>");
                });
            }
        });

    }

</script>

</div><!--end my guides-->
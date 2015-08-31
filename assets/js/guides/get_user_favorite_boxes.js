/**
 * Created by cbrownroberts on 8/10/15.
 */


function get_user_favorite_boxes(staff_id) {

    $(".fav-boxes-list").empty();
    jQuery.ajax({
        url: "helpers/favorite_pluslets_data.php?staff_id=" +staff_id,
        type: "GET",
        dataType: "json",
        data: {staff_id: staff_id},
        success: function(data) {

            if(!data.length) {
                //no results
                $(".fav-boxes-list").append( "<li>No boxes have been marked as a favorite. To do so, click the gears button on the box you wish to mark as a Favorite and activate the Favorite toggle switch.</li>");


            }

            $.each(data, function(idx, obj) {
                $(".fav-boxes-list").append( "<li data-pluslet-id='" + obj.id +"' class='clone-favorite fav-box-item' title='" + obj.title + "'>" + obj.title + "</li>");

            });
        }
    });

}